<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once dirname(__FILE__).'/libs/env.class.php';
class mobileplugin_login_mobile
{
    function common()
    {
        if (!DzEnv::isEnableMobile()) return;
        global $_G;
        $script = strtolower($_SERVER["SCRIPT_NAME"]);
        if ($this->endwith($script, "member.php")) {
            $mod = $_GET["mod"];
            if ($mod=="logging" && $_GET["action"]=="login" && isset($_GET["mobile"])) {
				 $loginurl = DzEnv::getSiteUrl()."/source/plugin/login_mobile/fe/login.html";
				 header("Location: $loginurl"); 
				 die(0);
            }
            if ($mod=="register" && isset($_GET["mobile"])) {
				 $url = DzEnv::getSiteUrl()."/source/plugin/login_mobile/fe/regist.html";
				 header("Location: $url"); 
				 die(0);
            }
        }
    }

    private function endwith($haystack, $needle) {   
        $length = strlen($needle);  
        if ($length == 0){    
            return true;  
        }
        return (substr($haystack, -$length) === $needle);
    }

}

class mobileplugin_login_mobile_member extends mobileplugin_login_mobile
{
    function logging()
    {
        if (!DzEnv::isEnableMobile()) return;
        global $_G;
        if(isset($_GET["action"]) && $_GET["action"]=="login" && isset($_GET["mobile"])) {
            $loginurl = DzEnv::getSiteUrl()."/source/plugin/login_mobile/fe/login.html";
            header("Location: $loginurl"); 
            die(0);
        }
    }

    function register()
    {
        if (!DzEnv::isEnableMobile()) return;
        global $_G;
        if(isset($_GET["mobile"])) {
            $url = DzEnv::getSiteUrl()."/source/plugin/login_mobile/fe/regist.html";
            header("Location: $url"); 
            die(0);
        }       
    }
}

