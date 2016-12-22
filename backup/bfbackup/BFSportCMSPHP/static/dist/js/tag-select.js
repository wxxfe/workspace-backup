/**
 * 标签选择组件
 * ------------------
 */
(function () {
    "use strict";

    var tagsHidden = $('#tags');
    var _port = window.location.port ? ':' + window.location.port : '';

    var $tabContent = $('.tab-content');

    //-----------------
    //已选tag处理

    //已选tag id
    var resetTags = function () {
        var tags = $('.label-tag'), _t = [];
        $.each(tags, function (index) {
            _t.push($(this).data('tid'));
        });
        tagsHidden.val(_t.join(','));
    };

    //删除已选tag
    $tabContent.on('click', '.remove-tag', function () {
        var fid = $(this).parent().data('tid');
        $('#s' + fid).parents('li').remove();
        $(this).parent().remove();
        resetTags();
    });

    //选择tag
    var addTag = function (val, text) {
        if (val == 0) return false;
        if (tagsHidden.val().indexOf(val) > -1) return false;

        var tag = '<span style="margin-right: 5px;" class="label label-success label-tag" data-tid="' + val + '">' + text + ' <i class="fa fa-close remove-tag" style="cursor: pointer;"></i></span>';
        $('#selected-box').append(tag);
        resetTags();
    };

    //-----------------------------
    //下拉选择标签

    // $tabContent.on('click', '.btn-add-tag', function () {
    //     var targetSelect = $(this).parent().prev().children('select');
    //     var selectTag = targetSelect.children(':selected');
    //     if (selectTag.val() == 0) return false;
    //     if (tagsHidden.val().indexOf(selectTag.val()) > -1) return false;
    //
    //     var tag = '<span style="margin-right: 5px;" class="label label-success label-tag" data-tid="' + selectTag.val() + '">' + selectTag.text() + ' <i class="fa fa-close remove-tag" style="cursor: pointer;"></i></span>';
    //     $('#selected-box').append(tag);
    //     resetTags();
    // });

    //下拉选择tag后，如果有级联tag的，获得级联tag数据，并生成html
    $(".tag-select").on("change", function (e) {

        var selected = $(':selected', this);

        addTag(selected.val(), selected.text());

        var type = selected.data('ptype');
        var tid = selected.data('tid');
        var event_id = $("#event-select option:selected").data('tid') || 0;

        if (!type || type == 'sports' || type == '-') return false;

        var url = 'http://' + document.domain + _port + '/api/getPredefineTags/' + type + '/' + tid + '/' + event_id;
        $.get(url, function (result) {
            var data = typeof result === 'string' ? JSON.parse(result) : result;
            for (var i = 0; i < data.length; i++) {
                setOptions(data[i]);
            }
        });

    });

    //生成级联的下拉tag
    var setOptions = function (data) {
        var select = $('#' + data.type + '-select'), option = '<option value="0">请选择</option>', items = data.data, o;
        var nextType = '';
        switch (data.type) {
            case 'event':
                nextType = 'team';
                break;
            case 'team':
                nextType = 'player';
                break;
            case 'player':
                nextType = "-";
                break;
        }
        for (o in items) {
            option += '<option data-ptype="' + nextType + '" data-tid="' + items[o].id + '" value="' + items[o].fake_id + '">' + items[o].name + '</option>';
        }
        select.html(option);
    };

    //-----------------------------
    //搜索标签
    $('#tag-search').select2({
        placeholder: '搜索标签',
        tag: false,

        ajax: {
            url: 'http://' + document.domain + _port + '/search/query/tag',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term,
                    page: params.page
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
        minimumInputLength: 1,
        templateResult: function (data) {
            var item = data;
            return item.name + '-' + item.type
        },
        templateSelection: function (data) {
            return '<span id="s' + data.fake_id + '">' + data.name + '</span>';
        }
    }).on('select2:select', function (d) {
        $('.select2-selection__rendered li.select2-selection__choice').hide();
        var tagInfo = d.params.data;

        addTag(tagInfo.fake_id, tagInfo.name);

        // var tag = '<span style="margin-right: 5px;" class="label label-success label-tag" data-tid="' + tagInfo.fake_id + '">' + tagInfo.name + ' <i class="fa fa-close remove-tag" style="cursor: pointer;"></i></span>';
        // $('#selected-box').append(tag);
        // resetTags();
    }).on('select2:unselect', function (d) {
        var tagInfo = d.params.data;
        $('.label-tag[data-tid="' + tagInfo.fake_id + '"]').remove();
        resetTags();
    });

    $('#search-tab-menu').on('click', function () {
        $('.select2-container').css('width', '100%');
    });

    // var resetTagsOnSearch = function (fakeId) {
    //     var currentTags = tagsHidden.val();
    //     currentTags = currentTags != '' ? currentTags.split(',') : [];
    //     if ($.inArray('' + fakeId, currentTags) < 0) {
    //         currentTags.push(fakeId);
    //     }
    //     tagsHidden.val(currentTags.join(','));
    // };

})();
