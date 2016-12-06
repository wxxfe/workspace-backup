# -*- coding: utf-8 -*-

from app.extensions import db
from app.models import PendingVideo


class PendingVideoService(object):

    @staticmethod
    def get_all(fresh, offset, limit):

        stmt = PendingVideo.query.filter_by(fresh=fresh)
        stmt = stmt.order_by(PendingVideo.created_at.desc())
        resources = stmt.offset(offset).limit(limit).all()

        return [resource.to_dict() for resource in resources]

    @staticmethod
    def get_one(resource_id):
        resource = PendingVideo.query.get(resource_id)
        return resource and resource.to_dict()

    @staticmethod
    def count(fresh):
        count = PendingVideo.query.filter_by(fresh=fresh).count()
        return count

    @staticmethod
    def search_by_name(keyword):
        stmt = PendingVideo.query.filter(
            PendingVideo.title.like('%' + keyword + '%'))
        resources = stmt.order_by(PendingVideo.created_at.desc()).all()
        return [resource.to_dict() for resource in resources]

    @staticmethod
    def delete(resource_id):
        resource = PendingVideo.query.get(resource_id)
        db.session.delete(resource)
        db.session.commit()

    @staticmethod
    def relate(resource_id):
        resource = PendingVideo.query.get(resource_id)
        resource.fresh = 0
        db.session.add(resource)
        db.session.commit()
