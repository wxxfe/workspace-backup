# -*- coding: utf-8 -*-

import math
import urllib

from flask import request


class Pagination(object):

    def __init__(self, page, count, total, window=3):
        total_page = int(math.ceil(float(total)/count))
        self.page = page
        self.total_page = total_page
        self.url = request.base_url
        self.window = window

        # get start/end pages
        start_page = 1
        end_page = total_page
        if page > window:
            start_page = page - window

        if page < total_page - window:
            end_page = page + window

        if page > total_page:
            pages = []
        else:
            pages = range(start_page, end_page+1)
        self.pages = pages

    def url_for(self, page):
        args = {}
        for k in request.args:
            args[k] = request.args[k]

        args['page'] = page

        # need optimize，cms online
        import re
        cms_str = request.base_url
        strinfo = re.compile('127.0.0.1:8012')
        cms_url = strinfo.sub('cms.sports.bf.com/', cms_str)
        return cms_url + '?' + urllib.urlencode(args)

        # return request.base_url + '?' + urllib.urlencode(args)

    def has_previous(self):
        return self.page > 1

    def has_next(self):
        return self.page < self.total_page

    def reach_start(self):
        return self.page > self.window

    def reach_end(self):
        return self.page < self.total_page - self.window
