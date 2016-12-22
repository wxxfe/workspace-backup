# -*- coding: utf-8 -*-

from flask import jsonify
from flask import current_app


# API response
def jsonify_with_data(err, **kwargs):
    resp = {
        'data': kwargs,
        'message': err[1],
        'errno': err[0]
        }
    return jsonify(resp), 200


def jsonify_with_error(err, errors=None):
    resp = {
        'message': err[1],
        'errno': err[0]
        }
    return jsonify(resp), 200
