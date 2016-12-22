# -*- coding: utf-8 -*-

import os


class DefaultConfig(object):

    DEBUG = False
    TESTING = False

    # image url
    IMAGE_URL = 'http://image.sports.baofeng.com/'

    # session
    SECRET_KEY = os.environ.get('SECRET_KEY') or 'NGUzMjc0MjBiYzQxMjkyZTgyZTk5ZTA2MDg2MDU1NDsd'
    # SESSION_COOKIE_DOMAIN = ''
    # SESSION_COOKIE_NAME = ''

    # database
    SQLALCHEMY_DATABASE_URI = 'mysql+pymysql://sports:sp0rts@192.168.200.192:3306/sports'
    SQLALCHEMY_BINDS = {
        'board': 'mysql+pymysql://board:b0ard@192.168.200.192:3306/board',
        'olympics': 'mysql+pymysql://olympics_rw:tg41nvBg^Wafx@192.168.200.192:3306/olympics'
    }
    SQLALCHEMY_TRACK_MODIFICATIONS = True
    SQLALCHEMY_COMMIT_ON_TEARDOWN = True
    SQLALCHEMY_ECHO = False


class DevelopmentConfig(DefaultConfig):

    ENV = 'development'
    DEBUG = True

    SESSION_COOKIE_DOMAIN = None


class StagingConfig(DefaultConfig):

    ENV = 'staging'


class ProductionConfig(DefaultConfig):

    LOG_FILE = "/opt/sports/cms/logs/cms.log"
    LOG_LEVEL = "DEBUG"
    ENV = 'product'

    # database
    SQLALCHEMY_DATABASE_URI = os.environ.get('DATABASE_URL') or \
        'mysql+pymysql://sports_rw:kZoqdohhSwo92uP@103.26.158.17:3306/sports'

    SQLALCHEMY_BINDS = {
        'olympics': 'mysql+pymysql://olympics_rw:aVoz3peoW2)tomg@110.172.215.17:3306/olympics'
    }

    SQLALCHEMY_POOL_SIZE = 512
    SQLALCHEMY_POOL_TIMEOUT = 3
    SQLALCHEMY_POOL_RECYCLE = 1800
    SQLALCHEMY_MAX_OVERFLOW = 128

