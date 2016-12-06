# -*- coding: utf-8 -*-

from sqlalchemy.dialects.mysql import TINYINT
from sqlalchemy.sql import text

from app.extensions import db
from app.utils.text import add_image_domain


class ProgramStatus:
    """when use sqlalchemy server_default,  server_default=0 is wrong.

        from sqlalchemy.sql import text
        ... server_default=text('0')

        The result is  DEFAULT 0.
    """

    HIDDEN = '0'
    SHOWN = '1'


class Program(db.Model):
    __tablename__ = 'program'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(256), nullable=False)
    visible = db.Column(
        TINYINT, nullable=False, server_default=text(ProgramStatus.HIDDEN))
    display_order = db.Column(
        db.Integer, nullable=False, server_default=text('0'))

    program_posts = db.relationship(
        'ProgramPost', backref='program', lazy='dynamic')

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
        return {
            'id': self.id,
            'title': self.title,
            'visible': self.visible,
            'display_order': self.display_order,
            'created_at': self.created_at,
        }


class ProgramPost(db.Model):
    __tablename__ = 'program_post'

    id = db.Column(db.Integer, primary_key=True)
    program_id = db.Column(
        db.Integer,
        db.ForeignKey('program.id', ondelete="CASCADE", onupdate="CASCADE"))

    title = db.Column(db.String(256), nullable=False)
    site = db.Column(db.String(20), nullable=False, server_default='')
    origin = db.Column(db.String(20), nullable=False, server_default='')
    args = db.Column(db.String(256), nullable=False, server_default='')
    play_url = db.Column(db.String(512), nullable=False, server_default='')
    brief = db.Column(db.String(200), nullable=False, server_default='')
    image = db.Column(db.String(256), nullable=False)
    play_code = db.Column(db.String(1024), nullable=False, server_default='')
    play_html = db.Column(db.String(8192), nullable=False, server_default='')
    isvr = db.Column(TINYINT, nullable=False, server_default=text('0'))

    publish_tm = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)

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
        return {
            'id': self.id,
            'title': self.title,
            'program_id': self.program_id,
            'play_url': self.play_url,
            'origin': self.origin,
            'site': self.site,
            'brief': self.brief,
            'image': add_image_domain(self.image),
            'play_code': self.play_code,
            'play_html': self.play_html,
            'isvr': self.isvr,
            'publish_tm': self.publish_tm,
        }

    def to_simple_dict(self):
        return {
            'title': self.title,
            'image': add_image_domain(self.image),
        }
