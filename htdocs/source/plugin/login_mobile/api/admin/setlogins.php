<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

try {
    $list = isset($_POST['list']) ? $_POST['list'] : array();
	$orderarr = array();
	foreach ($list as $k => $v) {
        $orderarr[$k] = $v["displayorder"];
    }
    array_multisort($orderarr, SORT_ASC, $list);

    C::t('common_setting')->update_batch(array("login_mobile_platlogins"=>$list));
    require_once libfile('function/cache');
    updatecache('setting');

    $res = array();
    AdminApi::result($res);
} catch (Exception $e) {
    $res = array (
        'retcode' => 10010,
        'retmsg'  => $e->getMessage(),
    );
    AdminApi::result($res);
}
?>
