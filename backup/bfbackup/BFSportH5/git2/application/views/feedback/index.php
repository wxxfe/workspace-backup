<?php $this->load->view('common/header', $header_footer_data) ?>

<form method="post" id="feedback">
    <div class="row">
        <div class="col-12 text-box">
            <textarea id="text-box" placeholder="请写下你遇到的问题或建议" name="text" cols="30" rows="10"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-box">
            <input type="text" name="contcat" placeholder="如需回复，请留下你的QQ号/手机号"/>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-box">
            <button type="submit" class="submit-btn">提交</button>
        </div>
    </div>
    <input type="hidden" id="app_version" name="app_version" value=""/>
    <input type="hidden" id="phone_model" name="phone_model" value=""/>
    <input type="hidden" id="phone_system" name="phone_system" value=""/>
    <input type="hidden" id="network" name="network" value=""/>
</form>
<script type="text/javascript">
    var appVersion = document.querySelector('#app_version'),
        phoneModel = document.querySelector('#phone_model'),
        phoneSystem = document.querySelector('#phone_system'),
        network = document.querySelector('#network'),
        feedback = document.querySelector('#feedback');

    var deviceCb = window.deviceCb = {};
    deviceCb.fetchDeviceInfo = function (result) {
        var res = JSON.parse(result);
        appVersion.value = res.appVersion;
        phoneModel.value = res.deviceModel;
        phoneSystem.value = res.systemVersion;
        network = res.reachability;
    }

    feedback.onsubmit = function () {
        var text = document.querySelector('#text-box');
        if (text.value == '') {
            window.webplay.showAlert("提示", "请输入反馈信息！", "确定", "");
            //alert('请输入反馈信息！');
            text.focus();
            return false;
        }
    }
    <?php if ($result == 1): ?>
    //alert('test');
    setTimeout(function () {
        //window.webplay.showAlert("提示", "发送成功！", "确定", "");
        window.webplay.showToastText("发送成功！");
    }, 100)

    <?php endif; ?>
</script>


<?php $this->load->view('common/footer', $header_footer_data) ?>
