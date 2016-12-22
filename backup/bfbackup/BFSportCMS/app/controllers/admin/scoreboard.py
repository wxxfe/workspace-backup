# -*- coding: utf-8 -*-

from collections import defaultdict
import operator

from flask import request
from flask import render_template

from app.service import ChannelService
from app.service import EventTeamService
from app.service import TeamPlayerService
from app.service import ScoreboardService
from app.service import TopscorerService
from app.service import TopassistService
from app.service import TeamService
from app.service import PlayerService
from app.utils.serialization import jsonify_with_data

from . import bp
from . import APIError
from .handler import InvalidArgument


# cup scoreboard list
@bp.route('/cup/scoreboards', methods=['GET', 'POST'])
def list_cup_scoreboards():

    group_list = ['A', 'B', 'C', 'D', 'E', 'F']

    events = ChannelService.get_cups(iscup=1)

    if events:
        default_id = events[0]['id']
    else:
        return render_template(
            'admin/scoreboard/cup_list.html',
            groups=[],
            events=events
        )

    try:
        event_id = int(request.args.get('event_id', default_id))
    except:
        raise InvalidArgument()

    teams = EventTeamService.get_all(event_id=event_id)

    def get_selected_team(group):
        team_info = TeamService.get_one(group['team_id'])
        if team_info:
            group['name'] = team_info['name']
        return group

    teams = map(get_selected_team, teams)

    team_groups = EventTeamService.get_all(event_id)

    def get_team_info(group):
        team_info = TeamService.get_one(group['team_id'])
        if team_info:
            group['team_name'] = team_info['name']
            group['team_badge'] = team_info['badge']

        score = ScoreboardService.get_filter_one(
            group['event_id'], group['team_id'])
        if score:
            group['id'] = score['id']
            group['wins'] = score['wins']
            group['draws'] = score['draws']
            group['loses'] = score['loses']
            group['goals_differential'] = score['goals_differential']
            group['points'] = score['points']
            group['section'] = group['wins'] + group['draws'] + group['loses']

        return group

    team_groups = map(get_team_info, team_groups)

    groups = defaultdict(list)

    for item in team_groups:
        groups[item["group"]].append(item)

    count_len = len(groups.keys())

    group_list[0:count_len]

    # sort points
    for key in group_list:
        if groups[key]:
            for item in groups[key]:
                if 'points' in item.keys():
                    groups[key].sort(key=operator.itemgetter('points'), reverse=True)
                else:
                    item['points'] = 0

    return render_template(
        'admin/scoreboard/cup_list.html',
        groups=groups,
        group_list=group_list,
        teams=teams,
        event_id=event_id,
        events=events
    )


# league top list
@bp.route('/league/scoreboards', methods=['GET'])
def list_league_scoreboards():

    events = ChannelService.get_cups(iscup=0)

    if events:
        default_id = events[1]['id']
    else:
        return render_template(
            'admin/scoreboard/league_list.html',
            groups=[],
            events=events
        )

    try:
        event_id = int(request.args.get('event_id', default_id))
    except:
        raise InvalidArgument()

    teams = EventTeamService.get_all(event_id=event_id)

    def get_selected_team(group):
        team_info = TeamService.get_one(group['team_id'])
        if team_info:
            group['name'] = team_info['name']
        return group

    teams = map(get_selected_team, teams)

    scoreboard_teams = ScoreboardService.get_all(event_id=event_id)

    def get_group_info(num, group):
        group['ranking'] = num
        group['section'] = group['wins'] + group['draws'] + group['loses']
        team_info = TeamService.get_one(group['team_id'])
        if team_info:
            group['team_name'] = team_info['name']
            group['team_badge'] = team_info['badge']
        return group

    groups = [get_group_info(num+1, group) for num, group in enumerate(scoreboard_teams)]

    return render_template(
        'admin/scoreboard/league_list.html',
        groups=groups,
        teams=teams,
        event_id=event_id,
        events=events
    )


@bp.route('/scoreboards/add', methods=['GET', 'POST'])
def add_scoreboard():

    try:
        event_id = int(request.args.get('event_id'))
        add_dict = request.form.to_dict()
        add_dict['event_id'] = event_id
    except:
        raise InvalidArgument()

    ScoreboardService.add(add_dict)

    return jsonify_with_data(APIError.OK)


@bp.route('/scoreboards/<int:scoreboard_id>/edit', methods=['GET', 'POST'])
def edit_scoreboard(scoreboard_id):

    try:
        event_id = int(request.args.get('event_id'))
        add_dict = request.form.to_dict()
        add_dict['event_id'] = event_id
    except:
        raise InvalidArgument()

    ScoreboardService.edit(scoreboard_id, add_dict)

    return jsonify_with_data(APIError.OK)


@bp.route('/scoreboards/<int:scoreboard_id>/delete')
def delete_scoreboard(scoreboard_id):
    ScoreboardService.delete(scoreboard_id)
    return jsonify_with_data(APIError.OK)


# top scorer list
@bp.route('/top/scorer', methods=['GET'])
def list_topscorer():

    events = ChannelService.get_all(type='football')

    if events:
        default_id = events[1]['id']

    try:
        event_id = int(request.args.get('event_id', default_id))
    except:
        raise InvalidArgument()

    scorers = TopscorerService.get_all(event_id)

    def get_info(num, scorer):
        if scorer['player_id']:
            player = PlayerService.get_one(scorer['player_id'])
            scorer['player_photo'] = player['photo']
            scorer['player_name'] = player['name']
        team_info = TeamPlayerService.get_filter_one(scorer['player_id'])
        if team_info:
            scorer['team_id'] = team_info['team_id']
            team = TeamService.get_one(team_info['team_id'])
            if team:
                scorer['team_name'] = team['name']
        scorer['ranking'] = num
        return scorer

    scorers = [get_info(num+1, scorer) for num, scorer in enumerate(scorers)]

    teams = EventTeamService.get_all(event_id)

    def get_team_info(team):
        team = TeamService.get_one(team['team_id'])
        team['name'] = team['name']
        return team

    teams = map(get_team_info, teams)

    return render_template(
                                          'admin/scoreboard/list_scorers.html',
                                          datas=scorers,
                                          teams=teams,
                                          events=events,
                                          event_id=event_id)


@bp.route('/top/scorer/add', methods=['GET', 'POST'])
def add_scorer():

    try:
        event_id = request.args.get('event_id', type=int)
        player_id = request.form.get('player_id', type=int)
        goals = request.form.get('goals', type=int)
    except:
        raise InvalidArgument()

    TopscorerService.add(event_id, player_id, goals)

    return jsonify_with_data(APIError.OK)


@bp.route('/top/scorer/<int:scorer_id>/edit', methods=['POST'])
def edit_scorer(scorer_id):

    try:
        goals = request.form.get('goals', type=int)
    except:
        raise InvalidArgument()

    TopscorerService.edit(scorer_id, goals)

    return jsonify_with_data(APIError.OK)


@bp.route('/top/scorer/<int:scorer_id>/delete')
def delete_scorer(scorer_id):
    TopscorerService.delete(scorer_id)
    return jsonify_with_data(APIError.OK)


@bp.route('/top/scorer/<int:team_id>/players', methods=['GET'])
def get_team_player(team_id):
    items = TeamPlayerService.get_player_all(team_id=team_id)

    def get_info(item):
        team = PlayerService.get_one(item['player_id'])
        item['player_name'] = team['name']
        return item

    players = map(get_info, items)

    return jsonify_with_data(APIError.OK, players=players)


# topassist
@bp.route('/top/assist', methods=['GET'])
def list_topassist():

    events = ChannelService.get_all(type='football')

    if events:
        default_id = events[1]['id']

    try:
        event_id = int(request.args.get('event_id', default_id))
    except:
        raise InvalidArgument()

    scorers = TopassistService.get_all(event_id)

    def get_info(num, scorer):

        if scorer['player_id']:
            player = PlayerService.get_one(scorer['player_id'])
            scorer['player_photo'] = player['photo']
            scorer['player_name'] = player['name']
        team_info = TeamPlayerService.get_filter_one(scorer['player_id'])
        if team_info:
            scorer['team_id'] = team_info['team_id']
            team = TeamService.get_one(team_info['team_id'])
            if team:
                scorer['team_name'] = team['name']
        scorer['ranking'] = num

        return scorer

    scorers = [get_info(num+1, scorer) for num, scorer in enumerate(scorers)]

    teams = EventTeamService.get_all(event_id)

    def get_team_info(team):
        team = TeamService.get_one(team['team_id'])
        team['name'] = team['name']
        return team

    teams = map(get_team_info, teams)

    return render_template(
                                          'admin/scoreboard/list_assists.html',
                                          events=events,
                                          datas=scorers,
                                          teams=teams,
                                          event_id=event_id)


@bp.route('/top/assist/add', methods=['GET', 'POST'])
def add_assist():

    try:
        event_id = request.args.get('event_id', type=int)
        player_id = request.form.get('player_id', type=int)
        assists = request.form.get('assists', type=int)
    except:
        raise InvalidArgument()

    TopassistService.add(event_id, player_id, assists)

    return jsonify_with_data(APIError.OK)


@bp.route('/top/assist/<int:scorer_id>/edit', methods=['POST'])
def edit_assist(scorer_id):

    try:
        assists = request.form.get('assists', type=int)
    except:
        raise InvalidArgument()

    TopassistService.edit(scorer_id, assists)

    return jsonify_with_data(APIError.OK)


@bp.route('/top/assist/<int:scorer_id>/delete')
def delete_assist(scorer_id):
    TopassistService.delete(scorer_id)
    return jsonify_with_data(APIError.OK)
