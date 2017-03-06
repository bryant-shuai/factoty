<?php

namespace Tools;

class Tools
{
    /**
     * 数组转关联数组
     * @param array $array
     * @param string $index
     * @return array|bool
     */
    public static function indexArray($array, $index)
    {
        if (!$array || !$index) {
            return [];
        }
        $result = [];
        foreach ($array as $key => $value) {
            if (!isset($value["$index"])) {
                return [];
            }
            $result[$value["$index"]] = $value;
        }
        return $result;
    }

    /**
     * 数组转关联数组,其中每个value又是一个list数组:1->n
     * @param array $array
     * @param string $index
     * @return array|bool
     */
    public static function indexSet($array, $index)
    {
        if (!is_array($array) || !$index) {
            return false;
        }
        $result = [];
        foreach ($array as $key => $value) {
            if (!isset($value["$index"])) {
                return false;
            }
            if (!isset($result[$value["$index"]])) {
                $result[$value["$index"]] = [];
            }
            $result[$value["$index"]][] = $value;
        }
        return $result;
    }

    /**
     * 发送GET请求
     * @param string $url 请求链接
     * @param array $getData 参数
     * @return string 请求返回的原始数据
     */
    public static function sendGet($url, $getData = [])
    {
        if (is_array($getData) && $getData) {
            $sign = true;
            foreach ($getData as $key => $value) {
                if ($sign) {
                    $url .= "?";
                    $sign = false;
                } else {
                    $url .= "&";
                }
                $url .= $key . "=" . $value;
            }
        }
        $result = file_get_contents($url);
        return $result;
    }

    /**
     * 发送POST请求
     * @param string $url 请求链接
     * @param array $postData 参数
     * @return string 请求返回的原始数据
     */
    public static function sendPost($url, $postData)
    {
        $postData = http_build_query($postData);
        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
                    "Content-Length: " . strlen($postData) . "\r\n",
                'content' => $postData
            ],
        ];

        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);

        return $result;
    }

}
