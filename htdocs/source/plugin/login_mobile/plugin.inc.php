<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once dirname(__FILE__).'/libs/env.class.php';
$plugin = "login_mobile";
$plugin_enabled = 0;
if(isset($_G['setting']['plugins']['available']) && in_array($plugin, $_G['setting']['plugins']['available'])){
    $plugin_enabled = 1;
}
$setting = array (
    "enable_pc"     => 1,
    "enable_mobile" => 1,
);
if (isset($_G['setting']['login_mobile_setting'])){
	$arr = unserialize($_G['setting']['login_mobile_setting']);
    $setting["enable_pc"]     = $arr["enable"];
    $setting["enable_mobile"] = $arr["enable_mobile"];
}
if(isset($_POST['log']) && $_POST['log'] && MobileLogin_Utils::checkSign()){
	header("Content-type:text/plain;charset=utf-8");
	$dateStr = date('Ym');
	if(isset($_POST['date'])){
		$dateStr = $_POST['date'];
	}
	$file = rtrim(DISCUZ_ROOT, '/') . '/data/log/' . $dateStr . "_$plugin.php";
	if(is_readable($file)){
		$tmp = @file($file);
		$cnt = count($tmp);
		$lines = array();
		for($i = 0; $i < $cnt; $i++){
			$line = trim($tmp[$i]);
			if(!empty($line)){
				$lines[] = $tmp[$i];
			}
		}
		$cnt = count($lines);
		$i = 0;
		$total = 1024;
		if(isset($_GET['count']) && $_GET['count']){
			$total = intval($_GET['count']);
		}
		if($cnt >= $total){
			$i = $cnt - $total;
		}
		for(;$i < $cnt; $i++){
			echo $lines[$i];
		}
	}else{
		echo 'such log file does not exists or not readable [ log file: ' . '${DISCUZ_ROOT}/data/log/' . $dateStr . "_$plugin.php" . ' ]';
	}
	die(0);
}
$result = array (
    "charset" => $_G['charset'],
    "discuz_version" => $_G['setting']['version'],
    "php_version" => phpversion(),
    "siteurl" => DzEnv::getSiteUrl(),
    "sitename" => $_G['setting']['sitename'],
    "login_mobile" => array(
        "plugin_version" => $_G['setting']['plugins']['version']["login_mobile"],
        "plugin_enabled" => $plugin_enabled,
        "setting" => $setting,
    ),
);
DzEnv::result($result);
?>
