<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/4/5
 * Time: 15:56
 */

require_once __DIR__ . '/AXJSMSClient.php';

use \sinri\axjsmsclient\AXJSMSClient;

AXJSMSClient::log('INFO', $_REQUEST);

$reports = AXJSMSClient::getRequest("report");

$report_list = explode(";", $reports);
foreach ($report_list as $report) {
    if (empty($report)) {
        continue;
    }
    list($type, $mobile, $msg_id, $send_time, $receive_time, $status_code) = explode(',', $report);
    AXJSMSClient::log("INFO", array(
        'type' => $type,
        'mobile' => $mobile,
        'msg_id' => $msg_id,
        'send_time' => $send_time,
        'receive_time' => $receive_time,
        'status_code' => $status_code,
    ));
}


echo "OK";