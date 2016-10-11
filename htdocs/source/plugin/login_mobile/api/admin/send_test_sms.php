<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
$params = array (
    "smsid" => DzEnv::get_param("smsid", "1"),
    "phone" => DzEnv::get_param("phone", ""),
    "username" => DzEnv::get_param("username", ""),
    "password" => DzEnv::get_param("password", ""),
    "template1" => DzEnv::get_param("template1", ""),
);
if (!login_mobile_validate::is_phone($params["phone"])) {
    AdminApi::result(array("retcode"=>10001,"retmsg"=>"请输入11位手机号"));
}
$content = $params["template1"];
$result = SendSMS::send($params["username"], $params["password"], $params["phone"], $content, $params["smsid"]);
$result["params"] = $result;
AdminApi::result($result);
?>
