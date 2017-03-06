<?php

namespace Service;

use App\Service;
use Error\ErrorCode;
use Error\ErrorObject;
use Model\Product;
use Tools\Tools;

//仅允许1.查询,2.从工厂更新过来产品信息
class ProductService extends Service
{
    public $column = ["id", "name", "weight_type", "unit", "price", "amount", "sign", "order", "rate", "product_type", "create_at", "update_at", "delete_at", "deleted"];

    //查询产品信息
    public function query($where = [], $column = [])
    {
        if (!$column) {
            $column = $this->column;
        }
        return Product::query($column, $where);
    }

    //创建
    public function insert($data)
    {
        return Product::insert($data);
    }

    //更新
    public function update($where, $data)
    {
        return Product::update($where, $data);
    }

    //删除
    public function delete($where)
    {
        return Product::delete($where);
    }

    //查询产品信息,附带地区的价格信息
    public function queryWithPrice()
    {
        //查询产品分类信息
        $products_type = $this->di["ProductTypeService"]->query();
        $products_type = Tools::indexArray($products_type, "id");
        //判断是否有不良品/工厂品分组
        $has_special_type = false;
        foreach ($products_type as $key => $type) {
            if (strpos($type["name"], "不良") !== false || strpos($type["name"], "工厂") !== false) {
                $has_special_type = $key;
                break;
            }
        }

        //查询产品信息
        $where = [];
        if ($has_special_type !== false) {
            $where = [
                "AND" => [
                    "product_type[!]" => $has_special_type,
                    "name[!~]" => "%不良%",
                    " name[!~]" => "%工厂%",
                ],
            ];
        } else {
            $where = [
                "AND" => [
                    "name[!~]" => "%不良%",
                    " name[!~]" => "%工厂%",
                ],
            ];
        }
        $where["ORDER"] = "order ASC";

        $column = ["id", "name", "weight_type", "unit", "price", "sign", "product_type"];
        $products = $this->query($where, $column);
        $products = Tools::indexArray($products, "name");

        //查询分区价格
        $client = $_SESSION["CLIENT"];
        $region_id = $client["region"];

        //查询产品在客户所属地区的价格信息
        $region_prices = $this->di["ProductPriceService"]->query(["region_id" => $region_id]);
        $region_prices = Tools::indexArray($region_prices, "product_name");

        //地区的价格信息
        foreach ($products as $product_name => $product) {
            //产品在客户所属地区的价格信息
            if(isset($region_prices[$product_name])) {
                $products[$product_name]["region_price"] = $region_prices[$product_name]["price"];
            } else {
                $products[$product_name]["region_price"] = $products[$product_name]["price"];
            }

            //若价格为负数，则说明不允许在这个地区标签下显示该店铺
            if ($products[$product_name]["region_price"] < 0) {
                unset($products[$product_name]);
            }
        }
        $products = array_values($products);
        return $products;
    }


    //查询产品信息,附带订单信息,附带地区的价格信息
    public function queryWithOrder($date = '', $where = [], $column = [])
    {
        //查询列
        if (!$column) {
            $column = $this->column;
        }

        //日期
        if (!$date) {
            $date = date("Y/m/d");
        }

        $_SESSION["PRODUCT_SIGN"] = 0;
        $product_sign = $_SESSION["PRODUCT_SIGN"];

        //查询产品信息
        $products = Product::query($column, $where);

        //客户信息,若没有找到客户信息,则提示没有发现客户信息
        if (isset($_SESSION["CLIENT"])) {
            $client = $_SESSION["CLIENT"];
            $region_id = $client["region"];

            //产品信息预处理
            $products = Tools::indexArray($products, "name");

            //查询订单信息
            $cwhere = [
                "AND" => [
                    "storename" => $client["storename"],
                    "thedate" => $date,
                ],
            ];
            $orders = $this->di["OrderService"]->query($cwhere);
            $orders = Tools::indexArray($orders, "product_name");
            //查询产品在客户所属地区的价格信息
            $region_prices = $this->di["ProductPriceService"]->query(["region_id" => $region_id]);
            $region_prices = Tools::indexArray($region_prices, "product_name");

            //拼入订单信息、当地区的价格信息
            foreach ($products as $product_name => $product) {
                //订单信息
                if (isset($orders[$product_name])) {
                    $products[$product_name]["need_amount"] = $orders[$product_name]['need_amount'];
                } else {
                    $products[$product_name]["need_amount"] = 0;
                }

                //产品在客户所属地区的价格信息
                if(isset($region_prices[$product_name])) {
                    $products[$product_name]["region_price"] = $region_prices[$product_name]["price"];
                } else {
                    $products[$product_name]["region_price"] = $products[$product_name]["price"];
                }

                //若价格为负数，则说明不允许在这个地区标签下显示该店铺
                if ($products[$product_name]["region_price"] < 0) {
                    unset($products[$product_name]);
                }

            }
            $products = array_values($products);
        }

        return $products;
    }

}
