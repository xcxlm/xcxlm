<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$phone = DzEnv::get_param("phone", "");
$msg   = DzEnv::get_param("msg", "");
if (!login_mobile_validate::is_phone($phone)) {
	AdminApi::result(array("retcode"=>10001,"retmsg"=>"请输入11位手机号"));
}
if ($msg=="") {
	AdminApi::result(array("retcode"=>10001,"retmsg"=>"短信内容不能为空"));
}
if (!isset($_G['setting']['login_mobile_smsset'])){
	DzEnv::error_result("sms_notset");
}
$appcfg   = unserialize($_G['setting']['login_mobile_smsset']);
$smsid    = isset($appcfg["smsid"]) ? $appcfg["smsid"] : "1";
$username = isset($appcfg["username"]) ? $appcfg["username"] : "";
$password = isset($appcfg["password"]) ? $appcfg["password"] : "";
$res = SendSMS::send($username, $password, $phone, $msg, $smsid);
AdminApi::result($res);
?>
