# -*- coding: utf-8 -*-

from app.extensions import db

from app.models import Team
from app.models import EventTeam


class TeamService(object):

    @staticmethod
    def get_one(team_id):
        team = Team.query.get(team_id)
        return team and team.to_dict()

    @staticmethod
    def get_all(event_id=None):
        if event_id:
            teams = Team.query.filter_by().all()
        else:
            teams = Team.query.all()

        return [team.to_dict() for team in teams]

    @staticmethod
    def get_show_all(offset, limit):
        teams = Team.query.filter_by().offset(offset).limit(limit).all()
        return [team.to_dict() for team in teams]

    @staticmethod
    def count():
        count = Team.query.filter_by().count()
        return count

    @staticmethod
    def add(event_id_group_dict, info_dict):
        team = Team(**info_dict)
        db.session.add(team)
        db.session.commit()

        for event_id in event_id_group_dict:
            event_team = EventTeam(
                event_id=event_id,
                team_id=team.id,
                group=event_id_group_dict[event_id])

            db.session.add(event_team)
            db.session.commit()

    @staticmethod
    def edit(event_id_group_dict, team_id, info_dict):
        team = Team.query.get(team_id)
        for k, v in info_dict.items():
            setattr(team, k, v)
        db.session.add(team)
        db.session.commit()

        event_teams = EventTeamService.get_filter_all(team_id)

        for event_team in event_teams:
            event = EventTeamService.get_one(event_team.id)
            db.session.delete(event)
            db.session.commit()

        for event_id in event_id_group_dict:
            event_team = EventTeam(
                event_id=event_id,
                team_id=team.id,
                group=event_id_group_dict[event_id])

            db.session.add(event_team)
            db.session.commit()

    @staticmethod
    def delete(team_id):
        team = Team.query.get(team_id)
        db.session.delete(team)
        db.session.commit()

    @staticmethod
    def search_by_name(keyword):
        teams = Team.query.filter(Team.name.like('%' + keyword + '%')).all()
        return [team.to_dict() for team in teams]


class EventTeamService(object):

    @staticmethod
    def get_filter_one(event_id, team_id):
        stmt = EventTeam.query.filter_by(team_id=team_id)
        event_team = stmt.filter_by(event_id=event_id).first()
        return event_team

    @staticmethod
    def get_filter_all(team_id=None):
        event_teams = EventTeam.query.filter_by(team_id=team_id).all()
        return event_teams

    @staticmethod
    def get_all_by_event(event_id):
        teams = EventTeam.query.join(Team,Team.id==EventTeam.team_id).add_columns(Team.name,Team.id).filter(EventTeam.event_id==event_id)
        return teams

    @staticmethod
    def get_one(id):
        team = EventTeam.query.get(id)
        return team

    @staticmethod
    def get_all(event_id):

        teams = EventTeam.query.filter_by(event_id=event_id)\
                                        .order_by(EventTeam.group.asc()).all()


        return [team.to_dict() for team in teams]
