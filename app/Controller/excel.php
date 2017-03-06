<?php

namespace Controller;

use App\Controller;

class excel extends Controller
{
    //导出订单
    public function order_export()
    {
        $para = $this->fetchData("GET", ["fromdate", "todate"], true);
        $areas = isset($_GET["areas"]) ? strtoupper($_GET["areas"]) : "";
        $print = isset($_GET["for"]) && strtoupper($_GET["for"]) == "PRINT" ? true : false;

        $result = $this->di["ExcelService"]->order_export($para, $areas, "need_amount", $print);
        return;
    }

    //订单分区汇总导出
    public function area_statistics_export()
    {
        $dates = $this->fetchData("GET", ["fromdate", "todate"], false);

        $result = $this->di["ExcelService"]->area_statistics_export($dates);
        return;
    }

}