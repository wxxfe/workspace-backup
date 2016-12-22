# -*- coding: utf-8 -*-

from sqlalchemy.sql import text

from app.extensions import db


class Gallery(db.Model):
    __tablename__ = 'gallery'

    id = db.Column(db.Integer, primary_key=True)
    event_id = db.Column(
        db.Integer,
        db.ForeignKey('event.id', ondelete="CASCADE", onupdate="CASCADE"))

    title = db.Column(db.String(256), nullable=False)
    brief = db.Column(db.String(256), nullable=False, server_default='')

    gallery_images = db.relationship(
        'GalleryImage', backref='gallery', lazy='dynamic')

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)


class GalleryImage(db.Model):
    __tablename__ = 'gallery_image'

    id = db.Column(db.Integer, primary_key=True)
    gallery_id = db.Column(
        db.Integer,
        db.ForeignKey('gallery.id', ondelete="CASCADE", onupdate="CASCADE"))

    title = db.Column(db.String(256), nullable=False)
    image = db.Column(db.String(256), nullable=False)
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
