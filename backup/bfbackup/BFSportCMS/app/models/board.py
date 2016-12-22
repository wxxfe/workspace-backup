# -*- coding: utf-8 -*-

from sqlalchemy.sql import text

from app.extensions import db


class TeamClass(db.Model):
    __bind_key__ = 'board'
    __tablename__ = 'team_class'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(16), nullable=False)
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
                name=self.name
            )
