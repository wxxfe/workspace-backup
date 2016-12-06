# -*- coding: utf-8 -*-

from app.extensions import db
from app.models import Match
from app.models import Event
from app.models import EventNews, EventNewsStatus
from app.models import SelectedMatch
from app.utils.text import calculate_datetime
from app.models import Site


class EventService(object):

    @staticmethod
    def get_all(event_id=None, status=None, default_tm=None):

        stmt = Match.query.filter_by()
        if event_id:
            stmt = stmt.filter_by(event_id=event_id)

        if status:
            stmt = stmt.filter_by(status=status)

        if default_tm:
            start_dt = default_tm
            end_dt = calculate_datetime(start_dt, days=1)
            stmt = stmt.filter(
                                       Match.start_tm >= start_dt,
                                       Match.start_tm < end_dt)

        matches = stmt.order_by(Match.start_tm.asc()).all()

        return matches

    @staticmethod
    def get_one(event_id):
        event = Event.query.get(event_id)
        return event


class SelectedMatchService(object):

    @staticmethod
    def get_all(event_id=None, type=None,  default_tm=None):

        stmt = SelectedMatch.query.filter_by()

        if event_id:
            stmt = stmt.filter(SelectedMatch.event_id == int(event_id))
        else:
            stmt = stmt.filter_by(event_id=0)

        if type:
            stmt = stmt.filter(SelectedMatch.type == type)

        if default_tm:
            start_dt = default_tm
            end_dt = calculate_datetime(start_dt, days=1)
            stmt = stmt.filter(
                                       SelectedMatch.created_at >= start_dt,
                                       SelectedMatch.created_at < end_dt)

        matches = stmt.order_by(SelectedMatch.created_at.asc()).all()

        return matches

    @staticmethod
    def add(event_id=None, type=None, match_id=None):
        match = SelectedMatch(
                                               event_id=event_id,
                                               type=type,
                                               match_id=match_id)

        db.session.add(match)
        db.session.commit()

    @staticmethod
    def delete(event_id=None, match_id=None):
        selected = SelectedMatch.query.filter_by(
            event_id=event_id,
            match_id=match_id).first()
        match = SelectedMatch.query.get(selected.id)
        db.session.delete(match)
        db.session.commit()

    @staticmethod
    def get_one(event_id, match_id, type):
        matches = SelectedMatch.query.filter_by(
            event_id=event_id, match_id=match_id, type=type).all()
        return matches


class EventNewsService(object):

    @staticmethod
    def get_all(event_id, type, offset=0, limit=0):

        stmt = EventNews.query.filter_by()

        if event_id:
            stmt = stmt.filter_by(event_id=event_id)
        if type == 'video':
            stmt = stmt.filter(EventNews.type == 'video')
        if type == 'literal':
            stmt = stmt.filter(EventNews.type == 'literal')

        if event_id:
            stmt = stmt.order_by(
                EventNews.tope.desc(), EventNews.publish_tm.desc())
        else:
            stmt = stmt.order_by(
                EventNews.toph.desc(), EventNews.publish_tm.desc())
        news = stmt.offset(offset).limit(limit).all()

        return news

    @staticmethod
    def count(event_id=None, type=None):
        stmt = EventNews.query.filter_by()
        if event_id and type:
            stmt = stmt.filter_by(event_id=event_id, type=type)
        elif event_id:
            stmt = stmt.filter_by(event_id=event_id)
        elif type:
            stmt = stmt.filter_by(type=type)
        count = stmt.count()

        return count

    @staticmethod
    def search_by_id(id):
        news = EventNews.query.filter_by(event_id=id).all()
        return news

    @staticmethod
    def search_by_title(title):
        news = EventNews.query.filter_by(title=title).first()
        return news

    @staticmethod
    def get_one(news_id):
        news = EventNews.query.get(news_id)
        return news

    @staticmethod
    def get_sites():
        source = Site.query.all()
        return source

    @staticmethod
    def add(
                 event_id, type, source, source_name,
                 title, isvr, image, play_url, play_code,
                 play_html, subtitle, content, args=None, publish_tm=None):

        if publish_tm:
            news = EventNews(
                    event_id=event_id,
                    type=type,
                    site=source,
                    origin=source_name,
                    title=title,
                    isvr=isvr,
                    image=image,
                    play_url=play_url,
                    play_code=play_code,
                    play_html=play_html,
                    subtitle=subtitle,
                    content=content,
                    args=args,
                    publish_tm=publish_tm,
            )
        else:
            news = EventNews(
                    event_id=event_id,
                    type=type,
                    site=source,
                    origin=source_name,
                    title=title,
                    isvr=isvr,
                    image=image,
                    play_url=play_url,
                    play_code=play_code,
                    play_html=play_html,
                    subtitle=subtitle,
                    content=content,
                    args=args,
            )

        db.session.add(news)
        db.session.commit()

    @staticmethod
    def edit(
                 news_id, event_id, type, source, source_name, title, isvr,
                 image, play_url, play_code, play_html, subtitle, content):

        news = EventNews.query.get(news_id)
        news.event_id = event_id
        news.type = type
        news.site = source
        news.origin = source_name
        news.title = title
        news.isvr = isvr
        news.image = image
        news.play_url = play_url
        news.play_code = play_code
        news.play_html = play_html
        news.subtitle = subtitle
        news.content = content

        db.session.add(news)
        db.session.commit()

    @staticmethod
    def delete(news_id):
        news = EventNews.query.get(news_id)
        db.session.delete(news)
        db.session.commit()

    @staticmethod
    def top(news_id, top, type):
        news = EventNews.query.get(news_id)

        if type == "toph":
            news.toph = top
        else:
            news.tope = top
        db.session.add(news)
        db.session.commit()

    @staticmethod
    def pin(news_id, pin):
        news = EventNews.query.get(news_id)
        news.pin = pin
        db.session.add(news)
        db.session.commit()

    @staticmethod
    def hide(news_id):
        news = EventNews.query.get(news_id)
        news.visible = EventNewsStatus.HIDDEN
        db.session.add(news)
        db.session.commit()

    @staticmethod
    def show(news_id):
        news = EventNews.query.get(news_id)
        news.visible = EventNewsStatus.SHOWN
        db.session.add(news)
        db.session.commit()
