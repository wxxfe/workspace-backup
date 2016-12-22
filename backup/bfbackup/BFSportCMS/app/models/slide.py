# -*- coding: utf-8 -*-

from sqlalchemy.dialects.mysql import TINYINT
from sqlalchemy.sql import text

from app.extensions import db
from app.utils.text import add_image_domain


class SlideStatus:

    SHOWN = '1'
    HIDDEN = '0'


class Slide(db.Model):
    __tablename__ = 'slide'

    id = db.Column(db.Integer, primary_key=True)
    event_id = db.Column(db.Integer, server_default=text('0'))
    display_order = db.Column(db.Integer, server_default=text('0'))
    platform = db.Column(db.String(20), nullable=False)
    visible = db.Column(TINYINT, server_default=text(SlideStatus.HIDDEN))
    type = db.Column(db.String(20), nullable=False)
    title = db.Column(db.String(100), nullable=False)
    target_id = db.Column(db.Integer, nullable=False, server_default=text('0'))
    parent = db.Column(db.String(20), nullable=False, server_default='')
    image = db.Column(db.String(256), nullable=False, server_default='')
    thumbnail = db.Column(db.String(512), nullable=False, server_default='')
    bgcolor = db.Column(db.String(20), nullable=False, server_default='')
    url = db.Column(db.String(512), nullable=False, server_default='')

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
                event_id=self.event_id,
                platform=self.platform,
                display_order=self.display_order,
                visible=self.visible,
                type=self.type,
                title=self.title,
                target_id=self.target_id,
                parent=self.parent,
                url=self.url,
                image=add_image_domain(self.image)
            )
