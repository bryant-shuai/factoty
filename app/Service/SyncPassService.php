<?php

namespace Service;

use App\Service;
use Model\SyncPass;

//仅允许1.查询,2.创建,3.更新,4.删除,5.工厂拉取订单数据,6.从工厂更新过来订单信息
class SyncPassService extends Service
{
    public $column = ["id", "newpass", "oldpass"];

    public function query($where = [], $column = [])
    {
        if (!$column) {
            $column = $this->column;
        }
        return SyncPass::query($column, $where);
    }

    public function update($where, $data)
    {
        return SyncPass::update($where, $data);
    }

}
