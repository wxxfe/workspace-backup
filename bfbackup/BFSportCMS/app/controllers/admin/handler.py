# -*- coding: utf-8 -*-

from flask import request
from flask import current_app

from app.errors import InvalidArgument, NotFound, ServerError
from app.utils.serialization import jsonify_with_error


from . import bp
from . import APIError


@bp.errorhandler(InvalidArgument)
def invalid_argument(e):
    return jsonify_with_error(APIError.BAD_REQUEST)


@bp.errorhandler(NotFound)
def not_found(e):
    return jsonify_with_error(APIError.NOT_FOUND)


@bp.errorhandler(ServerError)
def server_error(e):
    current_app.logger.error("Error on %s", request.path, exc_info=True)
    return jsonify_with_error(APIError.SERVER_ERROR)


@bp.errorhandler(Exception)
def all_error(e):
    current_app.logger.error("Error on %s", request.path, exc_info=True)
    return jsonify_with_error(APIError.SERVER_ERROR)
