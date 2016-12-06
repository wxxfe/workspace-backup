# -*- coding: utf-8 -*-


from flask import request
from flask import make_response

from app.utils.text import upload_image

from . import bp


ALLOWED_EXTENSIONS = set(['png', 'jpg', 'jpeg', 'gif', 'PNG', 'JPG', 'JPEG', 'GIF'])


def allowed_file(filename):
    return '.' in filename and \
           filename.rsplit('.', 1)[1] in ALLOWED_EXTENSIONS


@bp.route('/upload/', methods=['POST', 'OPTIONS'])
def cdeditor_upload():
    """CKEditor file upload"""
    error = ''
    url = ''
    callback = request.args.get("CKEditorFuncNum")
    if request.method == 'POST' and 'upload' in request.files:
        fileobj = request.files['upload']
        if fileobj and allowed_file(fileobj.filename):
            url = upload_image(fileobj)
        else:
            error = 'wrong file name'
    else:
        error = 'post error'

    res = """<script type="text/javascript">
                    window.parent.CKEDITOR.tools.callFunction(%s, '%s', '%s');
                    </script>""" % (callback, url, error)
    response = make_response(res)
    response.headers["Content-Type"] = "text/html"
    return response
