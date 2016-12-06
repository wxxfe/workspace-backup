<?php
$CI = & get_instance();
$CI->load->model('Tag_model', 'TM');
$this->TM = & $CI->TM;

$selectTags = $this->TM->getSelectionTags();

$currentSelected = array();
$ids = array();
if(!empty($selected)){
    $ids = explode(',',$selected);
    $currentSelected = $this->TM->db('sports')->getTagsByFakeId($selected);
}
?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tag-select-content" data-toggle="tab" aria-expanded="true">选择</a></li>
        <li><a href="#tag-search-content" id="search-tab-menu" data-toggle="tab" aria-expanded="true">搜索</a></li>
    </ul>
    <div class="tab-content">
        <div id="tag-select-content" class="tab-pane active">
            <div id="tagSelect">
                <input id="tags" type="hidden" name="tags" value="<?=$selected?>" />
                <table class="table table-hover">
                    <?php $types = array('sports','event','team','player'); ?>
                    <?php foreach($selectTags as $key => $tagGroup): ?>
                    <tr>
                        <td width="60"><?=$tagGroup['name']?></td>
                        <td>

                            <?php if(in_array($tagGroup['type'],$types)): ?>
                            <select id="<?=$tagGroup['type']?>-select" class="form-control tag-select">
                            <?php else: ?>
                            <select  class="form-control tag-select">
                            <?php endif; ?>
                                <option value="0">请选择</option>
                                <?php foreach($tagGroup['data'] as $tag): ?>

                                <?php if($tagGroup['type'] == 'sports'): ?>
                                <option data-ptype="event" data-tid="<?=$tag['id']?>" value="<?=$this->TM->makeFakeId($tagGroup['type'],$tag['id'])?>"><?=$tag['name']?></option>

                                <?php else: ?>
                                <option data-tid="<?=$tag['id']?>" value="<?=$this->TM->makeFakeId($tagGroup['type'],$tag['id'])?>"><?=$tag['name']?></option>

                                <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <style type="text/css">
            .select2-container--default .select2-selection--multiple .select2-selection__choice{
                color: #000;
            }
            .select2-container{display: block;}
            .label{display: inline-block;}
        </style>
        <div id="tag-search-content" class="tab-pane">
            <table class="table table-hover">
                <tr>
                    <td width="100%">
                        <select id="tag-search" class="form-control" multiple="multiple"></select>
                    </td>
                </tr>
            </table>
        </div>
        <table class="table">
            <tr>
                <td width="60">已选择</td>
                <td>
                    <div id="selected-box">
                    <?php foreach($currentSelected as $item): ?>
                    <span style="margin-right: 5px;" class="label label-success label-tag" data-tid="<?=$item['id']?>"><?=$item['name']?> <i class="fa fa-close remove-tag" style="cursor: pointer;"></i></span>
                    <?php endforeach; ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<script>
(function(){
    var files = [
        {name : 'select',type : 'css',src : '/static/plugins/select2/select2.min.css?d=20161028'},
        {name : 'select',type : 'script',src : '/static/plugins/select2/select2.full.min.js?d=20161028'},
        {name : 'tag-select',type : 'script',src : '/static/dist/js/tag-select.js?d=20161028'}
    ];
    window.addEventListener('load',function(){
        for(var i=0; i<files.length; i++){
            if(files[i].type == 'script'){
                var script = document.createElement('script');
                script.src = files[i].src;
                script.id = files[i].type + '-script';
                if(files[i].name == 'tag-select'){
                    setTimeout(function(){
                        document.body.appendChild(script);
                    },2000);
                }else{
                    if(!document.getElementById(files[i].type + '-script')){
                        document.body.appendChild(script);
                    }
                }
            }else{
                var css = document.createElement('link');
                css.rel = 'stylesheet';
                css.href = files[i].src;
                document.getElementsByTagName('head')[0].appendChild(css);
            }
        }
    });
})();
</script>
