<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

require_once dirname(__FILE__) . '/libs/env.class.php';

$action_rights = array (
    'send_test_sms' => array(1),
    'sendmsg' => array(1),
    'query_userlist2' => array(1),
    'query_smslist' => array(1),
    'query_userlist' => array(1),
    'unbind' => array(1),
    'bind' => array(1),
    'setlogins' => array(1),
);
$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "";
AdminApi::check_action($action);
$actionfile = AdminApi::getActionFile($action);
require_once($actionfile);

class AdminApi
{
	public static function result($result) {
		if (!isset($result['retcode'])) $result['retcode']=0;
		if (!isset($result['retmsg'])) $result['retmsg']='succ';
		echo json_encode($result);
		die(0);
	}

	public static function check_action($action)
	{
		global $_G,$action_rights;
		$groupid = $_G['groupid'];
		if (!isset($action_rights[$action])) {
			self::result(array("retcode"=>100010,"retmsg"=>"unkown action"));
		}
        $groupids = $action_rights[$action];
        if (!in_array($groupid,$groupids) && !in_array(0,$groupids)) {
            self::result(array("retcode"=>100020,"retmsg"=>"no rights to do"));
        }
	}

    public static function getActionFile($action) {
        $path = dirname(__FILE__)."/api/admin/";
        $actionfile = $path.strtolower($action).".php";
        if (!is_file($actionfile)) {
            self::result(array("retcode"=>100030,"retmsg"=>"$actionfile is not exist"));
        }
        return $actionfile;
    }
}
