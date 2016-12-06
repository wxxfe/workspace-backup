# -*- coding: utf-8 -*-

from app.extensions import db
from app.models import Section
from app.models import SectionPost


class SectionService(object):

    @staticmethod
    def get_all(offset=0, limit=0):

        stmt = Section.query.filter_by()

        stmt = stmt.order_by(Section.display_order.asc())

        sections = stmt.offset(offset).limit(limit).all()

        return [section.to_dict() for section in sections]

    @staticmethod
    def get_one(section_id):
        section = Section.query.get(section_id)
        return section

    @staticmethod
    def count():
        count = Section.query.filter_by().count()
        return count

    @staticmethod
    def add(title, display_order, image, logo):
        section = Section(
            title=title,
            display_order=display_order,
            image=image,
            logo=logo
        )

        db.session.add(section)
        db.session.commit()

    @staticmethod
    def edit(section_id, title, display_order, image, logo):
        section = Section.query.get(section_id)
        section.title = title
        section.display_order = display_order
        section.image = image
        section.logo = logo

        db.session.add(section)
        db.session.commit()

    @staticmethod
    def hide(section_id):
        section = Section.query.get(section_id)
        section.visible = 0
        db.session.add(section)
        db.session.commit()

    @staticmethod
    def show(section_id):
        section = Section.query.get(section_id)
        section.visible = 1
        db.session.add(section)
        db.session.commit()

    @staticmethod
    def delete(section_id):
        section = Section.query.get(section_id)
        db.session.delete(section)
        db.session.commit()


class SectionPostService(object):

    @staticmethod
    def get_all(section_id, offset=0, limit=0):

        stmt = SectionPost.query.filter_by(section_id=section_id)

        stmt = stmt.order_by(SectionPost.publish_tm.desc())

        posts = stmt.offset(offset).limit(limit).all()

        return [post.to_dict() for post in posts]

    @staticmethod
    def add(section_id, title, site, origin, content, image, publish_tm):
        post = SectionPost(
            section_id=section_id,
            title=title,
            site=site,
            origin=origin,
            content=content,
            image=image,
            publish_tm=publish_tm
        )

        db.session.add(post)
        db.session.commit()

    @staticmethod
    def edit(post_id, title, site, origin, content, image, publish_tm):
        post = SectionPost.query.get(post_id)
        post.title = title
        post.site = site
        post.origin = origin
        post.content = content
        post.image = image
        post.publish_tm = publish_tm

        db.session.add(post)
        db.session.commit()

    @staticmethod
    def delete(post_id):
        post = SectionPost.query.get(post_id)
        db.session.delete(post)
        db.session.commit()

    @staticmethod
    def count(section_id):
        count = SectionPost.query.filter_by(section_id=section_id).count()
        return count

    @staticmethod
    def get_one(post_id):
        post = SectionPost.query.get(post_id)
        return post
