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
echo "OK";