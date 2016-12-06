# -*- coding: utf-8 -*-

from sqlalchemy.dialects.mysql import TINYINT
from sqlalchemy.sql import text

from app.extensions import db
from app.utils.text import to_timestamp
from app.utils.text import add_image_domain


class SectionStatus:
    """when use sqlalchemy server_default,  server_default=0 is wrong.

        from sqlalchemy.sql import text
        ... server_default=text('0')

        The result is  DEFAULT 0.
    """

    HIDDEN = '0'
    SHOWN = '1'


class Section(db.Model):
    __tablename__ = 'section'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(256), nullable=False)
    visible = db.Column(
        TINYINT, nullable=False, server_default=text(SectionStatus.HIDDEN))
    display_order = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
    image = db.Column(db.String(256), nullable=False, server_default='')
    logo = db.Column(db.String(256), nullable=False, server_default='')

    section_posts = db.relationship(
        'SectionPost', backref='section', lazy='dynamic')

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
                'display_order': self.display_order,
                'visible': self.visible,
                'logo': add_image_domain(self.logo),
                'image': add_image_domain(self.image),
                'created_at': self.created_at,
            }


class SectionPost(db.Model):
    __tablename__ = 'section_post'

    id = db.Column(db.Integer, primary_key=True)
    section_id = db.Column(
        db.Integer,
        db.ForeignKey('section.id', ondelete="CASCADE", onupdate="CASCADE"))

    title = db.Column(db.String(256), nullable=False)
    site = db.Column(db.String(20), nullable=False, server_default='')
    origin = db.Column(db.String(20), nullable=False, server_default='')
    brief = db.Column(db.String(200), nullable=False, server_default='')
    content = db.Column(db.Text)
    image = db.Column(db.String(256), nullable=False)

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
                'site': self.site,
                'origin': self.origin,
                'content': self.content,
                'image': add_image_domain(self.image),
                'created_at': self.created_at,
                'publish_tm': self.publish_tm
            }
