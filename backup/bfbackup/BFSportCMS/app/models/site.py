# -*- coding: utf-8 -*-

from sqlalchemy.sql import text

from app.extensions import db


class Site(db.Model):
    __tablename__ = 'site'

    id = db.Column(db.Integer, primary_key=True)
    site = db.Column(
        db.String(20), nullable=False, server_default='', unique=True)
    live_title = db.Column(db.String(32), nullable=False, server_default='')
    news_origin = db.Column(db.String(32), nullable=False, server_default='')
    video_origin = db.Column(db.String(32), nullable=False, server_default='')

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                site=self.site,
                live_title=self.live_title
            )
