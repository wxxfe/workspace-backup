# -*- coding: utf-8 -*-

from flask import request
from flask import render_template
from flask import redirect
from flask import url_for

from app.service import ProgramService
from app.service import ProgramPostService
from app.service import SiteService
from app.utils.filters import Pagination
from app.utils.serialization import jsonify_with_data
from app.utils.serialization import jsonify_with_error
from app.utils.text import string_to_datetime

from . import bp
from . import APIError
from .handler import InvalidArgument


@bp.route('/programs', methods=['GET'])
def list_programs():

    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)

    programs = ProgramService.get_all((page - 1) * count, count)

    programs_count = ProgramService.count()

    pagination = Pagination(page, count, programs_count)

    return render_template(
                                          'admin/program/list.html',
                                          programs=programs,
                                          pagination=pagination)


@bp.route('/programs/<int:program_id>/show')
def show_program(program_id):
    ProgramService.show(program_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/programs/<int:program_id>/hide')
def hide_program(program_id):
    ProgramService.hide(program_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/programs/add', methods=['GET', 'POST'])
def add_program():

    if request.method == 'GET':
        return render_template('admin/program/add.html')

    try:
        title = request.form.get('title')
        display_order = int(request.form.get('display_order', 0))
        if display_order == 0:
            count = ProgramService.count()
            display_order = count + 1
    except:
        raise InvalidArgument()

    ProgramService.add(title, display_order)

    return jsonify_with_data(APIError.OK)


@bp.route('/programs/<int:program_id>/edit', methods=['GET', 'POST'])
def edit_program(program_id):

    program = ProgramService.get_one(program_id)
    if request.method == 'GET':
        return render_template('admin/program/edit.html', program=program)

    title = request.form.get('title')
    display_order = int(request.form.get('display_order', 0))
    if display_order == 0:
        count = ProgramService.count()
        display_order = count + 1

    ProgramService.edit(program_id, title, display_order)

    return jsonify_with_data(APIError.OK)


@bp.route('/programs/<int:program_id>/delete')
def delete_program(program_id):
    ProgramService.delete(program_id)
    return jsonify_with_data(APIError.OK)


@bp.route('/programs/<int:program_id>/posts', methods=['GET'])
def list_program_posts(program_id):

    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)

    posts = ProgramPostService.get_all(
        program_id, (page - 1) * count, count)

    programs_count = ProgramPostService.count(program_id)

    pagination = Pagination(page, count, programs_count)

    return render_template(
                                          'admin/program/list_posts.html',
                                          posts=posts,
                                          program_id=program_id,
                                          pagination=pagination)


@bp.route('/programs/<int:program_id>/posts/add', methods=['GET', 'POST'])
def add_program_post(program_id):

    if request.method == 'GET':
        sites = SiteService.get_all()
        return render_template(
            'admin/program/add_post.html',
            program_id=program_id,
            sites=sites
        )

    try:
        title = request.form.get('title')
        isvr = request.form.get('isvr', 0, int)
        site = request.form.get('site', '')
        origin = request.form.get('origin', '')
        brief = request.form.get('brief', '')
        image = request.form.get('true_image', '')
        play_url = request.form.get('play_url')
        play_html = request.form.get('play_html')
        play_code = request.form.get('play_code')
    except:
        raise InvalidArgument()

    try:
        publish_tm = request.form.get('publish_tm', '')
        string_to_datetime(publish_tm)
    except:
        return jsonify_with_error(APIError.BAD_FORMAT)

    ProgramPostService.add(
        program_id, title, isvr, site, origin, brief,
        image, play_url, play_html, play_code, publish_tm)

    return redirect(url_for('admin.list_program_posts', program_id=program_id))


@bp.route(
    '/programs/<int:program_id>/posts/<int:post_id>/edit',
    methods=['GET', 'POST'])
def edit_program_post(program_id, post_id):

    post = ProgramPostService.get_one(post_id)
    if request.method == 'GET':
        sites = SiteService.get_all()
        return render_template(
            'admin/program/edit_post.html',
            sites=sites,
            program_id=program_id,
            post=post)
    title = request.form.get('title')
    isvr = request.form.get('isvr', 0, int)
    site = request.form.get('site', '')
    origin = request.form.get('origin', '')
    brief = request.form.get('brief', '')
    image = request.form.get('true_image', '')
    play_url = request.form.get('play_url')
    play_html = request.form.get('play_html')
    play_code = request.form.get('play_code')

    try:
        publish_tm = request.form.get('publish_tm', '')
        string_to_datetime(publish_tm)
    except:
        return jsonify_with_error(APIError.BAD_FORMAT)

    ProgramPostService.edit(post_id, title, isvr, site, origin, brief,
                 image, play_url, play_html, play_code, publish_tm)

    return redirect(url_for('admin.list_program_posts', program_id=program_id))


@bp.route(
    '/programs/posts/<int:post_id>/delete')
def delete_program_post(post_id):
    ProgramPostService.delete(post_id)
    return jsonify_with_data(APIError.OK)
