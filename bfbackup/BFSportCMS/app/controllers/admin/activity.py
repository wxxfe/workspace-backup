# -*- coding: utf-8 -*-

from flask import request
from flask import render_template
from flask import redirect
from flask import url_for

from app.service import ActivityService
from app.service import ChannelService
from app.utils.filters import Pagination
from app.utils.serialization import jsonify_with_data

from . import bp
from . import APIError
from .handler import InvalidArgument, ServerError


@bp.route('/activitys', methods=['GET'])
def list_activitys():

    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)

    events = ChannelService.get_all()
    activitys = ActivityService.get_all((page - 1) * count, count)

    activity_count = ActivityService.count()

    pagination = Pagination(page, count, activity_count)

    return render_template(
                                          'admin/activity/list.html',
                                          events=events,
                                          activitys=activitys,
                                          pagination=pagination)


@bp.route('/activitys/add', methods=['POST'])
def add_activity():

    add_dict = request.form.to_dict()
    add_dict['event_id'] = int(add_dict['event_id'])
    ActivityService.add(add_dict)

    return jsonify_with_data(APIError.OK)


@bp.route('/activitys/<int:activity_id>/edit', methods=['GET', 'POST'])
def edit_activity(activity_id):
    add_dict = request.form.to_dict()
    add_dict['event_id'] = int(add_dict['event_id'])

    ActivityService.edit(activity_id, add_dict)

    return jsonify_with_data(APIError.OK)


@bp.route('/activitys/<int:activity_id>/show')
def show_activity(activity_id):
    ActivityService.show(activity_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/activitys/<int:activity_id>/hide')
def hide_activity(activity_id):
    ActivityService.hide(activity_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/activitys/<int:activity_id>/delete')
def delete_activity(activity_id):
    ActivityService.delete(activity_id)
    return jsonify_with_data((200, 'OK'))
