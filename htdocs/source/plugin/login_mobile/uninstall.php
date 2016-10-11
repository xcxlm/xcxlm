<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = "drop table  if exists `" . DB::table('mobile_login_connection') . "`";
runquery($sql);
$sql = "drop table  if exists `" . DB::table('mobile_login_seccode') . "`";
runquery($sql);
$sql = "drop table  if exists `" . DB::table('mobile_login_sms') . "`";
runquery($sql);

$finish = TRUE;
?>
