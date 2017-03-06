<?php
include __VIEW_DIR__ . "/layout/header.php";


?>
<script>
    /**
     * 绑定登陆的ajax请求
     */
    function onClickToCharge() {
        var price = document.getElementById('price').value;
        $.ajax({
            url: '/wechat/charge',
            type: 'POST',
            dataType: 'JSON',
            data: {
                price: price
            },
            success: function (data) {
                //alert(JSON.stringify(data));
                if (data['code'] != 0) {
                    alert("错误:" + data['code'] + "。错误信息:" + data['message']);
                } else {
                    var charge = confirm("确认充值:" + price + "元?");
                    if (charge) {
                        //调用微信JS api 支付
                        window.location.href = "/wxpay/";
                    }
                }
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
        <h3>微信充值</h3>
        <div class="input-group">
            <span class="input-group-addon">充值金额</span>
            <input
                id="price"
                type="number"
                style="height:30px"
                class="form-control"
            />
        </div>
        <div style="margin-top: 20px">
            <button
                onclick="onClickToCharge()"
                type="button"
                class="form-control btn btn-primary"
            >
                充值
            </button>
        </div>
    </div>
</div>
<?php
include __VIEW_DIR__ . "/layout/footer.php";
?>
