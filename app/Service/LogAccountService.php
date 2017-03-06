<?php

namespace Service;

use App\Service;
use Model\LogAccount;
use Tools\Tools;

//仅允许1.查询,2.从工厂同步过来信息
class LogAccountService extends Service
{
    public $column = ["id", "client_id", "code", "amount", "operator", "create_at", "update_at", "delete_at", "deleted", "extra", "manual"];

    //查询产品信息
    public function query($where = [], $column = [])
    {
        if (!$column) {
            $column = $this->column;
        }
        return LogAccount::query($column, $where);
    }

    //创建
    public function insert($data)
    {
        return LogAccount::insert($data);
    }

    //更新
    public function update($where, $data)
    {
        return LogAccount::update($where, $data);
    }

    //删除
    public function delete($where)
    {
        return LogAccount::delete($where);
    }

}
