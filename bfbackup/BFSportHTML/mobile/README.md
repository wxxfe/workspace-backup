# website Front-End
体育项目前端静态文件

安装
This task requires you to have Ruby and Sass installed. If you're on OS X or Linux you probably already have Ruby installed; test with ruby -v in your terminal. When you've confirmed you have Ruby installed, run gem install sass to install Sass.

sudo gem install sass

npm install

# 测试单个组件
``` bash
node test/app button
```

# 多终端的适配写法
## px转rem规则
``` css
.ba-button {
    width: 150px;                    // 不加注释，px会转化成rem
    height: 64px; /*px*/             // 加这样的注释，会转化成多dpr适配的样式
    font-size: 28px; /*px*/          
    border: 1px solid #ddd; /*no*/   // 加这样的注释，不会做任何处理
}
```
转化之后的样式为
``` css
.ba-button {
  width: 2.34375rem;
  border: 1px solid #ddd;
}

[data-dpr="1"] .ba-button {
  height: 32px;
  font-size: 14px;
}

[data-dpr="2"] .ba-button {
  height: 64px;
  font-size: 28px;
}

[data-dpr="3"] .ba-button {
  height: 96px;
  font-size: 42px;
}
```
＃ 组件列表
- [x] border mixin
- [x] 头像 mixin
- [x] 按钮类 下载 点赞
- [x] 查看更多
- [x] 小标题
- [x] 标题 1
- [x] 单头像
- [x] tab
- [x] 标签
- [x] 分割线
- [x] 下载条   1 
- [x] 文章3
- [x] 相关新闻2
- [x] 相关图集
- [x] 视频列表
- [x] 播放器
- [x] 帖子详情
- [x] 回复列表
- [x] 下载弹层 
- [x] 评论列表
- [x] 竞猜
- [x] 热门话题列表
- [x] 比分条
- [x] 栅格系统
- [x] 聊天消息框
- [x] 人物排行榜
- [x] 数字字体
- [x] 表格


#其他说明

##项目的命令路径，示例：

`./node_modules/.bin/webpack`

`./node_modules/.bin/grunt`


##项目目录说明

src/scripts 目录放所有页面模板的js

src/scss/components 目录放了组件的所有代码和预览图（html，scss，js）

src/scss/pages 目录放了页面的样式scss

##分享的页面必须要有的文件

```
scss
@import "../../components/sharepage/index";

js
require.config({
    baseUrl: 'src/scss'
});

require([
    'components/libs/fastclick',
    'components/libs/zepto.min',
    'components/abase/util',
    'components/abase/index',
    'components/deviceapi/index',
    'components/listitem/index',
    'components/list/index',
    'components/article/index',
    'components/button/index',
    'components/channel/index',
    'components/sharepage/index'
], function (FastClick) {
    window.pageType = Utils.pageType;

    pageType !== 'app' && SharePageFactory();

    FastClick.attach(document.body);

    ChannelShareFactory();

    var buttons = document.querySelectorAll('.button');
    for (var i = 0, len = buttons.length; i < len; i++) {
        ButtonFactory({element: buttons[i]})
    }

    var list = document.querySelector('.list');
    list && ListFactory({element: list});
});
```

##如果是app内的页面，php输出的最终页面还有需要有

`<input id="page-type" type="hidden" value="<?=$type?>" />`

##如果有相关资讯类的a元素，其所有a的父元素必须加上class="news-action"