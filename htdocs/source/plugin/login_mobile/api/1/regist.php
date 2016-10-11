<?php
if (!defined('IN_MOBILE_API')) {
    exit('Access Denied');
}
require './source/class/class_core.php';
$discuz = C::app();
$discuz->init();



$phone = DzEnv::get_param("phone","",'POST');
$username = DzEnv::get_param("username",null,"POST");
$password = DzEnv::get_param("password",null,"POST");
$pcode = DzEnv::get_param("pcode",null,'POST');
if (!$username || !$password || !$pcode || !login_mobile_validate::is_phone($phone)) {
	DzEnv::error_result("invalid_param");
}

if (!C::t("#login_mobile#mobile_login_seccode")->check($phone,$pcode)) {
	DzEnv::error_result("error_smscode");
}

$username = iconv('UTF-8', CHARSET.'//ignore', urldecode($username));
$email = "mob_$phone@null.null";
$profile = array (
    "mobile" => $phone,
);
$uid = login_mobile_uc::regist($username,$password,$email,$profile);
if (!is_numeric($uid)) {
	DzEnv::error_result($uid);
}
C::t("#login_mobile#mobile_login_connection")->save($phone,$username);
runlog("login_mobile","regist_succ $uid|$username|$phone");


$result = array (
    "retcode" => 0,
    "retmsg" => lang("plugin/login_mobile","regist_succ"),
    "uid" => $uid,
);
DzEnv::result($result);


?>
