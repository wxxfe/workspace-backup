# -*- coding: utf-8 -*-

import sys
import os
import logging
from logging.handlers import RotatingFileHandler

from flask import Flask

from app.settings import DevelopmentConfig
from app.controllers.admin import bp as admin_bp
from app.extensions import db
from app.utils import text

__all__ = ['create_app']


DEFAULT_APP_NAME = 'app'


def create_app(config=None):
    app = Flask(DEFAULT_APP_NAME)

    configure_app(app, config)
    configure_extensions(app)
    configure_blueprints(app)
    configure_filters(app)
    configure_logging(app)

    app.logger.debug(' * Runing in %(ENV)s environment' % app.config)

    return app


def configure_app(app, config):

    # development config is default
    if not config:
        config = DevelopmentConfig
    app.config.from_object(config)


def configure_extensions(app):
    db.app = app
    db.init_app(app)


def configure_blueprints(app):
    app.register_blueprint(admin_bp, url_prefix='')


def configure_logging(app):

    logfile = app.config.get("LOG_FILE", "stdout").lower()

    if logfile == "stdout":
        handler = logging.StreamHandler(sys.stdout)
    else:
        dirname = os.path.dirname(logfile)
        if not os.path.exists(dirname):
            os.makedirs(dirname, 0755)

        handler = RotatingFileHandler(logfile, maxBytes=20 * 1024 * 1024,
                                      backupCount=40)

    handler.setFormatter(logging.Formatter("%(asctime)-12s *%(process)d* "
                                           "%(levelname)-6s %(message)s"))
    handler.setLevel(app.config.get("LOG_LEVEL", "INFO").upper())
    app.logger.addHandler(handler)


def configure_filters(app):
    app.jinja_env.filters['to_cn_date'] = text.to_cn_date
    app.jinja_env.filters['to_date'] = text.to_date
