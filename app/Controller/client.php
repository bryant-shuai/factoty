<?php
//
//namespace Controller;
//
//use app\Controller;
//use Error\ErrorCode;
//
//class client extends Controller
//{
//    //修改密码的方法
//    public function resetpwd()
//    {
//        //获取提交数据
//        $para = $this->fetchData("POST",["oldpwd","newpwd"],false);
//
//        if (count($para) == 2) {
//            //更新数据库
//            $result = $this->di['ClientService']->resetpwd($para);
//            $this->para["result"] = $result;
//        }
//        $this->view = "resetpwd";
//    }
//
//    //解除绑定的方法
//    public function unbind()
//    {
//        //$para = $this->fetchData("post",["id"],true);
//        $where = ["id" => $_SESSION["CLIENT"]['id']];
//
//        //给函数调用处参数赋值
//        $data = [
//            "wxid" => "",
//            'update_at' => time(),
//        ];
//        //执行更新,直接给wxid赋值为空字符串
//        $result = $this->di["ClientService"]->update($where,$data);
//        $this->data($result);
//    }
//
//}
