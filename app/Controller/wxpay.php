<?php
//namespace Controller;
//
//require_once __LIB_DIR__ . "/Wxpay/WxPay.JsApiPay.php";
//require_once __LIB_DIR__ . "/Wxpay/WxPay.Data.php";
//require_once __LIB_DIR__ . "/Wxpay/WxPay.Config.php";
//require_once __LIB_DIR__ . "/Wxpay/WxPay.Api.php";
//
//
//use app\Controller;
//use Wxpay\JsApiPay;
//use Wxpay\WxPayApi;
//use Wxpay\WxPayConfig;
//use Wxpay\WxPayUnifiedOrder;
//
//
//class wxpay extends Controller
//{
//    public function index()
//    {
//        $this->pay();
//    }
//
//    public function test()
//    {
//        $this->pay();
//    }
//
//    private function pay()
//    {
//        $wxorder = $_SESSION["wxorder"];
//        $client = $_SESSION["CLIENT"];
//        //error_log("\n>>\$wxorder\n" . print_r($wxorder, true) . "\n\n\n", 3, '/var/log/php.njzs-wechat.log');
//
//        //①、获取用户openid
//        $tools = new JsApiPay();
//        $openId = $tools->GetOpenid();
//        //②、统一下单
//        $input = new WxPayUnifiedOrder();
//        $input->SetBody($wxorder["orderinfo"]["body"]);
//        $input->SetAttach($client["storename"]);
//        $input->SetOut_trade_no(intval($wxorder["time"]));
//        $input->SetTotal_fee($wxorder["orderinfo"]["total_fee"] . "");
//        $input->SetTime_start(date("YmdHis", $wxorder["time"]));
//        $input->SetTime_expire(date("YmdHis", $wxorder["time"] + 600));
//        $input->SetGoods_tag("Charge");
//        $input->SetNotify_url(WxPayConfig::NOTIFY_URL);
//        $input->SetTrade_type("JSAPI");
//        $input->SetOpenid($openId);
//        //error_log("\n>>\$input\n" . print_r($input, true) . "\n\n\n", 3, '/var/log/php.njzs-wechat.log');
//
//        $order = WxPayApi::unifiedOrder($input);
//        //error_log("\n>>\$order\n" . print_r($order, true) . "\n\n\n", 3, '/var/log/php.njzs-wechat.log');
//
//        if (isset($order["result_code"]) && $order["result_code"] == "SUCCESS") {
//            $jsApiParameters = $tools->GetJsApiParameters($order);
//            //获取共享收货地址js函数参数
//            $editAddress = $tools->GetEditAddressParameters();
//
//
//            echo '
//<html>
//<head>
//    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
//    <meta name="viewport" content="width=device-width, initial-scale=1"/>
//    <title>微信支付</title>
//    <script type="text/javascript">
//        //调用微信JS api 支付
//        function jsApiCall() {
//            WeixinJSBridge.invoke(
//                \'getBrandWCPayRequest\',
//                ' . $jsApiParameters . ',
//                function (res) {
//                    //alert(JSON.stringify(res));
//                    WeixinJSBridge.log(res.err_msg);
//                    if (res && res.err_msg == \'get_brand_wcpay_request:ok\') {
//                        //发送请求告知支付成功
//
//
//                        var redirect = confirm(\'支付成功!是否跳转到首页?\');
//                        if (redirect){
//                            window.location.href = "/"
//                        } else {
//                            window.location.href = "/wechat/charge";
//                        }
//                    } else {
//                        alert(\'支付出现异常!请重试...\')
//                    }
//                }
//            );
//        }
//
//        function callpay() {
//            if (typeof WeixinJSBridge == "undefined") {
//                if (document.addEventListener) {
//                    document.addEventListener(\'WeixinJSBridgeReady\', jsApiCall, false);
//                } else if (document.attachEvent) {
//                    document.attachEvent(\'WeixinJSBridgeReady\', jsApiCall);
//                    document.attachEvent(\'onWeixinJSBridgeReady\', jsApiCall);
//                }
//            } else {
//                jsApiCall();
//            }
//        }
//
//        window.onload = callpay();
//    </script>
//</head>
//<body>
//</body>
//</html>
//        ';
//            exit();
//        } else {
//            echo "<script>alert('" . $order["err_code_des"] . "');history.go(-1);</script>";
//        }
//
//
//    }
//}
