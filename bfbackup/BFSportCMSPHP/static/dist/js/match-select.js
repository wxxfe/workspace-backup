/**
 * 关联比赛组件
 * ------------------
 */
(function () {
    "use strict";

    var _port = window.location.port ? ':' + window.location.port : '';

    //比赛ID输入框
    var $matchId = $('#match-id-link-match');

    //比赛信息
    var $matchInfo = $('#match-select-info');

    //比赛ID值变化
    $matchId.change(function (e) {
        matchInfo($matchId.val());
    });

    function matchInfo(id) {
        if (id) {
            $matchId.val(id);
            $.ajax({
                method: "GET",
                url: 'http://' + document.domain + _port + '/match/getMatchInfo/' + $matchId.val()
            }).done(function (data) {
                if (data) {
                    $matchInfo.text(data);
                } else {
                    $matchId.val('');
                    $matchInfo.text('比赛不存在！');
                }
            });
        }
    }

    matchInfo($matchId.val());

    window.addEventListener('message', function (e) {
        matchInfo(e.data);
        $('.match-select-modal').modal('hide');
    }, false);

    //----------------------------
    //搜索

    $('#match-search').select2({
        placeholder: '搜索比赛',
        tag: false,
        ajax: {
            url: 'http://' + document.domain + _port + '/search/query/items/match',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term,
                    offset: params.page || 0,
                    limit: 10
                }
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: data.result,
                    pagination: {
                        more: (params.page * 30) < data.total
                    }
                };
            },

            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        templateResult: function (data) {
            var item = data;
            return !item.loading ? item.teams[0].name + ' VS ' + item.teams[1].name : '';
        },
        templateSelection: function (data) {
            var item = data;
            if (item.teams) {
                matchInfo(item.id);
                return item.teams[0].name + ' VS ' + item.teams[1].name;
            } else {
                return window.currentMatch ? currentMatch : '搜索比赛';
            }
        }
    });

})();


