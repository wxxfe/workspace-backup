/**
 * @des: 分页
 * @example: new pages(10, 1, 10, "url",function);
 */

define(function () {
    /**
     * @param totalPages 总页数
     * @param currentPage 当前页
     * @param pageSize 每页条数
     * @param apiUrl 接口url
     * @param callback 回调函数，ajax请求成功后返回数据给回调函数处理
     * @constructor
     */
    function Pages(totalPages, currentPage, pageSize, apiUrl, callback) {
        if (totalPages < 1 || currentPage < 1 || pageSize < 1 || Object.prototype.toString.call(apiUrl) !== '[object String]' || Object.prototype.toString.call(callback) !== '[object Function]') {
            throw "参数错误";
        }
        this.totalPages = totalPages;
        this.currentPage = currentPage;
        this.pageSize = pageSize;
        this.apiUrl = apiUrl;
        this.callback = callback;
        //每页的缓存数据
        this.pagesData = [];
    }

    /**
     * 请求某页的数据
     * @param pageNum 页码
     */
    Pages.prototype.getPageData = function (pageNum) {
        $.ajax({
            type: "GET",
            timeout: 500,
            url: this.apiUrl + pageNum + '/' + this.pageSize,
            success: function (msg) {

                var data = $.parseJSON(msg);

                // {
                //     "status": 1, 值：0 - 失败，1 - 成功
                //     "posts": [
                //     {
                //     },
                //     ...]
                // }

                if (data.status == 1 && $.isArray(data.posts) && data.posts.length) {
                    //如果请求成功并且数据有效,则去除错误处理计时器
                    if (this.errorTimeoutID) {
                        clearTimeout(this.errorTimeoutID);
                        this.errorTimeoutID = undefined;
                    }

                    //存入缓存数组
                    this.pagesData[this.currentPage - 1] = data.posts;

                    //执行回调
                    this.callback(data.posts);
                }


            }.bind(this)
        });

        //异常处理,保证ajax的success没有执行的情况下也执行回调函数,也就是错误情况下的回调函数。没用ajax的error回调是因为它不能包括所有错误情况
        this.errorTimeoutID = setTimeout(function () {
            if (this && this.errorTimeoutID) this.callback(null);
        }.bind(this), 600);
    };

    /**
     * 下一页，如果超过总页数则调回第一页，只是设置页面，没有请求数据
     * @return 返回下一页缓存数据，没有则是null;
     */
    Pages.prototype.nextPageRepeat = function () {
        this.currentPage++;
        if (this.currentPage > this.totalPages || this.currentPage < 1) this.currentPage = 1;
        return this.pagesData[this.currentPage - 1];
    };

    return Pages;

});
