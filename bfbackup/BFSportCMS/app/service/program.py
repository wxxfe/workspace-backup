# -*- coding: utf-8 -*-

from app.extensions import db
from app.models import Program
from app.models import ProgramPost


class ProgramService(object):

    @staticmethod
    def get_all(offset=0, limit=0):

        stmt = Program.query.filter_by()

        stmt = stmt.order_by(Program.display_order.asc())

        programs = stmt.offset(offset).limit(limit).all()

        return [program.to_dict() for program in programs]

    @staticmethod
    def get_simple_all():
        stmt = Program.query.filter_by()
        programs = stmt.order_by(Program.display_order.asc()).all()
        return [program.to_dict() for program in programs]

    @staticmethod
    def get_one(program_id):
        program = Program.query.get(program_id)
        return program

    @staticmethod
    def count():
        count = Program.query.filter_by().count()
        return count

    @staticmethod
    def add(title, display_order):
        program = Program(
            title=title,
            display_order=display_order
        )

        db.session.add(program)
        db.session.commit()

    @staticmethod
    def edit(program_id, title, display_order):
        program = Program.query.get(program_id)
        program.title = title
        program.display_order = display_order

        db.session.add(program)
        db.session.commit()

    @staticmethod
    def hide(program_id):
        program = Program.query.get(program_id)
        program.visible = 0
        db.session.add(program)
        db.session.commit()

    @staticmethod
    def show(program_id):
        program = Program.query.get(program_id)
        program.visible = 1
        db.session.add(program)
        db.session.commit()

    @staticmethod
    def delete(program_id):
        program = Program.query.get(program_id)
        db.session.delete(program)
        db.session.commit()


class ProgramPostService(object):

    @staticmethod
    def get_all(program_id, offset=0, limit=0):

        stmt = ProgramPost.query.filter_by(program_id=program_id)
        stmt = stmt.order_by(ProgramPost.publish_tm.desc())
        posts = stmt.offset(offset).limit(limit).all()

        return [post.to_dict() for post in posts]

    @staticmethod
    def add(program_id, title, isvr, site, origin, brief,
                 image, play_url, play_html, play_code, publish_tm, args=None):

        post = ProgramPost(
            program_id=program_id,
            title=title,
            isvr=isvr,
            site=site,
            origin=origin,
            brief=brief,
            play_url=play_url,
            play_html=play_html,
            play_code=play_code,
            image=image,
            publish_tm=publish_tm,
            args=args
        )

        db.session.add(post)
        db.session.commit()

    @staticmethod
    def edit(post_id, title, isvr, site, origin, brief,
                 image, play_url, play_html, play_code, publish_tm):
        post = ProgramPost.query.get(post_id)
        post.title = title
        post.isvr = isvr
        post.site = site
        post.origin = origin
        post.brief = brief
        post.play_url = play_url
        post.play_html = play_html
        post.play_code = play_code
        post.image = image
        post.publish_tm = publish_tm

        db.session.add(post)
        db.session.commit()

    @staticmethod
    def delete(post_id):
        post = ProgramPost.query.get(post_id)
        db.session.delete(post)
        db.session.commit()

    @staticmethod
    def count(program_id):
        count = ProgramPost.query.filter_by(program_id=program_id).count()
        return count

    @staticmethod
    def get_one(post_id):
        post = ProgramPost.query.get(post_id)
        return post
