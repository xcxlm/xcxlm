<?php

class login_mobile_bksvr
{
    
    public static function get_sms_info()
    {
		$info = array(
            "list" => array("本插件当前已经集成了多个短信平台客户端供您选择。"),
            "default_sms" => 7,
            "clients"=>array(
                array("text"=>"短信宝","value"=>7,"desc"=>"您可以前往<a href=\\\"http://www.smsbao.com/reg?r=10236\\\" target=\\\"_blank\\\">短信宝官网</a>申请账号"),
                array("text"=>"上海维信互动","value"=>2,"desc"=>"您可以前往<a href=\\\"http://www.veesing.com/\\\" target=\\\"_blank\\\">上海维信互动官网</a>申请账号"),
                array("text"=>"莫名短信","value"=>"1",
                      "desc"=>"您可以前往<a href=\\\"http://www.duanxin.cm/\\\" target=\\\"_blank\\\">莫名短信官网</a>申请账号"),
                array("text"=>"吉信通","value"=>"3","desc"=>"您可以前往<a href=\\\"http://www.winic.org/\\\" target=\\\"_blank\\\">吉信通官网</a>申请账号"),
				array("text"=>"中国网建","value"=>"4","desc"=>"您可以前往<a href=\\\"http://sms.webchinese.com.cn/\\\" target=\\\"_blank\\\">中国网建SMS短信通官网</a>申请账号"),
				array("text"=>"智验科技","value"=>"5","desc"=>"您可以前往<a href=\\\"http://www.zhiyan.net/\\\" target=\\\"_blank\\\">智验科技官网</a>申请账号"),
				array("text"=>"互亿无线","value"=>"6","desc"=>"您可以前往<a href=\\\"http://www.ihuyi.com/\\\" target=\\\"_blank\\\">互亿无线官网</a>申请账号"),
				array("text"=>"游族网络","value"=>"99","desc"=>""),
            )
        );
        try {
			$url = "http://139.196.29.35/login_mobile/index.php/api/index?version=2.3.0";
			$res = self::http_request($url);
			if (isset($res["list"])) $info["list"] = $res["list"];
			if (isset($res["default_sms"])) $info["default_sms"] = intval($res["default_sms"]);
			if (isset($res["clients"])) $info["clients"] = $res["clients"];
        } catch (Exception $e) {
            runlog("login_mobile", "get_sms_info fail: ".$e->getMessage());
        }
        return $info;
    }

    
    private static function http_request($url, $postData=null)
    {
        $data = "";
        $urlarr = array($url);
        foreach ($urlarr as $k => $ithurl) {
			$ch = curl_init();
            if ($k!=0 && $domain!="") {
				$header = array ("Host: $domain");
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			} 
			if(!is_null($postData)){
				$curlPost = http_build_query($postData);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
			}
			curl_setopt($ch, CURLOPT_URL, $ithurl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			$data      = curl_exec($ch);
			$errorInfo = curl_error($ch);
            $httpCode  = curl_getinfo($ch,CURLINFO_HTTP_CODE);
			if($httpCode!=200 || !empty($errorInfo)){
				curl_close($ch);
                continue;
			}
			if(empty($data) && empty($postData)){
				curl_close($ch);
                break;
			}
			curl_close($ch);
		}
        if ($data == "") {
			$tmp = file_get_contents($url);
		    if(!empty($tmp)){
			    $data = $tmp;
		    }
        }
        $res = json_decode($data,true);
        if (empty($res)) {
            throw new Exception("http_request_fail [res:$data]");
        }
        return $res;
    }
}
?>
