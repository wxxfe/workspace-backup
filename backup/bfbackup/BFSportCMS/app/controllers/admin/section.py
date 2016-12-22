# -*- coding: utf-8 -*-

from flask import request
from flask import render_template
from flask import redirect
from flask import url_for

from app.service import SectionService
from app.service import SectionPostService
from app.service import SiteService
from app.utils.filters import Pagination
from app.utils.serialization import jsonify_with_data
from app.utils.serialization import jsonify_with_error
from app.utils.text import string_to_datetime

from . import bp
from . import APIError
from .handler import InvalidArgument


@bp.route('/sections', methods=['GET'])
def list_sections():

    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)

    sections = SectionService.get_all((page - 1) * count, count)

    sections_count = SectionService.count()

    pagination = Pagination(page, count, sections_count)

    return render_template(
                                          'admin/section/list.html',
                                          sections=sections,
                                          pagination=pagination)


@bp.route('/sections/<int:section_id>/show')
def show_section(section_id):
    SectionService.show(section_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/sections/<int:section_id>/hide')
def hide_section(section_id):
    SectionService.hide(section_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/sections/add', methods=['GET', 'POST'])
def add_section():

    if request.method == 'GET':
        return render_template('admin/section/add.html')

    count = SectionService.count()

    try:
        title = request.form.get('title')
        display_order = int(request.form.get('display_order', 0))
        if display_order == 0:
            display_order = count + 1
        image = request.form.get('bgimage')
        logo = request.form.get('logo')
    except:
        raise InvalidArgument()

    SectionService.add(title, display_order, image, logo)

    return redirect(url_for('admin.list_sections'))


@bp.route('/sections/<int:section_id>/edit', methods=['GET', 'POST'])
def edit_section(section_id):

    section = SectionService.get_one(section_id)
    if request.method == 'GET':
        return render_template('admin/section/edit.html', section=section)

    title = request.form.get('title')
    display_order = request.form.get('display_order')

    image = request.form.get('bgimage')
    logo = request.form.get('logo')

    SectionService.edit(section_id, title, display_order, image, logo)

    return redirect(url_for('admin.list_sections'))


@bp.route('/sections/<int:section_id>/delete')
def delete_section(section_id):
    SectionService.delete(section_id)
    return jsonify_with_data(APIError.OK)


@bp.route('/sections/<int:section_id>/posts', methods=['GET'])
def list_section_posts(section_id):

    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)

    posts = SectionPostService.get_all(
        section_id, (page - 1) * count, count)

    posts_count = SectionPostService.count(section_id)

    pagination = Pagination(page, count, posts_count)

    return render_template(
                                          'admin/section/list_posts.html',
                                          posts=posts,
                                          section_id=section_id,
                                          pagination=pagination)


@bp.route('/sections/<int:section_id>/posts/add', methods=['GET', 'POST'])
def add_section_post(section_id):

    if request.method == 'GET':
        sites = SiteService.get_all()
        return render_template(
            'admin/section/add_post.html',
            sites=sites,
            section_id=section_id)

    try:
        title = request.form.get('title')
        site = request.form.get('site')
        origin = request.form.get('origin')
        content = request.form.get('content')
        image = request.form.get('true_image', '')
    except:
        raise InvalidArgument()

    try:
        publish_tm = request.form.get('publish_tm', '')
        string_to_datetime(publish_tm)
    except:
        return jsonify_with_error(APIError.BAD_FORMAT)

    SectionPostService.add(
        section_id, title, site, origin, content, image, publish_tm)

    return redirect(url_for('admin.list_section_posts', section_id=section_id))


@bp.route(
    '/sections/<int:section_id>/posts/<int:post_id>/edit',
    methods=['GET', 'POST'])
def edit_section_post(section_id, post_id):

    post = SectionPostService.get_one(post_id)
    if request.method == 'GET':
        sites = SiteService.get_all()
        return render_template(
            'admin/section/edit_post.html',
            sites=sites,
            section_id=section_id,
            post=post)

    title = request.form.get('title')
    site = request.form.get('site')
    origin = request.form.get('origin')
    content = request.form.get('content')
    image = request.form.get('true_image', '')

    try:
        publish_tm = request.form.get('publish_tm', '')
        string_to_datetime(publish_tm)
    except:
        return jsonify_with_error(APIError.BAD_FORMAT)

    SectionPostService.edit(
        post_id, title, site, origin, content, image, publish_tm)

    return redirect(url_for('admin.list_section_posts', section_id=section_id))


@bp.route(
    '/sections/posts/<int:post_id>/delete')
def delete_section_post(post_id):
    SectionPostService.delete(post_id)
    return jsonify_with_data(APIError.OK)
