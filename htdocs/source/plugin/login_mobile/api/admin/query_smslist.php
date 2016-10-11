<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
$res = C::t("#login_mobile#mobile_login_sms")->query();
AdminApi::result($res);
