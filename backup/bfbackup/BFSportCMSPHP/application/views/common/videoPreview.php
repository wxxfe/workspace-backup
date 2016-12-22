<div class="modal fade" id="myModal<?=$video['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?=$video['id']?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel<?=$video['id']?>"><?=$video['title']?></h4>
            </div>
            <div class="modal-body">

                <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="100%" height="400">
                    <param name="wmode" value="direct">
                    <param name="quality" value="high">
                    <param name="allowScriptAccess" value="always">
                    <param name="allowFullScreen" value="true">
                    <param name="movie" value="/static/player/cloud.swf">
                    <param name="flashvars" value="iid=<?=$video['play_code']?>&width=100%&height=400&auto=1&vr=<?=$video['isvr']?>">
                    <embed 
                        width="100%" 
                        height="400" 
                        allowScriptAccess="always" 
                        wmode="direct" 
                        quality="high"
                        allowFullScreen= "true"
                        flashvars="iid=<?=urlencode($video['play_code'])?>&width=100%&height=400&auto=1&vr=<?=$video['isvr']?>"
                        src="/static/player/cloud.swf" 
                        name="sportsPlayer" 
                        type="application/x-shockwave-flash" 
                        allowFullScreen="true">
                </object>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
