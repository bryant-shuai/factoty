<?php

namespace Service;

use App\Service;
use Model\WxOrder;

//仅允许1.查询,2.创建,3.更新,4.删除,5.工厂拉取订单数据,6.从工厂更新过来订单信息
class WxOrderService extends Service
{
    public $column = ["id", "clientid", "orderinfo", "status", "time", "synced"];

    public function query($where = [], $column = [])
    {
        if (!$column) {
            $column = $this->column;
        }
        return WxOrder::query($column, $where);
    }

    public function insert($data)
    {
        return WxOrder::insert($data);
    }

    public function update($where, $data)
    {
        return WxOrder::update($where, $data);
    }

    public function delete($where)
    {
        return WxOrder::delete($where);
    }

    public function createWxOrder($clientid, $price)
    {
        $price = (float)$price;
        if ($price <= 0) {
            return false;
        }

        //数据准备,并存储到数据库
        $price_text = $price;
        $price = $price * 100;
        $order_info = [
            "total_fee" => $price,
            "body" => "充值" . $price_text . "元",
        ];
        $order_info_str = json_encode($order_info, JSON_UNESCAPED_UNICODE);
        $wx_order_data = [
            "clientid" => $clientid,
            "orderinfo" => $order_info_str,
            "time" => time(),
        ];
        $result = $this->di["WxOrderService"]->insert($wx_order_data);

        if (!$result) {
            return false;
        } else {
            $where = ["id" => $result];
            $wxorder = $this->query($where);
            $wxorder = $wxorder[0];
            return $wxorder;
        }

    }
}
