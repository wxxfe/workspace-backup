# -*- coding: utf-8 -*-

from app.extensions import db

from app.models import Site
from app.models import Match, MatchVisibleStatus
from app.models import MatchLive
from app.models import MatchNews, MatchNewsStatus
from app.models import MatchVideo, MatchVideoStatus

from app.models import Team


class TeamService(object):

    @staticmethod
    def get_one(team_id):
        team = Team.query.get(team_id)
        return team and team.to_dict()


class SiteService(object):

    @staticmethod
    def get_one(site=None):
        site = Site.query.filter(Site.site == site).first()
        return site and site.to_dict()

    @staticmethod
    def get_all():
        site = Site.query.all()
        return site


class MatchLiveService(object):

    @staticmethod
    def get_all(match_id=None):
        lives = MatchLive.query.filter(MatchLive.match_id == match_id).all()
        return [live.to_dict() for live in lives]

    @staticmethod
    def add(match_id, site, play_url, play_code):
        live = MatchLive(
                                    match_id=match_id,
                                    site=site,
                                    play_url=play_url,
                                    play_code=play_code)

        db.session.add(live)
        db.session.commit()

    @staticmethod
    def delete(live_id):
        live = MatchLive.query.get(live_id)
        db.session.delete(live)
        db.session.commit()

    @staticmethod
    def add_dict(info_dict):
        live = MatchLive(**info_dict)
        db.session.add(live)
        db.session.commit()


class MatchService(object):

    @staticmethod
    def get_all(event_id, status, data, offset, limit):

        stmt = Match.query.filter_by()

        if event_id:
            stmt = stmt.filter_by(event_id=event_id)

        if status:
            stmt = stmt.filter_by(status=status)

        if data:
            pass

        matches = stmt.offset(0).limit(limit).all()

        return matches

    @staticmethod
    def get_match_status_id(status=None):
        if status == 'notstarted':
            match_status = 0
        elif status == 'ongoing':
            match_status = 1
        else:
            match_status = 2

        return match_status

    @staticmethod
    def get_match_status_str(status=None):

        if status == 0:
            match_status = 'notstarted'
        elif status == 1:
            match_status = 'ongoing'
        else:
            match_status = 'finished'

        return match_status

    @staticmethod
    def get_match_info(match_id):
        match = Match.query.get(match_id)
        if not match:
            return {}

        def get_live_title(match):
            site = SiteService.get_one(match['site'])
            if site:
                match['title'] = site['live_title']
            else:
                match['title'] = match['site']
            return match

        match_lives = MatchLiveService.get_all(match_id)
        match_lives_info = map(get_live_title, match_lives)
        data = match.to_dict()
        data['type'] = 'match'
        data['live_play_urls'] = match_lives_info
        data['status'] = MatchService.get_match_status_str(match.status)

        data['team1'] = TeamService.get_one(match.team1_id)
        if data['team1']:
            data['team1']['score'] = match.team1_score
            data['team1']['likes'] = match.team1_likes
            del(data['team1_id'])
            del(data['team1_score'])
            del(data['team1_likes'])
            del(data['team1']['english_name'])
            del(data['team1']['head_coach'])
            del(data['team1']['website'])

        data['team2'] = TeamService.get_one(match.team2_id)

        if data['team2']:
            data['team2']['score'] = match.team2_score
            data['team2']['likes'] = match.team2_likes
            del(data['team2_id'])
            del(data['team2_score'])
            del(data['team2_likes'])
            del(data['team2']['english_name'])
            del(data['team2']['head_coach'])
            del(data['team2']['website'])

        del(data['event_id'])
        del(data['finish_tm'])
        del(data['round'])
        return data

    @staticmethod
    def isHaveLiveVideo(match_id):
        count = MatchLive.query.filter_by(match_id=match_id).count()
        if count > 0 :
            return True
        else :
            return False

    @staticmethod
    def add(info_dict):
        match = Match(**info_dict)
        db.session.add(match)
        db.session.commit()

    @staticmethod
    def edit(match_id, info_dict):
        match = Match.query.get(match_id)
        for k, v in info_dict.items():
            setattr(match, k, v)
        db.session.add(match)
        db.session.commit()

    @staticmethod
    def delete(live_id):
        match = Match.query.get(live_id)
        db.session.delete(match)
        db.session.commit()

    @staticmethod
    def get_one(match_id):
        match = Match.query.get(match_id)
        return match and match.to_dict()

    @staticmethod
    def hide(video_id):
        match = Match.query.get(video_id)
        match.visible = MatchVisibleStatus.HIDDEN
        db.session.add(match)
        db.session.commit()

    @staticmethod
    def show(video_id):
        match = Match.query.get(video_id)
        match.visible = MatchVisibleStatus.SHOWN
        db.session.add(match)
        db.session.commit()


class MatchVideoService(object):

    @staticmethod
    def get_all(match_id, type, offset=0, limit=10):

        stmt = MatchVideo.query.filter_by()

        if match_id:
            stmt = stmt.filter_by(match_id=match_id)
        if type == 'replay':
            stmt = stmt.filter(MatchVideo.type == 'replay')
        if type == 'highlight':
            stmt = stmt.filter(MatchVideo.type == 'highlight')

        if type == 'forecast':
            stmt = stmt.filter(MatchVideo.type == 'forecast')

        if type == 'sort':
            stmt = stmt.filter(MatchVideo.pin == 1)
            stmt = stmt.order_by(MatchVideo.display_order.asc())
        else:
            stmt = stmt.order_by(
                MatchVideo.top.desc(), MatchVideo.publish_tm.desc())

        videos = stmt.offset(offset).limit(limit).all()

        return videos

    @staticmethod
    def search_by_id(id):
        videos = MatchVideo.query.filter_by(match_id=id).all()
        return videos

    @staticmethod
    def count(match_id=None, type=None):
        stmt = MatchVideo.query.filter_by()
        if match_id and type:
            stmt = stmt.filter_by(match_id=match_id, type=type)
        elif match_id:
            stmt = stmt.filter_by(match_id=match_id)
        elif type:
            stmt = stmt.filter_by(type=type)
        count = stmt.count()

        return count

    @staticmethod
    def delete(video_id):
        video = MatchVideo.query.get(video_id)
        db.session.delete(video)
        db.session.commit()

    @staticmethod
    def top(video_id, top):
        video = MatchVideo.query.get(video_id)
        video.top = top
        db.session.add(video)
        db.session.commit()

    @staticmethod
    def count_pin(match_id):
        stmt = MatchVideo.query.filter_by(pin=1)
        if match_id:
            stmt = stmt.filter_by(match_id=match_id)
        count = stmt.count()

        return count

    @staticmethod
    def pin(match_id, video_id, pin):
        video = MatchVideo.query.get(video_id)
        video.pin = pin
        video.display_order = MatchVideoService.count_pin(match_id)
        db.session.add(video)
        db.session.commit()

    @staticmethod
    def get_one(video_id):
        video = MatchVideo.query.get(video_id)
        return video

    @staticmethod
    def get_sites():
        source = Site.query.all()
        return source

    @staticmethod
    def add(match_id, title, isvr, type, site,
        duration,
        image,
        play_url, play_html, 
        play_code,
        args=None, publish_tm=None):

        if publish_tm:
            video = MatchVideo(
                    match_id=match_id,
                    title=title,
                    isvr=isvr,
                    type=type,
                    site=site,
                    duration=duration,
                    image=image,
                    play_url=play_url,
                    play_html=play_html,
                    play_code=play_code,
                    args=args,
                    publish_tm=publish_tm)
        else:
            video = MatchVideo(
                    match_id=match_id,
                    title=title,
                    isvr=isvr,
                    type=type,
                    site=site,
                    duration=duration,
                    image=image,
                    play_url=play_url,
                    play_html=play_html,
                    play_code=play_code,
                    args=args)

        db.session.add(video)
        db.session.commit()

    @staticmethod
    def edit(video_id, title, isvr, image, play_url, play_html, play_code):
        video = MatchVideo.query.get(video_id)
        video.title = title
        video.isvr = isvr
        video.image = image
        video.play_url = play_url
        video.play_html = play_html
        video.play_code = play_code

        db.session.add(video)
        db.session.commit()

    @staticmethod
    def sort(match_id, video_id, current, final):

        def sub_number(video):
            video.display_order = MatchVideo.display_order - 1
            db.session.add(video)
            db.session.commit()

        def inc_number(video):
            video.display_order = MatchVideo.display_order + 1
            db.session.add(video)
            db.session.commit()

        if current < final:
            stmt = MatchVideo.query.filter_by(match_id=match_id)
            stmt = MatchVideo.query.filter_by(pin=1)
            video_ids = stmt.filter(current < MatchVideo.display_order,  MatchVideo.display_order <= final).all()
            map(sub_number, video_ids)

        if current > final:
            stmt = MatchVideo.query.filter_by(match_id=match_id)
            stmt = MatchVideo.query.filter_by(pin=1)
            video_ids = stmt.filter(current > MatchVideo.display_order,  MatchVideo.display_order >= final).all()
            map(inc_number, video_ids)

        video = MatchVideo.query.get(video_id)
        video.display_order = final
        db.session.add(video)
        db.session.commit()

    @staticmethod
    def hide(video_id):
        video = MatchVideo.query.get(video_id)
        video.visible = MatchVideoStatus.HIDDEN
        db.session.add(video)
        db.session.commit()

    @staticmethod
    def show(video_id):
        video = MatchVideo.query.get(video_id)
        video.visible = MatchVideoStatus.SHOWN
        db.session.add(video)
        db.session.commit()


class MatchNewsService(object):

    @staticmethod
    def get_all(match_id, type, offset=0, limit=10):

        stmt = MatchNews.query.filter_by()

        if match_id:
            stmt = stmt.filter_by(match_id=match_id)
        if type == 'video':
            stmt = stmt.filter(MatchNews.type == 'video')
        if type == 'literal':
            stmt = stmt.filter(MatchNews.type == 'literal')

        stmt = stmt.order_by(MatchNews.top.desc(), MatchNews.publish_tm.desc())
        news = stmt.offset(offset).limit(limit).all()

        return news

    @staticmethod
    def count(match_id=None, type=None):
        stmt = MatchNews.query.filter_by()
        if match_id and type:
            stmt = stmt.filter_by(match_id=match_id, type=type)
        elif match_id:
            stmt = stmt.filter_by(match_id=match_id)
        elif type:
            stmt = stmt.filter_by(type=type)
        count = stmt.count()

        return count

    @staticmethod
    def search_by_id(id):
        news = MatchNews.query.filter_by(match_id=id).all()
        return news

    @staticmethod
    def delete(news_id):
        news = MatchNews.query.get(news_id)
        db.session.delete(news)
        db.session.commit()

    @staticmethod
    def get_one(news_id):
        news = MatchNews.query.get(news_id)
        return news

    @staticmethod
    def get_sites():
        source = Site.query.all()
        return source

    @staticmethod
    def add(
                 match_id, type, source, source_name,
                 title, isvr, image, play_url,
                 play_code, play_html, subtitle, content,args=None, publish_tm=None):

        if publish_tm:
            news = MatchNews(
                    match_id=match_id,
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
                    publish_tm=publish_tm)
        else:
            news = MatchNews(
                    match_id=match_id,
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
                    args=args)
        db.session.add(news)
        db.session.commit()

    @staticmethod
    def edit(
                 news_id, match_id, type, source, source_name,
                 title, isvr, image, play_url,
                 play_code, play_html, subtitle, content):

        news = MatchNews.query.get(news_id)
        news.match_id = match_id
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
    def top(news_id, top):
        news = MatchNews.query.get(news_id)
        news.top = top
        db.session.add(news)
        db.session.commit()

    @staticmethod
    def pin(news_id, pin):
        news = MatchNews.query.get(news_id)
        news.pin = pin
        db.session.add(news)
        db.session.commit()

    @staticmethod
    def hide(news_id):
        news = MatchNews.query.get(news_id)
        news.visible = MatchNewsStatus.HIDDEN
        db.session.add(news)
        db.session.commit()

    @staticmethod
    def show(news_id):
        news = MatchNews.query.get(news_id)
        news.visible = MatchNewsStatus.SHOWN
        db.session.add(news)
        db.session.commit()
