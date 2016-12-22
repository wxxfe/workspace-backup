# -*- coding: utf-8 -*-

from app.models import TeamClass
from app.utils.serialization import jsonify_with_data

from . import bp
from . import APIError


@bp.route('/board/demo')
def get_team_class():

    teams = TeamClass.query.filter_by()\
                                         .order_by(TeamClass.display_order.asc())\
                                         .all()

    team_class = [team.to_dict() for team in teams]

    return jsonify_with_data(APIError.OK, team_class=team_class)

