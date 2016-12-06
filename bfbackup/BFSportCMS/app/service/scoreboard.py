# -*- coding: utf-8 -*-

from app.extensions import db
from app.models import Scoreboard
from app.models import Topscorer
from app.models import Topassist


class ScoreboardService(object):

    @staticmethod
    def get_filter_one(event_id, team_id):

        score = Scoreboard.query.filter_by(event_id=event_id)\
                                        .filter_by(team_id=team_id).first()

        return score and score.to_dict()

    @staticmethod
    def get_all(event_id):
        stmt = Scoreboard.query.filter_by(event_id=event_id)
        scores = stmt.order_by(Scoreboard.points.desc()).all()
        return [score.to_dict() for score in scores]

    @staticmethod
    def add(info_dict):
        score = Scoreboard(**info_dict)
        db.session.add(score)
        db.session.commit()

    @staticmethod
    def edit(scoreboard_id, info_dict):
        score = Scoreboard.query.get(scoreboard_id)
        if score:
            for k, v in info_dict.items():
                setattr(score, k, v)
            db.session.add(score)
            db.session.commit()
        else:
            score = Scoreboard(**info_dict)
            db.session.add(score)
            db.session.commit()

    @staticmethod
    def delete(scoreboard_id):
        score = Scoreboard.query.get(scoreboard_id)
        db.session.delete(score)
        db.session.commit()


class TopscorerService(object):

    @staticmethod
    def get_all(event_id):
        stmt = Topscorer.query.filter_by(event_id=event_id)
        scorers = stmt.order_by(Topscorer.goals.desc()).all()
        return [scorer.to_dict() for scorer in scorers]

    @staticmethod
    def get_one(scorer_id):
        scorer = Topscorer.query.get(scorer_id)
        return scorer

    @staticmethod
    def count(event_id):
        count = Topscorer.query.filter_by(event_id=event_id).count()
        return count

    @staticmethod
    def add(event_id, player_id, goals):
        item = Topscorer(
            event_id=event_id,
            player_id=player_id,
            goals=goals
        )

        db.session.add(item)
        db.session.commit()

    @staticmethod
    def edit(scorer_id, goals):
        item = Topscorer.query.get(scorer_id)
        item.goals = goals

        db.session.add(item)
        db.session.commit()

    @staticmethod
    def delete(scorer_id):
        item = Topscorer.query.get(scorer_id)
        db.session.delete(item)
        db.session.commit()


class TopassistService(object):

    @staticmethod
    def get_all(event_id):
        stmt = Topassist.query.filter_by(event_id=event_id)
        scorers = stmt.order_by(Topassist.assists.desc()).all()
        return [scorer.to_dict() for scorer in scorers]

    @staticmethod
    def get_one(assist_id):
        scorer = Topassist.query.get(assist_id)
        return scorer

    @staticmethod
    def count(event_id):
        count = Topassist.query.filter_by(event_id=event_id).count()
        return count

    @staticmethod
    def add(event_id, player_id, assists):
        item = Topassist(
            event_id=event_id,
            player_id=player_id,
            assists=assists
        )

        db.session.add(item)
        db.session.commit()

    @staticmethod
    def edit(assist_id, assists):
        item = Topassist.query.get(assist_id)
        item.assists = assists

        db.session.add(item)
        db.session.commit()

    @staticmethod
    def delete(assist_id):
        item = Topassist.query.get(assist_id)
        db.session.delete(item)
        db.session.commit()
