<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
require_once dirname(__FILE__).'/libs/env.class.php';

if (isset($_REQUEST['username'])) {
    $params = array (
        'smsid'     => DzEnv::get_param('smsid', '1'),
        'username'  => DzEnv::get_param('username', ''),
        'password'  => DzEnv::get_param('password', ''),
        'template1' => DzEnv::get_param('template1', ''),
        'template2' => DzEnv::get_param('template2', ''),
    );
    C::t('common_setting')->update_batch(array('login_mobile_smsset'=>$params));
    updatecache('setting');
    $landurl = 'action=plugins&operation=config&do='.$pluginid.'&identifier=login_mobile&pmod=z_smsset';
	cpmsg('plugins_edit_succeed', $landurl, 'succeed');
}



require_once dirname(__FILE__).'/libs/menu.inc.php';
$info = login_mobile_bksvr::get_sms_info();
$params = array(
    'testapi'   => DzEnv::getSiteUrl().'/plugin.php?id=login_mobile:adminapi&action=send_test_sms',
    'username'  => '',
    'password'  => '',
    'template1' => '这是一条测试短信，请忽略。',
    'template2' => '您的验证码是：【变量】。',
    'list'      => $info['list'],
    'smsid'     => $info['default_sms'],
    'clients'   => $info['clients'],
);
if (isset($_G['setting']['login_mobile_smsset'])){
	$appcfg = unserialize($_G['setting']['login_mobile_smsset']);
    isset($appcfg['smsid']) && $params['smsid']=$appcfg['smsid'];
    isset($appcfg['username']) && $params['username']=iconv(CHARSET, 'UTF-8//ignore', $appcfg['username']);
    isset($appcfg['password']) && $params['password']=$appcfg['password'];
    if (isset($appcfg['template1']) && $appcfg['template1']!='') {
        $params['template1'] = iconv(CHARSET, 'UTF-8//ignore', $appcfg['template1']);
    }
    if (isset($appcfg['template2']) && $appcfg['template2']!='') {
        $params['template2'] = iconv(CHARSET, 'UTF-8//ignore', $appcfg['template2']);
    }
}
$tplVars = array(
    'plugin_path'=>DzEnv::getPluginPath(),
);
MobileLogin_Utils::loadTemplate(dirname(__FILE__).'/view/z_smsset.tpl', $params, $tplVars);
?>
