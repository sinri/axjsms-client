<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/4/5
 * Time: 15:18
 */

require_once __DIR__ . '/../AXJSMSClient.php';

date_default_timezone_set("Asia/Shanghai");

$params = array(
    "api_base_url" => "http://111.13.56.193:9007",
    "username" => "hzlqkj",
    "password" => "qwe135",
);

$client = new \sinri\axjsmsclient\AXJSMSClient($params);
$code = $client->sendSMS("18768113897", "人类真是虚无", $result);

echo "code: " . $code . PHP_EOL;
echo "as: " . \sinri\axjsmsclient\AXJSMSClient::statusCodeDictionary($code) . PHP_EOL;
echo "result: " . $result . PHP_EOL;