# -*- coding: utf-8 -*-

from datetime import date, datetime
from app.utils.text import date_to_timestamp, timestamp_to_datetime

from flask import request
from flask import render_template
from flask import redirect
from flask import url_for

from app.models import Collection

from app.service import MatchNewsService
from app.service import EventNewsService
from app.service import OGScheduleService
from app.service import CollectionService
from app.service import OGNewsService
from app.service import OGVideoService
from app.service import CollectionVideoService

from app.utils.filters import Pagination
from app.utils.serialization import jsonify_with_data
from app.utils.serialization import jsonify_with_error
from app.utils.text import add_image_domain

from . import bp
from . import APIError
from .handler import InvalidArgument


@bp.route('/olympics/collections', methods=['GET'])
def list_collections():

    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)

    type = request.args.get('type', 'all')
    programs = CollectionService.get_all(type, (page - 1) * count, count)

    programs_count = CollectionService.count()

    pagination = Pagination(page, count, programs_count)

    return render_template(
        'admin/olympics/collection/list.html',
        programs=programs,
        type=type,
        pagination=pagination)


@bp.route(
    '/olympics/collections/<int:collection_id>/videos',
    methods=['GET', 'POST'])
def list_collections_videos(collection_id):
    item = Collection.query.get(collection_id)

    def add_info_image(video):
        video.image = add_image_domain(video.image)
        return video

    videos = map(add_info_image, item.videos)

    return render_template(
        'admin/olympics/collection/videos.html',
        collection_id=collection_id,
        videos=videos)


@bp.route(
    '/olympics/collections/<int:collection_id>/videos/<int:video_id>/relate',
    methods=['POST'])
def relate_collections_videos(collection_id, video_id):
    video = OGVideoService.get_one(video_id)
    if not video:
        return jsonify_with_error((10001, u"该视频不存在！"))

    item = CollectionVideoService.get_filter_one(collection_id, video_id)
    if item:
        return jsonify_with_error((10001, u"该视频已关联到该合集！"))

    CollectionVideoService.add(collection_id, video_id)
    return jsonify_with_data((200, 'OK'))


@bp.route(
    '/olympics/collections/<int:collection_id>/videos/<int:video_id>/delete',
    methods=['GET', 'POST'])
def delete_collections_videos(collection_id, video_id):
    CollectionVideoService.delete(collection_id, video_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/collections/add', methods=['GET', 'POST'])
def add_collection():

    if request.method == 'GET':
        return render_template('admin/olympics/collection/add.html')

    try:
        title = request.form.get('title')
        type = request.form.get('type')
        image = request.form.get('cover', '')
    except:
        raise InvalidArgument()

    CollectionService.add(title, type, image)

    return redirect(url_for('admin.list_collections'))


@bp.route(
    '/olympics/collections/<int:collection_id>/edit', methods=['GET', 'POST'])
def edit_collection(collection_id):

    item = CollectionService.get_one(collection_id)
    if request.method == 'GET':
        return render_template(
            'admin/olympics/collection/edit.html', item=item)

    title = request.form.get('title')
    type = request.form.get('type')
    image = request.form.get('cover')

    CollectionService.edit(collection_id, title, type, image)

    return redirect(url_for('admin.list_collections'))


@bp.route('/olympics/collections/<int:collection_id>/delete')
def delete_collection(collection_id):
    CollectionService.delete(collection_id)
    return jsonify_with_data(APIError.OK)


@bp.route('/olympics/collections/<int:collection_id>/show')
def show_olympics_collection(collection_id):
    CollectionService.show(collection_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/collections/<int:collection_id>/hide')
def hide_olympics_collection(collection_id):
    CollectionService.hide(collection_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/videos', methods=['GET', 'POST'])
def list_olympics_videos():
    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)
    keyword = request.form.get('keyword')

    if keyword is not None:
        videos = OGVideoService.search_by_name(keyword)
        return render_template(
            'admin/olympics/video/videos_list.html',
            videos=videos)

    type = request.args.get('type', 'all')
    videos = OGVideoService.get_all(type, (page - 1) * count, count)
    videos_count = OGVideoService.count(type=type)
    pagination = Pagination(page, count, videos_count)

    return render_template(
        'admin/olympics/video/videos_list.html',
        videos=videos,
        pagination=pagination,
        current_type=type)


@bp.route('/olympics/videos/add', methods=['GET', 'POST'])
def add_olympics_video():

    type = request.args.get('type', 'all')

    if request.method == 'GET':
        return render_template(
            'admin/olympics/video/videos_add.html',
            type=type)

    title = request.form.get('title')
    isvr = request.form.get('isvr', 0, int)
    type = request.form.get('type')
    duration = request.form.get('duration')
    image = request.form.get('cover')
    play_url = request.form.get('play_url')
    play_code = request.form.get('play_code')

    OGVideoService.add(
        title, isvr, type, 'bfonline',
        duration, image, play_url, play_code)

    return redirect(url_for('admin.list_olympics_videos'))


@bp.route('/olympics/videos/<int:video_id>/edit', methods=['GET', 'POST'])
def edit_olympics_video(video_id):

    video = OGVideoService.get_one(video_id)
    if request.method == 'GET':
        return render_template(
            'admin/olympics/video/videos_edit.html',
            video=video.to_dict())

    title = request.form.get('title')
    isvr = request.form.get('isvr', 0, int)
    image = request.form.get('cover')
    play_url = request.form.get('play_url')
    play_code = request.form.get('play_code')

    OGVideoService.edit(video_id, title, isvr, image, play_url, play_code)

    return redirect(url_for('admin.list_olympics_videos'))


@bp.route('/olympics/videos/<int:video_id>/show')
def show_olympics_video(video_id):
    OGVideoService.show(video_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/videos/<int:video_id>/hide')
def hide_olympics_video(video_id):
    OGVideoService.hide(video_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/videos/<int:video_id>/top', methods=['GET'])
def top_olympics_video(video_id):

    try:
        top = int(request.args.get('value'))
    except:
        raise InvalidArgument()

    value = bool(top)

    OGVideoService.top(video_id, top=value)

    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/videos/<int:video_id>/delete')
def delete_olympics_video(video_id):
    OGVideoService.delete(video_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/schedules')
def list_olympics_schedules():

    default_tm = request.args.get('date')
    if not default_tm:
        default_tm = timestamp_to_datetime(date_to_timestamp(date.today()))
    else:
        string_to_tm = datetime.strptime(
            default_tm, "%Y-%m-%d")
        default_tm = string_to_tm

    schedules = [
        {'date': (u'星期二', '8-2'), 'id': '2016-08-02'},
        {'date': (u'星期三', '8-3'), 'id': '2016-08-03'},
        {'date': (u'星期四', '8-4'), 'id': '2016-08-04'},
        {'date': (u'星期五', '8-5'), 'id': '2016-08-05'},
        {'date': (u'星期六', '8-6'), 'id': '2016-08-06'},
        {'date': (u'星期日', '8-7'), 'id': '2016-08-07'},
        {'date': (u'星期一', '8-8'), 'id': '2016-08-08'},
        {'date': (u'星期二', '8-9'), 'id': '2016-08-09'},
        {'date': (u'星期三', '8-10'), 'id': '2016-08-10'},
        {'date': (u'星期四', '8-11'), 'id': '2016-08-11'},
        {'date': (u'星期五', '8-12'), 'id': '2016-08-12'},
        {'date': (u'星期六', '8-13'), 'id': '2016-08-13'},
        {'date': (u'星期日', '8-14'), 'id': '2016-08-14'},
        {'date': (u'星期一', '8-15'), 'id': '2016-08-15'},
        {'date': (u'星期二', '8-16'), 'id': '2016-08-16'},
        {'date': (u'星期三', '8-17'), 'id': '2016-08-17'},
        {'date': (u'星期四', '8-18'), 'id': '2016-08-18'},
        {'date': (u'星期五', '8-19'), 'id': '2016-08-19'},
        {'date': (u'星期六', '8-20'), 'id': '2016-08-20'},
        {'date': (u'星期日', '8-21'), 'id': '2016-08-21'},
    ]

    matches = OGScheduleService.get_all(default_tm)

    return render_template(
        'admin/olympics/schedule/schedule_list.html',
        matches=matches,
        sDate=default_tm.strftime('%Y-%m-%d'),
        schedules=schedules)


@bp.route('/olympics/schedules/<int:schedule_id>/top')
def top_olympics_schedules(schedule_id):
    try:
        top = int(request.args.get('value'))
    except:
        raise InvalidArgument()
    OGScheduleService.top(schedule_id, top=top)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/schedules/add', methods=['GET', 'POST'])
def add_olympics_schedule():
    if request.method == 'GET':
        return render_template('admin/olympics/schedule/add_schedule.html')

    date = request.args.get('date')
    start_tm = request.form.get('start_tm')
    large_project = request.form.get('large_project')
    small_project = request.form.get('small_project')
    round = request.form.get('round')

    OGScheduleService.add(start_tm, large_project, small_project, round)

    return redirect(url_for('admin.list_olympics_schedules', date=date))


@bp.route(
    '/olympics/schedules/<int:schedule_id>/edit', methods=['GET', 'POST'])
def edit_olympics_schedule(schedule_id):

    schedule = OGScheduleService.get_one(schedule_id)
    if request.method == 'GET':
        return render_template(
            'admin/olympics/schedule/edit_schedule.html',
            schedule=schedule.to_dict())

    date = request.args.get('date')
    start_tm = request.form.get('start_tm')
    large_project = request.form.get('large_project')
    small_project = request.form.get('small_project')
    round = request.form.get('round')

    OGScheduleService.edit(
        schedule_id, start_tm, large_project, small_project, round)

    return redirect(url_for('admin.list_olympics_schedules', date=date))


@bp.route('/olympics/schedules/<int:schedule_id>/delete')
def delete_olympics_OGScheduleService(schedule_id):
    OGScheduleService.delete(schedule_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/news', methods=['GET', 'POST'])
def list_olympics_news():

    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)

    try:
        type = request.args.get('type', 'all')
    except:
        raise InvalidArgument()

    news = OGNewsService.get_all(type, (page - 1) * count, count)

    if type == 'all':
        news_count = OGNewsService.count()
    else:
        news_count = OGNewsService.count(type=type)

    pagination = Pagination(page, count, news_count)

    def add_info_sync(news):
        value = EventNewsService.search_by_title(news['title'])
        news['sync'] = bool(value)
        return news

    news = map(add_info_sync, news)

    return render_template(
        'admin/olympics/news/news_list.html',
        news=news,
        pagination=pagination,
        type=type)


@bp.route('/olympics/news/add', methods=['GET', 'POST'])
def add_olympics_news():

    if request.method == 'GET':
        sites = MatchNewsService.get_sites()
        return render_template(
            'admin/olympics/news/news_add.html', sites=sites)

    try:
        type = request.form.get('type')
        title = request.form.get('title')
        site = request.form.get('site')
        subtitle = request.form.get('subtitle')
        content = request.form.get('content')
        image = request.form.get('cover')
    except:
        raise InvalidArgument()

    OGNewsService.add(type, title, subtitle, content, image, site)

    return redirect(url_for('admin.list_olympics_news'))


@bp.route('/olympics/news/<int:news_id>/edit', methods=['GET', 'POST'])
def edit_olympics_news(news_id):

    if request.method == 'GET':
        news = OGNewsService.get_one(news_id).to_dict()
        sites = MatchNewsService.get_sites()
        return render_template(
            'admin/olympics/news/news_edit.html', news=news, sites=sites)

    type = request.form.get('type')
    title = request.form.get('title')
    subtitle = request.form.get('subtitle')
    content = request.form.get('content')
    image = request.form.get('cover')

    OGNewsService.edit(news_id, type, title, subtitle, content, image)

    return redirect(url_for('admin.list_olympics_news'))


@bp.route('/olympics/news/<int:news_id>/top', methods=['GET'])
def top_olympics_news(news_id):

    try:
        top = int(request.args.get('value'))
    except:
        raise InvalidArgument()

    OGNewsService.top(news_id, top=top)

    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/news/<int:news_id>/delete')
def delete_olympics_news(news_id):
    OGNewsService.delete(news_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/news/<int:news_id>/show')
def show_olympics_news(news_id):
    OGNewsService.show(news_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/news/<int:news_id>/hide')
def hide_olympics_news(news_id):
    OGNewsService.hide(news_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/news/<int:news_id>/sync', methods=['GET'])
def sync_olympics_news(news_id):

    news = OGNewsService.get_one(news_id)
    try:
        event_id = 14
        EventNewsService.add(
            event_id, 'literal', news.site, '',
            news.title, 0, news.image, '', '',
            '', news.subtitle, news.content, '', news.created_at)
    except:
        raise InvalidArgument()

    return redirect(url_for('admin.list_olympics_news'))
