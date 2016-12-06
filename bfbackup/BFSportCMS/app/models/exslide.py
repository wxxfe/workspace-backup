# -*- coding: utf-8 -*-

from sqlalchemy.dialects.mysql import TINYINT
from sqlalchemy.sql import text

from app.extensions import db
from app.utils.text import add_image_domain


class ExSlide(db.Model):
    __tablename__ = 'ex_slide'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(100), nullable=False)
    image = db.Column(db.String(256), nullable=False, server_default='')
    large_image = db.Column(db.String(256), nullable=False, server_default='')
    type = db.Column(db.String(20), nullable=False)
    ref_id = db.Column(db.Integer, server_default=text('0'))
    data = db.Column(db.String(1024), nullable=False, server_default='')
    position = db.Column(db.Integer, server_default=text('0'))
    created_at = db.Column(db.TIMESTAMP,
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
                title=self.title,
                image=add_image_domain(self.image),
                large_image=add_image_domain(self.large_image),
                type=self.type,
                ref_id=self.ref_id,
                data=self.data,
                position=self.position,
                created_at=self.created_at,
                updated_at=self.updated_at
            )
