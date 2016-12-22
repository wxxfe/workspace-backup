# -*- coding: utf-8 -*-

from app.extensions import db
from app.models import Event
from app.models import Channel
from app.models import ChannelStatus
from app.models import WSHeadline


class ChannelService(object):

    @staticmethod
    def get_shown_all():
        events = Event.query.filter_by().all()
        return [event.to_dict() for event in events]

    @staticmethod
    def get_all(type=None):
        stmt = Event.query.filter_by()

        if type:
            stmt = stmt.filter_by(type=type)

        events = stmt.order_by(Event.id.asc()).all()
        return [event.to_dict() for event in events]

    @staticmethod
    def get_cups(iscup=1):
        stmt = Event.query.filter_by(iscup=iscup)
        events = stmt.order_by(Event.id.asc()).all()
        return [event.to_dict() for event in events]

    @staticmethod
    def get_cups(iscup=0, type='football'):
        stmt = Event.query.filter_by(iscup=iscup)
        stmt = stmt.filter_by(type=type)
        events = stmt.order_by(Event.id.asc()).all()
        return [event.to_dict() for event in events]

    @staticmethod
    def get_one(channel_id):
        channel = Event.query.get(channel_id)
        return channel

    @staticmethod
    def add(name, type, brief):
        channel = Event(name=name, type=type, brief=brief)

        db.session.add(channel)
        db.session.commit()

    @staticmethod
    def edit(channel_id, name, type, brief):
        channel = Event.query.get(channel_id)
        channel.name = name
        channel.type = type
        channel.brief = brief

        db.session.add(channel)
        db.session.commit()

    @staticmethod
    def hide(channel_id):
        channel = Event.query.get(channel_id)
        channel.visible = 0
        db.session.add(channel)
        db.session.commit()

    @staticmethod
    def show(channel_id):
        channel = Event.query.get(channel_id)
        channel.visible = 1
        db.session.add(channel)
        db.session.commit()

    @staticmethod
    def delete(channel_id):
        channel = Event.query.get(channel_id)
        db.session.delete(channel)
        db.session.commit()


class NewChannelService(object):

    @staticmethod
    def get_filter_all(platf=None):

        if platf == 'mobile':
            stmt = Channel.query.filter(Channel.platf != 'website')
        elif platf == 'website':
            stmt = Channel.query.filter(Channel.platf != 'mobile')
        else:
            stmt = Channel.query.filter_by()

        channels = stmt.order_by(Channel.display_order.asc()).all()
        return [channel.to_dict() for channel in channels]

    @staticmethod
    def get_one(channel_id):
        channel = Channel.query.get(channel_id)
        return channel

    @staticmethod
    def add(name, alias, type, platform, ref_id, display_order):
        channel = Channel(
                                        name=name,
                                        alias=alias,
                                        type=type,
                                        platf=platform,
                                        ref_id=ref_id,
                                        display_order=display_order)

        db.session.add(channel)
        db.session.commit()

    @staticmethod
    def edit(channel_id, name, alias, platform):
        channel = Channel.query.get(channel_id)
        channel.name = name
        channel.alias = alias
        channel.platf = platform
        db.session.add(channel)
        db.session.commit()

    @staticmethod
    def hide(channel_id):
        channel = Channel.query.get(channel_id)
        channel.visible = ChannelStatus.HIDDEN
        db.session.add(channel)
        db.session.commit()

    @staticmethod
    def show(channel_id):
        channel = Channel.query.get(channel_id)
        channel.visible = ChannelStatus.SHOWN
        db.session.add(channel)
        db.session.commit()

    @staticmethod
    def delete(channel_id):
        channel = Channel.query.get(channel_id)
        db.session.delete(channel)
        db.session.commit()

    @staticmethod
    def count():
        count = Channel.query.filter_by().count()
        return count

    @staticmethod
    def sort(channel_id, current, final):

        def sub_number(channel):
            channel.display_order = Channel.display_order - 1
            db.session.add(channel)
            db.session.commit()

        def inc_number(channel):
            channel.display_order = Channel.display_order + 1
            db.session.add(channel)
            db.session.commit()

        if current < final:
            channel_ids = Channel.query.filter(current < Channel.display_order,  Channel.display_order <= final).all()
            map(sub_number, channel_ids)

        if current > final:
            channel_ids = Channel.query.filter(current > Channel.display_order,  Channel.display_order >= final).all()
            map(inc_number, channel_ids)

        channel = Channel.query.get(channel_id)
        channel.display_order = final
        db.session.add(channel)
        db.session.commit()


class WSHeadlineService(object):

    @staticmethod
    def get_filter_all(channel_id):
        stmt = WSHeadline.query.filter_by(channel_id=channel_id)
        headlines = stmt.order_by(WSHeadline.created_at.desc()).all()
        return [headline.to_dict() for headline in headlines]

    @staticmethod
    def add(channel_id, html):
        channel = WSHeadline(channel_id=channel_id, html=html)
        db.session.add(channel)
        db.session.commit()

    @staticmethod
    def edit(headline_id, html):
        headline = WSHeadline.query.get(headline_id)
        headline.html = html
        db.session.add(headline)
        db.session.commit()

    @staticmethod
    def delete(headline_id):
        headline = WSHeadline.query.get(headline_id)
        db.session.delete(headline)
        db.session.commit()

    @staticmethod
    def get_one(channel_id):
        headline = WSHeadline.query.get(channel_id)
        return headline
