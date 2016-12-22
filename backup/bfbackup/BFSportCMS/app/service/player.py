# -*- coding: utf-8 -*-

from app.extensions import db

from app.models import Player
from app.models import TeamPlayer


class PlayerService(object):

    @staticmethod
    def get_one(player_id):
        player = Player.query.get(player_id)
        return player and player.to_dict()

    @staticmethod
    def get_all():
        players = Player.query.all()
        return [player.to_dict() for player in players]

    @staticmethod
    def get_show_all(offset, limit):
        players = Player.query.filter_by().offset(offset).limit(limit).all()
        return [player.to_dict() for player in players]

    @staticmethod
    def count():
        count = Player.query.filter_by().count()
        return count

    @staticmethod
    def add(info_dict):
        player = Player(**info_dict)
        db.session.add(player)
        db.session.commit()

    @staticmethod
    def edit(player_id, info_dict):
        player = Player.query.get(player_id)
        for k, v in info_dict.items():
            setattr(player, k, v)
        db.session.add(player)
        db.session.commit()

    @staticmethod
    def delete(team_id):
        player = Player.query.get(team_id)
        db.session.delete(player)
        db.session.commit()


class TeamPlayerService(object):

    @staticmethod
    def get_filter_one(player_id):
        team_player = TeamPlayer.query.filter_by(player_id=player_id).first()
        return team_player and team_player.to_dict()

    @staticmethod
    def get_player_all(team_id):
        players = TeamPlayer.query.filter_by(team_id=team_id).all()
        return [player.to_dict() for player in players]
