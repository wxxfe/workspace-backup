# -*- coding: utf-8 -*-

from app.extensions import db
from app.models import SubPage


class SubPageService(object):

    @staticmethod
    def get_all(event_id=None):
        stmt = SubPage.query.filter_by()

        if event_id:
            stmt = SubPage.query.filter_by(event_id=event_id)
        pages = stmt.order_by(SubPage.display_order.asc()).all()
        return [page.to_dict() for page in pages]

    @staticmethod
    def add(event_id, title, target, brief, display_order):
        sub_page = SubPage(
            event_id=event_id,
            title=title,
            target=target,
            brief=brief,
            display_order=display_order)

        db.session.add(sub_page)
        db.session.commit()

    @staticmethod
    def edit(subpage_id, title, target, brief):
        sub_page = SubPage.query.get(subpage_id)
        sub_page.title = title
        sub_page.target = target
        sub_page.brief = brief
        db.session.add(sub_page)
        db.session.commit()

    @staticmethod
    def delete(subpage_id):
        sub_page = SubPage.query.get(subpage_id)
        db.session.delete(sub_page)
        db.session.commit()

    @staticmethod
    def count(event_id):
        count = SubPage.query.filter_by(event_id=event_id).count()
        return count

    @staticmethod
    def sort(subpage_id, current, final):

        def sub_number(subpage):
            subpage.display_order = SubPage.display_order - 1
            db.session.add(subpage)
            db.session.commit()

        def inc_number(subpage):
            subpage.display_order = SubPage.display_order + 1
            db.session.add(subpage)
            db.session.commit()

        if current < final:
            subpage_ids = SubPage.query.filter(current < SubPage.display_order,  SubPage.display_order <= final).all()
            map(sub_number, subpage_ids)

        if current > final:
            subpage_ids = SubPage.query.filter(current > SubPage.display_order,  SubPage.display_order >= final).all()
            map(inc_number, subpage_ids)

        subpage = SubPage.query.get(subpage_id)
        subpage.display_order = final
        db.session.add(subpage)
        db.session.commit()
