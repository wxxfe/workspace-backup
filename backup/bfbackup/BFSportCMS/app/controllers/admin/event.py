# -*- coding: utf-8 -*-

from datetime import date, datetime
import time
import re

from flask import request
from flask import render_template
from flask import redirect
from flask import url_for

from app.models import MatchStatus
from app.service import SiteService
from app.service import TeamService
from app.service import EventTeamService
from app.service import EventService
from app.service import MatchService
from app.service import MatchLiveService
from app.service import SelectedMatchService
from app.service import EventNewsService
from app.utils.text import date_to_timestamp
from app.utils.text import to_cn_date
from app.utils.text import timestamp_to_datetime
from app.utils.text import add_image_domain
from app.utils.filters import Pagination
from app.utils.serialization import jsonify_with_data

from . import bp
from . import APIError
from .handler import InvalidArgument


@bp.route('/event/hot')
def list_hot_matches():

    event_id = request.args.get('event_id', 0)
    event_id = int(event_id)
    default_tm = request.args.get('date')
    if not default_tm:
        default_tm = timestamp_to_datetime(date_to_timestamp(date.today()))
    else:
        string_to_tm = datetime.strptime(
            default_tm, "%Y-%m-%d")
        default_tm = string_to_tm

    # get match info
    def get_match_info(match):
        match = MatchService.get_match_info(match.id)
        return match

    # all matches
    match_list = EventService.get_all(event_id=event_id, default_tm=default_tm)
    matches = map(get_match_info, match_list)

    # get selected match info
    def get_selected_match(match):
        match = MatchService.get_match_info(match.match_id)
        return match

    # selected matches
    selected_match_list = SelectedMatchService.get_all(
        event_id=event_id,
        type='hot')

    selected_matches = map(get_selected_match, selected_match_list)

    # calendar matches
    schedule_list = EventService.get_all(event_id=event_id)

    #  get match format date
    def get_format_date(match):
        match_tm = match.start_tm.strftime('%Y-%m-%d')
        return match_tm

    format_schedules = map(get_format_date, schedule_list)

    calendar_list = sorted(set(format_schedules), key=format_schedules.index)

    def get_calendar_date(match):
        string_to_tm = datetime.strptime(
            match, "%Y-%m-%d")
        cn_date = to_cn_date(string_to_tm)
        schedule = {}
        schedule['id'] = match
        schedule['date'] = cn_date
        return schedule

    schedules = map(get_calendar_date, calendar_list)
    return render_template(
                                          'admin/event/hot_list.html',
                                          event_id=event_id,
                                          matches=matches,
                                          schedules=schedules,
                                          sDate=default_tm.strftime('%Y-%m-%d'),
                                          selected_matches=selected_matches)


@bp.route('/event/<int:match_id>/recommend')
def recommend_match(match_id):

    event_id = request.args.get('event_id', 0)
    event_id = int(event_id)
    type = request.args.get('type', 'hot')

    match = SelectedMatchService.get_one(event_id, match_id, type)
    if match:
        return redirect(request.referrer)

    SelectedMatchService.add(
        event_id=event_id,
        type=type,
        match_id=match_id)

    return redirect(request.referrer)


@bp.route('/event/<int:match_id>/unrecommend')
def unrecommend_match(match_id):
    event_id = request.args.get('event_id', 0)
    SelectedMatchService.delete(event_id=event_id, match_id=match_id)
    return redirect(request.referrer)


@bp.route('/event/discuss')
def list_discuss_matches():
    event_id = request.args.get('event_id', 0)
    event_id = int(event_id)
    default_tm = request.args.get('date')
    if not default_tm:
        default_tm = timestamp_to_datetime(date_to_timestamp(date.today()))
    else:
        string_to_tm = datetime.strptime(
            default_tm, "%Y-%m-%d")
        default_tm = string_to_tm

    # get match info
    def get_match_info(match):
        match = MatchService.get_match_info(match.id)
        return match

    # all finished matches
    match_list = EventService.get_all(
        event_id=event_id,
        status=MatchStatus.FINISHED,
        default_tm=default_tm)
    matches = map(get_match_info, match_list)

    # get selected match info
    def get_selected_match(match):
        match = MatchService.get_match_info(match.match_id)
        return match

    # selected matches
    selected_match_list = SelectedMatchService.get_all(
        event_id=event_id,
        type='discuss')
    selected_matches = map(get_selected_match, selected_match_list)

    # calendar finished matches
    schedule_list = EventService.get_all(
        event_id=event_id,
        status=MatchStatus.FINISHED)

    #  get match format date
    def get_format_date(match):
        match_tm = match.start_tm.strftime('%Y-%m-%d')
        return match_tm

    format_schedules = map(get_format_date, schedule_list)

    calendar_list = sorted(set(format_schedules), key=format_schedules.index)

    def get_calendar_date(match):
        string_to_tm = datetime.strptime(
            match, "%Y-%m-%d")
        cn_date = to_cn_date(string_to_tm)
        schedule = {}
        schedule['id'] = match
        schedule['date'] = cn_date
        return schedule
    schedules = map(get_calendar_date, calendar_list)

    return render_template(
                                          'admin/event/discuss_list.html',
                                          event_id=event_id,
                                          matches=matches,
                                          schedules=schedules,
                                          selected_matches=selected_matches)


@bp.route('/event/match/schedules')
def list_match_schedules():
    event_id = request.args.get('event_id', 0)
    event_id = int(event_id)
    default_tm = request.args.get('date')
    if not default_tm:
        default_tm = timestamp_to_datetime(date_to_timestamp(date.today()))
    else:
        string_to_tm = datetime.strptime(
            default_tm, "%Y-%m-%d")
        default_tm = string_to_tm

    # get match info
    def get_match_info(match):
        match = MatchService.get_match_info(match.id)
        return match

    # all matches
    match_list = EventService.get_all(
        event_id=event_id,
        default_tm=default_tm)
    matches = map(get_match_info, match_list)

    # calendar finished matches
    schedule_list = EventService.get_all(
        event_id=event_id)

    #  get match format date
    def get_format_date(match):
        match_tm = match.start_tm.strftime('%Y-%m-%d')
        return match_tm

    format_schedules = map(get_format_date, schedule_list)

    calendar_list = sorted(set(format_schedules), key=format_schedules.index)

    def get_calendar_date(match):
        string_to_tm = datetime.strptime(
            match, "%Y-%m-%d")
        cn_date = to_cn_date(string_to_tm)
        schedule = {}
        schedule['id'] = match
        schedule['date'] = cn_date
        return schedule
    schedules = map(get_calendar_date, calendar_list)

    return render_template(
                                          'admin/event/match_schedule_list.html',
                                          event_id=event_id,
                                          matches=matches,
                                          sDate=default_tm.strftime('%Y-%m-%d'),
                                          schedules=schedules)


@bp.route('/event/match/schedules/<int:schedule_id>/show')
def show_event_schedules(schedule_id):
    MatchService.show(schedule_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/event/match/schedules/<int:schedule_id>/hide')
def hide_event_schedules(schedule_id):
    MatchService.hide(schedule_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/event/schedules')
def list_schedules():

    event_id = request.args.get('event_id', 0)
    event_id = int(event_id)
    default_tm = request.args.get('date')
    if not default_tm:
        default_tm = timestamp_to_datetime(date_to_timestamp(date.today()))
    else:
        string_to_tm = datetime.strptime(
            default_tm, "%Y-%m-%d")
        default_tm = string_to_tm

    # get match info
    def get_match_info(match):
        lives = MatchLiveService.get_all(match_id=match.id)
        match = MatchService.get_match_info(match.id)
        match['lives'] = lives
        return match

    # all matches
    match_list = EventService.get_all(event_id=event_id, default_tm=default_tm)
    matches = map(get_match_info, match_list)

    # calendar finished matches
    schedule_list = EventService.get_all(event_id=event_id)

    #  get match format date
    def get_format_date(match):
        match_tm = match.start_tm.strftime('%Y-%m-%d')
        return match_tm

    format_schedules = map(get_format_date, schedule_list)

    calendar_list = sorted(set(format_schedules), key=format_schedules.index)

    def get_calendar_date(match):
        string_to_tm = datetime.strptime(
            match, "%Y-%m-%d")
        cn_date = to_cn_date(string_to_tm)
        schedule = {}
        schedule['id'] = match
        schedule['date'] = cn_date
        return schedule
    schedules = map(get_calendar_date, calendar_list)

    sites = SiteService.get_all()

    return render_template(
                                          'admin/event/schedule_list.html',
                                          event_id=event_id,
                                          sites=sites,
                                          matches=matches,
                                          sDate=default_tm,
                                          schedules=schedules)


@bp.route('/event/schedules/add', methods=['GET', 'POST'])
def add_schedule():

    event_id = int(request.args.get('event_id'))
    teams = EventTeamService.get_all_by_event(event_id)

    if request.method == 'GET':
        return render_template('admin/event/add_schedule.html', teams=teams)

    schedule_dict = {}

    schedule_dict['event_id'] = event_id
    schedule_dict['start_tm'] = request.form.get('start_tm')
    schedule_dict['status'] = request.form.get('status',type=int)
    schedule_dict['round'] = request.form.get('round',0,type=int)
    schedule_dict['brief'] = request.form.get('brief')
    schedule_dict['team1_id'] = request.form.get('team1_id')
    schedule_dict['team1_score'] = request.form.get('team1_score',0,type=int)
    schedule_dict['team2_id'] = request.form.get('team2_id')
    schedule_dict['team2_score'] = request.form.get('team2_score',0,type=int)

    MatchService.add(schedule_dict)

    return redirect(url_for('admin.list_match_schedules', event_id=event_id))


@bp.route('/event/schedules/<int:schedule_id>/edit', methods=['GET', 'POST'])
def edit_schedule(schedule_id):
    event_id = request.args.get('event_id', 0)
    event_id = int(event_id)
    match = MatchService.get_one(schedule_id)
    teams = EventTeamService.get_all_by_event(event_id)
    default_tm = request.args.get('date')
    if request.method == 'GET':
        return render_template(
                                              'admin/event/edit_schedule.html',
                                              match=match, teams=teams)

    schedule_dict = {}

    schedule_dict['event_id'] = event_id
    schedule_dict['start_tm'] = request.form.get('start_tm')
    schedule_dict['status'] = request.form.get('status',type=int)
    schedule_dict['round'] = request.form.get('round',0,type=int)
    schedule_dict['brief'] = request.form.get('brief')
    schedule_dict['team1_id'] = request.form.get('team1_id')
    schedule_dict['team1_score'] = request.form.get('team1_score',0,type=int)
    schedule_dict['team2_id'] = request.form.get('team2_id')
    schedule_dict['team2_score'] = request.form.get('team2_score',0,type=int)

    MatchService.edit(schedule_id, schedule_dict)

    return redirect(url_for('admin.list_match_schedules', event_id=event_id,date=default_tm))


@bp.route('/event/schedules/<int:schedule_id>/delete')
def delete_schedule(schedule_id):
    MatchService.delete(schedule_id)
    return jsonify_with_data(APIError.OK)


@bp.route('/event/schedules/lives/add', methods=['GET', 'POST'])
def add_lives():

    if request.method == 'GET':
        return render_template('admin/event/schedule_list.html')

    live_str = request.form.to_dict()['data']
    live_dict = eval(live_str)
    match_id = int(live_dict['id'])

    # delete the lives
    def delete_live(lives):
        MatchLiveService.delete(lives['id'])
        return True

    match_lives = MatchLiveService.get_all(match_id)
    match_delete= map(delete_live, match_lives)

    lives = live_dict['allSources']

    for live in lives:
        try:
            stream_time = live['stream_time']
            if stream_time:
                resault= re.search('/', stream_time)
                if resault:
                    tmp = time.strptime(live['stream_time'], "%Y/%m/%d %H:%M")
                    live['stream_time'] = time.strftime("%Y-%m-%d %H:%M:%S", tmp)
        except:
            raise InvalidArgument()
        live['match_id'] = int(live_dict['id'])
        live['site'] = live['sourcesName']
        live['play_url'] = live['sourcesUrl']
        live['play_code'] = live['playCode']
        live['feed_code'] = live['feedCode']
        live['play_html'] = live['playHtml']
        del(live['sourcesName'])
        del(live['sourcesUrl'])
        del(live['playCode'])
        del(live['feedCode'])
        del(live['playHtml'])

    # get match info
    def add_match_info(lives):
        MatchLiveService.add_dict(lives)
        return True
    matches = map(add_match_info, lives)

    return jsonify_with_data((200, 'OK'))


@bp.route('/event/news', methods=['GET', 'POST'])
def list_event_news():

    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)

    try:
        event_id = int(request.args.get('event_id'))
        type = request.args.get('type')
    except:
        raise InvalidArgument()

    if request.method == 'POST':
        search_id = request.form.get('search')
        return redirect(url_for(
            'admin.list_event_news',
            event_id=search_id,
            type=type))

    news = EventNewsService.get_all(
            event_id, type, (page - 1) * count, count)

    # get match format date
    def add_info_image(news):
        news.image = add_image_domain(news.image)
        return news
    news = map(add_info_image, news)

    if event_id and type:
        news_count = EventNewsService.count(event_id, type=type)
    elif event_id:
        news_count = EventNewsService.count(event_id)
    elif type:
        news_count = EventNewsService.count(type=type)
    else:
        news_count = EventNewsService.count()

    pagination = Pagination(page, count, news_count)

    return render_template(
                                          'admin/event/news_list.html',
                                          news=news,
                                          pagination=pagination,
                                          current_id=event_id,
                                          current_type=type,
                                          url=request.url)


@bp.route('/event/news/add', methods=['GET', 'POST'])
def add_event_news():

    redirect_url = request.args.get('redirect', '')
    event_id = request.args.get('event_id')
    if request.method == 'GET':
        sites = EventNewsService.get_sites()
        return render_template(
            'admin/event/news_add.html', sites=sites, event_id=event_id, url=redirect_url)

    try:
        eid = int(request.form.get('event_id'))
        type = request.form.get('type')
        source = request.form.get('site')
        source_name = request.form.get('site_name')
        title = request.form.get('title')
        isvr = request.form.get('isvr', 0, int)
        image = request.form.get('cover')
        play_url = request.form.get('play_url', '')
        play_code = request.form.get('play_code', '')
        play_html = request.form.get('play_html', '')
        subtitle = request.form.get('subtitle')
        content = request.form.get('content')
    except:
        raise InvalidArgument()

    EventNewsService.add(
        eid, type, source, source_name, title, isvr,
        image, play_url, play_code, play_html, subtitle, content)

    return redirect(url_for('admin.list_event_news', event_id=event_id))


@bp.route('/event/news/<int:news_id>/edit', methods=['GET', 'POST'])
def edit_event_news(news_id):

    redirect_url = request.args.get('redirect', '')

    if request.method == 'GET':
        sites = EventNewsService.get_sites()
        news = EventNewsService.get_one(news_id).to_dict()
        return render_template(
            'admin/event/news_edit.html', sites=sites, news=news)

    eid = request.form.get('event_id', type=int)
    type = request.args.get('type')
    source = request.form.get('site')
    source_name = request.form.get('site_name')
    title = request.form.get('title')
    isvr = request.form.get('isvr', 0, int)
    image = request.form.get('cover')
    play_url = request.form.get('play_url', '')
    play_code = request.form.get('play_code', '')
    play_html = request.form.get('play_html', '')
    subtitle = request.form.get('subtitle')
    content = request.form.get('content')

    EventNewsService.edit(
        news_id, eid, type, source, source_name,
        title, isvr, image, play_url, play_code, play_html, subtitle, content)

    if redirect_url == '':
        return redirect(url_for('admin.list_event_news'))
    else:
        return redirect(redirect_url)


@bp.route('/event/news/<int:news_id>/top', methods=['GET'])
def top_event_news(news_id):

    try:
        top = int(request.args.get('value'))
        type = request.args.get('type')
    except:
        raise InvalidArgument()

    EventNewsService.top(news_id, top=top, type=type)

    return jsonify_with_data((200, 'OK'))


@bp.route('/event/news/<int:news_id>/pin', methods=['GET'])
def pin_event_news(news_id):

    try:
        pin = int(request.args.get('value'))
    except:
        raise InvalidArgument()

    value = bool(pin)

    EventNewsService.pin(news_id, pin=value)

    return jsonify_with_data((200, 'OK'))


@bp.route('/event/news/<int:news_id>/delete')
def delete_event_news(news_id):
    EventNewsService.delete(news_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/event/news/<int:news_id>/show')
def show_event_news(news_id):
    EventNewsService.show(news_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/event/news/<int:news_id>/hide')
def hide_event_news(news_id):
    EventNewsService.hide(news_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/event/<int:match_id>/forecast', methods=['GET', 'POST'])
def add_forecast(match_id):

    event_id = request.args.get('event_id', 0)
    event_id = int(event_id)
    match = MatchService.get_one(match_id)
    default_tm = request.args.get('date')
    if request.method == 'GET':
        return render_template('admin/event/add_forecast.html', match=match)

    match_dict = request.form.to_dict()
    MatchService.edit(match_id, match_dict)

    return redirect(url_for('admin.list_hot_matches', event_id=event_id,date=default_tm))
