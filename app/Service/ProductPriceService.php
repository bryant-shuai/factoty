<?php

namespace Service;

use App\Service;
use Model\ProductPrice;

class ProductPriceService extends Service
{
    public $column = ["id", "region_id", "product_name", "price"];

    public function query($where = [], $column = [])
    {
        if (!$column) {
            $column = $this->column;
        }
        return ProductPrice::query($column, $where);
    }

    public function insert($data)
    {
        return ProductPrice::insert($data);
    }

    public function update($where, $data)
    {
        return ProductPrice::update($where, $data);
    }

    public function delete($where = [])
    {
        return ProductPrice::delete($where);
    }

}
