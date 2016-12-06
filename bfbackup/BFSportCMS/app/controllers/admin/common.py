# -*- coding: utf-8 -*-

from flask import redirect
from flask import url_for

from app.service import MatchNewsService
from app.service import OGScheduleService
from app.utils.serialization import jsonify_with_data

from . import bp


@bp.route('/news/sites')
def get_news_sites():
    sites = MatchNewsService.get_sites()
    sites_dict = {}

    def add_info_site(site):
        sites_dict[site.site] = site.live_title
        return True
    map(add_info_site, sites)
    return jsonify_with_data((200, 'OK'), sites=sites_dict)


@bp.route('/olympics/schedules/import')
def import_olympics_schedules():
    import xlrd
    data = xlrd.open_workbook('olympic.xlsx')
    table = data.sheets()[2]
    nrows = table.nrows
    for row in range(nrows):
        if row > 0:
            info = table.row_values(row)
            if info:
                OGScheduleService.add(info[0], info[1], info[2], info[3])
    return redirect(url_for('admin.list_olympics_schedules'))
