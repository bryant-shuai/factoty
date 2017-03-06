<?php

namespace Service;

use App\Service;
use Error\ErrorCode;
use Error\ErrorObject;
use Model\Client;

//仅允许1.查询,2.从工厂更新过来客户信息
class ClientService extends Service
{
    public $column = ["id", "wxid", "storename", "username", "area", "region", "address", "order", "phone", "deposit", "change", "create_at", "update_at", "delete_at", "deleted"];

    //查询
    public function query($where = [], $column = [])
    {
        if (!$column) {
            $column = $this->column;
        }
        $result = Client::query($column, $where);
        return $result;
    }

    //创建
    public function insert($data)
    {
        return Client::insert($data);
    }

    //更新
    public function update($where, $data)
    {
        return Client::update($where, $data);
    }

    //删除
    public function delete($where)
    {
        return Client::delete($where);
    }

    //根据微信号查询
    public function queryByWxid($wxid = "", $column = [])
    {
        if (!$column) {
            $column = $this->column;
        }
        $where = ["wxid" => $wxid];

        $clients = Client::query($column, $where);

        if (empty($clients)) {
            return [];
        } else {
            $client = $clients[0];
            $_SESSION["CLIENT"] = $client;
            return $client;
        }
    }

    //登陆
    public function login($data)
    {
        //微信号未绑定,查询客户信息
        $oClient = Client::loadObj($data["username"], "username");
        if (!$oClient) {
            throw new ErrorObject(ErrorCode::CLIENT_NOT_FIND);
        } elseif ($oClient->data["password"] != $data["password"]) {
            throw new ErrorObject(ErrorCode::CLIENT_PASSWORD_WRONG);
        }

        $clientdata = $oClient->data;
        unset($clientdata["password"]);
        $_SESSION["CLIENT"] = $clientdata;
        return $clientdata;
    }

    //登陆
    public function fakelogin($data)
    {
        //微信号未绑定,查询客户信息
        $oClient = Client::loadObj($data["username"], "username");
        if (!$oClient) {
            throw new ErrorObject(ErrorCode::CLIENT_NOT_FIND);
        }

        $clientdata = $oClient->data;
        unset($clientdata["password"]);
        $_SESSION["CLIENT"] = $clientdata;
        return $clientdata;
    }

    //登出
    public function logout()
    {
        unset($_SESSION["CLIENT"]);
        return true;
    }

    //绑定客户
    public function bindClient($username, $password, $wxid)
    {
        //先查询微信号是否绑定
        $where = ['wxid' => $wxid];
        $clients = Client::query($this->column, $where);
        if ($clients && is_array($clients)) {
            //微信号已经绑定
            throw new ErrorObject(ErrorCode::CLIENT_WX_BOUND);
        } else {
            //微信号未绑定,查询客户信息
            $where = [
                "AND" => [
                    "username" => $username,
                    "password" => $password,
                ],
            ];
            $clients = Client::query($this->column, $where);
            //发现客户
            $client = $clients[0];

            if ($client["wxid"]) {
                //已经绑定了微信号
                throw new ErrorObject(ErrorCode::CLIENT_BOUND);
            } else {
                //未绑定微信号,则进行绑定
                $data = ["wxid" => $wxid];
                $result = Client::update($where, $data);
                return !!$result;
            }
        }
    }

    //修改密码
    public function resetpwd($pwds)
    {
        //取ID
        $client_id = $_SESSION["CLIENT"]['id'];
        $where = [
            "id" => $client_id,
        ];
        $result = $this->query($where, "*");
        if (!$result || $result[0]["password"] != $pwds["oldpwd"]) {
            throw new ErrorObject(ErrorCode::CLIENT_PASSWORD_WRONG);
        }
        $data = [
            "password" => $pwds["newpwd"],
            'update_at' => time(),
        ];
        $result = $this->update($where, $data);
        return !!$result;
    }

}
