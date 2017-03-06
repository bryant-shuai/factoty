<?php
//
//namespace Controller;
//
//use App\Controller;
//use Error\ErrorCode;
//use Error\ErrorObject;
//use Tools\Tools;
//
//class index extends Controller
//{
//    //视图方法,微信重定向入口,期间进行各种判定
//    public function index()
//    {
//        //检查微信授权码,从$_GET或$_SESSIO中查询授权码,若没有找到,则显示错误
//        if (!isset($_GET["code"]) && !isset($_SESSION["wx_oauth_code"])) {
//            throw new ErrorObject(ErrorCode::NO_OAUTH2_CODE);
//        }
//        //若微信返回了授权码,则更新授权码,并更新web_access_token
//        if (isset($_GET["code"])) {
//            $_SESSION["wx_oauth_code"] = $_GET["code"];
//            $web_access_token = $this->di["WechatService"]->getWebAccessToken($_SESSION["wx_oauth_code"]);
//        }
//
//        //查询web_access_token,必要时进行更新
//        $web_access_token = $this->di["WechatService"]->getOrRefreshWebAccessToken();
//
//        //读取微信openid
//        $openid = isset($_SESSION["web_access_token"]) ? $_SESSION["web_access_token"]["openid"] : "";
//
//        //日期参数
//        if (isset($_GET["thedate"])) {
//            $date = date("Y/m/d", strtotime($_GET["thedate"]));
//        } else {
//            $date = date("Y/m/d");
//        }
//
//        //产品/备品参数
//        if (isset($_GET["sign"])) {
//            $product_sign = $_GET["sign"];
//        } else {
//            $product_sign = 0;
//        }
//        //查询客户
//        $client = $this->di["ClientService"]->queryByWxid($openid);
//        //指定视图名字
//        if (empty($client)) {
//            //若没有查询到店铺,则跳转到店铺绑定界面
//            $this->view = "bind_client";
//        } else {
//            $this->para["client"] = $client;
//            //若查询到店铺,则进行订单的各种操作
//            $this->view = "order";
//
//            //查询产品分类信息
//            $products_type = $this->di["ProductTypeService"]->query();
//            $products_type = Tools::indexArray($products_type, "id");
//            //判断是否有不良品/工厂品分组
//            $has_special_type = false;
//            foreach ($products_type as $key => $type) {
//                if (strpos($type["name"], "不良") !== false || strpos($type["name"], "工厂") !== false) {
//                    $has_special_type = $key;
//                    break;
//                }
//            }
//
//            //查询产品信息
//            $where = [];
//            if ($has_special_type !== false) {
//                $where = [
//                    "AND" => [
//                        "product_type[!]" => $has_special_type,
//                        "name[!~]" => "%不良%",
//                        " name[!~]" => "%工厂%",
//                    ],
//                ];
//            } else {
//                $where = [
//                    "AND" => [
//                        "name[!~]" => "%不良%",
//                        " name[!~]" => "%工厂%",
//                    ]
//                ];
//            }
//            $where["ORDER"] = "product_type";
//            $products = $this->di["ProductService"]->queryWithOrder($date, $where);
//
//            //初始数据的预处理
//            foreach ($products as $key => $value) {
//                $procuct_type_id = $value["product_type"];
//                if (isset($products_type[$procuct_type_id])) {
//                    $products[$key]["product_type"] = $products_type[$procuct_type_id]["name"];
//                } else {
//                    $products[$key]["product_type"] = "未分类";
//                }
//            }
//
//            //进行分组
//            $products_group = Tools::indexSet($products, "product_type");
//            $this->para["products_group"] = $products_group;
//
//            //查询订单总价
//            $orderPrice = $this->di["OrderService"]->getOrderPrice($date);
//            $this->para["orderPrice"] = $orderPrice;
//        }
//    }
//
//    //接口方法,提交订单
//    public function order()
//    {
//        if (empty($_SESSION["CLIENT"])) {
//            //若没有查询到店铺,则跳转到店铺绑定界面
//            throw new ErrorObject(ErrorCode::NOT_BIND_CLIENT);
//        } else {
//            //时间限制,对客户下订单修改订单时间进行限制
//            //08:00 ~ 14:00
//            $time1 = strtotime("8:00:00");
//            $time2 = strtotime("12:00:00");
//
//            $now = time();
//            /*if ($now < $time1 || $time2 < $now) {
//                throw new ErrorObject(ErrorCode::ORDER_NOT_IN_TIME);
//            }*/
//
//            //检测请求方法
//            $this->verifyPost();
//            //检测并提取提交数据
//            $key = ["product_id", "need_amount"];
//            $para = $this->fetchData("POST", $key, true);
//
//            //查询数据库
//            $result = $this->di["OrderService"]->orderCreate($para);
//            $this->data($result);
//        }
//    }
//
//    //接口方法,提交订单
//    public function order_all()
//    {
//        if (empty($_SESSION["CLIENT"])) {
//            //若没有查询到店铺,则跳转到店铺绑定界面
//            throw new ErrorObject(ErrorCode::NOT_BIND_CLIENT);
//        } else {
//            //时间限制,对客户下订单修改订单时间进行限制
//            //08:00 ~ 14:00
//            $time1 = strtotime("8:00:00");
//            $time2 = strtotime("12:00:00");
//
//            $now = time();
//            /*if ($now < $time1 || $time2 < $now) {
//                throw new ErrorObject(ErrorCode::ORDER_NOT_IN_TIME);
//                return;
//            }*/
//
//            //检测请求方法
//            $this->verifyPost();
//            //检测并提取提交数据
//            $para = $this->fetchData("POST", ["order"], true);
//            $order = $para["order"];
////          $order = json_decode($order, true);     //测试使用
//
//            //查询数据库
//            $result = $this->di["OrderService"]->orderCreateAll($order);
//            $this->data($result);
//        }
//    }
//
//    //接口方法,绑定客户
//    public function bind_client()
//    {
//        //查询web_access_token,必要时进行更新
//        $web_access_token = $this->di["WechatService"]->getOrRefreshWebAccessToken();
//        $openid = isset($_SESSION["web_access_token"]) ? $_SESSION["web_access_token"]["openid"] : "";
//
//        //检测请求方法
//        $this->verifyPost();
//        //检测并提取提交数据
//        $key = ["username", "password"];
//        $para = $this->fetchData("POST", $key, true);
//
//        //查询数据库
//        $result = $this->di["ClientService"]->bindClient($para["username"], $para["password"], $openid);
//        $this->data($result);
//    }
//
//}
