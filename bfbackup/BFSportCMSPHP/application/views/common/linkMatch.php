<style type="text/css">
    .select2-container .select2-selection--single {
        height: 32px;
    }
</style>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#match-input-box" data-toggle="tab" aria-expanded="true">输入比赛ID</a></li>
        <li><a href="#match-search-content" id="search-tab-menu" data-toggle="tab" aria-expanded="true">搜索</a></li>
    </ul>
    <div class="tab-content">
        <div id="match-input-box" class="tab-pane active">
            <div>
                <input id="match-id-link-match" class="form-control" style="width: 60%; display: inline-block;"
                       type="text" placeholder="请输入比赛ID"
                       value="<?= $current ?>"
                       name="match_id"
                />
                <button type="button" class="btn btn-primary" style="position: relative;top:-2px;"
                        data-toggle="modal" data-target=".match-select-modal">选择
                </button>
            </div>
            <div id="match-select-info"></div>
        </div>
        <div id="match-search-content" class="tab-pane">
            <select id="match-search" class="form-control"></select>
        </div>
    </div>
</div>

<div class="modal fade match-select-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width: 970px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">选择比赛</h4>
            </div>
            <div class="modal-body" style="padding: 0;">
                <iframe id="match-select"
                        name="match-select"
                        title="match-select"
                        width="970"
                        height="400"
                        frameborder="0"
                        scrolling="yes"
                        marginheight="0"
                        marginwidth="0"
                        allowTransparency="true"
                        src="<?= site_url('match/matchSelect') ?>">
                </iframe>
            </div>
        </div>
    </div>
</div>

<script>

    (function () {
        var files = [
            {name: 'select', type: 'css', src: '/static/plugins/select2/select2.min.css'},
            {name: 'select', type: 'script', src: '/static/plugins/select2/select2.full.min.js'},
            {name: 'match-select', type: 'script', src: '/static/dist/js/match-select.js'}
        ];
        window.addEventListener('load', function () {
            for (var i = 0; i < files.length; i++) {
                if (files[i].type == 'script') {
                    var script = document.createElement('script');
                    script.src = files[i].src;
                    script.id = files[i].type + '-script';
                    if (files[i].name == 'match-select') {
                        setTimeout(function () {
                            document.body.appendChild(script);
                        }, 2000);
                    } else {
                        if (!document.getElementById(files[i].type + '-script')) {
                            document.body.appendChild(script);
                        }
                    }
                } else {
                    var css = document.createElement('link');
                    css.rel = 'stylesheet';
                    css.href = files[i].src;
                    document.getElementsByTagName('head')[0].appendChild(css);
                }
            }
        });
    })();

</script>
