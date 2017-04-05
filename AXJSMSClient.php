<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/4/5
 * Time: 14:42
 */

namespace sinri\axjsmsclient;


class AXJSMSClient
{
    const API_VERSION = '3.0';
    const IN_DEBUG = false;

    private $apiBaseUrl = "";
    private $username = '';
    private $password = '';

    /**
     * AXJSMSClient constructor.
     * @param array $params
     */
    public function __construct($params = array())
    {
        $this->apiBaseUrl = self::readArray($params, 'api_base_url');
        $this->username = self::readArray($params, 'username');
        $this->password = self::readArray($params, 'password');
    }

    public function sendSMS($mobiles, $content, &$result = '')
    {
        //http://111.13.56.193:9007/axj_http_server/sms?name=test&pass=test&subid=123&mobiles=13800138000&content=test123&sendtime=20131001123000
        $url = $this->apiBaseUrl . '/axj_http_server/sms';
        if (is_array($mobiles)) {
            $mobiles = implode(',', $mobiles);
        }
        $send_time = date('YmdHis');
        $data = array(
            'name' => $this->username,
            'pass' => $this->password,
            'subid' => '',//扩展号码，用户定义的，可选
            'mobiles' => $mobiles,//逗号分隔的11位手机号，不超过1000个
            'content' => $content,//内容 UTF-8
            'sendtime' => $send_time,
        );
        $response = self::curlPost($url, $data);
        list($result, $code) = explode(",", $response);
        return $code;
    }

    public static function statusCodeDictionary($code)
    {
        static $dic = array(
            '00' => '递交成功',
            '01' => '基本参数异常',
            '02' => '手机号不正常',
            '03' => '扩展参数异常',
            '04' => '内容解析异常',
        );
        if (isset($dic[$code])) {
            return $dic[$code];
        } else {
            return $code;
        }
    }

    // toolkit
    public static function readArray($array, $key, $default = '')
    {
        if (empty($array)) {
            return $default;
        }
        if (!isset($array[$key])) {
            return $default;
        }
        return $array[$key];
    }

    public static function curlPost($url, $data = [])
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, TRUE);
// ↓はmultipartリクエストを許可していないサーバの場合はダメっぽいです
// @DrunkenDad_KOBAさん、Thanks
//curl_setopt($curl,CURLOPT_POSTFIELDS, $POST_DATA);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  // オレオレ証明書対策
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);  //
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
//        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie');
//        curl_setopt($curl, CURLOPT_COOKIEFILE, 'tmp');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); // Locationヘッダを追跡
//curl_setopt($curl,CURLOPT_REFERER,        "REFERER");
//curl_setopt($curl,CURLOPT_USERAGENT,      "USER_AGENT");
        $output = curl_exec($curl);
        if (self::IN_DEBUG) {
            echo "[URL: {$url}]" . PHP_EOL;
            echo $output . PHP_EOL;
            echo "[FIN]" . PHP_EOL;
        }
        return $output;
    }

    public static function getRequest($name, $default = '')
    {
        if (isset($_REQUEST[$name])) {
            return $_REQUEST[$name];
        }
        return $default;
    }

    public static function log($level, $object)
    {
        $file = __DIR__ . '/log/radar.' . date('Ymd') . '.log';
        file_put_contents(
            $file,
            date('Y-m-d H:i:s') .
            " [{$level}] " .
            json_encode($object, JSON_UNESCAPED_UNICODE),
            FILE_APPEND
        );
    }
}