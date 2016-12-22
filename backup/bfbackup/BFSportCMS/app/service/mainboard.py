# -*- coding: utf-8 -*-

from app.extensions import db
from app.models import ExSlide,SlideStatus,ExSelectedMatch,ExHotspot,ExDiscovery,ExColumn,ExColumnPost


class MainBoardSlideService(object):

    @staticmethod
    def get_all(channel_id=None):

        stmt = ExSlide.query.filter_by()

        slides = stmt.order_by(ExSlide.id.asc()).all()

        return [slide.to_dict() for slide in slides]

    @staticmethod
    def get_one(slide_id):
        slide = ExSlide.query.get(slide_id)
        return slide

    @staticmethod
    def add(info_dict):
        slide = ExSlide(**info_dict)
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def edit(slide_id, info_dict):
        slide = ExSlide.query.get(slide_id)
        for k, v in info_dict.items():
            setattr(slide, k, v)
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def hide(slide_id):
        slide = ExSlide.query.get(slide_id)
        slide.visible = SlideStatus.HIDDEN
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def show(slide_id):
        slide = ExSlide.query.get(slide_id)
        slide.visible = SlideStatus.SHOWN
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def delete(slide_id):
        slide = ExSlide.query.get(slide_id)
        db.session.delete(slide)
        db.session.commit()


class MainBoardSelectedMatchService(object):

    @staticmethod
    def get_all(event_id=None, type=None,  default_tm=None):

        stmt = ExSelectedMatch.query.filter_by()

        stmt = stmt.filter_by()

        matches = stmt.order_by(ExSelectedMatch.display_order.asc()).all()

        return matches

    @staticmethod
    def setAttr(id, key, val):
        dbObj = ExSelectedMatch.query.get(id)
        setattr(dbObj, key, val)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def add(match_id=None):
        match = ExSelectedMatch(match_id=match_id)

        db.session.add(match)
        db.session.commit()

    @staticmethod
    def delete(match_id=None):
        selected = ExSelectedMatch.query.filter_by(match_id=match_id).first()
        match = ExSelectedMatch.query.get(selected.id)
        db.session.delete(match)
        db.session.commit()

    @staticmethod
    def get_one(match_id):
        matches = ExSelectedMatch.query.filter_by(match_id=match_id).all()
        return matches

class MainBoardHotspotService(object):

    @staticmethod
    def get_all():
        stmt = ExHotspot.query.filter_by()
        hotspots = stmt.order_by(ExHotspot.display_order.asc()).all()
        return [hotspot.to_dict() for hotspot in hotspots]

    @staticmethod
    def add(info_dict):
        dbObj = ExHotspot(**info_dict)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def delete(hotspot_id):
        dbObj = ExHotspot.query.get(hotspot_id)
        db.session.delete(dbObj)
        db.session.commit()

    @staticmethod
    def get_one(hotspot_id):
        dbObj = ExHotspot.query.get(hotspot_id)
        return dbObj

    @staticmethod
    def edit(hotspot_id, info_dict):
        dbObj = ExHotspot.query.get(hotspot_id)
        for k, v in info_dict.items():
            setattr(dbObj, k, v)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def setAttr(hotspot_id, key,val):
        dbObj = ExHotspot.query.get(hotspot_id)
        setattr(dbObj, key, val)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def hide(slide_id):
        slide = ExHotspot.query.get(slide_id)
        slide.visible = SlideStatus.HIDDEN
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def show(slide_id):
        slide = ExHotspot.query.get(slide_id)
        slide.visible = SlideStatus.SHOWN
        db.session.add(slide)
        db.session.commit()

    @staticmethod
    def setAttr(id, key, val):
        dbObj = ExHotspot.query.get(id)
        setattr(dbObj, key, val)
        db.session.add(dbObj)
        db.session.commit()

class MainBoardDiscoveryService(object):

    @staticmethod
    def get_all():
        stmt = ExDiscovery.query.filter_by()
        obj = stmt.order_by(ExDiscovery.id.asc()).all()
        return [item.to_dict() for item in obj]

    @staticmethod
    def add(info_dict):
        dbObj = ExDiscovery(**info_dict)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def delete(discovery_id):
        dbObj = ExDiscovery.query.get(discovery_id)
        db.session.delete(dbObj)
        db.session.commit()

    @staticmethod
    def get_one(discovery_id):
        dbObj = ExDiscovery.query.get(discovery_id)
        return dbObj

    @staticmethod
    def edit(discovery_id, info_dict):
        dbObj = ExDiscovery.query.get(discovery_id)
        for k, v in info_dict.items():
            setattr(dbObj, k, v)
        db.session.add(dbObj)
        db.session.commit()

class MainBoardColumnService(object):

    @staticmethod
    def get_all():
        stmt = ExColumn.query.filter_by()
        obj = stmt.order_by(ExColumn.display_order.asc()).all()
        return [item.to_dict() for item in obj]

    @staticmethod
    def add(info_dict):
        dbObj = ExColumn(**info_dict)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def delete(column_id):
        dbObj = ExColumn.query.get(column_id)
        db.session.delete(dbObj)
        db.session.commit()

    @staticmethod
    def get_one(column_id):
        dbObj = ExColumn.query.get(column_id)
        return dbObj

    @staticmethod
    def edit(column_id, info_dict):
        dbObj = ExColumn.query.get(column_id)
        for k, v in info_dict.items():
            setattr(dbObj, k, v)
        db.session.add(dbObj)
        db.session.commit()

    @staticmethod
    def setAttr(id, key, val):
        dbObj = ExColumn.query.get(id)
        setattr(dbObj, key, val)
        db.session.add(dbObj)
        db.session.commit()

class MainBoardColumnPostService(object):

    @staticmethod
    def get_all(column_id=0):

        stmt = ExColumnPost.query.filter_by(column_id=column_id)

        items = stmt.order_by(ExColumnPost.display_order.asc()).all()

        return [item.to_dict() for item in items]

    @staticmethod
    def get_one(column_post_id):
        item = ExColumnPost.query.get(column_post_id)
        return item

    @staticmethod
    def add(info_dict):
        item = ExColumnPost(**info_dict)
        db.session.add(item)
        db.session.commit()

    @staticmethod
    def edit(column_post_id, info_dict):
        item = ExColumnPost.query.get(column_post_id)
        for k, v in info_dict.items():
            setattr(item, k, v)
        db.session.add(item)
        db.session.commit()

    @staticmethod
    def hide(column_post_id):
        item = ExColumnPost.query.get(column_post_id)
        item.visible = SlideStatus.HIDDEN
        db.session.add(item)
        db.session.commit()

    @staticmethod
    def show(column_post_id):
        item = ExColumnPost.query.get(column_post_id)
        item.visible = SlideStatus.SHOWN
        db.session.add(item)
        db.session.commit()

    @staticmethod
    def delete(column_post_id):
        item = ExColumnPost.query.get(column_post_id)
        db.session.delete(item)
        db.session.commit()

    @staticmethod
    def setAttr(id, key, val):
        dbObj = ExColumnPost.query.get(id)
        setattr(dbObj, key, val)
        db.session.add(dbObj)
        db.session.commit()
