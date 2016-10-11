<?php
require_once 'validate.class.php';
require_once 'utils.class.php';
require_once 'bksvr.class.php';
require_once 'sms.class.php';
require_once 'seccode.class.php';
require_once 'uc.class.php';
class DzEnv
{
    
    public static function isEnablePc()
    {
        global $_G;
        $enable=1;
        if(isset($_G['setting']['login_mobile_setting'])) {
            $setting = unserialize($_G['setting']['login_mobile_setting']);
            $enable = intval($setting["enable"]);
        }
        return ($enable==1);
    }
    
    public static function isEnableMobile()
    {
        global $_G;
        $enable=1;
        if(isset($_G['setting']['login_mobile_setting'])) {
            $setting = unserialize($_G['setting']['login_mobile_setting']);
            $enable = intval($setting["enable_mobile"]);
        }
        return ($enable==1);
    }
    
    public static function result(array $result)
    {
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    }
    
    public static function error_result($errormsg,$errcode=100001)
    {
        $errmsg = lang("plugin/login_mobile", $errormsg);
        $err = array (
            "retcode" => $errcode,
            "retmsg"  => iconv(CHARSET,"UTF-8//ignore",$errmsg),
        );
        self::result($err);
    }
	
    public static function get_param($key, $dv=null, $field='request')
    {
        if ($field=='GET') {
            return isset($_GET[$key]) ? $_GET[$key] : $dv;
        }
        else if ($field=='POST') {
            return isset($_POST[$key]) ? $_POST[$key] : $dv;
        }
        else {
            return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $dv;
        }
    }
    
    public static function getSiteUrl()
    {
        global $_G;
		return rtrim($_G['siteurl'], '/');
    }
    
    public static function getPluginPath()
    {
        return self::getSiteUrl().'/source/plugin/login_mobile';
    }
}

?>
