<?php

use think\exception\HttpResponseException;
use think\Response;

// +----------------------------------------------------------------------
// | 返回结果
// +----------------------------------------------------------------------

if (!function_exists('result')) {
    /**
     * @title 封装json响应
     *
     * @param mixed $data 数据
     * @param integer $code 1是成功；0是失败；其它码看说明
     * @param mixed $msg 提示消息
     *
     * @throws HttpResponseException
     */
    function result(mixed $msg = '操作成功',mixed $data = null, int $code = 1)
    {
        $res = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
            'time' => date('Y-m-d H:i:s')
        ];
        throw new HttpResponseException(json($res));
    }
}

if (!function_exists('success')) {

    /**
     * 操作成功返回的数据
     * @param string $msg 提示信息
     * @param mixed|null $data 要返回的数据
     * @param int $code 错误码，默认为1
     */
    function success(string $msg = '成功', mixed $data = null, int $code = 1): void
    {
        result($msg, $data, $code);
    }

}

if (!function_exists('error')) {

    /**
     * 操作成功返回的数据
     * @param string $msg 提示信息
     * @param mixed|null $data 要返回的数据
     * @param int $code 错误码，默认为0
     */
    function error(string $msg = '失败', mixed $data = null, int $code = 0): void
    {
        result($msg, $data, $code);
    }

}

// +----------------------------------------------------------------------
// | 字符串处理
// +----------------------------------------------------------------------

if (!function_exists('encodeStr')) {

    /**
     * 将英文逗号分割的字符串用@符号包裹
     * @param string $str 字符串（英文逗号分割）
     */
    function encodeStr(string $str): string
    {
        $array = explode(',',$str);
        return  implode(',', array_map(function ($number) {
            return '@' . $number . '@';
        }, $array));
    }

}

if (!function_exists('decodeStr')) {

    /**
     * 将@符号包裹的字符串解析成不带@符号的
     * @param string $str 字符串（@符号包裹）
     */
    function decodeStr(string $str): string
    {
        // 第一步：去除所有的 '@' 符号
        $str_no_at = str_replace('@', '', $str);
        // 第二步：将剩余的 '@' 替换为逗号 ','
        return str_replace('，', ',', $str_no_at);
    }

}

// +----------------------------------------------------------------------
// | cdn
// +----------------------------------------------------------------------

if (!function_exists('cdnurl')) {

    /**
     * 获取上传资源的CDN的地址
     * @param string $url 资源相对地址
     * @param boolean $domain 是否显示域名 或者直接传入域名
     * @return string
     */
    function cdnurl($url, $domain = false)
    {
        $regex = "/^((?:[a-z]+:)?\/\/|data:image\/)(.*)/i";
        $cdnurl = config('cdn.cdnurl');
        if (is_bool($domain) || stripos($cdnurl, '/') === 0) {
            $url = preg_match($regex, $url) || ($cdnurl && stripos($url, $cdnurl) === 0) ? $url : $cdnurl . $url;
        }
        if ($domain && !preg_match($regex, $url)) {
            $domain = is_bool($domain) ? request()->domain() : $domain;
            $url = $domain . $url;
        }
        return $url;
    }
}

// +----------------------------------------------------------------------
// | 跨域检测
// +----------------------------------------------------------------------

if (!function_exists('check_cors_request')) {
    /**
     * 跨域检测
     */
    function check_cors_request()
    {
        if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] && config('cors.cors_request_domain')) {
            $info = parse_url($_SERVER['HTTP_ORIGIN']);
            $domainArr = explode(',', config('cors.cors_request_domain'));
            $domainArr[] = request()->host(true);
            if (in_array("*", $domainArr) || in_array($_SERVER['HTTP_ORIGIN'], $domainArr) || (isset($info['host']) && in_array($info['host'], $domainArr))) {
                header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
            } else {
                $response = Response::create('跨域检测无效', 'html', 403);
                throw new HttpResponseException($response);
            }

            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');

            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
                }
                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
                }
                $response = Response::create('', 'html');
                throw new HttpResponseException($response);
            }
        }
    }
}


// +----------------------------------------------------------------------
// | 订单号
// +----------------------------------------------------------------------

if (!function_exists('makePaySn')) {
    /**
     * 生成20位编号(时间+微秒+随机数+会员ID%1000)，该值会传给第三方支付接口
     * 长度 =12位 + 3位 + 2位 + 3位  = 20位
     * 1000个会员同一微秒提订单，重复机率为1/100
     * @param $userId
     * @return string
     */
    function makePaySn($userId): string
    {
        return date('ymdHis', time()) . sprintf('%03d', (float)microtime() * 1000) . mt_rand(10, 99) . sprintf('%03d', intval($userId) % 1000);
    }
}
