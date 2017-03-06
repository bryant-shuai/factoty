<?php

namespace Service;

use App\Service;

class SyncHandleService extends Service
{

    ////////////////////////////////////// 接收工厂变动数据

    //处理工厂同步过来的客户数据
    function _deal_client($sid, $id, $data, $type)
    {
        // \vd($id,'deal id');
        // \vd($type,'deal type');
        // \vd($data,'deal data');

        if (strtoupper($type) == "DELETE") {
            \Model\Client::deleteById($id);
            return true;
        }

        $oClient = \Model\Client::loadObj($id);
        if (!$oClient) {
            \vd('not found!');
            \vd($data, '$data');
            $data["change"] = 0;
            $id = \Model\Client::insert($data);
            \vd($id);
        } else {
            \vd($oClient);

            //处理云端余额变动
            $oClient->data["deposit"] += $data["change"];
            unset($data["change"]);
            unset($data["password"]);
            $oClient->data = $data;

            $oClient->save();
        }
        return true;
    }

    //处理工厂同步过来的产品数据
    function _deal_product($sid, $id, $data, $type)
    {
        // \vd($id,'deal id');
        // \vd($type,'deal type');
        // \vd($data,'deal data');

        if (strtoupper($type) == "DELETE") {
            \Model\Product::deleteById($id);
            return true;
        }

        $oProduct = \Model\Product::loadObj($id);
        if (!$oProduct) {
            \vd('not found!');
            \vd($data, '$data');
            $id = \Model\Product::insert($data);
            \vd($id);
        } else {
            \vd($oProduct);
            $oProduct->data = $data;
            $oProduct->save();
        }
        return true;
    }

    //处理工厂同步过来的订单数据
    function _deal_order($sid, $id, $data, $type)
    {
        // \vd($id,'deal id');
        // \vd($type,'deal type');
        // \vd($data,'deal data');

        if (strtoupper($type) == "DELETE") {
            \Model\Order::deleteById($id);
            return true;
        }

        $oOrder = \Model\Order::loadObj($id);
        if (!$oOrder) {
            \vd('not found!');
            \vd($data, '$data');
            $id = \Model\Order::insert($data);
            \vd($id);
        } else {
            \vd($oOrder);
            $oOrder->data = $data;
            $oOrder->save();
        }
        return true;
    }

    //处理工厂同步过来的地区价格数据
    function _deal_product_price($sid, $id, $data, $type)
    {
        // \vd($id,'deal id');
        // \vd($type,'deal type');
        // \vd($data,'deal data');

        if (strtoupper($type) == "DELETE") {
            \Model\ProductPrice::deleteById($id);
            return true;
        }

        $oProductPrice = \Model\ProductPrice::loadObj($id);
        if (!$oProductPrice) {
            \vd('not found!');
            \vd($data, '$data');
            $id = \Model\ProductPrice::insert($data);
            \vd($id);
        } else {
            \vd($oProductPrice);
            $oProductPrice->data = $data;
            $oProductPrice->save();
        }
        return true;
    }

    //处理工厂同步过来的产品分类数据
    function _deal_product_type($sid, $id, $data, $type)
    {
        // \vd($id,'deal id');
        // \vd($type,'deal type');
        // \vd($data,'deal data');

        if (strtoupper($type) == "DELETE") {
            \Model\ProductType::deleteById($id);
            return true;
        }

        $oProductType = \Model\ProductType::loadObj($id);
        if (!$oProductType) {
            \vd('not found!');
            \vd($data, '$data');
            $id = \Model\ProductType::insert($data);
            \vd($id);
        } else {
            \vd($oProductType);
            $oProductType->data = $data;
            $oProductType->save();
        }
        return true;
    }

    //处理工厂同步过来的订单数据
    function _deal_region($sid, $id, $data, $type)
    {
        // \vd($id,'deal id');
        // \vd($type,'deal type');
        // \vd($data,'deal data');

        $oRegion = \Model\Region::loadObj($id);
        if (!$oRegion) {
            \vd('not found!');
            \vd($data, '$data');
            $id = \Model\Order::insert($data);
            \vd($id);
        } else {
            \vd($oRegion);
            $oRegion->data = $data;
            $oRegion->save();
        }
        return true;
    }

    //处理工厂同步过来的订单数据
    function _deal_log_account($sid, $id, $data, $type)
    {
        // \vd($id,'deal id');
        // \vd($type,'deal type');
        // \vd($data,'deal data');

        $type = strtoupper($type);
        if ($type == "DELETE") {
            $res = \Model\LogAccount::deleteById($id);
            \vd($res);
        } elseif ($type == "INSERT") {
            \vd($data);
            $id = \Model\LogAccount::insert($data);
            \vd($id);
        } else {
            $oProduct = \Model\LogAccount::loadObj($id);
            $oProduct->data = $data;
            $oProduct->save();
        }

        return true;

    }









    ////////////////////////////////////// 把变动的数据同步到工厂

    //同步到客户端的方法
    function sync_client($id)
    {
        $oClient = \Model\Client::loadObj($id);
        $oSyncToLocal = new \Model\SyncToLocal;
        $oSyncToLocal->data['key'] = 'client:' . $id;
        $_data = $oClient->data;
        $data = [
            'wxid' => $_data['wxid'],
            'password' => $_data['password'],
            'change' => $_data['change'],
        ];
        $oSyncToLocal->data['data'] = \en($data);
        $oSyncToLocal->save();

        //清除change字段的值
        $oClient->data["change"] = 0;
        $oClient->save();
    }

    //转储变动的某订单信息到sync表
    function sync_order($id, $type)
    {
        $oSyncToLocal = new \Model\SyncToLocal;
        $oSyncToLocal->data['key'] = 'order:' . $id;
        $oSyncToLocal->data['type'] = strtoupper($type);

        if (strtoupper($type) != "DELETE") {
            $oOrder = \Model\Order::loadObj($id);
            $oSyncToLocal->data['data'] = \en($oOrder->data);
        } else {
            $oSyncToLocal->data['data'] = "";
        }
        $oSyncToLocal->save();
    }

    //转储变动的某订单信息到sync表
    function sync_return_back($id, $type)
    {
        $oSyncToLocal = new \Model\SyncToLocal;
        $oSyncToLocal->data['key'] = 'return:' . $id;
        $oSyncToLocal->data['type'] = strtoupper($type);

        if (strtoupper($type) != "DELETE") {
            $o = \Model\ReturnBack::loadObj($id);
            $oSyncToLocal->data['data'] = \en($o->data);
        } else {
            $oSyncToLocal->data['data'] = "";
        }
        $oSyncToLocal->save();
    }


}