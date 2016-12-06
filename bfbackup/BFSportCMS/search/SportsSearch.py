# -*- coding: utf-8 -*-
#@author
#     __  ____             __  __      _ 
#    /  |/  (_)___  ____ _/ / / /_  __(_)
#   / /|_/ / / __ \/ __ `/ /_/ / / / / / 
#  / /  / / / / / / /_/ / __  / /_/ / /  
# /_/  /_/_/_/ /_/\__, /_/ /_/\__,_/_/   
#                /____/                  
#
# 2016-05-24

import xapian
import json
from SportsData import *
from config import SearchConfig

class SportsIndex(object):

    def __init__(self):
	self.db = xapian.Database(SearchConfig.DB_PATH)
	self.queryparser = xapian.QueryParser()
	self.queryparser.set_stemmer(xapian.Stem("en"))
	self.queryparser.set_stemming_strategy(self.queryparser.STEM_SOME)

    def search(self,keyword,prefix,offset=0,pagesize=10):

	query = self.queryparser.parse_query(keyword,0,prefix)

	enquire = xapian.Enquire(self.db)
	enquire.set_query(query)
        enquire.set_sort_by_value_then_relevance(1, True)

	matches = enquire.get_mset(offset, pagesize)
	return matches

if __name__ == "__main__":
    sportsNews = SportsIndex()
    matches = sportsNews.search('英超','event_news_video_')

print "%i results found." % matches.get_matches_estimated()
print "Results 1-%i:" % matches.size()

for m in matches:
   print "%i: %i%% docid=%i [%s]" % (m.rank + 1, m.percent, m.docid, json.loads(m.document.get_data()).get('title',u''))

