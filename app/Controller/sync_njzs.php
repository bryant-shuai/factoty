<?php
//
//namespace Controller;
//
//use App\Controller;
//use Error\ErrorCode;
//use Error\ErrorObject;
//
//class sync_njzs extends Controller
//{
//    //同步密码
//    public function sync_pass()
//    {
//        //检测提交方式
//        $this->verifyPost();
//        //检测并提取提交数据
//        $para = $this->fetchData("POST", ["newpass", "oldpass"], true);
//
//        //本地修改同步密码
//        $where = ["newpass" => $para["oldpass"]];
//        $result = $this->di["SyncPassService"]->update($where, $para);
//        $this->data($result);
//    }
//
//    //从云端更新本地客户信息
//    public function query_clients()
//    {
//        //检测提交方式
//        $this->verifyPost();
//        //检测并提取提交数据
//        $para = $this->fetchData("POST", ["syncpass"], true);
//
//        //验证同步密码
//        $sync_pass = $this->di["SyncPassService"]->query();
//        if (!$sync_pass) {
//            throw new ErrorObject(ErrorCode::SYNC_PASS_NOT_FIND);
//        } elseif ($para["syncpass"] != $sync_pass[0]["newpass"]) {
//            throw new ErrorObject(ErrorCode::SYNC_PASS_ERROR);
//        } else {
//            //查询数据
//            $column = ["id", "wxid", "storename", "deposit", "change"];
//            $clients = $this->di["ClientService"]->query([], $column);
//            if (!$clients) {
//                $clients = [];
//            } else {
//                //清空余额变动
//                $data = ["change" => 0];
//                $this->di["ClientService"]->update([], $data);
//            }
//
//            //返回信息
//            $this->data($clients);
//        }
//    }
//
//    //同步本地客户信息到云端
//    public function update_clients()
//    {
//        //检测提交方式
//        $this->verifyPost();
//        //检测并提取提交数据
//        $para = $this->fetchData("POST", ["syncpass", "data"], true);
//
//        //验证同步密码
//        $sync_pass = $this->di["SyncPassService"]->query();
//        if (!$sync_pass) {
//            throw new ErrorObject(ErrorCode::SYNC_PASS_NOT_FIND);
//        } elseif ($para["syncpass"] != $sync_pass[0]["newpass"]) {
//            throw new ErrorObject(ErrorCode::SYNC_PASS_ERROR);
//        } else {
//            $data = $para["data"];
//            $clients = $data["clients"];
//            $region = $data["region"];
//
//            //删除所有客户数据
//            $result = $this->di["ClientService"]->delete([]);
//
//            //创建客户数据
//            $updated_rows = 0;
//            $fail = [];
//            foreach ($clients as $key => $client) {
//                $client["change"] = 0;
//                $result = $this->di["ClientService"]->insert($client);
//                if ($result) {
//                    $updated_rows++;
//                } else {
//                    $fail[] = $client;
//                }
//            }
//
//            //删除并新建所有产品分类数据
//            $result = $this->di["RegionService"]->delete([]);
//            foreach ($region as $key => $value) {
//                $result = $this->di["RegionService"]->insert($value);
//            }
//
//            $this->data(["all_rows" => count($clients), "updated_rows" => $updated_rows, "fail" => $fail]);
//        }
//    }
//
////    //从云端更新本地产品信息
////    public function query_products()
////    {
////        //检测提交方式
////        $this->verifyPost();
////        //检测并提取提交数据
////        $para = $this->fetchData("POST", ["syncpass"], true);
////
////        //验证同步密码
////        $sync_pass = $this->di["SyncPassService"]->query();
////        if (!$sync_pass) {
////            throw new ErrorObject(ErrorCode::SYNC_PASS_NOT_FIND);
////        } elseif ($para["syncpass"] != $sync_pass[0]["newpass"]) {
////            throw new ErrorObject(ErrorCode::SYNC_PASS_ERROR);
////        } else {
////            //查询数据并返回
////            $products = $this->di["ProductService"]->query();
////            if (!$products) {
////                $products = [];
////            }
////            $this->data($products);
////        }
////    }
//
//    //同步本地产品信息到云端
//    public function update_products()
//    {
//        //检测提交方式
//        $this->verifyPost();
//        //检测并提取提交数据
//        $para = $this->fetchData("POST", ["syncpass", "data"], true);
//
//        //验证同步密码
//        $sync_pass = $this->di["SyncPassService"]->query();
//
//        if (!$sync_pass) {
//            throw new ErrorObject(ErrorCode::SYNC_PASS_NOT_FIND);
//        } elseif ($para["syncpass"] != $sync_pass[0]["newpass"]) {
//            throw new ErrorObject(ErrorCode::SYNC_PASS_ERROR);
//
//        } else {
//            $data = $para["data"];
//            $products = $data["products"];
//            $products_type = $data["products_type"];
//            $region_prices = $data["region_prices"];
//
//            //删除所有产品数据
//            $result = $this->di["ProductService"]->delete([]);
//
//            //重新创建产品数据
//            $updated_rows = 0;
//            $fail = [];
//            foreach ($products as $key => $product) {
//                $product["amount"] = 0;
//                $result = $this->di["ProductService"]->insert($product);
//                if ($result) {
//                    $updated_rows++;
//                } else {
//                    $fail[] = $product;
//                }
//            }
//
//            //删除并新建所有产品分类数据
//            $result = $this->di["ProductTypeService"]->delete([]);
//            foreach ($products_type as $key => $type) {
//                $result = $this->di["ProductTypeService"]->insert($type);
//            }
//
//            //删除并新建所有产品价格数据
//            $result = $this->di["ProductPriceService"]->delete([]);
//            if ($region_prices) {
//                foreach ($region_prices as $key => $price) {
//                    $result = $this->di["ProductPriceService"]->insert($price);
//                }
//            }
//
//            $this->data(["all_rows" => count($products), "updated_rows" => $updated_rows, "fail" => $fail]);
//        }
//    }
//
//    //从云端更新本地订单信息
//    public function query_orders()
//    {
//        //检测提交方式
//        $this->verifyPost();
//        //检测并提取提交数据
//        $para = $this->fetchData("POST", ["syncpass", "thedate"], true);
//
//        //验证同步密码
//        $sync_pass = $this->di["SyncPassService"]->query();
//        if (!$sync_pass) {
//            throw new ErrorObject(ErrorCode::SYNC_PASS_NOT_FIND);
//        } elseif ($para["syncpass"] != $sync_pass[0]["newpass"]) {
//            throw new ErrorObject(ErrorCode::SYNC_PASS_ERROR);
//        } else {
//            //查询数据并返回
//            //时间限制,对工厂拉取订单修改订单时间进行限制
//            //08:00 ~ 12:00
//            $time1 = strtotime("8:00:00");
//            $time2 = strtotime("12:00:00");
//
//            $now = time();
//            if ($time1 < $now && $now < $time2) {
//                //throw new ErrorObject(ErrorCode::ORDER_CANNOT_SYNC);
//                //return;
//            }
//
//            $where = ["thedate" => $para["thedate"]];
//            $column = ["client_id", "storename", "product_id", "product_name", "price", "sign", "need_amount", "thedate"];
//            $orders = $this->di["OrderService"]->query($where, $column);
//            if (!$orders) {
//                $orders = [];
//            }
//            $this->data($orders);
//        }
//    }
//
//    //同步本地订单信息到云端
//    public function update_orders()
//    {
//        //检测提交方式
//        $this->verifyPost();
//        //检测并提取提交数据
//        $para = $this->fetchData("POST", ["syncpass", "data", "thedate"], true);
//
//        //验证同步密码
//        $sync_pass = $this->di["SyncPassService"]->query();
//        if (!$sync_pass) {
//            throw new ErrorObject(ErrorCode::SYNC_PASS_NOT_FIND);
//        } elseif ($para["syncpass"] != $sync_pass[0]["newpass"]) {
//            throw new ErrorObject(ErrorCode::SYNC_PASS_ERROR);
//        } else {
//            //时间限制,对工厂拉取订单修改订单时间进行限制
//            //08:00 ~ 12:00
//            $time1 = strtotime("8:00:00");
//            $time2 = strtotime("12:00:00");
//
//            $now = time();
//            if ($time1 < $now && $now < $time2) {
//                //throw new ErrorObject(ErrorCode::ORDER_CANNOT_SYNC);
//                //return;
//            }
//            $orders = $para["data"];
//            //更新当天数据
//            $updated_rows = 0;
//            $inserted_rows = 0;
//            $fail = [];
//            $result = $this->di["OrderService"]->delete(["thedate" => $para["thedate"]]);
//            foreach ($orders as $key => $order) {
//                //新建
//                $result = $this->di["OrderService"]->insert($order);
//                if ($result) {
//                    $updated_rows++;
//                } else {
//                    $fail[] = $order;
//                }
//            }
//
//            $this->data([
//                "all_rows" => count($orders),
//                "updated_rows" => $updated_rows,
//                "inserted_rows" => $inserted_rows,
//                "fail" => $fail
//            ]);
//        }
//    }
//}
