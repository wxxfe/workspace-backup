# -*- coding: utf-8 -*-

from sqlalchemy.dialects.mysql import TINYINT
from sqlalchemy.sql import text

from app.extensions import db


channel_enums = (
    "headline",
    "highlight",
    "event",
    "program",
    "section",
    "general",
    "gallery")


platf_enums = (
    "mobile",
    "website",
    "all")


class ChannelStatus:
    """when use sqlalchemy server_default,  server_default=0 is wrong.

        from sqlalchemy.sql import text
        ... server_default=text('0')

        The result is  DEFAULT 0.
    """

    HIDDEN = '0'
    SHOWN = '1'


class Channel(db.Model):
    __tablename__ = 'channel'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(16), nullable=False)
    alias = db.Column(db.String(32), nullable=False, server_default='')
    type = db.Column(
        db.Enum(*channel_enums), nullable=False, server_default="event")
    platf = db.Column(
        db.Enum(*platf_enums), nullable=False, server_default="all")
    ref_id = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
    display_order = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
    visible = db.Column(
        TINYINT, nullable=False, server_default=text(ChannelStatus.HIDDEN))

    ws_headlines = db.relationship(
        'WSHeadline', backref='ws_headline', lazy='dynamic')

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
                id=self.id,
                name=self.name,
                alias=self.alias,
                platf=self.platf,
                visible=self.visible,
                display_order=self.display_order,
                type=self.type,
                ref_id=self.ref_id
            )


class WSHeadline(db.Model):
    __tablename__ = 'ws_headline'

    id = db.Column(db.Integer, primary_key=True)
    channel_id = db.Column(
        db.Integer,
        db.ForeignKey('channel.id', ondelete="CASCADE", onupdate="CASCADE"))
    html = db.Column(db.String(4096), nullable=False, server_default='')

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
                id=self.id,
                channel_id=self.channel_id,
                html=self.html
            )
