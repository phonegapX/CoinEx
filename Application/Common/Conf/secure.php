<?php
if (!empty($_POST)) $_POST = wjSec($_POST);
if (!empty($_GET))   $_GET = wjSec($_GET);
if (!empty($_COOKIE)) $_COOKIE =  wjSec($_COOKIE);
if (!empty($_SESSION))  $_SESSION = wjSec($_SESSION);
if (!empty($_FILES))  $_FILES = wjSec($_FILES);


function wjSec(&$array) {
	if (is_array ( $array )) {
		foreach ( $array as $k => $v ) {
			if(inteli($k,$v)){
				$array [$k] = $v;
			}else{
				$array [$k] = wjSec ( $v );
			}
		}
	} else if (is_numeric ( $array )) {
			$array = wjStrFilter ( $array , 0 , 0 );
	} else{
			$array = wjStrFilter ( $array );
	}
	return $array;
}

function inteli($k,$v){
	if($k=='addr' && preg_match("(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[a-zA-Z0-9]{34}",$v)){
		return true;
	}
	if(($k=='password'||$k=='paypassword') && preg_match("(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[a-zA-Z0-9]{6,16}",$v)){
		return true;
	}
	if($k=='ueditorcontent' || $k=='en_ueditorcontent'){
		return true;
	}
	//&& (preg_match("/^https?\:\/\/(tupiancw\.oss-cn-beijing\.aliyuncs\.com\hyjy)\/[a-zA-Z0-9\/_-]{1,}\.(jpg|png)$/",$v))
	if($k=='img' ){
		return true;
	}
	if(($k=="js_qb" || $k=="js_ym" || $k=="js_gw" || $k=="url") && preg_match("/^^((https|http)?:\/\/)[^\s]+$/",$v)){
		return true;
	}
	if($v=="EBTC"){
		return true;
	}
	return false;
}

function wjStrFilter($str,$pi_Def="",$pi_iType=1){

 if ( isset($_GET[$str]) )
    $str = trim($_GET[$str]);
  else if ( isset($_POST[$str]))
    $str = trim($_POST[$str]);
  else if ($str)
    $str = trim($str);
  else
    return $pi_Def;
	// INT
  if ($pi_iType==0)
  {
    if (is_numeric($str))
      return $str;
    else
      return $pi_Def;
  }
  
 // String
if($str){
	$str = str_replace('%20','',$str);
    $str = str_replace('%27','',$str);
    $str = str_replace('%2527','',$str);
    $str = str_replace('*','',$str);
    $str = str_replace('"','&quot;',$str);
    $str = str_replace("'",'',$str);
    $str = str_replace('"','',$str);
    $str = str_replace('<','&lt;',$str);
    $str = str_replace('>','&gt;',$str);
    $str = str_replace("{",'',$str);
    $str = str_replace('}','',$str);
	$str=str_replace('#','',$str);
	$str=str_replace('--','',$str);
	$str=str_replace('%','',$str);
	
	$str=preg_replace("/insert/i", "",$str);
	$str=preg_replace("/update/i", "",$str);
	$str=preg_replace("/delete/i", "",$str);
	$str=preg_replace("/select/i", "",$str);
	$str=preg_replace("/drop/i", "",$str);
	$str=preg_replace("/load_file/i", "",$str);
	$str=preg_replace("/outfile/i", "",$str);
	$str=preg_replace("/into/i", "",$str);
	$str=preg_replace("/exec/i", "",$str);
	$str=preg_replace("/tw_/i", "",$str);
	$str=preg_replace("/union/i", "",$str);
	$str=preg_replace("/%/i", "",$str);
	
	if (get_magic_quotes_gpc()){
		$str = str_replace("\\\"", "&quot;",$str);
		$str = str_replace("\\''", "&#039;",$str);
	}else{
		$str = addslashes($str);
		$str = str_replace("\"", "&quot;",$str);
		$str = str_replace("'", "&#039;",$str);
		
	}
	$str=mysql_escape_string($str);
	
	$str=RemoveXSS($str);
	$str=RemoveWJ($str);
	
}
return $str;
}


function RemoveXSS($val) { 
		// remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed 
		// this prevents some character re-spacing such as <java\0script> 
		// note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs 
	   //$val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val); 
	  
	   // straight replacements, the user should never need these since they're normal characters 
	   // this prevents like <IMG SRC=@avascript:alert('XSS')> 
	   $search = 'abcdefghijklmnopqrstuvwxyz';
	   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
	   $search .= '1234567890!@#$%^&*()';
	   $search .= '~`";:?+/={}[]-|\'\\';
	   for ($i = 0; $i < strlen($search); $i++) {
	      // ;? matches the ;, which is optional
	      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
	  
	      // @ @ search for the hex values
	      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
	      // @ @ 0{0,7} matches '0' zero to seven times 
	      $val = preg_replace('/(�{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
	   }
	  
	   // now the only remaining whitespace attacks are \t, \n, and \r
	   $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
	   $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
	   $ra = array_merge($ra1, $ra2);
	  
	   $found = true; // keep replacing as long as the previous round replaced something
	   while ($found == true) {
	      $val_before = $val;
	      for ($i = 0; $i < sizeof($ra); $i++) {
	         $pattern = '/';
	         for ($j = 0; $j < strlen($ra[$i]); $j++) {
	            if ($j > 0) {
	               $pattern .= '('; 
	               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
	               $pattern .= '|'; 
	               $pattern .= '|(�{0,8}([9|10|13]);)';
	               $pattern .= ')*';
	            }
	            $pattern .= $ra[$i][$j];
	         }
	         $pattern .= '/i'; 
	         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag 
	         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags 
	         if ($val_before == $val) { 
	            // no replacements were made, so exit the loop 
	            $found = false; 
	         } 
	      } 
	   } 
	   return $val; 
	}

function RemoveWJ($val) { 
	$val=fliter_script($val);
	$val=fliter_html($val);
	$val=fliter_sql($val);
	$val=fliter_str($val);
	$val=filter_dir($val);
	$val=filter_path($val);
	$val=filter_phptag($val);
	$val=str_out($val);   
	return $val; 
}
/**
* 安全过滤类-过滤javascript,css,iframes,object等不安全参数 过滤级别高
* Controller中使用方法：$this->controller->fliter_script($value)
* @param string $value 需要过滤的值
* @return string
*/
function fliter_script($value) {
$value = preg_replace("/(javascript:)?on(click|load|key|mouse|error|abort|move|unload|change|dblclick|move|reset|resize|submit)/i","&111n\\2",$value);
$value = preg_replace("/(.*?)<\/script>/si","",$value);
$value = preg_replace("/(.*?)<\/iframe>/si","",$value);
$value = preg_replace ("//iesU", '', $value);
return $value;
}

/**
* 安全过滤类-过滤HTML标签
* Controller中使用方法：$this->controller->fliter_html($value)
* @param string $value 需要过滤的值
* @return string
*/
function fliter_html($value) {
if (function_exists('htmlspecialchars')) return htmlspecialchars($value);
return str_replace(array("&", '"', "'", "<", ">"), array("&", "\"", "'", "<", ">"), $value);
}

/**
* 安全过滤类-对进入的数据加下划线 防止SQL注入
* Controller中使用方法：$this->controller->fliter_sql($value)
* @param string $value 需要过滤的值
* @return string
*/
function fliter_sql($value) {
$sql = array("select", 'insert', "update", "delete", "\'", "\/\*",
"\.\.\/", "\.\/", "union", "into", "load_file", "outfile");
$sql_re = array("","","","","","","","","","","","");
return str_replace($sql, $sql_re, $value);
}


/**
* 安全过滤类-字符串过滤 过滤特殊有危害字符
* Controller中使用方法：$this->controller->fliter_str($value)
* @param string $value 需要过滤的值
* @return string
*/
function fliter_str($value) {
$badstr = array("\0", "%00", "\r", '&', ' ', '"', "'", "<", ">", " ", "%3C", "%3E");
$newstr = array('', '', '', '&', ' ', '"', "'", "<", ">", " ", "<", ">");
$value = str_replace($badstr, $newstr, $value);
$value = preg_replace('/&((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $value);
return $value;
}

/**
* 私有路劲安全转化
* Controller中使用方法：$this->controller->filter_dir($fileName)
* @param string $fileName
* @return string
*/
function filter_dir($fileName) {
$tmpname = strtolower($fileName);
$temp = array(':/',"\0", "..");
if (str_replace($temp, '', $tmpname) !== $tmpname) {
return false;
}
return $fileName;
}

/**
* 过滤目录
* Controller中使用方法：$this->controller->filter_path($path)
* @param string $path
* @return array
*/
function filter_path($path) {
$path = str_replace(array("'",'#','=','`','$','%','&'), '', $path);
return rtrim(preg_replace('/(\/){2,}|(\\\){1,}/', '/', $path), '/');
}

/**
* 过滤PHP标签
* Controller中使用方法：$this->controller->filter_phptag($string)
* @param string $string
* @return string
*/
function filter_phptag($string) {
return str_replace(array(''), array('<?', '?>'), $string);
}

/**
* 安全过滤类-返回函数
* Controller中使用方法：$this->controller->str_out($value)
* @param string $value 需要过滤的值
* @return string
*/
function str_out($value) {
$badstr = array("<", ">", "%3C", "%3E");
$newstr = array("<", ">", "<", ">");
$value = str_replace($newstr, $badstr, $value);
return stripslashes($value); //下划线
}
?>