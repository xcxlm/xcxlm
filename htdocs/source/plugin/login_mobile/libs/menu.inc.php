<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
$memuLang = array(
    'tips_title'  => '插件使用提示',
    'browser_tip' => '请在<a href="http://www.google.cn/chrome/browser/desktop/index.html" target="_blank"><font style="color:red;font-weight:bold">chrome</font></a>或<a href="http://www.firefox.com.cn/" target="_blank"><font style="color:red;font-weight:bold">firefox</font></a>浏览器中使用本插件的后台管理页面',
    'needsms_tip' => '本插件需要发送验证码短信，请先设置 <a href="admin.php?frames=yes&action=plugins&operation=config&identifier=login_mobile&pmod=z_smsset" target="_blank">短信平台</a> 并保证短信发送成功。',
    'help_tip' => '在使用过程中遇到任何问题，请随时与我们联系，<b>QQ: 492108207</b>',
);
$charset = strtolower($_G['charset']);
if($charset!='utf-8' && $charset!='utf8'){
    foreach($memuLang as $k => &$v){
        $v = MobileLogin_Utils::diconv("UTF-8", $charset, $v);
    }   
}
if (isset($lang)) {
    $lang = array_merge($lang,$memuLang);
} else {
    $lang = $memuLang;
}
$str = '';

$str.= '<li>' . $lang['needsms_tip'] . '</li>';
$str.= '<li>' . $lang['help_tip'] . '</li>';
showtips($str,'',true, $lang['tips_title']);
?>
