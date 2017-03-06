<?php
//
//namespace Controller;
//
//use App\Controller;
//use Error\ErrorCode;
//use Error\ErrorObject;
//
//class wechat extends Controller
//{
//    //对接微信接口
//    public function index()
//    {
//        //是微信验证服务器配置
//        if (isset($_GET["echostr"])) {
//            $echoStr = $_GET["echostr"];
//            if ($this->_checkSignature()) {
//                echo $echoStr;
//                exit();
//            } else {
//                exit();
//            }
//        } //不是微信验证服务器配置
//        else {
//            //
//            exit();
//        }
//    }
//
//    //微信验证服务器配置,验证函数
//    private function _checkSignature()
//    {
//        $signature = $_GET["signature"];
//        $timestamp = $_GET["timestamp"];
//        $nonce = $_GET["nonce"];
//
//        $token = "zLPBEaNZy1sBVF06QtmQQO1jTdhFaoMs";
//        $tmpArr = array($token, $timestamp, $nonce);
//        sort($tmpArr, SORT_STRING);
//        $tmpStr = implode($tmpArr);
//        $tmpStr = sha1($tmpStr);
//
//        if ($tmpStr == $signature) {
//            return true;
//        } else {
//            return false;
//        }
//    }
//
//    //视图&接口方法,充值
//    public function charge()
//    {
//        //如果提交了数据
//        if ($_POST && isset($_POST["price"])) {
//            //处理充值操作,并更新实时的商户信息
//            //1.取数据
//            $price = $this->fetchData("POST", ["price"], true)["price"];
//            $price = (float)$price;
//            if ($price > 0) {
//                //2.创建订单
//                $client = $_SESSION["CLIENT"];
//                $wxorder = $this->di["WxOrderService"]->createWxOrder($client["id"], $price);
//
//                if ($wxorder) {
//                    $wxorder["orderinfo"] = json_decode($wxorder["orderinfo"], true);
//                    $_SESSION["wxorder"] = $wxorder;
//                    $this->data($wxorder);
//                    return;
//                } else {
//                    throw new ErrorObject(ErrorCode::CREATE_CHARGE_FAIL);
//                }
//            } else {
//                throw new ErrorObject(ErrorCode::CREATE_CHARGE_FAIL);
//            }
//        }
//        $this->view = "charge";
//        return;
//    }
//
//    //扫码支付回调地址
//    public function qrcode_pay_native()
//    {
//        exit();
//    }
//
//
//}
