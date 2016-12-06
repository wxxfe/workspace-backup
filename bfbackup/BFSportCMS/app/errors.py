# -*- coding: utf-8 -*-


class BaseException(Exception):
    pass


class InvalidArgument(BaseException):
    pass


class NotFound(BaseException):
    pass


class ServerError(BaseException):
    pass
