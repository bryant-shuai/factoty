<?php
include __VIEW_DIR__ . "/layout/header.php";
?>
<script>
    /**
     * 绑定登陆的ajax请求
     */
    function onClickToLogin() {
        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;
        password = md5(password);
        $.ajax({
            url: '/index/bind_client',
            type: 'POST',
            dataType: 'JSON',
            data: {
                username: username,
                password: password
            },
            success: function (data) {
                if (data['code'] != 0) {
                    alert("错误:" + data['code'] + "。错误信息:" + data['message']);
                } else {
                    alert("绑定成功。");
                }
                window.location.href = "/";
            },
            error: function (error) {
                alert("请求失败!");
            }
        });

        return false;
    }
</script>
<div class="container">
    <div class="well" style="margin:0 auto;">
        <h3>请绑定店铺</h3>
        <div class="input-group">
            <span class="input-group-addon">账号</span>
            <input
                id="username"
                type="text"
                style="height:30px"
                class="form-control"
            />
        </div>
        <div class="input-group" style="margin-top: 10px">
            <span class="input-group-addon">密码</span>
            <input
                id="password"
                type="password"
                style="height:30px"
                class="form-control"
            >
        </div>
        <div style="margin-top: 20px">
            <button
                onclick="onClickToLogin()"
                type="button"
                class="form-control btn btn-primary"
            >
                登陆系统
            </button>
        </div>
    </div>
</div>
<?php
include __VIEW_DIR__ . "/layout/footer.php";
?>
