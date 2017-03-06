<?php

namespace Error;

/**
 * Class LogType
 * @package Config
 * 用于向UI层返回状态码
 * 注意分区,每个区建议100个
 */
class ErrorCode
{
    //常用区
    const NO_ERROR = 0;                         //请求成功
    const UNKNOWN_ERROR = -1;                   //未知错误
    const REQUEST_METHOD = 1;                   //请求方法错误
    const PARAMETER_ERROR = 2;                  //请求参数错误
    const PERMISSION_DENY = 3;                  //当前没有权限
    const NOT_BIND_CLIENT = 4;                  //当前没有权限
    const CHARGE_FAIL = 5;                      //充值失败
    const CREATE_CHARGE_FAIL = 6;               //创建充值订单失败
    const ORDER_NOT_IN_TIME = 7;                //订单接收时间为每天的早00:00:00到中午12:00:00
    const ORDER_CANNOT_SYNC = 8;                //订单正在接收中,暂不允许同步

    //数据库操作状态
    const QUERY_FAIL = 100;                     //查询失败
    const INSERT_FAIL = 101;                    //插入失败
    const UPDATE_FAIL = 102;                    //更新失败
    const DELETE_FAIL = 103;                    //删除失败
    const QUERY_NO_DATA = 104;                  //没有查询到数据

    //Client系
    const CLIENT_QUERY_FAIL = 1000;             //客户查询失败
    const CLIENT_INSERT_FAIL = 1001;            //客户插入失败
    const CLIENT_UPDATE_FAIL = 1002;            //客户更新失败
    const CLIENT_DELETE_FAIL = 1003;            //客户删除失败
    const CLIENT_NOT_LOGIN = 1004;              //客户未登录
    const CLIENT_DUPLICATE = 1005;              //客户名字或者店铺名字重复
    const CLIENT_LOGIN_FAIL = 1006;             //客户登录失败
    const CLIENT_NOT_FIND = 1007;               //客户未查到
    const CLIENT_WX_BOUND = 1008;               //客户微信已经绑定
    const CLIENT_BOUND = 1009;                  //客户已经绑定
    const CLIENT_BOUND_FAIL = 1010;             //客户绑定失败
    const CLIENT_DEPOSIT_NOT_ENOUGH  = 1011;    //客户押金不足
    const CLIENT_PASSWORD_WRONG  = 1012;        //密码输入错误

    //Order系
    const ORDER_QUERY_FAIL = 2000;              //订单查询失败
    const ORDER_INSERT_FAIL = 2001;             //订单插入失败
    const ORDER_UPDATE_FAIL = 2002;             //订单更新失败
    const ORDER_DELETE_FAIL = 2003;             //订单删除失败
    const ORDER_STATISTICS_FAIL = 2004;         //订单统计失败
    const ORDER_NOT_FIND = 2005;                //订单未查到

    //Product系
    const PRODUCT_QUERY_FAIL = 3000;            //产品查询失败
    const PRODUCT_INSERT_FAIL = 3001;           //产品插入失败
    const PRODUCT_UPDATE_FAIL = 3002;           //产品更新失败
    const PRODUCT_DELETE_FAIL = 3003;           //产品删除失败
    const PRODUCT_NOT_FIND = 3004;              //产品未查到
    const PRODUCT_DUPLICATE = 3005;             //产品名字重复

    //Wx微信接入
    const NO_OAUTH2_CODE = 4000;                //没有找到微信OAuth2.0授权码

    //Sync系
    const SYNC_PASS_FAIL = 13000;               //同步密码更新失败
    const SYNC_PASS_NOT_FIND = 13001;           //未查询到同步密码
    const SYNC_PULL_CLIENTS_FAIL = 13002;       //从云端更新本地客户信息失败
    const SYNC_PUSH_CLIENTS_FAIL = 13003;       //同步本地客户信息到云端失败
    const SYNC_PULL_PRODUCTS_FAIL = 13004;      //从云端更新本地产品信息失败
    const SYNC_PUSH_PRODUCTS_FAIL = 13005;      //同步本地产品信息到云端失败
    const SYNC_PULL_ORDERS_FAIL = 13006;        //从云端更新本地订单信息失败
    const SYNC_PUSH_ORDERS_FAIL = 13007;        //同步本地订单信息到云端失败
    const SYNC_BACKUP_REMOTE_FAIL = 13008;      //备份云端数据库失败
    const SYNC_BACKUP_LOCAL_FAIL = 13009;       //备份本地数据库失败
    const SYNC_PASS_ERROR = 13010;              //同步密码错误
    

    const MSG = [
        //常用区
        self::NO_ERROR => '请求成功',
        self::UNKNOWN_ERROR => '未知错误',
        self::REQUEST_METHOD => '请求方法错误',
        self::PARAMETER_ERROR => '请求参数错误',
        self::PERMISSION_DENY => '当前没有权限',
        self::NOT_BIND_CLIENT => '未绑定客户',
        self::CHARGE_FAIL => '充值失败',
        self::CREATE_CHARGE_FAIL => '创建充值订单失败',
        self::ORDER_NOT_IN_TIME => '订单接收时间为每天的早00:00:00到中午12:00:00',
        self::ORDER_CANNOT_SYNC => '订单正在接收中,暂不允许同步',

        //数据库操作状态
        self::QUERY_FAIL => '查询失败',
        self::INSERT_FAIL => '插入失败',
        self::UPDATE_FAIL => '更新失败',
        self::DELETE_FAIL => '删除失败',
        self::QUERY_NO_DATA => '没有查询到数据',

        //Client系
        self::CLIENT_QUERY_FAIL => '客户查询失败',
        self::CLIENT_INSERT_FAIL => '客户插入失败',
        self::CLIENT_UPDATE_FAIL => '客户更新失败',
        self::CLIENT_DELETE_FAIL => '客户删除失败',
        self::CLIENT_NOT_LOGIN => '客户未登录',
        self::CLIENT_DUPLICATE => '客户名字或者店铺名字重复',
        self::CLIENT_LOGIN_FAIL => '客户登录失败',
        self::CLIENT_NOT_FIND => '客户未查到',
        self::CLIENT_WX_BOUND => '客户微信已经绑定',
        self::CLIENT_BOUND => '客户已经绑定',
        self::CLIENT_BOUND_FAIL => '客户绑定失败',
        self::CLIENT_DEPOSIT_NOT_ENOUGH => '客户押金不足',
        self::CLIENT_PASSWORD_WRONG => '密码输入错误',

        //Order系
        self::ORDER_QUERY_FAIL => '订单查询失败',
        self::ORDER_INSERT_FAIL => '订单插入失败',
        self::ORDER_UPDATE_FAIL => '订单更新失败',
        self::ORDER_DELETE_FAIL => '订单删除失败',
        self::ORDER_NOT_FIND => '订单未查到',

        //Product系
        self::PRODUCT_QUERY_FAIL => '产品查询失败',
        self::PRODUCT_INSERT_FAIL => '产品插入失败',
        self::PRODUCT_UPDATE_FAIL => '产品更新失败',
        self::PRODUCT_DELETE_FAIL => '产品删除失败',
        self::PRODUCT_NOT_FIND => '产品未查到',
        self::PRODUCT_DUPLICATE => '产品名字重复',

        //Wx微信接入
        self::NO_OAUTH2_CODE => '请使用微信打开并授权本登录本网站',

        //Sync系
        self::SYNC_PASS_FAIL  => '同步密码更新失败',
        self::SYNC_PASS_NOT_FIND  => '未查询到同步密码',
        self::SYNC_PULL_CLIENTS_FAIL  => '从云端更新本地客户信息失败',
        self::SYNC_PUSH_CLIENTS_FAIL  => '同步本地客户信息到云端失败',
        self::SYNC_PULL_PRODUCTS_FAIL  => '从云端更新本地产品信息失败',
        self::SYNC_PUSH_PRODUCTS_FAIL  => '同步本地产品信息到云端失败',
        self::SYNC_PULL_ORDERS_FAIL  => '从云端更新本地订单信息失败',
        self::SYNC_PUSH_ORDERS_FAIL  => '同步本地订单信息到云端失败',
        self::SYNC_BACKUP_REMOTE_FAIL  => '备份云端数据库失败',
        self::SYNC_BACKUP_LOCAL_FAIL  => '备份本地数据库失败',
        self::SYNC_PASS_ERROR  => '同步密码错误',

    ];
}

Class ErrorObject extends \Exception
{
    public function __construct($code = ErrorCode::NO_ERROR, $message = '')
    {
        //message
        if ($message) {
            $this->message = $message;
        } elseif (array_key_exists($code, ErrorCode::MSG)) {
            $this->message = ErrorCode::MSG[$code];
        } else {
            $this->message = ErrorCode::MSG[ErrorCode::UNKNOWN_ERROR];
        }

        //code
        if (array_key_exists($code, ErrorCode::MSG)) {
            $this->code = $code;
        } else {
            $this->message = ErrorCode::UNKNOWN_ERROR;
        }
    }
}
