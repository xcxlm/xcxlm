<?php
if (!defined('IN_MOBILE_API')) {
    exit('Access Denied');
}

require './source/class/class_core.php';
$discuz = C::app();
$discuz->init();

$result = array (
    "uid" => $_G["uid"],
	"username" => iconv(CHARSET,"UTF-8//ignore",$_G["username"]),
	"groupid" => $_G["groupid"],
    "avatar" => avatar($_G["uid"], 'big', true),
    "phone" => "",
);

$phone = C::t("#login_mobile#mobile_login_connection")->getPhone($_G["username"]);
if ($phone !== false) $result["phone"] = $phone;


if ($_G["uid"]==0) {
    lang("template");
    $result["secquestions"] = array (
        array("value"=>0, "name"=>get_sec_question("security_question")),
        array("value"=>1, "name"=>get_sec_question("security_question_1")),
        array("value"=>2, "name"=>get_sec_question("security_question_2")),
        array("value"=>3, "name"=>get_sec_question("security_question_3")),
        array("value"=>4, "name"=>get_sec_question("security_question_4")),
        array("value"=>5, "name"=>get_sec_question("security_question_5")),
        array("value"=>6, "name"=>get_sec_question("security_question_6")),
        array("value"=>7, "name"=>get_sec_question("security_question_7")),
    );

    
    $result['login_platform'] = isset($_G['setting']['login_mobile_platlogins']) ?
                                unserialize($_G['setting']['login_mobile_platlogins']) : array();
}

DzEnv::result($result);


function get_sec_question($key)
{
    global $_G;
    $question = $_G["lang"]["template"][$key];
    return iconv(CHARSET, "UTF-8//ignore", $question);
}


?>
