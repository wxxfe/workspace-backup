
# -*- coding: utf-8 -*-
"""
    controllers.admin
    ~~~~~~~~~~~~~~

    olympics controllers

    :author:yangxiaolei
    :version:1.0
    :date:2016-07-30
"""
import types
from flask import request
from flask import render_template
from flask import redirect
from flask import url_for

from app.service import OlympicsSlideService

from app.service import OGMedalService
from app.service import OGHtmlService
from app.service import OGNewsService
from app.service import OlympicsChampionService
from app.service import OlympicsGalleryService
from app.service import OlympicsGalleryImageService
from app.service import OlympicsChampionNewsService
from app.service import OGLiveService
from app.utils.serialization import jsonify_with_data
from app.utils.filters import Pagination

from . import bp


#轮播图
@bp.route('/olympics/slides')
def olympics_list_slides():

    platform = request.args.get('platform', 'app')
    slides = OlympicsSlideService.get_all(platform=platform)
    return render_template('admin/olympics/slide/list.html',slides=slides,platform=platform)


@bp.route('/olympics/slides/add', methods=['GET', 'POST'])
def olympics_add_slide():
    if request.method == 'GET':
        if request.args.get('platform','app') == 'app' :
            return render_template('admin/olympics/slide/add.html')
        else:
            return render_template('admin/olympics/slide/add_web.html')
    obj_dict = request.form.to_dict()
    if any(obj_dict['display_order']) == False:
        obj_dict['display_order'] = 0
    obj_dict['image'] = obj_dict['cover']
    del (obj_dict['cover'])
    plt = request.args.get('platform', 'app')
    if plt == 'app' and obj_dict['type'] != 'html':
        type = obj_dict['type']
        ref_id = obj_dict['ref_id']
        if not OlympicsSlideService.checkIDValid(type, ref_id):
            return redirect(request.referrer)

    if obj_dict['type'] == 'html':
        obj_dict['ref_id'] = 0

    if plt == 'web' :
        obj_dict['ref_id'] = 0

    OlympicsSlideService.add(obj_dict)
    return redirect(url_for('admin.olympics_list_slides',platform=plt))


@bp.route('/olympics/slides/<int:slide_id>/show')
def show_olympics_slide(slide_id):
    OlympicsSlideService.show(slide_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/slides/<int:slide_id>/hide')
def hide_olympics_slide(slide_id):
    OlympicsSlideService.hide(slide_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/slides/<int:slide_id>/delete')
def olympics_delete_slide(slide_id):
    OlympicsSlideService.delete(slide_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/slides/<int:slide_id>/edit', methods=['GET', 'POST'])
def olympics_edit_slide(slide_id):
    slide = OlympicsSlideService.get_one(slide_id)
    platform = request.args.get('platform', 'app')
    if request.method == 'GET':
        return render_template('admin/olympics/slide/edit.html',slide=slide,platform=platform)
    obj_dict = request.form.to_dict()
    obj_dict['image'] = obj_dict['cover']
    del (obj_dict['cover'])
    if obj_dict['type'] == "1" or obj_dict['type'] == "2" :
        obj_dict['data'] == ""
    if obj_dict['type'] == "3" :
        obj_dict['ref_id'] == 0
    OlympicsSlideService.edit(slide_id, obj_dict)
    return redirect(url_for('admin.olympics_list_slides',platform=platform))


#奖牌榜
@bp.route('/olympics/medal/list',methods=['GET'])
def olympics_medal_list():
    res = OGMedalService.get_all()
    return render_template('admin/olympics/medal/list.html',res=res,)


@bp.route('/olympics/medal/add', methods=['GET', 'POST'])
def olympics_medal_add():
    if request.method == 'GET':
        return render_template(
            'admin/olympics/medal/add.html')
    column_dict = request.form.to_dict()
    OGMedalService.add(column_dict)
    if any(column_dict['gold_medals']) == False:
        column_dict['gold_medals'] = 0
    if any(column_dict['silver_medals']) == False:
        column_dict['silver_medals'] = 0
    if any(column_dict['bronze_medals']) == False:
        column_dict['bronze_medals'] = 0
    return redirect(url_for('admin.olympics_medal_list'))


@bp.route('/olympics/medal/<int:medal_id>/edit', methods=['GET', 'POST'])
def olympics_medal_edit(medal_id):
    medal = OGMedalService.get_one(medal_id)
    if request.method == 'GET':
        return render_template('admin/olympics/medal/edit.html',medal=medal)
    column_dict = request.form.to_dict()
    if any(column_dict['gold_medals']) == False:
        column_dict['gold_medals'] = 0
    if any(column_dict['silver_medals']) == False:
        column_dict['silver_medals'] = 0
    if any(column_dict['bronze_medals']) == False:
        column_dict['bronze_medals'] = 0
    OGMedalService.edit(medal_id, column_dict)
    return redirect(url_for('admin.olympics_medal_list'))


@bp.route('/olympics/medal/<int:medal_id>/delete')
def olympics_medal_delete(medal_id):
    OGMedalService.delete(medal_id)
    return jsonify_with_data((200, 'OK'))


#手工碎片管理
@bp.route('/olympics/html/list',methods=['GET'])
def olympics_html_list():
    res = OGHtmlService.get_all()
    return render_template('admin/olympics/html/list.html',res=res,)


@bp.route('/olympics/html/add', methods=['GET', 'POST'])
def olympics_html_add():
    if request.method == 'GET':
        return render_template(
            'admin/olympics/html/add.html')
    column_dict = request.form.to_dict()
    OGHtmlService.add(column_dict)
    return redirect(url_for('admin.olympics_html_list'))


@bp.route('/olympics/html/<int:html_id>/edit', methods=['GET', 'POST'])
def olympics_html_edit(html_id):
    html = OGHtmlService.get_one(html_id)
    if request.method == 'GET':
        return render_template('admin/olympics/html/edit.html',html=html)
    column_dict = request.form.to_dict()
    OGHtmlService.edit(html_id, column_dict)
    return redirect(request.referrer)


@bp.route('/olympics/html/<int:html_id>/delete')
def olympics_html_delete(html_id):
    OGHtmlService.delete(html_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/champion/list', methods=['GET'])
def olympics_champion_list():
    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)
    champions = OlympicsChampionService.get_all((page - 1) * count, count)
    videos_count = OlympicsChampionService.count()
    pagination = Pagination(page, count, videos_count)

    return render_template(
        'admin/olympics/champion/list.html',
        pagination=pagination,
        champions=champions,)


@bp.route('/olympics/champion/add', methods=['GET', 'POST'])
def olympics_champion_add():
    if request.method == 'GET':
        return render_template(
            'admin/olympics/champion/add.html')
    obj_dict = request.form.to_dict()
    obj_dict['image'] = obj_dict['cover']
    del (obj_dict['cover'])
    if obj_dict.has_key('num') and obj_dict['num'] == '':
        obj_dict['num'] = 0
    OlympicsChampionService.add(obj_dict)
    return redirect(url_for('admin.olympics_champion_list'))

@bp.route('/olympics/champion/<int:champion_id>/delete')
def olympics_champion_delete(champion_id):
    OlympicsChampionNewsService.deleteChampionNews(champion_id)
    OlympicsChampionService.delete(champion_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/champion/<int:champion_id>/edit', methods=['GET', 'POST'])
def olympics_champion_edit(champion_id):
    champion = OlympicsChampionService.get_one(champion_id)
    if request.method == 'GET':
        return render_template('admin/olympics/champion/edit.html',champion=champion)
    champion_dict = request.form.to_dict()
    champion_dict['image'] = champion_dict['cover']
    del (champion_dict['cover'])
    OlympicsChampionService.edit(champion_id, champion_dict)
    return redirect(url_for('admin.olympics_champion_list'))


@bp.route('/olympics/champion/<int:champion_id>/newslist', methods=['GET', 'POST'])
def olympics_champion_newslist(champion_id):
    if request.method == 'GET':
        championNews = OlympicsChampionService.getChampionNews(champion_id)
        return render_template('admin/olympics/champion/newslist.html',championNews=championNews,champion_id = champion_id)
    return redirect(request.referrer)


@bp.route('/olympics/champion/<int:champion_news_id>/editnews', methods=['GET', 'POST'])
def olympics_champion_editnews(champion_news_id):
    championNews = OlympicsChampionNewsService.get_one(champion_news_id)
    if request.method == 'GET':
        return render_template('admin/olympics/champion/editnews.html',championNews=championNews)
    champion_dict = request.form.to_dict()
    OlympicsChampionNewsService.edit(champion_news_id, champion_dict)
    return redirect(url_for('admin.olympics_champion_newslist',champion_id=championNews.champion_id))

@bp.route('/olympics/champion/<int:champion_news_id>/delnews', methods=['GET', 'POST'])
def olympics_champion_delnews(champion_news_id):
    OlympicsChampionService.delNews(champion_news_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/champion/addnews', methods=['GET', 'POST'])
def olympics_champion_addnews():
    championNewsDict = request.form.to_dict()
    champion_id=championNewsDict['champion_id']
    del(championNewsDict['act'])
    type = championNewsDict['type']
    ref_id = championNewsDict['news_id']
    if not OlympicsSlideService.checkIDValid(type, ref_id):
        return redirect(request.referrer)

    news = OlympicsChampionNewsService.get_filter_one(champion_id, ref_id)
    if not news:
        OlympicsChampionService.addNews(championNewsDict)

    return redirect(url_for('admin.olympics_champion_newslist',champion_id=champion_id))


@bp.route('/olympics/gallery/list', methods=['GET'])
def olympics_gallery_list():
    """图集管理列表"""

    page = request.args.get('page', 1, type=int)
    count = request.args.get('count', 20, type=int)
    gallerys = OlympicsGalleryService.get_all((page - 1) * count, count)
    videos_count = OlympicsGalleryService.count()
    pagination = Pagination(page, count, videos_count)

    return render_template(
        'admin/olympics/gallery/list.html',
        pagination=pagination,
        gallerys=gallerys)


@bp.route('/olympics/gallery/<int:gallery_id>/show')
def show_olympics_gallery(gallery_id):
    OlympicsGalleryService.show(gallery_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/gallery/<int:gallery_id>/hide')
def hide_olympics_gallery(gallery_id):
    OlympicsGalleryService.hide(gallery_id)
    return jsonify_with_data((200, 'OK'))


@bp.route('/olympics/gallery/add',methods=['GET','POST'])
def olympics_gallery_add():
    """添加图集"""
    if request.method == 'GET':
        return render_template(
            'admin/olympics/gallery/add.html')
    obj_dict = request.form.to_dict()
    obj_dict['image'] = obj_dict['cover']
    del (obj_dict['cover'])
    OlympicsGalleryService.add(obj_dict)
    return redirect(url_for('admin.olympics_gallery_list'))

@bp.route('/olympics/gallery/<int:gallery_id>/delete')
def olympics_gallery_delete(gallery_id):
    OlympicsGalleryImageService.deleteByGalleryID(gallery_id)
    OlympicsGalleryService.delete(gallery_id)
    return jsonify_with_data((200, 'OK'))

@bp.route('/olympics/gallery/<int:gallery_id>/edit',methods=['GET','POST'])
def olympics_gallery_edit(gallery_id):
    gallery = OlympicsGalleryService.get_one(gallery_id)
    if request.method == 'GET':
        return render_template('admin/olympics/gallery/edit.html', gallery=gallery)
    gallery_dict = request.form.to_dict()
    gallery_dict['image'] = gallery_dict['cover']
    if not gallery_dict.has_key('top'):
        gallery_dict['top'] = 0
    if not gallery_dict.has_key('visible'):
        gallery_dict['visible'] = 0
    del (gallery_dict['cover'])
    OlympicsGalleryService.edit(gallery_id, gallery_dict)
    return redirect(request.referrer)


@bp.route('/olympics/galleryImg/<int:gallery_id>/list',methods=['GET'])
def olympics_gallery_image_list(gallery_id):
    image = OlympicsGalleryImageService.get_all_gallery_image(gallery_id)
    return render_template('admin/olympics/gallery/list_image.html', image=image,gallery_id=gallery_id)


@bp.route('/olympics/galleryImg/<int:gallery_id>/add',methods=['GET','POST'])
def olympics_gallery_image_add(gallery_id):
    if request.method == 'GET':
        return render_template(
            'admin/olympics/gallery/add_image.html',gallery_id=gallery_id)
    obj_dict = request.form.to_dict()
    obj_dict['image'] = obj_dict['cover']
    del (obj_dict['cover'])
    OlympicsGalleryImageService.add(obj_dict)
    return redirect(url_for('admin.olympics_gallery_image_list',gallery_id=gallery_id))

@bp.route('/olympics/galleryImg/<int:gallery_id>/multiadd',methods=['GET','POST'])
def olympics_gallery_image_multiadd(gallery_id):
    if request.method == 'GET':
        return render_template(
            'admin/olympics/gallery/multiadd_image.html',gallery_id=gallery_id)
    total=request.form.get('total')
    gallery_id = request.form.get('gallery_id')
    gallery_id = str(gallery_id)
    total = int(total)
    for i in range(1,total+1):
        title = request.form.get('title'+str(i))
        desc = request.form.get('brief' + str(i))
        image = request.form.get('cover' + str(i))
        obj_dict = {'gallery_id':gallery_id,'title':title,'desc':desc,'image':image}
        OlympicsGalleryImageService.add(obj_dict)
    return redirect(url_for('admin.olympics_gallery_image_list',gallery_id=gallery_id))


@bp.route('/olympics/galleryImg/<int:gallery_image_id>/edit',methods=['GET','POST'])
def olympics_gallery_image_edit(gallery_image_id):
    if request.method == 'GET':
        gallery_image = OlympicsGalleryImageService.get_one(gallery_image_id).to_dict()
        return render_template(
            'admin/olympics/gallery/edit_image.html',gallery_image=gallery_image)
    obj_dict = request.form.to_dict()
    obj_dict['image'] = obj_dict['cover']
    del (obj_dict['cover'])
    OlympicsGalleryImageService.edit(gallery_image_id,obj_dict)
    return redirect(request.referrer)


#直播
@bp.route('/olympics/live/list',methods=['GET'])
def olympics_live_list():
    res = OGLiveService.get_all()
    return render_template('admin/olympics/live/list.html',res=res,)


@bp.route('/olympics/live/add', methods=['GET', 'POST'])
def olympics_live_add():
    if request.method == 'GET':
        return render_template(
            'admin/olympics/live/add.html')
    dict = request.form.to_dict()
    OGLiveService.add(dict)
    return redirect(url_for('admin.olympics_live_list'))


@bp.route('/olympics/live/<int:live_id>/edit', methods=['GET', 'POST'])
def olympics_live_edit(live_id):
    live = OGLiveService.get_one(live_id)
    if request.method == 'GET':
        return render_template('admin/olympics/live/edit.html',live=live)
    column_dict = request.form.to_dict()
    OGLiveService.edit(live_id, column_dict)
    return redirect(url_for('admin.olympics_live_list'))


@bp.route('/olympics/live/<int:live_id>/delete')
def olympics_live_delete(live_id):
    OGLiveService.delete(live_id)
    return jsonify_with_data((200, 'OK'))

