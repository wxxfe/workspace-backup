# -*- coding: utf-8 -*-

from flask import render_template

from app.service import ChannelService

from . import bp


@bp.route('/layout')
def layout_base():
    return render_template('admin/layout.html')


@bp.route('/layout/header')
def layout_header():
    return render_template('admin/header.html')


@bp.route('/layout/side')
def layout_side():
    channels = ChannelService.get_all()
    return render_template('admin/side.html', channels=channels)


@bp.route('/layout/dashboard')
def layout_dashboard():
    return render_template('admin/dashboard.html')
