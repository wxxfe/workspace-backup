# -*- coding: utf-8 -*-

from flask import request
from flask import render_template
from flask import redirect
from flask import url_for

from app.service import SlideService
from app.service import ChannelService
from app.utils.serialization import jsonify_with_data

from . import bp


@bp.route('/slides')
def list_slides():
    platform = request.args.get('platform', 'app')

    channel_id = int(request.args.get('channel_id', 0))

    slides = SlideService.get_all(platform=platform)

    if channel_id == 0:
        slides = SlideService.get_all(platform=platform, channel_id=channel_id)

    if channel_id:
        slides = SlideService.get_all(platform=platform, channel_id=channel_id)

    channels = ChannelService.get_all(type='football')

    return render_template(
                                          'admin/slide/list.html',
                                          slides=slides,
                                          platform=platform,
                                          channel_id=channel_id,
                                          channels=channels)


@bp.route('/slides/add', methods=['GET', 'POST'])
def add_slide():
    channels = ChannelService.get_shown_all()
    if request.method == 'GET':
        return render_template(
            'admin/slide/add.html',
            channels=channels)

    platform = request.args.get('platform', 'app')
    slide_dict = request.form.to_dict()

    if slide_dict['type'] == "ad":
        slide_dict['target_id'] = 0
    elif slide_dict['type'] == 'html':
        slide_dict['target_id'] = 0
    else:
        slide_dict['target_id'] = int(slide_dict['target_id'])
    if not slide_dict['display_order']:
        slide_dict['display_order'] = 0
    slide_dict['image'] = slide_dict['cover']
    slide_dict['platform'] = platform
    del (slide_dict['cover'])

    SlideService.add(slide_dict)

    return redirect(url_for('admin.list_slides', platform=platform))


@bp.route('/slides/web/add', methods=['GET', 'POST'])
def add_web_slide():
    channel_id = int(request.args.get('channel_id', 0))
    channels = ChannelService.get_shown_all()
    if request.method == 'GET':
        return render_template(
            'admin/slide/add_web.html',
            channel_id=channel_id,
            channels=channels)

    platform = request.args.get('platform', 'web')
    slide_dict = request.form.to_dict()

    slide_dict['target_id'] = 0
    if not slide_dict['display_order']:
        slide_dict['display_order'] = 0
    slide_dict['image'] = slide_dict['cover']
    if slide_dict.has_key("covermin"):
        slide_dict['thumbnail'] = slide_dict['covermin']
        del (slide_dict['covermin'])
    slide_dict['platform'] = platform
    del (slide_dict['cover'])

    SlideService.add(slide_dict)

    return redirect(url_for(
        'admin.list_slides',
        platform=platform,
        channel_id=channel_id))


@bp.route('/slides/<int:slide_id>', methods=['GET', 'POST'])
def edit_slide(slide_id):
    channels = ChannelService.get_shown_all()
    slide = SlideService.get_one(slide_id)
    if request.method == 'GET':
        return render_template(
                                              'admin/slide/edit.html',
                                              slide=slide,
                                              channels=channels)

    slide_dict = request.form.to_dict()
    if slide_dict['type'] == "ad":
        slide_dict['target_id'] = 0
    elif slide_dict['type'] == 'html':
        slide_dict['target_id'] = 0
    else:
        slide_dict['target_id'] = int(slide_dict['target_id'])
    slide_dict['image'] = slide_dict['cover']
    slide_dict['display_order'] = int(slide_dict['display_order'])
    del (slide_dict['cover'])

    SlideService.edit(slide_id, slide_dict)

    return redirect(url_for('admin.list_slides', platform='app'))


@bp.route('/slides/web/<int:slide_id>', methods=['GET', 'POST'])
def edit_web_slide(slide_id):
    channel_id = int(request.args.get('channel_id', 0))
    channels = ChannelService.get_shown_all()
    slide = SlideService.get_one(slide_id)
    if request.method == 'GET':
        return render_template(
                                              'admin/slide/edit_web.html',
                                              slide=slide,
                                              channel_id=channel_id,
                                              channels=channels)

    slide_dict = request.form.to_dict()
    if not slide_dict['bgcolor']:
        slide_dict['bgcolor'] = "#000000"
    slide_dict['target_id'] = 0
    display_order = slide_dict['display_order'] or 0
    slide_dict['image'] = slide_dict['cover']
    if slide_dict.has_key("covermin"):
        slide_dict['thumbnail'] = slide_dict['covermin']
        del (slide_dict['covermin'])
    slide_dict['display_order'] = display_order
    del (slide_dict['cover'])

    SlideService.edit(slide_id, slide_dict)

    return redirect(url_for('admin.list_slides', platform='web', channel_id=channel_id))


@bp.route('/slides/<int:slide_id>/show')
def show_slide(slide_id):
    SlideService.show(slide_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/slides/<int:slide_id>/hide')
def hide_slide(slide_id):
    SlideService.hide(slide_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/slides/<int:slide_id>/delete')
def delete_slide(slide_id):
    SlideService.delete(slide_id)
    return jsonify_with_data((200, 'OK'))
