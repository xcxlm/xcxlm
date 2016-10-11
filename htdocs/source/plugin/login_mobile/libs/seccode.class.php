<?php
class DzSecCode
{
    private static $seccode_cookie_key = "mobile_login_seccode";

	
	public static function check()
	{
		if (!isset($_REQUEST["seccode"])) {
			return false;
		}
		$code = strtolower($_REQUEST["seccode"]);
		$vcode = $_COOKIE[self::$seccode_cookie_key];
		return md5($code) == $vcode;
	}

	
	public static function mkcode($num=4, $onlynum=false)
	{
		
		$charset = array(
			"a","b","c","d","e","f","g","h","i","j","k","l","m",
			"n","o","p","q","r","s","t","u","v","w","x","y","z",
			"0","1","2","3","4","5","6","7","8","9"
		);
        if ($onlynum) {
            $charset = array("0","1","2","3","4","5","6","7","8","9");
        }
		$len = count($charset);
		$res = "";
		shuffle($charset);
		for ($i=0; $i<$num; ++$i) {
			$rn = mt_rand(0,$len-1);
			$char = $charset[$rn];
			$charset[$rn] = $charset[$len-1];
			--$len;

			if (!is_numeric($char)) {
				$seed = mt_rand(0,1);
				if ($seed == 0) $char = strtoupper($char);
			}
			$res.= $char;
		}
		
		$lower = strtolower($res);
		setcookie(self::$seccode_cookie_key, md5($lower));
		return $res;
	}

    
    public static function display($text, $image_width=120, $image_height=40)
    {
		$image = imagecreatetruecolor($image_width, $image_height) or die("CreateVerifyImage failde");
		$black_color = imagecolorallocate($image, mt_rand(200, 254), mt_rand(200, 254), mt_rand(200, 254)); 
		imagefill($image, 0, 0, $black_color);
		$font_color = imagecolorallocate($image, mt_rand(0, 120), mt_rand(0, 120), mt_rand(0, 120));  
		
		$font_file = dirname(__FILE__)."/../data/msyh.ttf";
		$verify_text = $text;
		$verify_text_show = "";  
		for ($i = 0; $i < strlen($verify_text); $i++)  
			$verify_text_show .= ($verify_text[$i] . " ");  
		
		$font_size  = 20;  
		$font_angle = 1; 
		$font_pos_x = 15;  
		$font_pos_y = $image_height - 10;  
		imagettftext($image, $font_size, $font_angle, $font_pos_x, $font_pos_y, $font_color, $font_file, $verify_text_show);  
		
		$NOISE_DOT_NUM = mt_rand(300, 500);
		for ($i = 0; $i < $NOISE_DOT_NUM; $i++) {  
			imagesetpixel($image, mt_rand(0, $image_width), mt_rand(0, $image_height), $font_color);  
		} 
		
        

		header('Content-Type: image/jpeg');
		imagejpeg($image);
		exit(0);
    }

    
    public static function display2($seccode, $width=120, $height=40)
    {
        global $_G;
		require './source/class/class_core.php';
		require_once libfile('class/seccode'); 
		$code = new seccode();
		$code->code = $seccode;
		$code->width = $width;
		$code->height = $height;
		$code->background = ""; 
		$code->adulterate = ""; 
		$code->color = "";
		$code->fontpath = DISCUZ_ROOT.'./static/image/seccode/font/';
		$code->datapath = DISCUZ_ROOT.'./static/image/seccode/';
		$code->includepath = DISCUZ_ROOT.'./source/class/';
		$code->display();
        exit();
    }
}

?>
