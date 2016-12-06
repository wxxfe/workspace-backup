# -*- coding: utf-8 -*-

from app.extensions import db
from app.models import Activity


class ActivityService(object):

    @staticmethod
    def get_all(offset=0, limit=0):

        stmt = Activity.query.filter_by()

        activitys = stmt.offset(offset).limit(limit).all()

        return [activity.to_dict() for activity in activitys]

    @staticmethod
    def count():
        count = Activity.query.filter_by().count()
        return count

    @staticmethod
    def add(info_dict):
        activity = Activity(**info_dict)
        db.session.add(activity)
        db.session.commit()

    @staticmethod
    def edit(activity_id, info_dict):
        activity = Activity.query.get(activity_id)
        for k, v in info_dict.items():
            setattr(activity, k, v)
        db.session.add(activity)
        db.session.commit()

    @staticmethod
    def hide(activity_id):
        activity = Activity.query.get(activity_id)
        activity.visible = 0
        db.session.add(activity)
        db.session.commit()

    @staticmethod
    def show(activity_id):
        activity = Activity.query.get(activity_id)
        activity.visible = 1
        db.session.add(activity)
        db.session.commit()

    @staticmethod
    def delete(activity_id):
        activity = Activity.query.get(activity_id)
        db.session.delete(activity)
        db.session.commit()
