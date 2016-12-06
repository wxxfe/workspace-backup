# -*- coding: utf-8 -*-

import os

from flask_script import Server
from flask_script import Manager
from flask_script import prompt_bool

from app.settings import DevelopmentConfig
from app.settings import StagingConfig
from app.settings import ProductionConfig
from app.extensions import db
from app import create_app
from app import models

# create app with env
config_map = {
    'dev': DevelopmentConfig,
    'stag': StagingConfig,
    'product': ProductionConfig
}

# set env via environment variable

env = 'dev'
env = os.environ.get('ENV', env)

app = create_app(config_map[env])
manager = Manager(app)


# database
@manager.command
def initdb():
    if prompt_bool("Are you sure? You will init your database"):
        db.create_all()


@manager.command
def dropdb():
    if prompt_bool("Are you sure? You will lose all your data!"):
        db.drop_all()


@manager.shell
def make_shell_context():
    return dict(app=manager.app, db=db, models=models)


manager.add_command("runserver", Server(host='0.0.0.0', port=8013))

if __name__ == '__main__':
    manager.run()
