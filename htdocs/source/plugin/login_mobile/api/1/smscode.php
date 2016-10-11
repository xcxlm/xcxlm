<?php
if (!defined('IN_MOBILE_API')) {
    exit('Access Denied');
}

require './source/class/class_core.php';
$discuz = C::app();
$discuz->init();


if (!DzSecCode::check()) {
	DzEnv::error_result("error_seccode");
}


$phone  = DzEnv::get_param("phone",null,'POST');
$regist = isset($_REQUEST["regist"]) ? true : false;
if (!login_mobile_validate::is_phone($phone)) { 
	DzEnv::error_result("phone_error");
}


$username = C::t("#login_mobile#mobile_login_connection")->getUserName($phone);
if ($regist && $username!==false) {
    DzEnv::error_result("phone_used");
}
if (!$regist && $username===false) {
    DzEnv::error_result("phone_not_regist");
}


if (!isset($_G['setting']['login_mobile_smsset'])){
    DzEnv::error_result("sms_notset");
}
$appcfg = unserialize($_G['setting']['login_mobile_smsset']);
$smsid    = isset($appcfg["smsid"]) ? $appcfg["smsid"] : "1";
$username = isset($appcfg["username"]) ? $appcfg["username"] : "";
$password = isset($appcfg["password"]) ? $appcfg["password"] : "";


$code = DzSecCode::mkcode(4,true);
$content = SendSMS::getSecodeMessage($code);


$res = SendSMS::send($username, $password, $phone, $content, $smsid);
C::t("#login_mobile#mobile_login_seccode")->save($phone,$code);

DzEnv::result($res);
?>
