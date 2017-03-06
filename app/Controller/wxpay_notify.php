<?php
//namespace Controller;
//
//require_once __LIB_DIR__ . "/Wxpay/WxPay.Data.php";
//require_once __LIB_DIR__ . "/Wxpay/WxPay.Api.php";
//require_once __LIB_DIR__ . "/Wxpay/WxPay.Notify.php";
//
//use App\Controller;
//use Service\ClientService;
//use Service\WxOrderService;
//use WxPay\WxPayApi;
//use WxPay\WxPayNotify;
//use WxPay\WxPayOrderQuery;
//
//class wxpay_notify extends Controller
//{
//    //入口方法
//    public function index()
//    {
//        //error_log("\n\n\n<><><><><><><><><><><><><><><><><><><>\n>>notify start\n\n", 3, '/var/log/php.njzs-wechat.log');
//        $notifyCallBack = new PayNotifyCallBack();
//        $notifyCallBack->Handle(false);
//        //error_log("\n\n\n<><><><><><><><><><><><><><><><><><><>\n>>notify stop\n\n", 3, '/var/log/php.njzs-wechat.log');
//        exit();
//    }
//
//}
//
//class PayNotifyCallBack extends WxPayNotify
//{
//    //查询订单
//    public function Queryorder($transaction_id)
//    {
//        $input = new WxPayOrderQuery();
//        $input->SetTransaction_id($transaction_id);
//        $result = WxPayApi::orderQuery($input);
//        if (array_key_exists("return_code", $result)
//            && array_key_exists("result_code", $result)
//            && $result["return_code"] == "SUCCESS"
//            && $result["result_code"] == "SUCCESS"
//        ) {
//            //本地查询订单
//            $where = ["time" => $result['out_trade_no']];
//            $wxOrderService = new WxOrderService();
//            $orders = $wxOrderService->query($where);
//            if ($orders && $orders[0]["status"] == 0) {
//                //更新本地订单状态
//                $data = ["status" => 1];
//                $_result = $wxOrderService->update($where, $data);
//                $order = $orders[0];
//                $order["orderinfo"] = json_decode($order["orderinfo"], true);
//
//                //充值到账户
//                $where = ["id" => $order["clientid"]];
//                $charge = $order["orderinfo"]["total_fee"] * 0.01;
//                $data = [
//                    "deposit[+]" => $charge,
//                    "change[+]" => $charge
//                ];
//                $clientService = new ClientService();
//                $_result = $clientService->update($where, $data);
//
//                return true;
//            }
//        }
//        return false;
//    }
//
//    //重写回调处理函数
//    public function NotifyProcess($data, &$msg)
//    {
//        if (!array_key_exists("transaction_id", $data)) {
//            $msg = "输入参数不正确";
//            return false;
//        }
//        //查询订单，判断订单真实性
//        if (!$this->Queryorder($data["transaction_id"])) {
//            $msg = "订单查询失败";
//            return false;
//        }
//
//        return true;
//    }
//}