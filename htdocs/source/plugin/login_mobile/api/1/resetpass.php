<?php
if (!defined('IN_MOBILE_API')) {
    exit('Access Denied');
}
require './source/class/class_core.php';
$discuz = C::app();
$discuz->init();


$phone = DzEnv::get_param("phone","",'POST');
$password = DzEnv::get_param("password",null,"POST");
$pcode = DzEnv::get_param("pcode",null,'POST');
if (!$password || !$pcode || !login_mobile_validate::is_phone($phone)) {
	DzEnv::error_result("invalid_param");
}
$username = C::t("#login_mobile#mobile_login_connection")->getUserName($phone);
if (!$regist && $username===false) {
    DzEnv::error_result("phone_not_regist");
}
$passwdlen = dstrlen($password);
if ($passwdlen<6 || $passwdlen > 20) {
    DzEnv::error_result("password_len_invalid");
}

if (!C::t("#login_mobile#mobile_login_seccode")->check($phone,$pcode)) {
	DzEnv::error_result("error_smscode");
}

loaducenter();
$res = uc_user_edit($username, '', $password, '', 1);
if ($res<0) {
	DzEnv::error_result("reset_pass_fail");
}

$result = array (
    "retcode" => 0,
    "retmsg" => "您的密码已重置",
);
DzEnv::result($result);



?>
