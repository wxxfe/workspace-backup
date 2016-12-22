# -*- coding: utf-8 -*-

from sqlalchemy.sql import text
from app.extensions import db
from app.utils.text import to_timestamp
from app.utils.text import add_image_domain

#对外数据-主版-推荐比赛
class ExSelectedMatch(db.Model):
    __tablename__ = 'ex_selected_match'

    id = db.Column(db.Integer, primary_key=True)
    match_id = db.Column(db.Integer, server_default=text('0'))
    display_order = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
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
                match_id=self.match_id,
                display_order=self.display_order,
                created_at=to_timestamp(self.created_at),
                updated_at=to_timestamp(self.updated_at)
            )

#对外数据-主版-今日推荐模型
class ExHotspot(db.Model):
    __tablename__ = 'ex_hotspot'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(100), nullable=False)
    brief = db.Column(db.String(128), nullable=False,server_default='')
    image = db.Column(db.String(256), nullable=False, server_default='')
    type = db.Column(db.String(20), nullable=False)
    ref_id = db.Column(db.Integer, nullable=False,server_default=text('0'))
    data = db.Column(db.String(1024), nullable=False, server_default='')
    display_order = db.Column(db.Integer, server_default=text('0'))
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
                brief=self.brief,
                image=add_image_domain(self.image),
                type=self.type,
                ref_id=self.ref_id,
                data=self.data,
                display_order=self.display_order,
                created_at=self.created_at,
                updated_at=self.updated_at
            )
#对外数据-主版-发现
class ExDiscovery(db.Model):
    __tablename__ = 'ex_discovery'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(100), nullable=False, server_default='')
    image = db.Column(db.String(256), nullable=False, server_default='')
    url = db.Column(db.String(256), nullable=False, server_default='')
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
                url=self.url,
                created_at=self.created_at,
                updated_at=self.updated_at
            )

#对外数据-主版-发现
class ExColumn(db.Model):
    __tablename__ = 'ex_column'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(100), nullable=False, server_default='')
    type = db.Column(db.String(20), nullable=False)
    display_order = db.Column(db.Integer, server_default=text('0'))
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
                type=self.type,
                display_order=self.display_order,
                created_at=self.created_at,
                updated_at=self.updated_at
            )

class ExColumnPost(db.Model):
    __tablename__ = 'ex_column_post'

    id = db.Column(db.Integer, primary_key=True)
    column_id = db.Column(db.Integer,nullable=False)
    title = db.Column(db.String(100), nullable=False)
    image = db.Column(db.String(256), nullable=False, server_default='')
    type = db.Column(db.String(20), nullable=False)
    ref_id = db.Column(db.Integer, server_default=text('0'))
    display_order = db.Column(db.Integer, server_default=text('0'))
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
                column_id=self.column_id,
                title=self.title,
                image=add_image_domain(self.image),
                type=self.type,
                ref_id=self.ref_id,
                display_order=self.display_order,
                created_at=self.created_at,
                updated_at=self.updated_at
            )

class ExSlide(db.Model):
    __tablename__ = 'ex_slide'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(100), nullable=False)
    image = db.Column(db.String(256), nullable=False, server_default='')
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
                type=self.type,
                ref_id=self.ref_id,
                data=self.data,
                position=self.position,
                created_at=self.created_at,
                updated_at=self.updated_at
            )
