#@author
#     __  ____             __  __      _ 
#    /  |/  (_)___  ____ _/ / / /_  __(_)
#   / /|_/ / / __ \/ __ `/ /_/ / / / / / 
#  / /  / / / / / / /_/ / __  / /_/ / /  
# /_/  /_/_/_/ /_/\__, /_/ /_/\__,_/_/   
#                /____/                  
#
# 2016-05-24
# -*- coding: utf-8 -*-

import xapian
import jieba
import json
from SportsData import *
from config import SearchConfig

class SportsIndex(object):

    def __init__(self):
	self.db = xapian.WritableDatabase(SearchConfig.DB_PATH,xapian.DB_CREATE_OR_OPEN)
	stemmer = xapian.Stem('en')
	self.termgenerator = xapian.TermGenerator()
	self.termgenerator.set_stemmer(xapian.Stem("en"))
        self.sports = SportsData()

    def index(self):
        self.eventTextNewsIndex()
        self.eventVideoNewsIndex()

    #赛事文字新闻索引
    def eventTextNewsIndex(self):

        news = self.sports.getEventNewsOfText()

	for fields in news:
	    doc = xapian.Document()
	    self.termgenerator.set_document(doc)

	    identifier = fields.get('id', u'')
	    title = fields.get('title', u'')
	    site = fields.get('site', u'')
	    publish_tm = fields.get('publish_tm', u'')

            for word in jieba.cut_for_search(title):
                self.termgenerator.index_text(word, 1, 'event_news_text_')
                self.termgenerator.index_text(word)

            self.termgenerator.index_text(site, 1, 'event_news_text_')
            self.termgenerator.index_text(site)

            doc.add_value(1,str(publish_tm))

	    doc.set_data(json.dumps(fields, encoding='utf8'))

	    idterm = u"Q" + identifier
	    doc.add_boolean_term(idterm)
	    self.db.replace_document(idterm, doc)

    #赛事视频新闻索引
    def eventVideoNewsIndex(self):

        videos = self.sports.getEventNewsOfVideo()

	for fields in videos:
	    doc = xapian.Document()
	    self.termgenerator.set_document(doc)

	    identifier = fields.get('id', u'')
	    title = fields.get('title', u'')
	    site = fields.get('site', u'')
	    publish_tm = fields.get('publish_tm', u'')

            for word in jieba.cut_for_search(title):
                self.termgenerator.index_text(word, 1, 'event_news_video_')
                self.termgenerator.index_text(word)

            self.termgenerator.index_text(site, 1, 'event_news_video_')
            self.termgenerator.index_text(site)

            doc.add_value(1,str(publish_tm))

	    doc.set_data(json.dumps(fields, encoding='utf8'))

	    idterm = u"Q" + identifier
	    doc.add_boolean_term(idterm)
	    self.db.replace_document(idterm, doc)

if __name__ == "__main__":
    ind = SportsIndex()
    ind.index()
