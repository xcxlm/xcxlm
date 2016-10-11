<?php
if (!defined('IN_MOBILE_API')) {
    exit('Access Denied');
}

require './source/class/class_core.php';
$discuz = C::app();
$discuz->init();

if ($_GET["action"]=="logout") MobileLoginAPI::logout();
else MobileLoginAPI::login();

class MobileLoginAPI
{
    public static function login()
    {
        
        if (!DzSecCode::check()) {
            DzEnv::error_result("error_seccode");
        }
        
		global $_G;
        $username = DzEnv::get_param("username",null,'POST');
        $password = DzEnv::get_param("password",null,'POST');
        $questionid = DzEnv::get_param("questionid", 0,'POST');
        $answer = DzEnv::get_param("answer", "",'POST');
        if (!$username || !$password) {
            DzEnv::error_result("invalid_param");
        }
		$username = iconv('UTF-8', CHARSET.'//ignore', urldecode($username));
		$answer = iconv('UTF-8', CHARSET.'//ignore', $answer);
        
        if (login_mobile_validate::is_phone($username)) {
            $res=C::t("#login_mobile#mobile_login_connection")->getUserName($username);
            if ($res!==false) {
                $username = $res;
            }
        }
        
        $uid = login_mobile_uc::logincheck($username, $password, $questionid, $answer);
        if (!is_numeric($uid)) {
            DzEnv::error_result($uid);
        }
        
        login_mobile_uc::dologin($uid);
        $result = array (
            "retcode"=>0,
            "retmsg"=>lang("plugin/login_mobile","login_succeed"),
            "username" => $username,
            "uid" => $uid,
        );
        runlog("login_mobile","login_succ $uid|$username");
        DzEnv::result($result);
    }

    public static function logout()
    {
        $uid = $_G['uid'];
        $username = $_G['username'];
        login_mobile_uc::dologout();
		$_G['groupid'] = $_G['member']['groupid'] = 7;
		$_G['uid'] = $_G['member']['uid'] = 0;
		$_G['username'] = $_G['member']['username'] = $_G['member']['password'] = '';
        $result = array(
            "retcode" => 0,
            "retmsg" => "logout success",
        );
        runlog("login_mobile","logout_succ $uid|$username");
        DzEnv::result($result);
    }
}

?>
