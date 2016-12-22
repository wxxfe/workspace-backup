# -*- coding: utf-8 -*-

import requests
import calendar
import time
from datetime import datetime
from datetime import timedelta
import base64
from werkzeug import secure_filename

from app.settings import DefaultConfig


DATETIME_FORMAT = "%Y-%m-%d %H:%M:%S"

weekday_map = {
    1: u'一',
    2: u'二',
    3: u'三',
    4: u'四',
    5: u'五',
    6: u'六',
    7: u'日',
}


def to_cn_date(dt):
    # dt = datetime.utcfromtimestamp(dt)
    month = dt.month
    day = dt.day
    weekday = weekday_map[dt.isoweekday()]

    week = u'星期%s' % weekday
    month_day = '%s-%s' % (month, day)

    return (week, month_day)


def to_date(ts):
    dt = datetime.fromtimestamp(ts)
    cn_dt = dt + timedelta(hours=0)
    return cn_dt.strftime("%Y-%m-%d %H:%M:%S")


# convert datetime type
def to_timestamp(dt):
    if not dt:
        return dt
    ts = time.mktime(dt.timetuple())
    return int(ts)


# convert day to timestamp
def date_to_timestamp(dt):
    if not dt:
        return dt
    from datetime import time as tmp_time
    dtime = tmp_time(0, 0, 0)
    dt = datetime.combine(dt, dtime)
    ts = time.mktime(dt.timetuple())
    return int(ts)


# convert  timestamp to datetime
def timestamp_to_datetime(dt):
    return datetime.fromtimestamp(dt)


def string_to_datetime(dt, dt_fmt=DATETIME_FORMAT):
    return datetime.strptime(dt, dt_fmt)


# base64 encode
def base64_url_encode(key):
    return base64.urlsafe_b64encode(key)


# base64 decode
def base64_url_decode(key):
    return base64.urlsafe_b64decode(str(key))


# ================== calculate datetime =====================

def calculate_datetime(dt, **kargs):
    return dt + timedelta(**kargs)


# deal with third part image address
def add_image_domain(image):
    prefix = "http"
    if not image:
        return image

    if not image.startswith(prefix):
        image = DefaultConfig.IMAGE_URL + image
    return image


def upload_image(file):
    url = "http://w.image.sports.baofeng.com/save"
    filename = secure_filename(file.filename)

    files = [
        ("image", (filename, file.read(), file.mimetype))
    ]

    headers = {
        "Sports-Token": "xVFpX0RU"
    }

    r = requests.post(url, headers=headers, files=files)

    res_json = r.json()

    if res_json['errno'] == 10000:
        url = 'http://w.image.sports.baofeng.com/' + res_json['data']['pid']
    else:
        url = '#'

    return url

