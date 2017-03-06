<?php
namespace Service;

use App\Service;
use Wx\WxConfig;
use Tools\Tools;

class WechatService extends Service
{
    //获取access_token
    public function getAccessToken()
    {
        //1.从问价读取access_token
        $result = $this->readAccessToken();

        //1.1.判断access_token的有效性,有效则返回access_token
        if ($result && $result["deadline"] > time()) {
            return $result["access_token"];
        }

        //2.请求access_token
        $AppID = WxConfig::$conf["appid"];
        $AppSecret = WxConfig::$conf["appsecret"];
        //$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$AppID&secret=$AppSecret";
        $url = WxConfig::$conf["token_url"];
        $data = [
            "grant_type" => "client_credential",
            "appid" => $AppID,
            "secret" => $AppSecret,
        ];


        //2.1.循环5次进行请求access_token,简单防止请求失败
        $times = 5;
        while ($times--) {
            $result = Tools::sendGet($url, $data);
            $result = json_decode($result, true);
            if (!isset($result["errcode"]) && isset($result["access_token"])) {
                //请求成功,存储access_token
                $this->saveAccessToken($result);

                //返回access_token
                return $result["access_token"];
            }
        }
        return false;
    }

    //存储access_token到文件
    private function saveAccessToken($access_token = [])
    {
        //access_token文件位置
        $filename = WxConfig::$conf["token_file"];
        //access_token本地失效提前时间
        $before_deadline = WxConfig::$conf["before_deadline"];

        //存储到文件
        $access_token_info = [
            "access_token" => $access_token["access_token"],
            //当前时间 + access_token的有效时间 -60s : 自然失效的前一分钟
            "deadline" => time() + $access_token["expires_in"] - $before_deadline,
        ];
        $access_token_info_str = json_encode($access_token_info);
        $result = file_put_contents($filename, $access_token_info_str);

        return !!$result;
    }

    //从文件读取access_token
    private function readAccessToken()
    {
        //access_token文件位置
        $filename = WxConfig::$conf["token_file"];

        //若没有发现文件,则创建文件,并返回false
        if (!file_exists($filename)) {
            fopen($filename, "w+");
            return false;
        }
        //否则读取文件,并返回
        $result = file_get_contents($filename);
        if ($result) {
            $result = json_decode($result, true);
        }
        return $result;

    }

    //通过授权信息(code),查询web_access_token
    public function getWebAccessToken($code = "")
    {
        //refresh_token有效,进行token刷新
        $web_token_url = WxConfig::$conf["web_token_url"];
        $para = [
            "appid" => WxConfig::$conf["appid"],
            "secret" => WxConfig::$conf["appsecret"],
            "code" => $code,
            "grant_type" => "authorization_code",
        ];
        $flag = false;
        foreach ($para as $key => $value) {
            if (!$flag) {
                $web_token_url .= "?";
                $flag = !$flag;
            } else {
                $web_token_url .= "&";
            }
            $web_token_url .= $key . "=" . $value;
        }

        $result = Tools::sendGet($web_token_url);
        $result = json_decode($result, true);
        if (!isset($result["errcode"]) && isset($result["access_token"])) {
            //请求成功
            //计算相关数据:两个token的失效deadline
            $result["deadline"] = time() + $result["expires_in"] - WxConfig::$conf["before_deadline"];
            $result["refresh_deadline"] = time() + WxConfig::$conf["web_refresh_token_deadline"] - WxConfig::$conf["before_deadline"];

            $_SESSION["web_access_token"] = $result;
            return $result;
        }
    }

    //获取网页授权的web_access_token
    public function getOrRefreshWebAccessToken()
    {
        //1.读取网页授权信息
        if (isset($_SESSION["web_access_token"])) {
            //判断access_token有效期
            $web_access_token = $_SESSION["web_access_token"];
            if ($web_access_token["deadline"] > time()) {
                //access_token有效
                return $web_access_token;
            } //判断refresh_token有效期
            elseif ($web_access_token["refresh_deadline"] > time()) {
                //refresh_token有效,进行token刷新
                $web_refresh_token_url = WxConfig::$conf["web_refresh_token_url"];
                $para = [
                    "appid" => WxConfig::$conf["appid"],
                    "grant_type" => "refresh_token",
                    "refresh_token" => $web_access_token["refresh_token"],
                ];
                $flag = false;
                foreach ($para as $key => $value) {
                    if (!$flag) {
                        $web_refresh_token_url .= "?";
                        $flag = !$flag;
                    } else {
                        $web_refresh_token_url .= "&";
                    }
                    $web_refresh_token_url .= $key . "=" . $value;
                }
                $result = Tools::sendGet($web_refresh_token_url);
                $result = json_decode($result, true);

                if (!isset($result["errcode"]) && isset($result["access_token"])) {
                    //请求成功
                    //计算相关数据:两个token的失效deadline
                    $result["deadline"] = time() + $result["expires_in"] - WxConfig::$conf["before_deadline"];
                    $result["refresh_deadline"] = time() + WxConfig::$conf["web_refresh_token_deadline"] - WxConfig::$conf["before_deadline"];

                    $_SESSION["web_access_token"] = $result;
                    return $result;
                }
            }
        } else {
            //调用微信授权
            $web_auth_url = WxConfig::$conf["web_auth_url"];
            $para = [
                "appid" => WxConfig::$conf["appid"],
                "redirect_uri" => urlencode(WxConfig::$conf["localhost"]),
                "response_type" => "code",
                "scope" => "snsapi_base",
                "state" => "123",
            ];

            $flag = false;
            foreach ($para as $key => $value) {
                $web_auth_url .= $flag ? "&" : "?";
                $web_auth_url .= $key . "=" . $value;
            }
            $web_auth_url .= "#wechat_redirect";
            header("Location: $web_auth_url");
            exit();
        }
    }

}
