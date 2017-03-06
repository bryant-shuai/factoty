<?php

namespace Controller;

use App\Controller;
use Error\ErrorCode;
use Error\ErrorObject;
use Tools\Tools;

class order extends Controller
{

    //查询返现信息
    public function return_back()
    {
        $client_id = $_SESSION["CLIENT"]['id'];
        $his = \Model\ReturnBack::finds('where client_id=' . $client_id . ' order by id desc limit 30');
        foreach ($his as $k => &$v) {
          $v['extra'] = \de($v['extra']);
        }
        $this->data($his);
    }

    //修改客户密码
    public function update_client_password_to_wechat()
    {
      $clientId = (int) $_POST['client_id'];
      if(empty($clientId)){
        $this->data(['ok' => '-1']);
      }
      $oClient = \Model\Client::loadObj($clientId);
      $oClient->data['password'] = $_POST['password'];
      $oClient->save();

      $this->data(['ok' => '1']);
    }

    //修改客户密码
    public function update_client_info_to_wechat()
    {
      $clientId = (int) $_POST['client_id'];
      if(empty($clientId)){
        $this->data(['ok' => '-1']);
      }
      $oClient = \Model\Client::loadObj($clientId);
      if(!empty($_POST['password'])) $oClient->data['password'] = $_POST['password'];
      $oClient->data['username'] = $_POST['username'];
      $oClient->save();

      $this->data(['ok' => '1']);
    }

    //确认一条返现信息
    public function return_back_check()
    {
        $oReturnBack = \Model\ReturnBack::loadObj($_POST['id']);

        if ($oReturnBack->data['status'] == 0) {
            $oReturnBack->data['status'] = 1;
            $oReturnBack->save();
            $this->di['SyncHandleService']->sync_return_back($oReturnBack->data['id'], "INSERT");
        }

        $client_id = $_SESSION["CLIENT"]['id'];
        $his = \Model\ReturnBack::finds('where client_id=' . $client_id . ' order by id desc limit 30');
        foreach ($his as $k => &$v) {
          $v['extra'] = \de($v['extra']);
        }
        $this->data($his);
    }

    //设置一条需要确认的返现
    function set_return_back__need_check() {

      \vd($_POST,'_POST');
      $data = $_POST;

      if(empty($data['amount'])){
        $this->data(['ok'=>'1']);
      }

      $oReturnBack = new \Model\ReturnBack;
      $oReturnBack->data = [
        'client_id' => $data['client_id'],
        'amount' => $data['amount'],
        'create_at' => date("Y/m/d H:i:s"),
        'extra' => \en([
            'content' => $data['msg'],
            'amount' => $data['amount'],
          ]),
      ];

      \vd($data,'data');
      \vd($oReturnBack->data,'$oReturnBack->data');
      $oReturnBack->save();

      $this->data(['ok' => '1']);
    }


    // //测试登陆
    // public function test_client()
    // {
    //     $_SESSION["CLIENT"] = ['id'=>1];
    // }


    //登陆
    public function login()
    {
        //检测并提取提交数据
        $key = ["username", "password"];
        $para = $this->fetchData("POST", $key, true);

        //查询数据库
        $result = $this->di["ClientService"]->login($para);
        $this->data($result);
    }

    //登陆
    public function fakelogin()
    {
        //检测并提取提交数据
        $key = ["username"];
        $para = $this->fetchData("GET", $key, true);

        //查询数据库
        $result = $this->di["ClientService"]->fakelogin($para);
        $this->data($result);
    }

    //登出
    public function logout()
    {
        $result = $this->di["ClientService"]->logout();
        $this->data($result);
    }

    //充值，需确认
    public function charge_need_check()
    {
        // \vd($_GET);

    }

    //重置密码
    public function resetpwd()
    {
        //检测并提取提交数据
        $key = ["oldpwd", "newpwd"];
        $para = $this->fetchData("POST", $key, true);

        //查询数据库
        $result = $this->di['ClientService']->resetpwd($para);
        $this->data($result);
    }

    //查询产品信息
    public function products()
    {

        if (empty($_SESSION["CLIENT"])) {
            //若没有查询到店铺,则跳转到店铺绑定界面
            throw new ErrorObject(ErrorCode::CLIENT_NOT_LOGIN);
        } else {

            $products = $this->di["ProductService"]->queryWithPrice();

            //查询产品分类信息
            $products_type = $this->di["ProductTypeService"]->query();
            $products_type = Tools::indexArray($products_type, "id");
            // \vd($products_type,'$products_type');

            //判断是否有不良品/工厂品分组
            $has_special_type = false;
            foreach ($products_type as $key => $type) {
                if (strpos($type["name"], "不良") !== false || strpos($type["name"], "工厂") !== false || strpos($type["name"], "活动") !== false) {
                    unset($products_type[$key]);
                }
            }


            $product_with_type = [];
            foreach ($products as $product) {
                if (empty($product_with_type['_' . $product['product_type']])) {
                    $product_with_type['_' . $product['product_type']] = [];
                }

                if (strpos($product["name"], "不良") !== false || strpos($product["name"], "工厂") !== false || strpos($product["name"], "活动") !== false) {
                    // unset($products_type[$key]);
                }else{
                  $product_with_type['_' . $product['product_type']][] = $product;
                }
            }

            // \vd($product_with_type,'$product_with_type');



            $this->data([
                'product_with_type' => $product_with_type,
                'types' => $products_type,
                'client' => $_SESSION["CLIENT"],
            ]);
            // $this->data($products);
        }
    }

    //查询订单信息
    public function orders()
    {
        if (empty($_SESSION["CLIENT"])) {
            //若没有查询到店铺,则跳转到店铺绑定界面
            throw new ErrorObject(ErrorCode::CLIENT_NOT_LOGIN);
        } else {
            $para = $this->fetchData("GET", ["thedate"], false);
            $thedate = isset($para["thedate"]) ? $para["thedate"] : date("Y/m/d");

            $orders = $this->di["OrderService"]->queryWithProduct($thedate);
            $sum = 0;
            foreach ($orders as $v) {
              $sum += $v['price']*$v['send_amount'];
            }
            // $this->data($orders);

            $this->data([
              'orders' => $orders,
              'sum' => $sum,
            ]);
        }
    }

    //创建新订单
    public function create()
    {
        if (empty($_SESSION["CLIENT"])) {
            //若没有查询到店铺,则跳转到店铺绑定界面
            throw new ErrorObject(ErrorCode::CLIENT_NOT_LOGIN);
        } else {

            //时间限制
            $osts = \Config\ORDER_START_TIME;
            $ost = strtotime($osts);
            $oets = \Config\ORDER_END_TIME;
            $oet = strtotime($oets);
            $now = time();
            if ($ost > $now || $now > $oet) {
                throw new ErrorObject(ErrorCode::ORDER_NOT_IN_TIME);
            }

            //检测并提取提交数据
            $key = ["product_id", "need_amount"];
            $para = $this->fetchData("POST", $key, true);
            if (floatval($para["need_amount"]) < 0) {
                throw new ErrorObject(ErrorCode::PARAMETER_ERROR);
            }

            //查询数据库
            $result = $this->di["OrderService"]->orderCreate($para);
            $this->data($result);
        }
    }

    //查询余额变动信息
    public function log_account_bk()
    {
        if (empty($_SESSION["CLIENT"])) {
            //若没有查询到店铺,则跳转到店铺绑定界面
            throw new ErrorObject(ErrorCode::CLIENT_NOT_LOGIN);
        } else {
            //检测并提取提交数据
            $key = ["fromdate", "todate", "pageno", "pagesize"];
            $para = $this->fetchData("GET", $key, false);

            $client = $_SESSION["CLIENT"];
            //数据基本处理
            $where = ["client_id" => $client["id"]];
            try {
                //组装条件
                if (isset($para["fromdate"])) {
                    $where["create_at[>=]"] = strtotime($para["fromdate"]);
                }
                if (isset($para["todate"])) {
                    $where["create_at[<]"] = strtotime($para["todate"]) + 86400;
                }
                if (count($where) >= 2) {
                    $where = ["AND" => $where];
                }

                //组装order
                $where["ORDER"] = "id DESC";

                //组装limit, 默认第一页, 默认页大小10
                if (!isset($para["pageno"]) || intval($para["pageno"]) < 1) {
                    $para["pageno"] = 1;
                }
                if (!isset($para["pagesize"]) || intval($para["pagesize"]) < 1) {
                    $para["pagesize"] = 10;
                }
                $where["LIMIT"] = [
                    ($para["pageno"] - 1) * $para["pagesize"],
                    $para["pagesize"]
                ];
            } catch (ErrorObject $e) {
                throw new ErrorObject(ErrorCode::PARAMETER_ERROR);
            }

            \vd($where, "finally");
            //查询数据
            $result = $this->di["LogAccountService"]->query($where);
            if (!$result) $result = [];
            $this->data($result);
        }
    }


    //查询余额变动信息
    public function log_account()
    {
        if (empty($_SESSION["CLIENT"])) {
            //若没有查询到店铺,则跳转到店铺绑定界面
            throw new ErrorObject(ErrorCode::CLIENT_NOT_LOGIN);
        } else {
            //检测并提取提交数据
            $key = ["fromdate", "todate", "pageno", "pagesize"];
            $para = $this->fetchData("GET", $key, false);

            $client = $_SESSION["CLIENT"];
            //数据基本处理
            $where = ["client_id" => $client["id"]];
            try {
                //组装条件
                if (isset($para["fromdate"])) {
                    $where["create_at[>=]"] = strtotime($para["fromdate"]);
                }
                if (isset($para["todate"])) {
                    $where["create_at[<]"] = strtotime($para["todate"]) + 86400;
                }
                if (count($where) >= 2) {
                    $where = ["AND" => $where];
                }

                //组装order
                $where["ORDER"] = "id DESC";

                //组装limit, 默认第一页, 默认页大小10
                if (!isset($para["pageno"]) || intval($para["pageno"]) < 1) {
                    $para["pageno"] = 1;
                }
                if (!isset($para["pagesize"]) || intval($para["pagesize"]) < 1) {
                    $para["pagesize"] = 10;
                }
                $where["LIMIT"] = [
                    ($para["pageno"] - 1) * $para["pagesize"],
                    $para["pagesize"]
                ];
            } catch (ErrorObject $e) {
                throw new ErrorObject(ErrorCode::PARAMETER_ERROR);
            }

            \vd($where, "finally");
            //查询数据
            $result = $this->di["LogAccountService"]->query($where);
            if (!$result) $result = [];
            $this->data($result);
        }
    }

}
