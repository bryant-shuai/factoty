<?php

namespace Service;

use App\Service;
use Model\Region;

class RegionService extends Service
{
    public $column = ["id", "region_id", "type"];

    /* ************
     * 基本业务方法 *query,insert,update,delete
     * ************/
    public function query($where = [], $column = [])
    {
        if (!$column) {
            $column = $this->column;
        }
        return Region::query($column, $where);
    }

    public function insert($data)
    {
        return Region::insert($data);
    }

    public function update($where, $data)
    {
        return Region::update($where, $data);
    }

    public function delete($where = [])
    {
        return Region::delete($where);
    }

}