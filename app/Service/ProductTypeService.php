<?php

namespace Service;

use App\Service;
use Model\ProductType;

class ProductTypeService extends Service
{
    public $column = ["id", "name"];

    public function query($where = [], $column = [])
    {
        if (!$column) {
            $column = $this->column;
        }
        return ProductType::query($column, $where);
    }

    public function insert($data)
    {
        return ProductType::insert($data);
    }

    public function update($where, $data)
    {
        return ProductType::update($where, $data);
    }

    public function delete($where = [])
    {
        return ProductType::delete($where);
    }

}
