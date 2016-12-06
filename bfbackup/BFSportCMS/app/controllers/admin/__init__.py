# -*- coding: utf-8 -*-

from flask import Blueprint

bp = Blueprint('admin', __name__)


class APIError:

    BAD_FORMAT = (10001, u"时间格式错误")
    BAD_REQUEST = (10001, u"请求参数错误")
    NOT_FOUND = (10001, u"没有找到")
    SERVER_ERROR = (10001, u"服务器异常")

    OK = (10000, 'OK')


from . import base
from . import activity
from . import channel
from . import event
from . import match
from . import player
from . import program
from . import scoreboard
from . import section
from . import slide
from . import media_video
from . import mainboard
from . import olympics
from . import team
from . import upload
from . import common

# board demo
from . import board

# olympics
from . import olympics
from . import olympics_style
