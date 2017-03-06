<?php

namespace Service;

error_reporting(E_ALL);
set_include_path(get_include_path() . PATH_SEPARATOR . __BASE_DIR__ . "/app/Lib/");

/** PHPExcel */
require_once __BASE_DIR__ . "/app/Lib/PHPExcel.php";
/** PHPExcel_Reader_IReader */
require_once __BASE_DIR__ . "/app/Lib/PHPExcel/Reader/Excel2007.php";
require_once __BASE_DIR__ . "/app/Lib/PHPExcel/Reader/Excel5.php";
require_once __BASE_DIR__ . "/app/Lib/PHPExcel/IOFactory.php";

use App\Model;
use App\Service;
use Error\ErrorCode;
use Error\ErrorObject;
use Tools\Tools;

use PHPExcel;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;

class ExcelService extends Service
{
    //查询订单/分捡数据
    public function order_export($dates, $area = '', $type = "send_amount", $print = false)
    {
        $message = "";

        $fromdate = date("Y/m/d");
        if (isset($dates["fromdate"])) {
            $fromdate = $dates["fromdate"];
        }
        $todate = date("Y/m/d", time() + 86400);
        if (isset($dates["todate"])) {
            $todate = date("Y/m/d", strtotime($dates["todate"]) + 86400);
        }

        if ($area) {
            $area = explode(",", $area);
        } else {
            $area = [];
        }

        //1.查询客户信息
        $column = ['id', 'storename', 'area', 'order'];
        $cwhere = ["deleted" => 0];
        if ($area) {
            $cwhere["area"] = $area;
            $cwhere = ["AND" => $cwhere];
        }
        $cwhere["ORDER"] = [
            "area ASC",
            "order ASC",
        ];

        $clients = $this->di["ClientService"]->query($cwhere, $column);
        if (!$clients) {
            $message .= "没有查询到客户信息。";
        } else {
            $clients = Tools::indexArray($clients, "id");
        }
        //客户的id
        $client_ids = array_keys($clients);
        $client_ids_str = implode(",", $client_ids);

        //2.查询商品信息,所有
        $pwhere = ["deleted" => 0];
        $products = $this->di["ProductService"]->query($pwhere);
        if (!$products) {
            $message .= "没有查询到商品信息。";
        } else {
            $products = Tools::indexArray($products, "id");
        }

        //3.查询指定日期已确认订单信息
        $subwhere = $client_ids_str ? "AND `client_id` IN (" . $client_ids_str . ") " : "";
        $orders = Model::sqlExec("SELECT `client_id`,`storename`,`product_id`,`product_name`,`price`,SUM(`need_amount`) AS `need_amount`,SUM(`send_amount`) AS `send_amount`,SUM(`get_amount`) AS `get_amount` FROM `njzs_order` WHERE `thedate` >= '" . $fromdate . "' AND `thedate` < '" . $todate . "' AND `deleted` = 0 " . ($subwhere) . "GROUP BY `client_id`,`storename`,`product_id`,`product_name`,`price`;");
        // $orders = Model::sqlQuery("SELECT `client_id`,`storename`,`product_id`,`product_name`,`price`,SUM(`need_amount`) AS `need_amount`,SUM(`send_amount`) AS `send_amount`,SUM(`get_amount`) AS `get_amount` FROM `njzs_order` WHERE `thedate` >= '2016/08/05' AND `thedate` < '2016/08/06' AND `deleted` = 0 AND `client_id` IN (1,2,3) GROUP BY `client_id`,`storename`,`product_id`,`product_name`,`price`;");
        if (!$orders) {
            $message .= "没有查询到订单信息。";
        }
        // \vd($orders,'ordersordersorders');

        if ($message) {
            throw new ErrorObject(ErrorCode::QUERY_NO_DATA, $message);
        }
        $this->generateOrderExcel($clients, $products, $orders, $dates, $type, $print);
    }

    //生成订单/分捡数据表
    private function generateOrderExcel($clients, $products, $orders, $dates, $type = "send_amount", $print = false)
    {
        $fileNamePerfix = $type == "send_amount" ? "调拨出库单" : "订单";

        //对订单进行二级排序
        $sorted_orders = Tools::indexSet($orders, "client_id");
        foreach ($sorted_orders as $key => $sorted_order) {
            $sorted_orders[$key] = Tools::indexArray($sorted_order, "product_id");
        }

        //生成Excel
        $phpExcel = new PHPExcel();
        $firstSheetTitle = $fileNamePerfix;

        $phpExcel->setActiveSheetIndex(0);
        $worksheet = $phpExcel->getActiveSheet();
        $worksheet->setTitle($firstSheetTitle);

        $clientStartCol = 4;
        $clientStartRow = 2;
        $clientCount = count($clients);
        //产品数据开始位置
        $productStartCol = 0;
        $productStartRow = 3;
        $productCount = count($products);
        $productEndRow = $productStartRow + $productCount - 1;
        //订单数据开始位置
        $orderStartCol = $clientStartCol;
        $orderStartRow = $productStartRow;
        $orderCount = count($orders);

        //基本设置
        $worksheet->getDefaultRowDimension()->setRowHeight(14);
        $worksheet->getDefaultColumnDimension()->setWidth(14);
        $worksheet->getDefaultStyle()->getFont()->setSize(10);

        //设置日期单元格
        $worksheet->setCellValue('A1', "订单日期");
        $worksheet->mergeCells('B1:C1');
        $worksheet->setCellValue('B1', $dates["fromdate"] . "-" . $dates["todate"]);

        //左上写入表头
        $worksheet->setCellValue('A2', "商品编号");
        $worksheet->setCellValue('B2', "商品名称");
        $worksheet->setCellValue('C2', "单位");
        $worksheet->setCellValue('D2', "商品合计");

        //写入商品列头,合计
        $col = $productStartCol;
        $row = $productStartRow;
        foreach ($products as $product) {
            $worksheet->getCellByColumnAndRow($col, $row)->setValue($product["id"]);
            $worksheet->getCellByColumnAndRow($col + 1, $row)->setValue($product["name"]);
            $worksheet->getCellByColumnAndRow($col + 2, $row)->setValue($product["unit"]);
            $_start = PHPExcel_Cell::stringFromColumnIndex($clientStartCol) . '' . $row;
            $_end = PHPExcel_Cell::stringFromColumnIndex($clientStartCol + $clientCount - 1) . '' . $row;
            $worksheet->getCellByColumnAndRow($col + 3, $row++)->setValue("=SUM($_start:$_end)");
        }

        //写入店铺行头及纵向统计数据:总表统计数据已注释
        $col = $clientStartCol;
        $row = $clientStartRow;
//        $worksheet->getCellByColumnAndRow(0, $productEndRow + 1)->setValue("总计");
//        $_start = PHPExcel_Cell::stringFromColumnIndex($clientStartCol-1) . "" . $productStartRow;
//        $_end = PHPExcel_Cell::stringFromColumnIndex($clientStartCol-1) . "" . $productEndRow;
//        $worksheet->getCellByColumnAndRow($clientStartCol-1, $productEndRow + 1)->setValue("=SUM($_start:$_end)");
        foreach ($clients as $client) {
            $worksheet->getCellByColumnAndRow($col, $row)->setValue($client["storename"] . "(" . $client["id"] . ")");
//            $_start = PHPExcel_Cell::stringFromColumnIndex($col) . '' . $productEndRow;
//            $_end = PHPExcel_Cell::stringFromColumnIndex($col) . '' . $productEndRow;
//            $worksheet->getCellByColumnAndRow($col, $productEndRow + 1)->setValue("=SUM($_start:$_end)");
            $col++;
        }

        //写入订单分拣信息
        if ($orders) {
            foreach ($orders as $order) {
                \vd($order);
                $cpos = array_keys(array_keys($clients), $order["client_id"])[0];
                $ppos = array_keys(array_keys($products), $order["product_id"])[0];
                $col = $orderStartCol + $cpos;
                $row = $orderStartRow + $ppos;
                $worksheet->getCellByColumnAndRow($col, $row)->setValue($order[$type]);
            }
        } else {
            $worksheet->getCellByColumnAndRow(2, 3)->setValue("没有分拣数据");
        }

        //根据不同文件生成不同表
        if ($print == true) {
            //循环添加订单数据
            $currsheet = $worksheet;
            $clientIndex = 0;
            $_pcurrrow = $productStartRow + $productCount;

            foreach ($clients as $key => $client) {
                $_header = true;    //是否需要写表头
                $_fromrow = $productStartRow;

                //当前客户分表订单数据开始/结束行
                $_currclientstartrow = $_pcurrrow + 9;
                $_currclientendrow = 0;

                foreach ($products as $_key => $product) {
                    $value = $worksheet->getCellByColumnAndRow(4 + $clientIndex, $_fromrow)->getValue();
                    //如果值不为空,不为0
                    if ($value && floatval($value) != 0) {

                        //如果需要写入表头,则进行表头写入
                        if ($_header) {
                            $_header = false;

                            //写入表头
                            $_pcurrrow += 5;
                            $currsheet->mergeCells("A" . ($_pcurrrow + 0) . ":F" . ($_pcurrrow + 0));
                            $currsheet->setCellValue("A" . ($_pcurrrow + 0), $fileNamePerfix);

                            $currsheet->mergeCells("A" . ($_pcurrrow + 1) . ":B" . ($_pcurrrow + 1));
                            $currsheet->setCellValue("A" . ($_pcurrrow + 1), "出库门店/仓库：工厂");
                            $currsheet->setCellValue("E" . ($_pcurrrow + 1), "订单日期");
                            $currsheet->setCellValue("F" . ($_pcurrrow + 1), $dates["fromdate"] . "-" . $dates["todate"]);

                            $currsheet->mergeCells("A" . ($_pcurrrow + 2) . ":B" . ($_pcurrrow + 2));
                            $currsheet->setCellValue("A" . ($_pcurrrow + 2), "进库门店/仓库：" . $client["storename"]);
                            $currsheet->mergeCells("C" . ($_pcurrrow + 2) . ":D" . ($_pcurrrow + 2));
                            $currsheet->setCellValue("C" . ($_pcurrrow + 2), "单号:-");
                            $currsheet->setCellValue("E" . ($_pcurrrow + 2), "经手人:");
                            $currsheet->setCellValue("F" . ($_pcurrrow + 2), "\\");
                            $currsheet->setCellValue("A" . ($_pcurrrow + 3), "商品编号");
                            $currsheet->setCellValue("B" . ($_pcurrrow + 3), "商品名称");
                            $currsheet->setCellValue("C" . ($_pcurrrow + 3), "单位");
                            $currsheet->setCellValue("D" . ($_pcurrrow + 3), "数量");
                            $currsheet->setCellValue("E" . ($_pcurrrow + 3), "调拨价");
                            $currsheet->setCellValue("F" . ($_pcurrrow + 3), "调拨金额");

                            $_pcurrrow += 4;
                        }

                        //产品编号
                        $_pid = $worksheet->getCellByColumnAndRow(0, $_fromrow)->getValue();
                        $currsheet->getCellByColumnAndRow(0, $_pcurrrow)->setValue($_pid);
                        //产品名字
                        $_pname = $worksheet->getCellByColumnAndRow(1, $_fromrow)->getValue();
                        $currsheet->getCellByColumnAndRow(1, $_pcurrrow)->setValue($_pname);
                        //产品单位
                        $_punit = $worksheet->getCellByColumnAndRow(2, $_fromrow)->getValue();
                        $currsheet->getCellByColumnAndRow(2, $_pcurrrow)->setValue($_punit);
                        //分拣数量
                        $_pamount = floatval($value);
                        $currsheet->getCellByColumnAndRow(3, $_pcurrrow)->setValue($_pamount);
                        //订单商品单价
                        $_price = $sorted_orders[$client["id"]][$_pid]["price"];
                        $currsheet->getCellByColumnAndRow(4, $_pcurrrow)->setValue($_price);
                        //分拣总价
                        //$totle = "=D" . $_pcurrrow . "*E" . $_pcurrrow;
                        $totle = floatval(number_format($_pamount * $_price, 2, ".", ""));
                        $currsheet->getCellByColumnAndRow(5, $_pcurrrow)->setValue($totle);
                        $_pcurrrow++;
                    }
                    $_fromrow++;
                }
                if (!$_header) {
                    //写入合计
                    $_currclientendrow = $_pcurrrow - 1;
                    //生成合计数据
                    $_start = PHPExcel_Cell::stringFromColumnIndex(5) . '' . $_currclientstartrow;
                    $_end = PHPExcel_Cell::stringFromColumnIndex(5) . '' . $_currclientendrow;

                    $currsheet->getCellByColumnAndRow(0, $_currclientendrow + 1)->setValue("合计");
                    $currsheet->getCellByColumnAndRow(5, $_currclientendrow + 1)->setValue("=SUM($_start:$_end)");
                }

                $clientIndex++;
            }

            $phpExcel->setActiveSheetIndex(0);
            //文件名
            $fileName = $fileNamePerfix . "." . $dates["fromdate"] . "-" . $dates["todate"] . "." . time() . ".xlsx";
            //文件写入句柄
            $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
            //生成文件
            //$filePathName = iconv("utf-8", "gb2312", self::$ExcelFilePath . $fileName);
            //$objWriter->save($filePathName);
            //直接下载
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');
            $objWriter->save("php://output");
            exit();

        } else {
//            //循环创建分工作表
//            $clientIndex = 0;
//
//            foreach ($clients as $key => $client) {
//                //error_log("\n\n\n\n\$clientIndex:$clientIndex:" . $client["storename"] . "\n\n", 3, "/var/log/php.njzs.log");
//
//                $currsheet = $phpExcel->createSheet();
//                $currsheet->setTitle($client["storename"] . "(" . $client["id"] . ")$firstSheetTitle");
//                $phpExcel->setActiveSheetIndex($clientIndex);
//
//                $currsheet->getDefaultRowDimension()->setRowHeight(14);
//                $currsheet->getDefaultColumnDimension()->setWidth(16);
//                $currsheet->getDefaultStyle()->getFont()->setSize(10);
//
//                //设置标题单元格
//                $currsheet->mergeCells('A1:F1');
//                $currsheet->setCellValue('A1', $fileNamePerfix);
//
//                //写入表头
//                $currsheet->mergeCells("A3:B3");
//                $currsheet->setCellValue('A3', "出库门店/仓库：工厂");
//                $currsheet->setCellValue('E3', "订单日期");
//                $currsheet->setCellValue('F3', $dates["fromdate"] . "-" . $dates["todate"]);
//
//                $currsheet->mergeCells("A4:B4");
//                $currsheet->setCellValue('A4', "进库门店/仓库：" . $client["storename"]);
//                $currsheet->mergeCells("C4:D4");
//                $currsheet->setCellValue('C4', "单号:-");
//                $currsheet->setCellValue('E4', "经手人:");
//                $currsheet->setCellValue('F4', "\\");
//                $currsheet->setCellValue('A5', "商品编号");
//                $currsheet->setCellValue('B5', "商品名称");
//                $currsheet->setCellValue('C5', "单位");
//                $currsheet->setCellValue('D5', "数量");
//                $currsheet->setCellValue('E5', "调拨价");
//                $currsheet->setCellValue('F5', "调拨金额");
//
//                //绑定数据
//                $_pcurrrow = 6;
//                $_fromrow = $productStartRow;
//
//                foreach ($products as $_key => $product) {
//                    $value = $worksheet->getCellByColumnAndRow(4 + $clientIndex, $_fromrow)->getValue();
//
//                    if ($value && floatval($value) != 0) {
//                        //产品编号
//                        $_pid = $worksheet->getCellByColumnAndRow(0, $_fromrow)->getValue();
//                        $currsheet->getCellByColumnAndRow(0, $_pcurrrow)->setValue($_pid);
//                        //产品名字
//                        $_pname = $worksheet->getCellByColumnAndRow(1, $_fromrow)->getValue();
//                        $currsheet->getCellByColumnAndRow(1, $_pcurrrow)->setValue($_pname);
//                        //产品单位
//                        $_punit = $worksheet->getCellByColumnAndRow(2, $_fromrow)->getValue();
//                        $currsheet->getCellByColumnAndRow(2, $_pcurrrow)->setValue($_punit);
//                        //分拣数量
//                        $_pamount = floatval($value);
//                        $currsheet->getCellByColumnAndRow(3, $_pcurrrow)->setValue($_pamount);
//                        //订单商品单价
//                        $_price = $sorted_orders[$client["id"]][$_pid]["price"];
//                        $currsheet->getCellByColumnAndRow(4, $_pcurrrow)->setValue($_price);
//                        //分拣总价
//                        //$totle = "=D" . $_pcurrrow . "*E" . $_pcurrrow;
//                        $totle = floatval(number_format($_pamount * $_price, 2, ".", ""));
//                        $currsheet->getCellByColumnAndRow(5, $_pcurrrow)->setValue($totle);
//                        $_pcurrrow++;
//                    }
//                    $_fromrow++;
//                }
//                $clientIndex++;
//            }

            $phpExcel->setActiveSheetIndex(0);
            //文件名
            $fileName = $fileNamePerfix . "." . $dates["fromdate"] . "-" . $dates["todate"] . "." . time() . ".xlsx";
            //文件写入句柄
            $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
            //生成文件
            //$filePathName = iconv("utf-8", "gb2312", self::$ExcelFilePath . $fileName);
            //$objWriter->save($filePathName);
            //直接下载
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');
            $objWriter->save("php://output");
            exit();
        }
    }

    //分区汇总
    public function area_statistics_export($dates)
    {
        $fromdate = date("Y/m/d");
        if (isset($dates["fromdate"])) {
            $fromdate = $dates["fromdate"];
        }
        $todate = date("Y/m/d", time() + 86400);
        if (isset($dates["todate"])) {
            $todate = date("Y/m/d", strtotime($dates["todate"]) + 86400);
        }
        //统计信息
        $statistics = Model::sqlExec("SELECT `njzs_order`.`product_id`, `njzs_client`.`area`, SUM(`njzs_order`.`need_amount`) need_amount from `njzs_order`, `njzs_client` where `njzs_client`.`id` = `njzs_order`.`client_id` AND `njzs_order`.`deleted` = 0 AND `njzs_order`.`thedate` >= '" . $fromdate . "' AND `njzs_order`.`thedate` < '" . $todate . "' GROUP BY `njzs_order`.`product_id`, `njzs_client`.`area` ORDER BY `njzs_order`.`product_id`, `njzs_client`.`area`;");
        $statistics = Tools::indexSet($statistics, "product_id");

        //产品信息
        $product = $this->di["ProductService"]->query();
        $product = Tools::indexArray($product, "id");

        //分区信息
        $areas = Model::sqlExec("SELECT DISTINCT area FROM njzs_client;");

        //信息处理
        $data = [];
        foreach ($statistics as $s_key => $s_value) {
            $tmp = [
                "product_id" => $s_key,
                "product_name" => $product[$s_key]["name"],
            ];
            foreach ($s_value as $r_key => $r_value) {
                $tmp[$r_value["area"]] = $r_value["need_amount"];
            }
            $data[] = $tmp;
        }

        //列处理
        $columns = [
            '产品ID' => 'product_id',
            '产品名字' => 'product_name',
        ];
        foreach ($areas as $key => $area) {
            $columns[$area["area"]] = $area["area"];
            $columns[$area["area"] . "(实分)"] = "";
        }

        $fromdate = str_replace("/", '', $fromdate);
        $todate = date("Ymd", strtotime($todate) - 86400);
        $params = [
            'columns' => $columns,
            'data' => $data,
            'filename' => "分区汇总$fromdate-$todate.xlsx",
            'title' => "分区汇总$fromdate-$todate"
        ];

        $this->exportExcel($params);
    }

    //通用导出
    public function exportExcel($params)
    {
        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);
        $worksheet = $phpExcel->getActiveSheet();
        $column_count = count($params['columns']);
        if (isset($params['title'])) {
            $worksheet->setTitle($params['title']);
            // excel标题
            $worksheet->mergeCells(PHPExcel_Cell::stringFromColumnIndex(0) . "1:" . PHPExcel_Cell::stringFromColumnIndex($column_count - 1) . "1");
            $worksheet->setCellValue('A1', $params['title']);
            $worksheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle('A1')->getFont()->setSize(16);
            $worksheet->getStyle('A1')->getFont()->setBold(true);
        }

        $column_idx = 0;

        $total_data_length = count($params['data']);
        foreach ($params['columns'] as $column_name => $column_en) {
            // 列标题
            $row_idx = 2;
            $cell = PHPExcel_Cell::stringFromColumnIndex($column_idx) . $row_idx;
            $worksheet->setCellValue($cell, $column_name);
            $worksheet->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle($cell)->getFont()->setSize(12);
            $worksheet->getStyle($cell)->getFont()->setBold(true);
            $worksheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($row_idx) . '')->setAutoSize(true);

            // 列数据
            if (count($params['data']) > 0) {
                foreach ($params['data'] as $row_data) {
                    $row_idx++;
                    if (!isset($row_data[$column_en])) {
                        continue;
                    }
                    $cell = PHPExcel_Cell::stringFromColumnIndex($column_idx) . $row_idx;
                    $worksheet->setCellValue($cell, $row_data[$column_en]);
                }
            }

            $column_idx++;
        }

        if (count($params['data']) == 0) {
            $worksheet->setCellValue('A4', "没有相关数据");
        }
        //文件名
        $fileName = $params['filename'];
        //文件写入句柄
        $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
        //生成文件
        //$filePathName = iconv("utf-8", "gb2312", self::$ExcelFilePath . $fileName);
        //$objWriter->save($filePathName);
        //直接下载
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $objWriter->save("php://output");
        exit();
    }
}
