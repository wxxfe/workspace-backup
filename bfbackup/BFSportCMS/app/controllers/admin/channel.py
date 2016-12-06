# -*- coding: utf-8 -*-

from flask import request
from flask import render_template
from flask import redirect
from flask import url_for

from app.service import SubPageService
from app.service import ChannelService
from app.service import NewChannelService
from app.service import WSHeadlineService
from app.utils.serialization import jsonify_with_data

from . import bp
from . import APIError
from .handler import InvalidArgument, ServerError


@bp.route('/new_channels', methods=['GET', 'POST'])
def list_new_channels():

    if request.method == 'GET':
        channels = NewChannelService.get_filter_all()
        events = ChannelService.get_all()
        eventsName = {}
        for item in events:
            eventsName[item['id']] = item['name']

        return render_template(
            'admin/channel/channel_list.html',
            eventName=eventsName,
            events=events,
            channels=channels)

    name = request.form.get('name')
    alias = request.form.get('alias')
    type = request.form.get('type')
    platform = request.form.get('platform')
    ref_id = request.form.get('ref_id', 0, type=int)

    # desplay order inc
    display_order = NewChannelService.count() + 1

    NewChannelService.add(name, alias, type, platform, ref_id, display_order)

    return redirect(url_for('admin.list_new_channels'))


@bp.route('/new_channels/<int:channel_id>/edit', methods=['GET', 'POST'])
def edit_new_channel(channel_id):
    name = request.form.get('name')
    alias = request.form.get('alias')
    platform = request.form.get('platform')
    NewChannelService.edit(channel_id, name, alias, platform)

    return jsonify_with_data((200, 'OK'))


@bp.route('/new_channels/<int:channel_id>/show')
def show_new_channel(channel_id):
    NewChannelService.show(channel_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/new_channels/<int:channel_id>/hide')
def hide_new_channel(channel_id):
    NewChannelService.hide(channel_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/new_channels/<int:channel_id>/delete')
def delete_new_channel(channel_id):
    NewChannelService.delete(channel_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/new_channels/sort', methods=['POST'])
def sort_new_channel():

    try:
        channel_id = int(request.form.get('id'))
        current = int(request.form.get('current'))
        final = int(request.form.get('final'))
    except:
        raise InvalidArgument()

    try:
        NewChannelService.sort(channel_id, current, final)
    except:
        raise ServerError()

    return jsonify_with_data(APIError.OK)


@bp.route('/channels')
def list_channels():
    channels = ChannelService.get_shown_all()
    return render_template('admin/channel/list.html', channels=channels)


@bp.route('/channels/add', methods=['GET', 'POST'])
def add_channel():
    if request.method == 'GET':
        return render_template('admin/channel/add.html')

    name = request.form.get('name')
    type = request.form.get('type')
    brief = request.form.get('brief')

    ChannelService.add(name, type, brief)

    return redirect(url_for('admin.list_channels'))


@bp.route('/channels/<int:channel_id>', methods=['GET', 'POST'])
def edit_channel(channel_id):

    channel = ChannelService.get_one(channel_id)
    if request.method == 'GET':
        return render_template('admin/channel/edit.html', channel=channel)

    name = request.form.get('name')
    type = request.form.get('type')
    brief = request.form.get('brief')

    ChannelService.edit(channel_id, name, type, brief)

    return redirect(url_for('admin.list_channels'))


@bp.route('/channels/<int:channel_id>/show')
def show_channel(channel_id):
    ChannelService.show(channel_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/channels/<int:channel_id>/hide')
def hide_channel(channel_id):
    ChannelService.hide(channel_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/channels/<int:channel_id>/delete')
def delete_channel(channel_id):
    ChannelService.delete(channel_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/channel_entrance', methods=['GET'])
def channel_entrance():

    # True is all and mobile
    events = ChannelService.get_all(type='football')

    if events:
        default_id = events[1]['id']
    else:
        return render_template(
            'admin/scoreboard/cup_list.html',
            pages=[]
        )

    try:
        event_id = int(request.args.get('event_id', default_id))
    except:
        raise InvalidArgument()

    pages = SubPageService.get_all(event_id=event_id)

    return render_template(
        'admin/channel/channel_entrance.html',
        events=events,
        event_id=event_id,
        pages=pages)


@bp.route('/channel_entrance/add', methods=['GET', 'POST'])
def add_channel_entrance():

    try:
        event_id = int(request.args.get('event_id'))
    except:
        raise InvalidArgument()

    title = request.form.get('title')
    brief = request.form.get('brief')
    target = request.form.get('target')
    display_order = SubPageService.count(event_id=event_id) + 1
    SubPageService.add(event_id, title, target, brief, display_order)

    return jsonify_with_data((200, 'OK'))


@bp.route('/channel_entrance/<int:subpage_id>/edit', methods=['GET', 'POST'])
def edit_channel_entrance(subpage_id):
    title = request.form.get('title')
    brief = request.form.get('brief')
    target = request.form.get('target')
    SubPageService.edit(subpage_id, title, target, brief)

    return jsonify_with_data((200, 'OK'))


@bp.route('/channel_entrance/sort', methods=['POST'])
def sort_channel_entrance():

    try:
        subpage_id = int(request.form.get('id'))
        current = int(request.form.get('current'))
        final = int(request.form.get('final'))
    except:
        raise InvalidArgument()

    try:
        SubPageService.sort(subpage_id, current, final)
    except:
        raise ServerError()

    return jsonify_with_data(APIError.OK)


@bp.route('/channel_entrance/<int:channel_id>/delete')
def delete_channel_entrance(channel_id):
    SubPageService.delete(channel_id)
    return jsonify_with_data((200, 'OK'))


# web site use
@bp.route('/website/headlines', methods=['GET'])
def list_ws_headline():

    # filter website
    channels = NewChannelService.get_filter_all(platf='website')

    if channels:
        default_id = channels[0]['id']
    else:
        return render_template(
            'admin/website/headlines_list.html',
            channels=channels
        )

    try:
        channel_id = int(request.args.get('channel_id', default_id))
    except:
        raise InvalidArgument()

    headlines = WSHeadlineService.get_filter_all(channel_id)

    return render_template(
        'admin/website/headlines_list.html',
        channel_id=channel_id,
        channels=channels,
        headlines=headlines
    )


@bp.route('/website/headlines/add', methods=['GET', 'POST'])
def add_ws_headline():

    channel_id = request.args.get('channel_id')

    if request.method == 'GET':
        return render_template(
            'admin/website/headline_add.html', channel_id=channel_id)

    try:
        html = request.form.get('html')
    except:
        raise InvalidArgument()

    WSHeadlineService.add(channel_id, html)

    return redirect(url_for(
        'admin.list_ws_headline', channel_id=channel_id))


@bp.route('/website/headlines/<int:headline_id>/edit', methods=['GET', 'POST'])
def edit_ws_headline(headline_id):

    channel_id = request.args.get('channel_id')

    if request.method == 'GET':
        headline = WSHeadlineService.get_one(headline_id)
        return render_template(
            'admin/website/headline_edit.html',
            headline=headline,
            channel_id=channel_id)

    html = request.form.get('html')

    WSHeadlineService.edit(headline_id, html)

    return redirect(url_for(
        'admin.list_ws_headline', channel_id=channel_id))


@bp.route('/website/headlines/<int:headline_id>/delete')
def delete_ws_headline(headline_id):
    WSHeadlineService.delete(headline_id)
    return jsonify_with_data((200, 'OK'))
