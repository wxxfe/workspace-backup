# -*- coding: utf-8 -*-
from datetime import date, datetime

from flask import request
from flask import render_template
from app.service import MainBoardSlideService
from app.service import ChannelService
from flask import redirect
from flask import url_for
from app.utils.serialization import jsonify_with_data

from app.service import MatchService
from app.utils.text import timestamp_to_datetime
from app.utils.text import date_to_timestamp
from app.service import EventService
from app.models import MatchStatus
from app.service import MainBoardSelectedMatchService
from app.service import MainBoardHotspotService
from app.service import MainBoardDiscoveryService
from app.service import MainBoardColumnService
from app.service import MainBoardColumnPostService
from app.utils.text import to_cn_date

from . import bp

@bp.route('/mainboard/slides')
def mainboard_list_slides():
    platform = request.args.get('platform', 'app')

    channel_id = int(request.args.get('channel_id', 0))

    slides = MainBoardSlideService.get_all()

    if channel_id == 0:
        slides = MainBoardSlideService.get_all(channel_id=channel_id)

    if channel_id:
        slides = MainBoardSlideService.get_all(channel_id=channel_id)

    channels = ChannelService.get_all(type='football')

    return render_template(
                                          'admin/mainboard/slide/list.html',
                                          slides=slides,
                                          channel_id=channel_id,
                                          channels=channels)


@bp.route('/mainboard/slides/add', methods=['GET', 'POST'])
def mainboard_add_slide():
    channels = ChannelService.get_shown_all()
    if request.method == 'GET':
        return render_template(
            'admin/mainboard/slide/add.html',
            channels=channels)
    obj_dict = request.form.to_dict()
    if not obj_dict['position']:
        obj_dict['position'] = 0
    obj_dict['image'] = obj_dict['cover']
    obj_dict['large_image'] = obj_dict['cover_large']
    if any(obj_dict['ref_id']) == False :
        obj_dict['ref_id'] = 0
    del (obj_dict['cover'])
    del (obj_dict['cover_large'])
    MainBoardSlideService.add(obj_dict)
    return redirect(url_for('admin.mainboard_list_slides'))


@bp.route('/mainboard/slides/<int:slide_id>/edit', methods=['GET', 'POST'])
def mainboard_edit_slide(slide_id):
    slide = MainBoardSlideService.get_one(slide_id)
    if request.method == 'GET':
        return render_template(
                                              'admin/mainboard/slide/edit.html',
                                              slide=slide)
    obj_dict = request.form.to_dict()
    obj_dict['image'] = obj_dict['cover']
    obj_dict['large_image'] = obj_dict['cover_large']
    del (obj_dict['cover'])
    del (obj_dict['cover_large'])
    if any(obj_dict['ref_id']) == False:
        obj_dict['ref_id'] = 0
    MainBoardSlideService.edit(slide_id, obj_dict)
    return redirect(url_for('admin.mainboard_list_slides'))


@bp.route('/mainboard/slides/<int:slide_id>/delete')
def mainboard_delete_slide(slide_id):
    MainBoardSlideService.delete(slide_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/mainboard/selectedmatch')
def mainboard_list_discuss_matches():
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
        match_id = match.id
        match = MatchService.get_match_info(match_id)
        match['isHaveLiveVideo'] = MatchService.isHaveLiveVideo(match_id)
        return match


    # all finished matches
    match_list = EventService.get_all(
        event_id=event_id,
        default_tm=default_tm)
    matches = map(get_match_info, match_list)


    # get selected match info
    def get_selected_match(match):
        display_order = match.display_order
        ex_selected_match_id = match.id
        match = MatchService.get_match_info(match.match_id)
        match['display_order']=display_order
        match['ex_selected_match_id']=ex_selected_match_id
        return match


    # selected matches
    selected_match_list = MainBoardSelectedMatchService.get_all(
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
    notice_exist = request.args.get('notice_exist', 0)
    return render_template('admin/mainboard/selectedmatch/list.html',
                           event_id=event_id,
                           matches=matches,
                           schedules=schedules,
                           selected_matches=selected_matches,
                           notice_exist=notice_exist)


#推荐比赛
@bp.route('/mainboard/<int:match_id>/recommend')
def mainboard_recommend_match(match_id):
    match = MainBoardSelectedMatchService.get_one(match_id)
    notice_exist=0
    if match:
        notice_exist = 1
    else:
        MainBoardSelectedMatchService.add(match_id=match_id)
    return redirect(url_for('admin.mainboard_list_discuss_matches',notice_exist=notice_exist))


@bp.route('/mainboard/<int:match_id>/unrecommend')
def mainboard_unrecommend_match(match_id):
    MainBoardSelectedMatchService.delete(match_id=match_id)
    return redirect(request.referrer)


@bp.route('/mainboard/<int:ex_selected_match_id>/<int:neworder>/modifyorder', methods=['GET', 'POST'])
def mainboard_modifyorder(ex_selected_match_id,neworder):
    MainBoardSelectedMatchService.setAttr(ex_selected_match_id,'display_order',neworder)
    return jsonify_with_data((200, 'OK'))


#今日热点
@bp.route('/mainboard/hotspot/list',methods=['GET'])
def mainboard_list_hostspot():
    #pagenum = 3;
    #cpage = request.args.get('cpage')
    hotspots = MainBoardHotspotService.get_all()
    return render_template('admin/mainboard/hotspot/list.html',hotspots=hotspots,)


@bp.route('/mainboard/hotspot/add', methods=['GET', 'POST'])
def mainboard_add_hotspot():
    if request.method == 'GET':
        return render_template(
            'admin/mainboard/hotspot/add.html')
    obj_dict = request.form.to_dict()
    if any(obj_dict['display_order']) == False:
        obj_dict['display_order'] = 0
    if any(obj_dict['ref_id']) == False:
        obj_dict['ref_id'] = 0
    obj_dict['image'] = obj_dict['cover']
    del (obj_dict['cover'])
    MainBoardHotspotService.add(obj_dict)
    return redirect(url_for('admin.mainboard_list_hostspot'))


@bp.route('/mainboard/hotspot/<int:hotspot_id>/delete')
def mainboard_delete_hotspot(hotspot_id):
    MainBoardHotspotService.delete(hotspot_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/mainboard/hotspot/<int:hotspot_id>/edit', methods=['GET', 'POST'])
def mainboard_edit_hotspot(hotspot_id):
    hotspot = MainBoardHotspotService.get_one(hotspot_id)
    if request.method == 'GET':
        return render_template('admin/mainboard/hotspot/edit.html',hotspot=hotspot)
    hotspot_dict = request.form.to_dict()
    hotspot_dict['image'] = hotspot_dict['cover']
    del (hotspot_dict['cover'])
    if any(hotspot_dict['ref_id']) == False:
        hotspot_dict['ref_id'] = 0
    MainBoardHotspotService.edit(hotspot_id, hotspot_dict)
    return redirect(request.referrer)


@bp.route('/mainboard/hotspot/<int:hotspot_id>/<int:neworder>/modifyorder', methods=['GET', 'POST'])
def mainboard_modifyorder_hotspot(hotspot_id,neworder):
    MainBoardHotspotService.setAttr(hotspot_id,'display_order',neworder)
    return jsonify_with_data((200, 'OK'))


#发现频道
@bp.route('/mainboard/discovery/list',methods=['GET'])
def mainboard_list_discovery():
    #pagenum = 3;
    #cpage = request.args.get('cpage')
    items = MainBoardDiscoveryService.get_all()
    return render_template('admin/mainboard/discovery/list.html',items=items,)


@bp.route('/mainboard/discovery/add', methods=['GET', 'POST'])
def mainboard_add_discovery():
    if request.method == 'GET':
        return render_template(
            'admin/mainboard/discovery/add.html')
    discovery_dict = request.form.to_dict()
    discovery_dict['image'] = discovery_dict['cover']
    del (discovery_dict['cover'])
    MainBoardDiscoveryService.add(discovery_dict)
    return redirect(request.referrer)


@bp.route('/mainboard/discovery/<int:discovery_id>/delete')
def mainboard_delete_discovery(discovery_id):
    MainBoardDiscoveryService.delete(discovery_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/mainboard/discovery/<int:discovery_id>/edit', methods=['GET', 'POST'])
def mainboard_edit_discovery(discovery_id):
    discovery = MainBoardDiscoveryService.get_one(discovery_id)
    if request.method == 'GET':
        return render_template('admin/mainboard/discovery/edit.html',discovery=discovery)
    discovery_dict = request.form.to_dict()
    discovery_dict['image'] = discovery_dict['cover']
    del (discovery_dict['cover'])
    MainBoardDiscoveryService.edit(discovery_id, discovery_dict)
    return redirect(request.referrer)


@bp.route('/mainboard/discovery/<int:discovery_id>/<int:neworder>/modifyorder', methods=['GET', 'POST'])
def mainboard_modifyorder_discovery(discovery_id,neworder):
    MainBoardHotspotService.setAttr(discovery_id,'display_order',neworder)
    return jsonify_with_data((200, 'OK'))


#专栏
@bp.route('/mainboard/column/list',methods=['GET'])
def mainboard_list_column():
    columns = MainBoardColumnService.get_all()
    return render_template('admin/mainboard/column/list.html',columns=columns,)


@bp.route('/mainboard/column/add', methods=['GET', 'POST'])
def mainboard_add_column():
    if request.method == 'GET':
        return render_template(
            'admin/mainboard/column/add.html')
    column_dict = request.form.to_dict()
    MainBoardColumnService.add(column_dict)
    if any(column_dict['display_order']) == False:
        column_dict['display_order'] = 0
    return redirect(url_for('admin.mainboard_list_column'))


@bp.route('/mainboard/column/<int:column_id>/delete')
def mainboard_delete_column(column_id):
    MainBoardColumnService.delete(column_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/mainboard/column/<int:column_id>/edit', methods=['GET', 'POST'])
def mainboard_edit_column(column_id):
    column = MainBoardColumnService.get_one(column_id)
    if request.method == 'GET':
        return render_template('admin/mainboard/column/edit.html',column=column)
    column_dict = request.form.to_dict()
    if any(column_dict['display_order']) == False:
        column_dict['display_order'] = 0
    MainBoardColumnService.edit(column_id, column_dict)
    return redirect(request.referrer)


@bp.route('/mainboard/column/<int:column_id>/<int:neworder>/modifyorder', methods=['GET', 'POST'])
def mainboard_modifyorder_column(column_id,neworder):
    MainBoardColumnService.setAttr(column_id,'display_order',neworder)
    return jsonify_with_data((200, 'OK'))


#专栏内容
@bp.route('/mainboard/column/post/list',methods=['GET'])
def mainboard_list_column_post():
    column_id = request.args.get('column_id', 0)
    columnPost = MainBoardColumnPostService.get_all(column_id)
    return render_template('admin/mainboard/column/list_post.html', columnPost=columnPost,column_id=column_id )


@bp.route('/mainboard/column/post/add', methods=['GET', 'POST'])
def mainboard_add_column_post():
    if request.method == 'GET':
        column_id = request.args.get('column_id', 0)
        return render_template(
            'admin/mainboard/column/add_post.html',column_id=column_id)
    column_id = request.args.get('column_id', 0)
    column_post_dict = request.form.to_dict()
    if any(column_post_dict['display_order']) == False:
        column_post_dict['display_order'] = 0
    if any(column_post_dict['ref_id']) == False:
        column_post_dict['ref_id'] = 0
    column_post_dict['image'] = column_post_dict['cover']
    del (column_post_dict['cover'])
    MainBoardColumnPostService.add(column_post_dict)
    return redirect(url_for('admin.mainboard_list_column_post',column_id=column_id))


@bp.route('/mainboard/column/post/<int:column_post_id>/delete')
def mainboard_delete_column_post(column_post_id):
    MainBoardColumnPostService.delete(column_post_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/mainboard/column/post/<int:column_post_id>/edit', methods=['GET', 'POST'])
def mainboard_edit_column_post(column_post_id):
    column_post = MainBoardColumnPostService.get_one(column_post_id)
    if request.method == 'GET':
        return render_template('admin/mainboard/column/edit_post.html',column_post=column_post)
    column_post_dict = request.form.to_dict()
    column_post_dict['image'] = column_post_dict['cover']
    if any(column_post_dict['ref_id']) == False:
        column_post_dict['ref_id'] = 0
    del (column_post_dict['cover'])
    MainBoardColumnPostService.edit(column_post_id, column_post_dict)
    return redirect(request.referrer)

@bp.route('/mainboard/column/post/<int:column_post_id>/<int:neworder>/modifyorder', methods=['GET', 'POST'])
def mainboard_modifyorder_column_post(column_post_id,neworder):
    MainBoardColumnPostService.setAttr(column_post_id,'display_order',neworder)
    return jsonify_with_data((200, 'OK'))
