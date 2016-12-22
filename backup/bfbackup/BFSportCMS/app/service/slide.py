# -*- coding: utf-8 -*-

from app.extensions import db
from app.models import Slide, SlideStatus


class SlideService(object):

    @staticmethod
    def get_all(platform=None, channel_id=None):

        stmt = Slide.query.filter_by(platform=platform)

        if channel_id == 0:
            stmt = stmt.filter_by(event_id=channel_id)

        if channel_id:
            stmt = stmt.filter_by(event_id=channel_id)

        slides = stmt.order_by(Slide.display_order.asc()).all()

        return [slide.to_dict() for slide in slides]

    @staticmethod
    def get_one(slide_id):
        slide = Slide.query.get(slide_id)
        return slide

    @staticmethod
    def add(info_dict):
        slide = Slide(**info_dict)
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def edit(slide_id, info_dict):
        slide = Slide.query.get(slide_id)
        for k, v in info_dict.items():
            setattr(slide, k, v)
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def hide(slide_id):
        slide = Slide.query.get(slide_id)
        slide.visible = SlideStatus.HIDDEN
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def show(slide_id):
        slide = Slide.query.get(slide_id)
        slide.visible = SlideStatus.SHOWN
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def delete(slide_id):
        slide = Slide.query.get(slide_id)
        db.session.delete(slide)
        db.session.commit()
