# -*- coding: utf-8 -*-

import json

from flask import request
from flask import url_for
from flask import redirect
from flask import render_template

from app.service import ChannelService
from app.service import PendingVideoService
from app.service import ProgramService
from app.service import EventNewsService
from app.service import ProgramPostService
from app.service import MatchNewsService
from app.service import MatchService
from app.service import MatchVideoService
from app.service import OGVideoService

from app.utils.filters import Pagination
from app.utils.text import add_image_domain
from app.utils.serialization import jsonify_with_data

from . import bp
from .handler import InvalidArgument, NotFound


@bp.route('/media/resources', methods=['GET', 'POST'])
def list_resources():

    fresh = request.args.get('fresh', 0, type=int)

    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)
    keyword = request.form.get('keyword')

    def get_resource_info(item):
        data = eval(item['data'])
        if 'cid' in data.keys():
            item['cid'] = data['cid']
        item['image'] = add_image_domain(data['image'])
        return item

    if keyword is not None:
        resources = PendingVideoService.search_by_name(keyword)
        resources = map(get_resource_info, resources)
        return render_template(
            'admin/media/list.html',
            fresh=fresh,
            resources=resources)

    resources = PendingVideoService.get_all(fresh, (page - 1) * count, count)
    resources_count = PendingVideoService.count(fresh)
    pagination = Pagination(page, count, resources_count)

    resources = map(get_resource_info, resources)

    return render_template(
        'admin/media/list.html',
        fresh=fresh,
        pagination=pagination,
        resources=resources)


@bp.route('/media/resources/<int:resource_id>/relate', methods=['GET', 'POST'])
def relate_resource(resource_id):

    resource = PendingVideoService.get_one(resource_id)
    tmp = json.loads(resource['data'])
    if request.method == 'GET':
        if tmp['tags']:
            tags = list(set(tmp['tags']))
            lable = [s.split(';')[1] for s in tags]
        else:
            lable = []
        resource['lable'] = '/'.join(lable)
        events = ChannelService.get_all(type='football')
        programs = ProgramService.get_simple_all()

        return render_template(
            'admin/media/relate.html',
            events=events,
            programs=programs,
            resource=resource)

    type = request.form.get('type')
    isvr = request.form.get('isvr', 0, int)

    args_dict = {}
    args_dict['cid'] = tmp['cid']
    args_dict['size'] = tmp['size']
    args_dict = json.dumps(args_dict)

    if type == 'event_video':
        event_id = int(request.form.get('event_id'))
        # add to eventnews table
        EventNewsService.add(
            event_id, 'video', 'bfonline', '暴风体育',
            tmp['title'], isvr, tmp['image'], '', tmp['play_code'],
            tmp['play_code'], '', '', args_dict, resource['created_at'])
    elif type == 'program':
        program_id = request.form.get('program_id')
        # add to program post table
        ProgramPostService.add(
            program_id, tmp['title'], isvr, 'bfonline', '暴风体育', '',
            tmp['image'], '', tmp['play_code'], tmp['play_code'], resource['created_at'], args_dict)
    elif type == 'olympic':
        OGVideoService.add(
            tmp['title'], isvr, 'highlight', 'bfonline',
            tmp['duration'], tmp['image'], '', tmp['play_code'],
            args_dict, resource['created_at'])
    else:
        try:
            match_id = request.form.get('match_id')
        except:
            raise InvalidArgument()

        match = MatchService.get_one(match_id)
        if not match:
            raise NotFound()

        if type == 'match_video':
            MatchNewsService.add(
                match_id, 'video', 'bfonline', '暴风体育',
                tmp['title'], isvr, tmp['image'], '', tmp['play_code'],
                tmp['play_code'], '', '', args_dict, resource['created_at'])
        else:
            MatchVideoService.add(
                match_id, tmp['title'], isvr, type, 'bfonline',
                tmp['duration'], tmp['image'], '', tmp['play_code'],
                tmp['play_code'], args_dict, resource['created_at'])

    PendingVideoService.relate(resource_id)

    return redirect(url_for('admin.list_resources'))


@bp.route('/media/resources/<int:resource_id>/delete')
def delete_resource(resource_id):
    PendingVideoService.delete(resource_id)
    return jsonify_with_data((200, 'OK'))
