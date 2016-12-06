# -*- coding: utf-8 -*-

from flask import request
from flask import render_template
from flask import url_for
from flask import redirect

from app.service import MatchVideoService
from app.service import MatchNewsService
from app.utils.filters import Pagination
from app.utils.text import add_image_domain
from app.utils.serialization import jsonify_with_data

from . import bp
from . import APIError
from .handler import InvalidArgument, ServerError


@bp.route('/matches/news', methods=['GET', 'POST'])
def list_news():

    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)

    match_id = request.args.get('match_id')
    type = request.args.get('type')

    if request.method == 'POST':
        search_id = request.form.get('search')
        return redirect(url_for(
            'admin.list_news', match_id=search_id, type=type))

    news = MatchNewsService.get_all(match_id, type, (page-1)*count, count)

    # get match format date
    def add_info_image(news):
        news.image = add_image_domain(news.image)
        return news
    news = map(add_info_image, news)

    if match_id and type:
        news_count = MatchNewsService.count(match_id, type=type)
    elif match_id:
        news_count = MatchNewsService.count(match_id)
    elif type:
        news_count = MatchNewsService.count(type=type)
    else:
        news_count = MatchNewsService.count()

    pagination = Pagination(page, count, news_count)

    return render_template(
          'admin/match/news_list.html',
          news=news,
          pagination=pagination,
          match_id=match_id,
          type=type,
          url=request.url)


@bp.route('/matches/news/add', methods=['GET', 'POST'])
def add_news():

    match_id = request.args.get('match_id') or request.form.get('match_id')
    type = request.args.get('type') or request.form.get('type')

    redirect_url = request.args.get('redirect', '')

    if request.method == 'GET':
        sites = MatchNewsService.get_sites()
        return render_template(
            'admin/match/news_add.html',
            sites=sites,
            url=redirect_url,
            match_id=match_id)

    source = request.form.get('site')
    source_name = request.form.get('site_name')
    title = request.form.get('title')
    isvr = request.form.get('isvr', 0, int)
    image = request.form.get('cover')
    play_url = request.form.get('play_url', '')
    play_code = request.form.get('play_code', '')
    play_html = request.form.get('play_html', '')
    subtitle = request.form.get('subtitle')
    content = request.form.get('content')

    MatchNewsService.add(
        match_id, type, source, source_name,
        title, isvr, image, play_url, play_code, play_html, subtitle, content)

    return redirect(url_for('admin.list_news', match_id=match_id, type=type))


@bp.route('/matches/news/<int:news_id>/edit', methods=['GET', 'POST'])
def edit_news(news_id):

    redirect_url = request.args.get('redirect', '')

    if request.method == 'GET':
        sites = MatchNewsService.get_sites()
        news = MatchNewsService.get_one(news_id).to_dict()
        return render_template(
            'admin/match/news_edit.html', sites=sites, news=news)

    mid = request.form.get('match_id', type=int)
    type = request.form.get('type', 'video')
    source = request.form.get('site')
    source_name = request.form.get('site_name')
    title = request.form.get('title')
    isvr = request.form.get('isvr', 0, int)
    image = request.form.get('cover')
    play_url = request.form.get('play_url', '')
    play_code = request.form.get('play_code', '')
    play_html = request.form.get('play_html', '')
    subtitle = request.form.get('subtitle')
    content = request.form.get('content')

    MatchNewsService.edit(
        news_id, mid, type, source, source_name,
        title, isvr, image, play_url, play_code, play_html, subtitle, content)

    if redirect_url == '':
        return redirect(url_for('admin.list_news'))
    else:
        return redirect(redirect_url)


# 置顶
@bp.route('/matches/news/<int:news_id>/top', methods=['GET'])
def top_match_news(news_id):

    try:
        top = int(request.args.get('value'))
    except:
        raise InvalidArgument()

    value = bool(top)

    MatchNewsService.top(news_id, top=value)

    return jsonify_with_data((200, 'OK'))


@bp.route('/matches/news/<int:news_id>/show')
def show_match_news(news_id):
    MatchNewsService.show(news_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/matches/news/<int:news_id>/hide')
def hide_match_news(news_id):
    MatchNewsService.hide(news_id)
    return jsonify_with_data((200, 'OK'))


# 推荐到焦点回顾 website
@bp.route('/matches/news/<int:news_id>/pin', methods=['GET'])
def pin_match_news(news_id):

    try:
        pin = int(request.args.get('value'))
    except:
        raise InvalidArgument()

    value = bool(pin)

    MatchNewsService.pin(news_id, pin=value)

    return jsonify_with_data((200, 'OK'))


@bp.route('/matches/news/<int:news_id>/delete')
def delete_news(news_id):
    MatchNewsService.delete(news_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/matches/videos', methods=['GET', 'POST'])
def list_videos():
    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)

    match_id = request.args.get('match_id')
    type = request.args.get('type')
    if request.method == 'POST':
        search_id = request.form.get('search')
        videos = MatchVideoService.search_by_id(search_id)
        return redirect(url_for(
            'admin.list_videos', match_id=search_id, type=type))

    videos = MatchVideoService.get_all(match_id, type, (page-1)*count, count)

    # init sort
    if match_id and type == "sort":

        video_list = []
        [ video_list.append(video.id) for video in videos if video.display_order == 0 ]

        if video_list:
            def init_video_sort(num, video):
                video.display_order = num
                return video
            videos = [init_video_sort(num+1, video) for num, video in enumerate(videos)]

    # get match format date
    def add_info_image(video):
        video.image = add_image_domain(video.image)
        return video
    videos = map(add_info_image, videos)

    if match_id and type:
        videos_count = MatchVideoService.count(match_id, type=type)
    elif match_id:
        videos_count = MatchVideoService.count(match_id)
    elif type:
        videos_count = MatchVideoService.count(type=type)
    else:
        videos_count = MatchVideoService.count()

    pagination = Pagination(page, count, videos_count)

    return render_template(
          'admin/match/videos_list.html',
          videos=videos,
          pagination=pagination,
          current_id=match_id,
          current_type=type,
          url=request.url)


@bp.route('/matches/videos/add', methods=['GET', 'POST'])
def add_video():

    redirect_url = request.args.get('redirect', '')
    match_id = request.args.get('match_id', '')
    type = request.args.get('type', '')

    if request.method == 'GET':
        sites = MatchVideoService.get_sites()
        return render_template(
            'admin/match/videos_add.html',
            source=sites,
            match_id=match_id,
            type=type,
            url=redirect_url)

    if match_id == '':
        match_id = int(request.form.get('match_id'))
    else:
        match_id = int(match_id)
    title = request.form.get('title')
    isvr = request.form.get('isvr', 0, int)
    image = request.form.get('cover')
    type = request.form.get('type')
    site = request.form.get('site')
    duration = request.form.get('duration')
    play_url = request.form.get('play_url')
    play_html = request.form.get('play_html')
    play_code = request.form.get('play_code')


    MatchVideoService.add(
        match_id, title, isvr, type, site,
        duration, image, play_url, play_html, play_code)

    if redirect_url == '':
        return redirect(url_for('admin.list_videos'))
    else:
        return redirect(redirect_url)


@bp.route('/matches/videos/<int:video_id>/edit', methods=['GET', 'POST'])
def edit_video(video_id):

    redirect_url = request.args.get('redirect', '')

    video = MatchVideoService.get_one(video_id)
    if request.method == 'GET':
        return render_template(
            'admin/match/videos_edit.html',
            video=video.to_dict(),
            url=redirect_url)

    video_id = request.form.get('video_id')
    title = request.form.get('title')
    isvr = request.form.get('isvr', 0, int)
    image = request.form.get('cover')
    play_url = request.form.get('play_url')
    play_html = request.form.get('play_html')
    play_code = request.form.get('play_code')

    MatchVideoService.edit(video_id, title, isvr, image, play_url, play_html, play_code)

    if redirect_url == '':
        return redirect(url_for('admin.list_videos'))
    else:
        return redirect(redirect_url)


@bp.route('/matches/videos/<int:video_id>/show')
def show_match_video(video_id):
    MatchVideoService.show(video_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/matches/videos/<int:video_id>/hide')
def hide_match_video(video_id):
    MatchVideoService.hide(video_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/matches/videos/<int:video_id>/top', methods=['GET'])
def top_match_video(video_id):

    try:
        top = int(request.args.get('value'))
    except:
        raise InvalidArgument()

    value = bool(top)

    MatchVideoService.top(video_id, top=value)

    return jsonify_with_data((200, 'OK'))


@bp.route('/matches/<int:match_id>/videos/<int:video_id>/pin', methods=['GET'])
def pin_match_video(match_id, video_id):

    try:
        pin = int(request.args.get('value'))
    except:
        raise InvalidArgument()

    value = bool(pin)

    MatchVideoService.pin(match_id, video_id, pin=value)

    return jsonify_with_data((200, 'OK'))


@bp.route('/matches/videos/<int:video_id>/delete')
def delete_video(video_id):
    MatchVideoService.delete(video_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/matches/<int:match_id>/videos/sort', methods=['POST'])
def sort_match_video(match_id):

    try:
        video_id = int(request.form.get('video_id'))
        current = int(request.form.get('current'))
        final = int(request.form.get('final'))
    except:
        raise InvalidArgument()

    try:
        MatchVideoService.sort(match_id, video_id, current, final)
    except:
        raise ServerError()

    return jsonify_with_data(APIError.OK)
