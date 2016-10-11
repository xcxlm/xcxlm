<?php



class SendSMS_Moming
{
    private static $_api = "http://api.duanxin.cm/";

    private static $rescodemap = array (
		"100" => "发送成功",
		"101" => "验证失败",
		"102" => "短信不足",
		"103" => "操作失败",
		"104" => "非法字符",
		"105" => "内容过多",
		"106" => "号码过多",
		"107" => "频率过快",
		"108" => "号码内容空",
		"109" => "账号冻结",
		"110" => "禁止频繁单条发送",
		"111" => "系统暂定发送",
		"112" => "号码不正确",
		"120" => "系统升级",
    );

    public static function send($username,$password,$phone,$content,$time='',$mid='') 
    {
		$http = self::$_api;
        $msg = iconv("UTF-8","GBK//ignore",$content);
		$data = array
			(
			 'action'=>'send',
			 'username'=>$username,					
			 'password'=>strtolower(md5($password)),	
			 'phone'=>$phone,				
			 'content'=>$msg,			
			 'time'=>$time		
			);
		$re= self::postSMS($http,$data);			
        $retcode = intval($re);
        $result = array (
            "retcode" => $retcode,
            "retmsg"  => self::parseResCode($retcode),
        );
        if ($result["retcode"]==100) $result["retcode"]=0;
        return $result;
    }

    public static function parseResCode($code)
    {
        return isset(self::$rescodemap[$code]) ? self::$rescodemap[$code] : "未知错误";
    }

	public static function postSMS($url,$data='')
	{  
		$post='';
		$row = parse_url($url);
		$host = $row['host'];
		$port = !empty($row['port']) ? $row['port']:80;
		$file = $row['path'];
		while (list($k,$v) = each($data)) 
		{
			$post .= rawurlencode($k)."=".rawurlencode($v)."&";	
		}
		$post = substr( $post , 0 , -1 );
		$len = strlen($post);
		$fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
		if (!$fp) {
			return "$errstr ($errno)\n";
		} else {
			$receive = '';
			$out = "POST $file HTTP/1.0\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Content-type: application/x-www-form-urlencoded\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Content-Length: $len\r\n\r\n";
			$out .= $post;		
			fwrite($fp, $out);
			while (!feof($fp)) {
				$receive .= fgets($fp, 128);
			}
			fclose($fp);
			$receive = explode("\r\n\r\n",$receive);
			unset($receive[0]);
			return implode("",$receive);
		}
	}
}
?>
