<?php

namespace Service;

use App\Model;
use App\Service;
use Error\ErrorCode;
use Error\ErrorObject;
use Model\Client;
use Model\Order;
use Tools\Tools;

//仅允许1.查询,2.创建,3.更新,4.删除,5.工厂拉取订单数据,6.从工厂更新过来订单信息
class OrderService extends Service
{
    public $column = ["id", "client_id", "storename", "product_id", "product_name", "price", "sign", "need_amount", "send_amount", "get_amount", "checked", "thedate", "create_at", "update_at", "delete_at", "deleted"];

    public function query($where = [], $column = [])
    {
        if (!$column) {
            $column = $this->column;
        }
        return Order::query($column, $where);
    }

    public function insert($data)
    {
        $result = Order::insert($data);
        if ($result != false) {
            $this->di['SyncHandleService']->sync_order($result, "INSERT");
        }
        return $result;
    }

    public function update($where, $data)
    {
        //查询受影响的ids
        $ids = $this->query($where, ["id"]);
        $ids = Tools::indexArray($ids, "id");
        $ids = array_keys($ids);

        $result = Order::update($where, $data);
        if ($result !== false) {
            //把受影响的记录转储到sync表中
            foreach ($ids as $key => $id) {
                $this->di['SyncHandleService']->sync_order($id, "UPDATE");
            }
        }

        return $result;
    }

    public function delete($where)
    {
        //查询受影响的ids
        $ids = $this->query($where, ["id"]);
        $ids = Tools::indexArray($ids, "id");
        $ids = array_keys($ids);

        $result = Order::delete($where);
        if ($result !== false) {
            //把受影响的记录转储到sync表中
            foreach ($ids as $key => $id) {
                $this->di['SyncHandleService']->sync_order($id, "DELETE");
            }
        }

        return $result;
    }

    //查询订单
    public function queryWithProduct($thedate)
    {
        $client = $_SESSION["CLIENT"];

        //查询订单信息
        $owhere = [
            "AND" => [
                // "storename" => $client["storename"],
                "client_id" => $client["id"],
                "thedate" => $thedate,
            ],
        ];
        $orders = $this->di["OrderService"]->query($owhere);
        $orders = Tools::indexArray($orders, "product_name");

        //订单涉及的商品
        $pwhere = ["name" => array_keys($orders)];
        $pcolumn = ["id", "name", "weight_type", "unit", "price", "product_type"];
        $products = $this->di["ProductService"]->query($pwhere, $pcolumn);
        $products = Tools::indexArray($products, "name");

        //拼接产品信息
        foreach ($orders as $key => &$order) {
            if (isset($products[$key])) {
                $order["product"] = $products[$key];
            }
        }

        return array_values($orders);
    }

    //创建订单
    public function orderCreate($data)
    {
        $date = date("Y/m/d");

        //查询产品信息
        $where = ["id" => $data["product_id"]];
        $products = $this->di["ProductService"]->query($where);
        if (!$products) {
            throw new ErrorObject(ErrorCode::PRODUCT_NOT_FIND);
        }
        $product = $products[0];

        //客户信息,若没有找到客户信息,则提示没有发现客户信息
        if (!isset($_SESSION["CLIENT"])) {
            throw new ErrorObject(ErrorCode::CLIENT_NOT_FIND);
        }
        $clientObj = Client::loadObj($_SESSION["CLIENT"]["id"]);
        $client = $clientObj->data;
        unset($client["password"]);
        $_SESSION["CLIENT"] = $client;

        //查询地区价格信息,
        $_where = [
            "AND" => [
                "region_id" => $client["region"],
                "product_name" => $product["name"]
            ]
        ];
        $region = $this->di["ProductPriceService"]->query($_where);
        if ($region) {
            $product["region_price"] = $region[0]["price"];
        } else {
            $product["region_price"] = $product["price"];
        }

        //查询当天已有订单总价,以计算是否超出余额
        $totalprice = $this->getOrderPrice($date);

        //查询当天相同订单,以计算差价
        $where = [
            "AND" => [
                "product_id" => $data["product_id"],
                "client_id" => $client["id"],
                "thedate" => $date,
            ],
        ];
        $orders = $this->query($where);

        //订单差额
        $price = 0;
        foreach ($orders as $key => $order) {
            $price += $order["need_amount"] * $order["price"];
        }
        $price = $data["need_amount"] * $product["region_price"] - $price; //需要再付的金额,为负则为需要返还的金额

        //订单价格判定
        if ($client["deposit"] < $totalprice + $price) {
            throw new ErrorObject(ErrorCode::CLIENT_DEPOSIT_NOT_ENOUGH);
        }

        //删除当天之前相同的订单
        $this->delete($where);

        //若当前订单数量不为0则创建新订单
        if (floatval($data["need_amount"]) > 0) {
            //创建新订单
            $data["client_id"] = $client["id"];
            $data["storename"] = $client["storename"];
            $data["product_name"] = $product["name"];
            $data["price"] = $product["region_price"];
            $data["sign"] = $product["sign"];
            $data["thedate"] = $date;
            $data["create_at"] = time();
            $new_order_id = $this->insert($data);

            //查询新订单并返回
            $where = ['id' => $new_order_id];
            $new_order = $this->query($where);

            //删除之前订单
            $where = [
                "AND" => [
                    "product_id" => $data["product_id"],
                    "client_id" => $client["id"],
                    "thedate" => $date,
                    "id[!]" => $new_order_id,
                ],
            ];
            $this->delete($where);


            return $new_order[0];
        }

        return [];
    }

    //创建订单
    public function orderCreateAll($orders)
    {
        $date = date("Y/m/d");

        //客户信息,若没有找到客户信息,则提示没有发现客户信息
        if (!isset($_SESSION["CLIENT"])) {
            throw new ErrorObject(ErrorCode::CLIENT_NOT_FIND);
        }
        $client = $_SESSION["CLIENT"];

        //查询产品信息
        $products = $this->di["ProductService"]->queryWithOrder($date);
        $products = Tools::indexArray($products, "id");

        //计算当前订单总价
        $totalprice = 0;
        foreach ($orders as $key => $value) {
            $product = $products[$key];
            $totalprice += floatval($product["region_price"]) * floatval($value);
        }

        //订单价格判定
        if ($client["deposit"] < $totalprice) {
            throw new ErrorObject(ErrorCode::CLIENT_DEPOSIT_NOT_ENOUGH);
        }

        //删除当天之前相同的订单
        $where = [
            "AND" => [
                "thedate" => $date,
                "client_id" => $client["id"]
            ]
        ];
        $this->delete($where);

        //循环创建新订单
        $fail = [];
        foreach ($orders as $key => $value) {
            //若当前订单数量不为0则创建新订单
            if (floatval($value)) {
                $product = $products[$key];

                //创建新订单
                $data["product_id"] = $key;
                $data["product_name"] = $product["name"];     //product_name
                $data["need_amount"] = $value;
                $data["client_id"] = $client["id"];
                $data["storename"] = $client["storename"];
                $data["price"] = $product["region_price"];
                $data["sign"] = $product["sign"];
                $data["thedate"] = $date;
                $data["create_at"] = time();
                $new_order_id = $this->insert($data);
                if ($new_order_id === 0) {
                    $fail[$key] = $value;
                }
            }
        }

        //查询新订单并返回
        $new_order = $this->query($where);
        $returndata = ["success" => $new_order, "fail" => $fail];
        return $returndata;
    }

    //获取当前订单总价
    public function getOrderPrice($date)
    {
        $client = $_SESSION["CLIENT"];
        $order_price = Model::sqlExec("select sum(`price` * `need_amount`) as `order_price` from `njzs_order` where `thedate` = '$date' and `client_id` = " . $client["id"]);
        $order_price = floatval($order_price[0]["order_price"]);
        return $order_price;
    }

}
