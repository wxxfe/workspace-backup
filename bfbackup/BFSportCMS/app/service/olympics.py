# -*- coding: utf-8 -*-

from app.utils.text import calculate_datetime

from app.extensions import db
from app.models import OGNews
from app.models import OGVideo
from app.models import Collection
from app.models import CollectionVideo
from app.models import OGSchedule
from app.models import OGSlide
from app.models import OGSlideStatus
from app.models import OGMedal
from app.models import OGHtml
from app.models import OGChampion
from app.models import ChampionNews
from app.models import OGGallery
from app.models import OGGalleryImage
from app.models import OGLive

class OlympicsSlideService(object):

    @staticmethod
    def get_all(platform=None):

        stmt = OGSlide.query.filter_by(platform=platform)

        slides = stmt.order_by(OGSlide.display_order.desc()).all()

        return [slide.to_dict() for slide in slides]

    @staticmethod
    def get_one(slide_id):
        slide = OGSlide.query.get(slide_id)
        return slide

    @staticmethod
    def add(info_dict):
        slide = OGSlide(**info_dict)
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def edit(slide_id, info_dict):
        slide = OGSlide.query.get(slide_id)
        for k, v in info_dict.items():
            setattr(slide, k, v)
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def hide(slide_id):
        slide = OGSlide.query.get(slide_id)
        slide.visible = OGSlideStatus.HIDDEN
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def show(slide_id):
        slide = OGSlide.query.get(slide_id)
        slide.visible = OGSlideStatus.SHOWN
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def delete(slide_id):
        slide = OGSlide.query.get(slide_id)
        db.session.delete(slide)
        db.session.commit()

    @staticmethod
    def checkIDValid(type,id):
        if type == 'match_news' or type == 'news':
            ret = OGNews.query.get(id)
        elif type == 'match_video' or type == 'video':
            ret = OGVideo.query.get(id)
        else:
            ret = ''

        return bool(ret)


class OGMedalService(object):

    @staticmethod
    def get_all():
        stmt = OGMedal.query.filter_by()
        obj = stmt.order_by(OGMedal.gold_medals.desc()).order_by(OGMedal.silver_medals.desc()).order_by(OGMedal.bronze_medals.desc()).all()
        return [item.to_dict() for item in obj]

    @staticmethod
    def add(info_dict):
        dbObj = OGMedal(**info_dict)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def delete(medal_id):
        dbObj = OGMedal.query.get(medal_id)
        db.session.delete(dbObj)
        db.session.commit()

    @staticmethod
    def get_one(medal_id):
        dbObj = OGMedal.query.get(medal_id)
        return dbObj

    @staticmethod
    def edit(medal_id, info_dict):
        dbObj = OGMedal.query.get(medal_id)
        for k, v in info_dict.items():
            setattr(dbObj, k, v)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def setAttr(id, key, val):
        dbObj = OGMedal.query.get(id)
        setattr(dbObj, key, val)
        db.session.add(dbObj)
        db.session.commit()


class OGHtmlService(object):

    @staticmethod
    def get_all():
        stmt = OGHtml.query.filter_by()
        obj = stmt.order_by(OGHtml.id.asc()).all()
        return [item.to_dict() for item in obj]

    @staticmethod
    def add(info_dict):
        dbObj = OGHtml(**info_dict)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def delete(html_id):
        dbObj = OGHtml.query.get(html_id)
        db.session.delete(dbObj)
        db.session.commit()

    @staticmethod
    def get_one(html_id):
        dbObj = OGHtml.query.get(html_id)
        return dbObj

    @staticmethod
    def edit(html_id, info_dict):
        dbObj = OGHtml.query.get(html_id)
        for k, v in info_dict.items():
            setattr(dbObj, k, v)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def setAttr(id, key, val):
        dbObj = OGHtml.query.get(id)
        setattr(dbObj, key, val)
        db.session.add(dbObj)
        db.session.commit()

class OlympicsChampionNewsService(object):

    @staticmethod
    def deleteChampionNews(champion_id):
        ChampionNews.query.filter_by(champion_id=champion_id).delete()
        db.session.commit()

    @staticmethod
    def get_filter_one(champion_id, news_id):
        stmt = ChampionNews.query.filter_by(champion_id=champion_id)
        news = stmt.filter_by(news_id=news_id).first()
        return news

    @staticmethod
    def edit(champion_news_id, info_dict):
        dbObj = ChampionNews.query.get(champion_news_id)
        for k, v in info_dict.items():
            setattr(dbObj, k, v)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def get_one(champion_new_id):
        dbObj = ChampionNews.query.get(champion_new_id)
        return dbObj


class OlympicsChampionService(object):

    @staticmethod
    def get_all(offset=0, limit=0):
        stmt = OGChampion.query.filter_by()
        stmt = stmt.order_by(OGChampion.num.desc())
        obj = stmt.offset(offset).limit(limit).all()
        return [item.to_dict() for item in obj]

    @staticmethod
    def count():
        count = OGChampion.query.filter_by().count()
        return count

    @staticmethod
    def add(info_dict):
        dbObj = OGChampion(**info_dict)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def delete(champion_id):
        dbObj = OGChampion.query.get(champion_id)
        db.session.delete(dbObj)
        db.session.commit()

    @staticmethod
    def get_one(champion_id):
        dbObj = OGChampion.query.get(champion_id)
        return dbObj

    @staticmethod
    def edit(champion_id, info_dict):
        dbObj = OGChampion.query.get(champion_id)
        for k, v in info_dict.items():
            setattr(dbObj, k, v)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def setAttr(id, key, val):
        dbObj = OGChampion.query.get(id)
        setattr(dbObj, key, val)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def getChampionNews(champion_id):
        def get_match_info(championNews):
            news_id = championNews.news_id
            title = championNews.title
            retdic = dict()
            if championNews.type == 'news':
                news = OGNewsService.get_one(news_id)
                if news:
                    newsdic = news.to_dict()
                    image = newsdic['image']
                else:
                    title = ''
                    image = ''

            elif championNews.type == 'video':
                video = OGVideoService.get_one(news_id)
                if video:
                    videodic = video.to_dict()
                    image = videodic['image']
                else:
                    image = ''
            retdic['title'] = title
            retdic['image'] = image
            retdic['id'] = news_id
            retdic['champion_news_id'] = championNews.id
            retdic['champion_news_type'] = championNews.type
            return retdic
        championNews = ChampionNews.query.filter_by(champion_id=champion_id)
        championNews = map(get_match_info,championNews)
        return championNews

    @staticmethod
    def addNews(info_dict):
        dbObj = ChampionNews(**info_dict)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def delNews(champion_news_id):
        item = ChampionNews.query.get(champion_news_id)
        db.session.delete(item)
        db.session.commit()


class OlympicsGalleryService(object):

    @staticmethod
    def get_all(offset=0, limit=0):
        stmt = OGGallery.query.filter_by()
        stmt = stmt.order_by(OGGallery.id.desc())
        obj = stmt.offset(offset).limit(limit).all()

        def process(obj):
            gallery = obj.to_dict()
            gallery['imgNum'] = OGGalleryImage.query.filter_by(gallery_id=gallery['id']).count()
            return gallery
        obj = map(process,obj)
        return obj

    @staticmethod
    def count():
        count = OGGallery.query.filter_by().count()
        return count

    @staticmethod
    def hide(gallery_id):
        item = OGGallery.query.get(gallery_id)
        item.visible = 0
        db.session.add(item)
        db.session.commit()

    @staticmethod
    def show(gallery_id):
        item = OGGallery.query.get(gallery_id)
        item.visible = 1
        db.session.add(item)
        db.session.commit()

    @staticmethod
    def add(info_dict):
        dbObj = OGGallery(**info_dict)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def delete(gallery_id):
        dbObj = OGGallery.query.get(gallery_id)
        db.session.delete(dbObj)
        db.session.commit()

    @staticmethod
    def get_one(gallery_id):
        dbObj = OGGallery.query.get(gallery_id)
        return dbObj

    @staticmethod
    def edit(gallery_id, info_dict):
        dbObj = OGGallery.query.get(gallery_id)
        for k, v in info_dict.items():
            setattr(dbObj, k, v)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def setAttr(id, key, val):
        dbObj = OGGallery.query.get(id)
        setattr(dbObj, key, val)
        db.session.add(dbObj)
        db.session.commit()


class OlympicsGalleryImageService(object):

    @staticmethod
    def get_all():
        stmt = OGGalleryImage.query.filter_by()
        obj = stmt.order_by(OGGalleryImage.id.asc()).all()
        return [item.to_dict() for item in obj]

    @staticmethod
    def add(info_dict):
        dbObj = OGGalleryImage(**info_dict)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def delete(gallery_image_id):
        dbObj = OGGalleryImage.query.get(gallery_image_id)
        db.session.delete(dbObj)
        db.session.commit()

    @staticmethod
    def deleteByGalleryID(gallery_id):
        OGGalleryImage.query.filter_by(gallery_id=gallery_id).delete()
        db.session.commit()

    @staticmethod
    def get_one(gallery_image_id):
        dbObj = OGGalleryImage.query.get(gallery_image_id)
        return dbObj

    @staticmethod
    def get_all_gallery_image(gallery_id):
        dbObj = OGGalleryImage.query.filter_by(gallery_id=gallery_id)
        def dictProcess(dic):
            return dic.to_dict()
        return map(dictProcess,dbObj)

    @staticmethod
    def edit(gallery_id, info_dict):
        dbObj = OGGalleryImage.query.get(gallery_id)
        for k, v in info_dict.items():
            setattr(dbObj, k, v)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def setAttr(id, key, val):
        dbObj = OGGalleryImage.query.get(id)
        setattr(dbObj, key, val)
        db.session.add(dbObj)
        db.session.commit()


class CollectionService(object):

    @staticmethod
    def get_all(type, offset=0, limit=0):

        if type == 'all':
            stmt = Collection.query.filter_by()
        else:
            stmt = Collection.query.filter_by(type=type)

        items = stmt.offset(offset).limit(limit).all()
        return [item.to_dict() for item in items]

    @staticmethod
    def get_one(collection_id):
        item = Collection.query.get(collection_id)
        return item and item.to_dict()

    @staticmethod
    def count():
        count = Collection.query.filter_by().count()
        return count

    @staticmethod
    def add(title, type, image):
        item = Collection(
            title=title,
            type=type,
            image=image
        )

        db.session.add(item)
        db.session.commit()

    @staticmethod
    def edit(collection_id, title, type, image):
        item = Collection.query.get(collection_id)
        item.title = title
        item.type = type
        if image:
            item.image = image

        db.session.add(item)
        db.session.commit()

    @staticmethod
    def hide(collection_id):
        item = Collection.query.get(collection_id)
        item.visible = 0
        db.session.add(item)
        db.session.commit()

    @staticmethod
    def show(collection_id):
        item = Collection.query.get(collection_id)
        item.visible = 1
        db.session.add(item)
        db.session.commit()

    @staticmethod
    def delete(collection_id):
        item = Collection.query.get(collection_id)
        db.session.delete(item)
        db.session.commit()


class CollectionVideoService(object):

    @staticmethod
    def delete(collection_id, video_id):
        item = (CollectionVideo.query.filter_by(collection_id=collection_id)
                                                       .filter_by(video_id=video_id).first())
        db.session.delete(item)
        db.session.commit()

    @staticmethod
    def get_filter_one(collection_id, video_id):
        item = (CollectionVideo.query.filter_by(collection_id=collection_id)
                                                       .filter_by(video_id=video_id).first())
        return item

    @staticmethod
    def add(collection_id, video_id):
        item = CollectionVideo(
            collection_id=collection_id,
            video_id=video_id
        )

        db.session.add(item)
        db.session.commit()


class OGScheduleService(object):

    @staticmethod
    def get_all(start_tm=None):

        if start_tm:
            start_dt = start_tm
            end_dt = calculate_datetime(start_dt, days=1)
            stmt = OGSchedule.query.filter(
                OGSchedule.start_tm >= start_dt,
                OGSchedule.start_tm < end_dt)
            items = stmt.order_by(OGSchedule.top.desc(), OGSchedule.start_tm.asc()).all()
            return [item.to_dict() for item in items]
        else:
            return []

    @staticmethod
    def add(start_tm, large_project, small_project, round):
        schedule = OGSchedule(
            start_tm=start_tm,
            large_project=large_project,
            small_project=small_project,
            round=round)
        db.session.add(schedule)
        db.session.commit()

    @staticmethod
    def edit(schedule_id, start_tm, large_project, small_project, round):
        schedule = OGSchedule.query.get(schedule_id)
        schedule.start_tm = start_tm
        schedule.large_project = large_project
        schedule.small_project = small_project
        schedule.round = round
        db.session.add(schedule)
        db.session.commit()

    @staticmethod
    def delete(schedule_id):
        item = OGSchedule.query.get(schedule_id)
        db.session.delete(item)
        db.session.commit()

    @staticmethod
    def get_one(schedule_id):
        item = OGSchedule.query.get(schedule_id)
        return item

    @staticmethod
    def top(schedule_id, top):
        item = OGSchedule.query.get(schedule_id)
        item.top = top
        db.session.add(item)
        db.session.commit()


class OGVideoService(object):

    @staticmethod
    def get_all(type, offset=0, limit=0):

        if type == 'all':
            stmt = OGVideo.query.filter_by()
        else:
            stmt = OGVideo.query.filter_by(type=type)

        stmt = stmt.order_by(OGVideo.top.desc(), OGVideo.created_at.desc())
        videos = stmt.offset(offset).limit(limit).all()
        return [video.to_dict() for video in videos]

    @staticmethod
    def get_one(video_id):
        video = OGVideo.query.get(video_id)
        return video

    @staticmethod
    def count(type=None):
        stmt = OGVideo.query.filter_by()
        if type == 'all':
            stmt = stmt.filter_by()
        else:
            stmt = stmt.filter_by(type=type)
        count = stmt.count()
        return count

    @staticmethod
    def add(title, isvr, type, site, duration, image, play_url, play_code,
        args=None, publish_tm=None):

        video = OGVideo(
                title=title,
                isvr=isvr,
                type=type,
                site=site,
                duration=duration,
                image=image,
                play_url=play_url,
                play_code=play_code,
                args=args,
                publish_tm=publish_tm
        )

        db.session.add(video)
        db.session.commit()

    @staticmethod
    def edit(video_id, title, isvr, image, play_url, play_code):
        video = OGVideo.query.get(video_id)
        video.title = title
        video.isvr = isvr
        video.image = image
        video.play_url = play_url
        video.play_code = play_code

        db.session.add(video)
        db.session.commit()

    @staticmethod
    def delete(video_id):
        video = OGVideo.query.get(video_id)
        db.session.delete(video)
        db.session.commit()

    @staticmethod
    def top(video_id, top):
        video = OGVideo.query.get(video_id)
        video.top = top
        db.session.add(video)
        db.session.commit()

    @staticmethod
    def hide(video_id):
        video = OGVideo.query.get(video_id)
        video.visible = '0'
        db.session.add(video)
        db.session.commit()

    @staticmethod
    def show(video_id):
        video = OGVideo.query.get(video_id)
        video.visible = '1'
        db.session.add(video)
        db.session.commit()

    @staticmethod
    def search_by_name(keyword):
        stmt = OGVideo.query.filter(
            OGVideo.title.like('%' + keyword + '%'))
        videos = stmt.order_by(OGVideo.created_at.desc()).all()
        return [video.to_dict() for video in videos]


class OGNewsService(object):

    @staticmethod
    def get_all(type, offset=0, limit=0):

        if type == 'all':
            stmt = OGNews.query.filter_by()
        else:
            stmt = OGNews.query.filter_by(type=type)

        stmt = stmt.order_by(OGNews.top.desc(), OGNews.created_at.desc())
        news = stmt.offset(offset).limit(limit).all()
        return [new.to_dict() for new in news]

    @staticmethod
    def get_one(news_id):
        news = OGNews.query.get(news_id)
        return news

    @staticmethod
    def count(type=None):
        stmt = OGNews.query.filter_by()
        if type:
            stmt = stmt.filter_by(type=type)
        count = stmt.count()
        return count

    @staticmethod
    def add(type, title, subtitle, content, image, site='bfonline'):

        news = OGNews(
                type=type,
                title=title,
                subtitle=subtitle,
                content=content,
                image=image,
                site=site,
        )

        db.session.add(news)
        db.session.commit()

    @staticmethod
    def edit(news_id, type, title, subtitle, content, image):

        news = OGNews.query.get(news_id)
        news.type = type
        news.title = title
        news.subtitle = subtitle
        news.content = content
        news.image = image

        db.session.add(news)
        db.session.commit()

    @staticmethod
    def delete(news_id):
        news = OGNews.query.get(news_id)
        db.session.delete(news)
        db.session.commit()

    @staticmethod
    def top(news_id, top):
        news = OGNews.query.get(news_id)
        news.top = top
        db.session.add(news)
        db.session.commit()

    @staticmethod
    def hide(news_id):
        news = OGNews.query.get(news_id)
        news.visible = '0'
        db.session.add(news)
        db.session.commit()

    @staticmethod
    def show(news_id):
        news = OGNews.query.get(news_id)
        news.visible = '1'
        db.session.add(news)
        db.session.commit()


class OGLiveService(object):

    @staticmethod
    def get_all():
        stmt = OGLive.query.filter_by()
        obj = stmt.order_by(OGLive.id.desc()).all()
        return [item.to_dict() for item in obj]

    @staticmethod
    def add(info_dict):
        dbObj = OGLive(**info_dict)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def delete(medal_id):
        dbObj = OGLive.query.get(medal_id)
        db.session.delete(dbObj)
        db.session.commit()

    @staticmethod
    def get_one(medal_id):
        dbObj = OGLive.query.get(medal_id)
        return dbObj

    @staticmethod
    def edit(medal_id, info_dict):
        dbObj = OGLive.query.get(medal_id)
        for k, v in info_dict.items():
            setattr(dbObj, k, v)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def setAttr(id, key, val):
        dbObj = OGLive.query.get(id)
        setattr(dbObj, key, val)
        db.session.add(dbObj)
        db.session.commit()
