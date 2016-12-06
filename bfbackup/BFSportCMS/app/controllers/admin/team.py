# -*- coding: utf-8 -*-

from flask import request
from flask import render_template

from app.service import ChannelService
from app.service import EventService
from app.service import TeamService
from app.service import EventTeamService
from app.utils.filters import Pagination
from app.utils.serialization import jsonify_with_data

from . import bp
from . import APIError
from .handler import InvalidArgument


@bp.route('/teams', methods=['GET', 'POST'])
def list_teams():

    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)
    keyword = request.form.get('keyword')

    def get_event_name(id):
        event = EventService.get_one(id)
        return event.name

    def get_event_info(team):

        event_teams = EventTeamService.get_filter_all(team_id=team['id'])

        event_info = {}

        if event_teams:
            for eteam in event_teams:
                event_info[eteam.event_id] = [get_event_name(eteam.event_id), eteam.group]

        team['event_info'] = event_info

        return team

    events = ChannelService.get_all()

    if keyword is not None:
        teams = TeamService.search_by_name(keyword)
        teams = map(get_event_info, teams)
        return render_template(
            'admin/team/list.html', teams=teams, events=events, keyword=keyword)

    teams = TeamService.get_show_all((page - 1) * count, count)
    teams = map(get_event_info, teams)

    team_count = TeamService.count()

    pagination = Pagination(page, count, team_count)

    return render_template(
        'admin/team/list.html',
        pagination=pagination,
        events=events,
        teams=teams)


def team_data_processing(add_dict):

    event_id_group_dict = eval(add_dict['event_id_group_dict'])

    if not event_id_group_dict:
        raise InvalidArgument()

    del (add_dict['event_id_group_dict'])
    del (add_dict['event_id'])
    del (add_dict['image'])

    return event_id_group_dict


@bp.route('/teams/add', methods=['POST'])
def add_team():

    add_dict = request.form.to_dict()

    TeamService.add(team_data_processing(add_dict), add_dict)

    return jsonify_with_data(APIError.OK)


@bp.route('/teams/<int:team_id>/edit', methods=['POST'])
def edit_team(team_id):

    add_dict = request.form.to_dict()

    team = TeamService.edit(team_data_processing(add_dict), team_id, add_dict)

    return jsonify_with_data(APIError.OK, team=team)


@bp.route('/teams/<int:team_id>/delete')
def delete_team(team_id):
    TeamService.delete(team_id)
    return jsonify_with_data(APIError.OK)
