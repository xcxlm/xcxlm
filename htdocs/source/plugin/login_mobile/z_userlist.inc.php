<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
require_once dirname(__FILE__).'/libs/env.class.php';
require_once dirname(__FILE__).'/libs/menu.inc.php';
$params = array(
    "ajaxapi"  => DzEnv::getSiteUrl()."/plugin.php?id=login_mobile:adminapi&",
    "username" => "",
    "password" => "",

);
if (isset($_G['setting']['login_mobile_smsset'])){
	$appcfg = unserialize($_G['setting']['login_mobile_smsset']);
    isset($appcfg["username"]) && $params["username"]=$appcfg["username"];
    isset($appcfg["password"]) && $params["password"]=$appcfg["password"];
}
$tplVars = array(
    "plugin_path"=>DzEnv::getPluginPath(),
);
MobileLogin_Utils::loadTemplate(dirname(__FILE__).'/view/z_userlist.tpl', $params, $tplVars);
