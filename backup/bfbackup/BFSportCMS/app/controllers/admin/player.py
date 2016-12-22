# -*- coding: utf-8 -*-

from flask import request
from flask import render_template

from app.service import PlayerService
from app.service import TeamService
from app.utils.filters import Pagination
from app.utils.serialization import jsonify_with_data

from . import bp
from . import APIError


@bp.route('/players', methods=['GET'])
def list_players():

    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)

    players = PlayerService.get_show_all((page - 1) * count, count)
    player_count = PlayerService.count()
    pagination = Pagination(page, count, player_count)

    return render_template(
        'admin/player/list.html',
        pagination=pagination,
        players=players)


@bp.route('/players/teams', methods=['GET'])
def list_player_teams():
    teams = TeamService.get_all()
    return jsonify_with_data(APIError.OK, teams=teams)


@bp.route('/players/add', methods=['POST'])
def add_player():

    add_dict = request.form.to_dict()
    if add_dict['height']:
        height = int(add_dict['height'])
    else:
        height = 0
    if add_dict['weight']:
        weight = int(add_dict['weight'])
    else:
        weight = 0
    add_dict['height'] = height
    add_dict['weight'] = weight
    PlayerService.add(add_dict)
    return jsonify_with_data(APIError.OK)


@bp.route('/players/<int:player_id>/edit', methods=['POST'])
def edit_player(player_id):

    add_dict = request.form.to_dict()
    PlayerService.edit(player_id, add_dict)
    return jsonify_with_data(APIError.OK)


@bp.route('/players/<int:player_id>/delete')
def delete_player(player_id):
    PlayerService.delete(player_id)
    return jsonify_with_data(APIError.OK)
