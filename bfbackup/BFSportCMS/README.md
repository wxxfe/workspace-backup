cms
=========


Code Style
-----------------

When use coding for cms, make sure you read code style.

See More: http://www.pocoo.org/internal/styleguide/


Commit message Style
---------------------------------

规范：

     每次提交，Commit message 都包括2个部分：Header，Body.
     其中，Header 是必需的，Body 可以省略。

格式：

     <type>: <subject>      # 50-character subject line

     <body>          # 72-character wrapped longer description.


    Header部分只有一行，包括2个字段：type（必需）和subject（必需）。

     type： 用于说明 commit 的类别，只允许使用下面7个标识。

               feat：新功能（feature）
               fix：修补bug
               docs：文档（documentation）
               style： 格式（不影响代码运行的变动）
               refactor：重构（即不是新增功能，也不是修改bug的代码变动）
               test：增加测试
               chore：构建过程或辅助工具的变动

     subject：是 commit 目的的简短描述，不超过50个字符。
               - 以动词开头，使用第一人称现在时
               - 结尾不加句号（.）
               - 首字母不要大写


    Body 部分
      Body 部分是对本次 commit 的详细描述，可以分成多行

     - 为什么这次修改是必要的？
    - 如何解决的问题？
     - 这些变化可能影响哪些地方？



Installation
-------------

Make sure you have python2.7, pip and virtualenv installed.

      $ virtualenv venv
      $ source venv/bin/activate
      (venv) $ pip install -r requirements.txt


Development
-----------

Start a development server::

    # git clone cms
    $ cd cms
    $ virtualenv --distribute venv
    $ source venv/bin/activate
    (venv)$ pip install -r requirements.txt
    (venv)$ python manager.py runserver

It should be running at localhost:port.



other:

git clone http://user:pwd@git.dev.bf.com/sports/cms.git

cd cms

sudo pip install virtualenv (可选,如果已经安装了virtualenv)

virtualenv venv

. venv/bin/activate

pip install -r requirements.txt

python manage.py initdb (可选,如果不需要初始化数据库可以不执行)

python manage.py runserver

And if you want to go back to the real world, use the following command:

deactivate


