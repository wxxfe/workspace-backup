# -*- coding: utf-8 -*-

class MysqlConfig(object):

    HOST = '192.168.200.192'
    DB = 'sports'
    USER = 'sports'
    PASSWORD = 'sp0rts'
    PORT = 3306


class SearchConfig(object):

    DB_PATH = '../sports_index/'

    INDEX_TABLE = ['event_news','match_news','match_video','match_live','player','team','match']

    PREFIX = {
        'event_news' : 'EN',
        'match_news' : 'MN',
        'match_video' : 'MV',
        'match_live' : 'ML',
        'player' : 'P',
        'team' : 'T',
        'match' : 'M'
    }
