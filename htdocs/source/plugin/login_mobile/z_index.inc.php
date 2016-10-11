<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
require_once dirname(__FILE__).'/libs/env.class.php';
require_once dirname(__FILE__).'/libs/menu.inc.php';
$params = array(
    "qqloginurl" => DzEnv::getSiteUrl()."/connect.php?mod=login&op=init&referer=forum.php&statfrom=login_simple",
    "adminapi"   => DzEnv::getSiteUrl()."/plugin.php?id=login_mobile:adminapi&action=",
    "platlogins" => isset($_G['setting']['login_mobile_platlogins']) ? unserialize($_G['setting']['login_mobile_platlogins']) : array(),
);
$tplVars = array(
    "plugin_path"=>DzEnv::getPluginPath(),
    "login_url" => DzEnv::getPluginPath()."/fe/login.html",
);
MobileLogin_Utils::loadTemplate(dirname(__FILE__).'/view/z_index.tpl', $params, $tplVars);
