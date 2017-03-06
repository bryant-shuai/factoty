<?php
include __VIEW_DIR__ . "/layout/header.php";

$flag = isset($_GET['sign']) ? $_GET['sign'] : 0;
?>
<script>
    <?php
        if (isset($para["result"])) {
            if ($para["result"] == true) {
                echo "alert('修改成功！');";
            } else {
                echo "alert('修改失败！');";
            }
        }
    ?>
</script>
<div>
    <form method="post" class="form-horizontal">
        <div class="form-group">
            <label for="oldpwd" class="col-sm-4 control-label">旧密码</label>
            <div class="col-sm-8">
                <input name="oldpwd" type="password" class="form-control" id="oldpwd" placeholder="请输入旧密码">
            </div>
        </div>
        <div class="form-group">
            <label for="newpwd1" class="col-sm-4 control-label">新密码</label>
            <div class="col-sm-8">
                <input name="newpwd" type="password" class="form-control" id="newpwd1" placeholder="请输入新密码">
            </div>
        </div>
        <div class="form-group">
            <label for="newpwd2" class="col-sm-4 control-label">确认密码</label>
            <div class="col-sm-8">
                <input type="password" class="form-control" id="newpwd2" placeholder="再次输入密码">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
                <button type="submit" class="btn btn-default">修改</button>
            </div>
        </div>
    </form>
</div>

<?php
include __VIEW_DIR__ . "/layout/footer.php";
?>
