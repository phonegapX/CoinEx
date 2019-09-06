<?php
if (!function_exists('array_column')) {
	function array_column(array $input, $columnKey, $indexKey = NULL)
	{
		$result = array();

		if (NULL === $indexKey) {
			if (NULL === $columnKey) {
				$result = array_values($input);
			}
			else {
				foreach ($input as $row) {
					$result[] = $row[$columnKey];
				}
			}
		}
		else if (NULL === $columnKey) {
			foreach ($input as $row) {
				$result[$row[$indexKey]] = $row;
			}
		}
		else {
			foreach ($input as $row) {
				$result[$row[$indexKey]] = $row[$columnKey];
			}
		}

		return $result;
	}
}

// 资金变更日志（前后台都含有）

/*
*币种类型 opstype($number,1)
*动作类型 opstype($number,2)
*动作类型数组 opstype($number,88)
*
*/

function opstype($number,$type)
{
	$ops_array = array(
		0 => '其他',
		1 => '充值(可用)',
		2 => '提现',
		3 => '变更',
		4 => '提现撤销',
		5 => '提现申请(可用)',
		6 => '转出申请',
		7 => '转入',
		8 => '会员互转(转出)',
		9 => '会员互转(转入)',
		10 => '买入(可用)',
		11 => '卖出(可用)',
		12 => '买入差价(可用)',
		13 => '买入(冻结)',
		14 => '卖出(冻结)',
		15 => '清理(冻结)',
		16 => '撤销买入(可用)',
		17 => '撤销卖出(可用)',
		18 => '委托买入(可用)',
		19 => '委托卖出(可用)',
		20 => '委托买入(冻结)',
		21 => '委托卖出(冻结)',
		22 => '买入差价(冻结)',
		23 => '清理(可用)',
		24 => '提现申请撤销(可用)',
		25 => '撤销买入(冻结)',
		26 => '撤销卖出(冻结)',
	);

	if($type == 2){
		return $ops_array[$number];
	}else if($type == 88){
		return $ops_array;
	}
}

function authgame($name)
{
	if (!check($name, 'w')) {
		return 0;
		exit();
	}

	if (M('VersionGame')->where(array('name' => $name, 'status' => 1))->find()) {
		return 1;
	}
	else {
		return 0;
		exit();
	}
}

function getUrl($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '');
	$data = curl_exec($ch);
	return $data;
}

function huafei($mobile = NULL, $num = NULL, $orderid = NULL)
{
	if (empty($mobile)) {
		return NULL;
	}

	if (empty($num)) {
		return NULL;
	}

	if (empty($orderid)) {
		return NULL;
	}

	header('Content-type:text/html;charset=utf-8');
	$appkey = C('huafei_appkey');
	$openid = C('huafei_openid');
	$recharge = new \Common\Ext\Recharge($appkey, $openid);
	$telRechargeRes = $recharge->telcz($mobile, $num, $orderid);

	if ($telRechargeRes['error_code'] == '0') {
		return 1;
	}
	else {
		return NULL;
	}
}

function mlog($text)
{
	$text = addtime(time()) . ' ' . $text . "\n";
	file_put_contents(APP_PATH . '/../sitetrade.log', $text, FILE_APPEND);
}

function authUrl($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '');
	$data = curl_exec($ch);
	return $data;
}

function userid($username = NULL, $type = 'username')
{
	if ($username && $type) {
		$userid = (APP_DEBUG ? NULL : S('userid' . $username . $type));

		if (!$userid) {
			$userid = M('User')->where(array($type => $username))->getField('id');
			S('userid' . $username . $type, $userid);
		}
	}
	else {
		$userid = session('userId');
	}

	return $userid ? $userid : NULL;
}

function username($id = NULL, $type = 'id')
{
	if ($id && $type) {
		$username = (APP_DEBUG ? NULL : S('username' . $id . $type));

		if (!$username) {
			$username = M('User')->where(array($type => $id))->getField('username');
			S('username' . $id . $type, $username);
		}
	}
	else {
		$username = session('userName');
	}

	return $username ? $username : NULL;
}


function op_t($text, $addslanshes = false)
{
	$text = nl2br($text);
	$text = real_strip_tags($text);

	if ($addslanshes) {
		$text = addslashes($text);
	}

	$text = trim($text);
	return $text;
}

function text($text, $addslanshes = false)
{
	return op_t($text, $addslanshes);
}

function html($text)
{
	return op_h($text);
}

function op_h($text, $type = 'html')
{
	$text_tags = '';
	$link_tags = '<a>';
	$image_tags = '<img>';
	$font_tags = '<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';
	$base_tags = $font_tags . '<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>';
	$form_tags = $base_tags . '<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';
	$html_tags = $base_tags . '<ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><span><object><embed><param>';
	$all_tags = $form_tags . $html_tags . '<!DOCTYPE><meta><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>';
	$text = real_strip_tags($text, $$type . '_tags');

	if ($type != 'all') {
		while (preg_match('/(<[^><]+)(ondblclick|onclick|onload|onerror|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background[^-]|codebase|dynsrc|lowsrc)([^><]*)/i', $text, $mat)) {
			$text = str_ireplace($mat[0], $mat[1] . $mat[3], $text);
		}

		while (preg_match('/(<[^><]+)(window\\.|javascript:|js:|about:|file:|document\\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
			$text = str_ireplace($mat[0], $mat[1] . $mat[3], $text);
		}
	}

	return $text;
}

function real_strip_tags($str, $allowable_tags = '')
{
	return strip_tags($str, $allowable_tags);
}

function clean_cache($dirname = './Runtime/')
{
	$dirs = array($dirname);

	foreach ($dirs as $value) {
		rmdirr($value);
	}

	@(mkdir($dirname, 511, true));
}

function getSubByKey($pArray, $pKey = '', $pCondition = '')
{
	$result = array();

	if (is_array($pArray)) {
		foreach ($pArray as $temp_array) {
			if (is_object($temp_array)) {
				$temp_array = (array) $temp_array;
			}

			if ((('' != $pCondition) && ($temp_array[$pCondition[0]] == $pCondition[1])) || ('' == $pCondition)) {
				$result[] = ('' == $pKey ? $temp_array : isset($temp_array[$pKey]) ? $temp_array[$pKey] : '');
			}
		}

		return $result;
	}
	else {
		return false;
	}
}

function debug($value, $type = 'DEBUG', $verbose = false, $encoding = 'UTF-8')
{
	if (M_DEBUG || MSCODE) {
		if (!IS_CLI) {
			Common\Ext\FirePHP::getInstance(true)->log($value, $type);
		}
	}
}

function CoinClient($username, $password, $ip, $port, $timeout = 3, $headers = array(), $suppress_errors = false,$coinname = 'btc')
{
	//比特币 莱特币 处理逻辑
	if($coinname == 'btc' || $coinname == 'ltc'){
		return new \Common\Ext\CoinClient($username, $password, $ip, $port, $timeout, $headers, $suppress_errors);
	}else if($coinname == 'eth'){
		//以太坊处理逻辑
		return new \Common\Ext\EthWallet($username, $password, $ip, $port, $timeout, $headers, $suppress_errors);
	}
	
}

function coinname($type){
	if( !S('COINNAME') ){
		$coin_list = D('Coin')->get_all_name_list();
		S('COINNAME',$coin_list,3600);
	}
	$coinname = S('COINNAME');
	return $coinname[$type];
	
}

function createQRcode($save_path, $qr_data = 'PHP QR Code :)', $qr_level = 'L', $qr_size = 4, $save_prefix = 'qrcode')
{
	if (!isset($save_path)) {
		return '';
	}

	$PNG_TEMP_DIR = &$save_path;
	vendor('PHPQRcode.class#phpqrcode');

	if (!file_exists($PNG_TEMP_DIR)) {
		mkdir($PNG_TEMP_DIR);
	}

	$filename = $PNG_TEMP_DIR . 'test.png';
	$errorCorrectionLevel = 'L';

	if (isset($qr_level) && in_array($qr_level, array('L', 'M', 'Q', 'H'))) {
		$errorCorrectionLevel = &$qr_level;
	}

	$matrixPointSize = 4;

	if (isset($qr_size)) {
		$matrixPointSize = &min(max((int) $qr_size, 1), 10);
	}

	if (isset($qr_data)) {
		if (trim($qr_data) == '') {
			exit('data cannot be empty!');
		}

		$filename = $PNG_TEMP_DIR . $save_prefix . md5($qr_data . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
		QRcode::png($qr_data, $filename, $errorCorrectionLevel, $matrixPointSize, 2, true);
	}
	else {
		QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2, true);
	}

	if (file_exists($PNG_TEMP_DIR . basename($filename))) {
		return basename($filename);
	}
	else {
		return false;
	}
}

function NumToStr($num)
{
	if (!$num) {
		return $num;
	}else{
		return number_format($num,2,'.','');
	}
}
function NumTofour($num)
{
	if (!$num) {
		return $num;
	}else{
		return number_format($num,6,'.','');
	}
}
function chongzu($str){
	if(!$str){
		return $str;
	}
	return str_replace('_','/',strtoupper($str));
}


function Num($num)
{
	if (!$num) {
		return $num;
	}

	if ($num == 0) {
		return 0;
	}

	$num = round($num, 8);
	$min = 0.0001;

	if ($num <= $min) {
		$times = 0;

		while ($num <= $min) {
			$num *= 10;
			$times++;

			if (10 < $times) {
				break;
			}
		}

		$arr = explode('.', $num);
		$arr[1] = str_repeat('0', $times) . $arr[1];
		return $arr[0] . '.' . $arr[1] . '';
	}

	return ($num * 1) . '';
}

function check_verify($code, $id = ".cn")
{
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}

function get_city_ip($ip = NULL)
{
	if (empty($ip)) {
		$ip = get_client_ip();
	}

	$Ip = new Org\Net\IpLocation();
	$area = $Ip->getlocation($ip);
	$str = $area['country'] . $area['area'];
	$str = mb_convert_encoding($str, 'UTF-8', 'GBK');

	if (($ip == '127.0.0.1') || ($str == false) || ($str == 'IANA保留地址用于本地回送')) {
		$str = '未分配或者内网IP';
	}

	return $str;
}

function send_post($url, $post_data)
{
	$postdata = http_build_query($post_data);
	$options = array(
		'http' => array('method' => 'POST', 'header' => 'Content-type:application/x-www-form-urlencoded', 'content' => $postdata, 'timeout' => 15 * 60)
		);
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	return $result;
}

function request_by_curl($remote_server, $post_string)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $remote_server);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'mypost=' . $post_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'qianyunlai.com\'s CURL Example beta');
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function tradeno()
{
	return substr(str_shuffle('ABCDEFGHIJKLMNPQRSTUVWXYZ'), 0, 2) . substr(str_shuffle(str_repeat('123456789', 4)), 0, 9);
}

function tradenoa()
{
	return substr(str_shuffle('ABCDEFGHIJKLMNPQRSTUVWXYZ'), 0, 9);
}

function tradenob()
{
	return substr(str_shuffle(str_repeat('123456789', 4)), 0, 2);
}

function get_user($id, $type = NULL, $field = 'id')
{
	$key = md5('get_user' . $id . $type . $field);
	$data = S($key);

	if (!$data) {
		$data = M('User')->where(array($field => $id))->find();
		S($key, $data);
	}

	if ($type) {
		$rs = $data[$type];
	}
	else {
		$rs = $data;
	}

	return $rs;
}

function ismobile()
{
	if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
		return true;
	}

	if (isset($_SERVER['HTTP_CLIENT']) && ('PhoneClient' == $_SERVER['HTTP_CLIENT'])) {
		return true;
	}

	if (isset($_SERVER['HTTP_VIA'])) {
		return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
	}

	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');

		if (preg_match('/(' . implode('|', $clientkeywords) . ')/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}

	if (isset($_SERVER['HTTP_ACCEPT'])) {
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && ((strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false) || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
	}

	return false;
}

function send_mobiles($mobile, $content)
{
	debug(array($content, $mobile), 'send_mobile');
	$url = C('mobile_url') . '/?Uid=' . C('mobile_user') . '&Key=' . C('mobile_pwd') . '&smsMob=' . $mobile . '&smsText=' . $content;

	if (function_exists('file_get_contents')) {
		$file_contents = file_get_contents($url);
	}
	else {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);
	}

	return $file_contents;
}


function addtime($time = NULL, $type = NULL)
{
	if (empty($time)) {
		return '---';
	}

	if (($time < 2545545) && (1893430861 < $time)) {
		return '---';
	}

	if (empty($type)) {
		$type = 'Y-m-d H:i:s';
	}

	return date($type, $time);
}

function check($data, $rule = NULL, $ext = NULL)
{
	$data = trim(str_replace(PHP_EOL, '', $data));

	if (empty($data)) {
		return false;
	}

	$validate['require'] = '/.+/';
	$validate['url'] = '/^http(s?):\\/\\/(?:[A-za-z0-9-]+\\.)+[A-za-z]{2,4}(?:[\\/\\?#][\\/=\\?%\\-&~`@[\\]\':+!\\.#\\w]*)?$/';
	$validate['currency'] = '/^\\d+(\\.\\d+)?$/';
	$validate['number'] = '/^\\d+$/';
	$validate['zip'] = '/^\\d{6}$/';
	$validate['btc'] = '/^(([1-9]{1}\\d*)|([0]{1}))(\\.(\\d){1,2})?$/';
	$validate['integer'] = '/^[\\+]?\\d+$/';
	$validate['double'] = '/^[\\+]?\\d+(\\.\\d+)?$/';
	$validate['english'] = '/^[A-Za-z]+$/';
	$validate['idcard'] = '/^([0-9]{15}|[0-9]{17}[0-9a-zA-Z])$/';
	$validate['truename'] = '/^[\\x{4e00}-\\x{9fa5}A-Za-z\s]{2,20}$/u';
	$validate['username'] = '/^[a-zA-Z]{1}[0-9a-zA-Z_]{5,15}$/';
	$validate['email'] = '/^\\w+([-+.]\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*$/';
	$validate['mobile'] = '/^1(([3][0123456789])|([4][0123456789])|([5][0123456789])|([7][0123456789])|([8][0123456789]))[0-9]{8}$/';
	// $validate['password'] = '/^[a-zA-Z0-9_\\@\\#\\$\\%\\^\\&\\*\\(\\)\\!\\,\\.\\?\\-\\+\\|\\=]{6,16}$/';
	$validate['password'] = '/^[a-zA-Z0-9]{6,16}$/';
	$validate['xnb'] = '/^[a-zA-Z]$/';
	
	if (isset($validate[strtolower($rule)])) {
		$rule = $validate[strtolower($rule)];
		return preg_match($rule, $data);
	}

	$Ap = '\\x{4e00}-\\x{9fff}' . '0-9a-zA-Z\\@\\#\\$\\%\\^\\&\\*\\(\\)\\!\\,\\.\\?\\-\\+\\|\\=';
	$Cp = '\\x{4e00}-\\x{9fff}';
	$Dp = '0-9';
	$Wp = 'a-zA-Z';
	$Np = 'a-z';
	$Tp = '@#$%^&*()-+=';
	$_p = '_';
	$pattern = '/^[';
	$OArr = str_split(strtolower($rule));
	in_array('a', $OArr) && ($pattern .= $Ap);
	in_array('c', $OArr) && ($pattern .= $Cp);
	in_array('d', $OArr) && ($pattern .= $Dp);
	in_array('w', $OArr) && ($pattern .= $Wp);
	in_array('n', $OArr) && ($pattern .= $Np);
	in_array('t', $OArr) && ($pattern .= $Tp);
	in_array('_', $OArr) && ($pattern .= $_p);
	isset($ext) && ($pattern .= $ext);
	$pattern .= ']+$/u';
	return preg_match($pattern, $data);
}

function check_arr($rs)
{
	foreach ($rs as $v) {
		if (!$v) {
			return false;
		}
	}

	return true;
}

function maxArrayKey($arr, $key)
{
	$a = 0;

	foreach ($arr as $k => $v) {
		$a = max($v[$key], $a);
	}

	return $a;
}

function arr2str($arr, $sep = ',')
{
	return implode($sep, $arr);
}

function str2arr($str, $sep = ',')
{
	return explode($sep, $str);
}

function url($link = '', $param = '', $default = '')
{
	return $default ? $default : U($link, $param);
}

function rmdirr($dirname)
{
	if (!file_exists($dirname)) {
		return false;
	}

	if (is_file($dirname) || is_link($dirname)) {
		return unlink($dirname);
	}

	$dir = dir($dirname);

	if ($dir) {
		while (false !== $entry = $dir->read()) {
			if (($entry == '.') || ($entry == '..')) {
				continue;
			}

			rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
		}
	}

	$dir->close();
	return rmdir($dirname);
}

function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
	$tree = array();

	if (is_array($list)) {
		$refer = array();

		foreach ($list as $key => $data) {
			$refer[$data[$pk]] = &$list[$key];
		}

		foreach ($list as $key => $data) {
			$parentId = $data[$pid];

			if ($root == $parentId) {
				$tree[] = &$list[$key];
			}
			else if (isset($refer[$parentId])) {
				$parent = &$refer[$parentId];
				$parent[$child][] = &$list[$key];
			}
		}
	}

	return $tree;
}

function tree_to_list($tree, $child = '_child', $order = 'id', &$list = array())
{
	if (is_array($tree)) {
		$refer = array();

		foreach ($tree as $key => $value) {
			$reffer = $value;

			if (isset($reffer[$child])) {
				unset($reffer[$child]);
				tree_to_list($value[$child], $child, $order, $list);
			}

			$list[] = $reffer;
		}

		$list = list_sort_by($list, $order, $sortby = 'asc');
	}

	return $list;
}

function list_sort_by($list, $field, $sortby = 'asc')
{
	if (is_array($list)) {
		$refer = $resultSet = array();

		foreach ($list as $i => $data) {
			$refer[$i] = &$data[$field];
		}

		switch ($sortby) {
		case 'asc':
			asort($refer);
			break;

		case 'desc':
			arsort($refer);
			break;

		case 'nat':
			natcasesort($refer);
		}

		foreach ($refer as $key => $val) {
			$resultSet[] = &$list[$key];
		}

		return $resultSet;
	}

	return false;
}

function list_search($list, $condition)
{
	if (is_string($condition)) {
		parse_str($condition, $condition);
	}

	$resultSet = array();

	foreach ($list as $key => $data) {
		$find = false;

		foreach ($condition as $field => $value) {
			if (isset($data[$field])) {
				if (0 === strpos($value, '/')) {
					$find = preg_match($value, $data[$field]);
				}
				else if ($data[$field] == $value) {
					$find = true;
				}
			}
		}

		if ($find) {
			$resultSet[] = &$list[$key];
		}
	}

	return $resultSet;
}

function d_f($name, $value, $path = DATA_PATH)
{
	if (APP_MODE == 'sae') {
		return false;
	}

	static $_cache = array();
	$filename = $path . $name . '.php';

	if ('' !== $value) {
		if (is_null($value)) {
		}
		else {
			$dir = dirname($filename);

			if (!is_dir($dir)) {
				mkdir($dir, 493, true);
			}

			$_cache[$name] = $value;
			$content = strip_whitespace('<?php' . "\t" . 'return ' . var_export($value, true) . ';?>') . PHP_EOL;
			return file_put_contents($filename, $content, FILE_APPEND);
		}
	}

	if (isset($_cache[$name])) {
		return $_cache[$name];
	}

	if (is_file($filename)) {
		$value = include $filename;
		$_cache[$name] = $value;
	}
	else {
		$value = false;
	}

	return $value;
}

function DownloadFile($fileName)
{
	ob_end_clean();
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Length: ' . filesize($fileName));
	header('Content-Disposition: attachment; filename=' . basename($fileName));
	readfile($fileName);
}

function download_file($file, $o_name = '')
{
	if (is_file($file)) {
		$length = filesize($file);
		$type = mime_content_type($file);
		$showname = ltrim(strrchr($file, '/'), '/');

		if ($o_name) {
			$showname = $o_name;
		}

		header('Content-Description: File Transfer');
		header('Content-type: ' . $type);
		header('Content-Length:' . $length);

		if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
			header('Content-Disposition: attachment; filename="' . rawurlencode($showname) . '"');
		}
		else {
			header('Content-Disposition: attachment; filename="' . $showname . '"');
		}

		readfile($file);
		exit();
	}
	else {
		exit('文件不存在');
	}
}

function wb_substr($str, $len = 140, $dots = 1, $ext = '')
{
	$str = htmlspecialchars_decode(strip_tags(htmlspecialchars($str)));
	$strlenth = 0;
	$output = '';
	preg_match_all('/[' . "\x1" . '-]|[' . "\xc2" . '-' . "\xdf" . '][' . "\x80" . '-' . "\xbf" . ']|[' . "\xe0" . '-' . "\xef" . '][' . "\x80" . '-' . "\xbf" . ']{2}|[' . "\xf0" . '-' . "\xff" . '][' . "\x80" . '-' . "\xbf" . ']{3}/', $str, $match);

	foreach ($match[0] as $v) {
		preg_match('/[' . "\xe0" . '-' . "\xef" . '][' . "\x80" . '-' . "\xbf" . ']{2}/', $v, $matchs);

		if (!empty($matchs[0])) {
			$strlenth += 1;
		}
		else if (is_numeric($v)) {
			$strlenth += 0.54500000000000004;
		}
		else {
			$strlenth += 0.47499999999999998;
		}

		if ($len < $strlenth) {
			$output .= $ext;
			break;
		}

		$output .= $v;
	}

	if (($len < $strlenth) && $dots) {
		$output .= '...';
	}

	return $output;
}

function msubstr($str, $start = 0, $length, $charset = 'utf-8', $suffix = true)
{
	if (function_exists('mb_substr')) {
		$slice = mb_substr($str, $start, $length, $charset);
	}
	else if (function_exists('iconv_substr')) {
		$slice = iconv_substr($str, $start, $length, $charset);

		if (false === $slice) {
			$slice = '';
		}
	}
	else {
		$re['utf-8'] = '/[' . "\x1" . '-]|[' . "\xc2" . '-' . "\xdf" . '][' . "\x80" . '-' . "\xbf" . ']|[' . "\xe0" . '-' . "\xef" . '][' . "\x80" . '-' . "\xbf" . ']{2}|[' . "\xf0" . '-' . "\xff" . '][' . "\x80" . '-' . "\xbf" . ']{3}/';
		$re['gb2312'] = '/[' . "\x1" . '-]|[' . "\xb0" . '-' . "\xf7" . '][' . "\xa0" . '-' . "\xfe" . ']/';
		$re['gbk'] = '/[' . "\x1" . '-]|[' . "\x81" . '-' . "\xfe" . '][@-' . "\xfe" . ']/';
		$re['big5'] = '/[' . "\x1" . '-]|[' . "\x81" . '-' . "\xfe" . ']([@-~]|' . "\xa1" . '-' . "\xfe" . '])/';
		preg_match_all($re[$charset], $str, $match);
		$slice = join('', array_slice($match[0], $start, $length));
	}

	if($suffix && strlen($str)>$length){
		return $slice . '...';
	}else{
		return $slice;
	}
}

function highlight_map($str, $keyword)
{
	return str_replace($keyword, '<em class=\'keywords\'>' . $keyword . '</em>', $str);
}

function del_file($file)
{
	$file = file_iconv($file);
	@(unlink($file));
}

function status_text($model, $key)
{
	if ($model == 'Nav') {
		$text = array('无效', '有效');
	}

	return $text[$key];
}

function user_auth_sign($user)
{
	ksort($user);
	$code = http_build_query($user);
	$sign = sha1($code);
	return $sign;
}

function get_link($link_id = NULL, $field = 'url')
{
	$link = '';

	if (empty($link_id)) {
		return $link;
	}

	$link = D('Url')->getById($link_id);

	if (empty($field)) {
		return $link;
	}
	else {
		return $link[$field];
	}
}

function get_cover($cover_id, $field = NULL)
{
	if (empty($cover_id)) {
		return false;
	}

	$picture = D('Picture')->where(array('status' => 1))->getById($cover_id);

	if ($field == 'path') {
		if (!empty($picture['url'])) {
			$picture['path'] = $picture['url'];
		}
		else {
			$picture['path'] = __ROOT__ . $picture['path'];
		}
	}

	return empty($field) ? $picture : $picture[$field];
}

function get_admin_name()
{
	$user = session(C('USER_AUTH_KEY'));
	return $user['admin_name'];
}

function is_login()
{
	$user = session(C('USER_AUTH_KEY'));

	if (empty($user)) {
		return 0;
	}
	else {
		return session(C('USER_AUTH_SIGN_KEY')) == user_auth_sign($user) ? $user['admin_id'] : 0;
	}
}

function is_administrator($uid = NULL)
{
	$uid = (is_null($uid) ? is_login() : $uid);
	return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
}

function show_tree($tree, $template)
{
	$view = new View();
	$view->assign('tree', $tree);
	return $view->fetch($template);
}

function int_to_string(&$data, $map = array(
	'status' => array(1 => '正常', -1 => '删除', 0 => '禁用', 2 => '未审核', 3 => '草稿')
	))
{
	if (($data === false) || ($data === NULL)) {
		return $data;
	}

	$data = (array) $data;

	foreach ($data as $key => $row) {
		foreach ($map as $col => $pair) {
			if (isset($row[$col]) && isset($pair[$row[$col]])) {
				$data[$key][$col . '_text'] = $pair[$row[$col]];
			}
		}
	}

	return $data;
}

function hook($hook, $params = array())
{
	return \Think\Hook::listen($hook, $params);
}

function get_addon_class($name)
{
	$type = (strpos($name, '_') !== false ? 'lower' : 'upper');

	if ('upper' == $type) {
		$dir = \Think\Loader::parseName(lcfirst($name));
		$name = ucfirst($name);
	}
	else {
		$dir = $name;
		$name = \Think\Loader::parseName($name, 1);
	}

	$class = 'addons\\' . $dir . '\\' . $name;
	return $class;
}

function get_addon_config($name)
{
	$class = get_addon_class($name);

	if (class_exists($class)) {
		$addon = new $class();
		return $addon->getConfig();
	}
	else {
		return array();
	}
}

function addons_url($url, $param = array())
{
	$url = parse_url($url);
	$case = C('URL_CASE_INSENSITIVE');
	$addons = ($case ? parse_name($url['scheme']) : $url['scheme']);
	$controller = ($case ? parse_name($url['host']) : $url['host']);
	$action = trim($case ? strtolower($url['path']) : $url['path'], '/');

	if (isset($url['query'])) {
		parse_str($url['query'], $query);
		$param = array_merge($query, $param);
	}

	$params = array('_addons' => $addons, '_controller' => $controller, '_action' => $action);
	$params = array_merge($params, $param);
	return U('Addons/execute', $params);
}

function get_addonlist_field($data, $grid, $addon)
{
	foreach ($grid['field'] as $field) {
		$array = explode('|', $field);
		$temp = $data[$array[0]];

		if (isset($array[1])) {
			$temp = call_user_func($array[1], $temp);
		}

		$data2[$array[0]] = $temp;
	}

	if (!empty($grid['format'])) {
		$value = preg_replace_callback('/\\[([a-z_]+)\\]/', function($match) use($data2) {
			return $data2[$match[1]];
		}, $grid['format']);
	}
	else {
		$value = implode(' ', $data2);
	}

	if (!empty($grid['href'])) {
		$links = explode(',', $grid['href']);

		foreach ($links as $link) {
			$array = explode('|', $link);
			$href = $array[0];

			if (preg_match('/^\\[([a-z_]+)\\]$/', $href, $matches)) {
				$val[] = $data2[$matches[1]];
			}
			else {
				$show = (isset($array[1]) ? $array[1] : $value);
				$href = str_replace(array('[DELETE]', '[EDIT]', '[ADDON]'), array('del?ids=[id]&name=[ADDON]', 'edit?id=[id]&name=[ADDON]', $addon), $href);
				$href = preg_replace_callback('/\\[([a-z_]+)\\]/', function($match) use($data) {
					return $data[$match[1]];
				}, $href);
				$val[] = '<a href="' . U($href) . '">' . $show . '</a>';
			}
		}

		$value = implode(' ', $val);
	}

	return $value;
}

function get_config_type($type = 0)
{
	$list = C('CONFIG_TYPE_LIST');
	return $list[$type];
}

function get_config_group($group = 0)
{
	$list = C('CONFIG_GROUP_LIST');
	return $group ? $list[$group] : '';
}

function parse_config_attr($string)
{
	$array = preg_split('/[,;\\r\\n]+/', trim($string, ',;' . "\r\n"));

	if (strpos($string, ':')) {
		$value = array();

		foreach ($array as $val) {
			list($k, $v) = explode(':', $val);
			$value[$k] = $v;
		}
	}
	else {
		$value = $array;
	}

	return $value;
}

function parse_field_attr($string)
{
	if (0 === strpos($string, ':')) {
		return eval(substr($string, 1) . ';');
	}

	$array = preg_split('/[,;\\r\\n]+/', trim($string, ',;' . "\r\n"));

	if (strpos($string, ':')) {
		$value = array();

		foreach ($array as $val) {
			list($k, $v) = explode(':', $val);
			$value[$k] = $v;
		}
	}
	else {
		$value = $array;
	}

	return $value;
}

function api($name, $vars = array())
{
	$array = explode('/', $name);
	$method = array_pop($array);
	$classname = array_pop($array);
	$module = ($array ? array_pop($array) : 'Common');
	$callback = $module . '\\Api\\' . $classname . 'Api::' . $method;

	if (is_string($vars)) {
		parse_str($vars, $vars);
	}

	return call_user_func_array($callback, $vars);
}

function think_encrypt($data, $key = '', $expire = 0)
{
	$key = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
	$data = base64_encode($data);
	$x = 0;
	$len = strlen($data);
	$l = strlen($key);
	$char = '';
	$i = 0;

	for (; $i < $len; $i++) {
		if ($x == $l) {
			$x = 0;
		}

		$char .= substr($key, $x, 1);
		$x++;
	}

	$str = sprintf('%010d', $expire ? $expire + time() : 0);
	$i = 0;

	for (; $i < $len; $i++) {
		$str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)) % 256));
	}

	return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
}

function think_decrypt($data, $key = '')
{
	$key = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
	$data = str_replace(array('-', '_'), array('+', '/'), $data);
	$mod4 = strlen($data) % 4;

	if ($mod4) {
		$data .= substr('====', $mod4);
	}

	$data = base64_decode($data);
	$expire = substr($data, 0, 10);
	$data = substr($data, 10);

	if ((0 < $expire) && ($expire < time())) {
		return '';
	}

	$x = 0;
	$len = strlen($data);
	$l = strlen($key);
	$char = $str = '';
	$i = 0;

	for (; $i < $len; $i++) {
		if ($x == $l) {
			$x = 0;
		}

		$char .= substr($key, $x, 1);
		$x++;
	}

	$i = 0;

	for (; $i < $len; $i++) {
		if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
			$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
		}
		else {
			$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
		}
	}

	return base64_decode($str);
}

function data_auth_sign($data)
{
	if (!is_array($data)) {
		$data = (array) $data;
	}

	ksort($data);
	$code = http_build_query($data);
	$sign = sha1($code);
	return $sign;
}

function format_bytes($size, $delimiter = '')
{
	$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	$i = 0;

	for (; $i < 5; $i++) {
		$size /= 1024;
	}

	return round($size, 2) . $delimiter . $units[$i];
}

function set_redirect_url($url)
{
	cookie('redirect_url', $url);
}

function get_redirect_url()
{
	$url = cookie('redirect_url');
	return empty($url) ? __APP__ : $url;
}

function time_format($time = NULL, $format = 'Y-m-d H:i')
{
	$time = ($time === NULL ? NOW_TIME : intval($time));
	return date($format, $time);
}

function create_dir_or_files($files)
{
	foreach ($files as $key => $value) {
		if ((substr($value, -1) == '/') && !is_dir($value)) {
			mkdir($value);
		}
		else {
			@(file_put_contents($value, ''));
		}
	}
}

function get_table_name($model_id = NULL)
{
	if (empty($model_id)) {
		return false;
	}

	$Model = M('Model');
	$name = '';
	$info = $Model->getById($model_id);

	if ($info['extend'] != 0) {
		$name = $Model->getFieldById($info['extend'], 'name') . '_';
	}

	$name .= $info['name'];
	return $name;
}

function get_model_attribute($model_id, $group = true)
{
	static $list;

	if (empty($model_id) || !is_numeric($model_id)) {
		return '';
	}

	if (empty($list)) {
		$list = S('attribute_list');
	}

	if (!isset($list[$model_id])) {
		$map = array('model_id' => $model_id);
		$extend = M('Model')->getFieldById($model_id, 'extend');

		if ($extend) {
			$map = array(
				'model_id' => array(
					'in',
					array($model_id, $extend)
					)
				);
		}

		$info = M('Attribute')->where($map)->select();
		$list[$model_id] = $info;
	}

	$attr = array();

	foreach ($list[$model_id] as $value) {
		$attr[$value['id']] = $value;
	}

	if ($group) {
		$sort = M('Model')->getFieldById($model_id, 'field_sort');

		if (empty($sort)) {
			$group = array(1 => array_merge($attr));
		}
		else {
			$group = json_decode($sort, true);
			$keys = array_keys($group);

			foreach ($group as &$value) {
				foreach ($value as $key => $val) {
					$value[$key] = $attr[$val];
					unset($attr[$val]);
				}
			}

			if (!empty($attr)) {
				$group[$keys[0]] = array_merge($group[$keys[0]], $attr);
			}
		}

		$attr = $group;
	}

	return $attr;
}

function get_table_field($value = NULL, $condition = 'id', $field = NULL, $table = NULL)
{
	if (empty($value) || empty($table)) {
		return false;
	}

	$map[$condition] = $value;
	$info = M(ucfirst($table))->where($map);

	if (empty($field)) {
		$info = $info->field(true)->find();
	}
	else {
		$info = $info->getField($field);
	}

	return $info;
}

function get_tag($id, $link = true)
{
	$tags = D('Article')->getFieldById($id, 'tags');

	if ($link && $tags) {
		$tags = explode(',', $tags);
		$link = array();

		foreach ($tags as $value) {
			$link[] = '<a href="' . U('/') . '?tag=' . $value . '">' . $value . '</a>';
		}

		return join($link, ',');
	}
	else {
		return $tags ? $tags : 'none';
	}
}

function addon_model($addon, $model)
{
	$dir = \Think\Loader::parseName(lcfirst($addon));
	$class = 'addons\\' . $dir . '\\model\\' . ucfirst($model);
	$model_path = ONETHINK_ADDON_PATH . $dir . '/model/';
	$model_filename = \Think\Loader::parseName(lcfirst($model));
	$class_file = $model_path . $model_filename . '.php';

	if (!class_exists($class)) {
		if (is_file($class_file)) {
			\Think\Loader::import($model_filename, $model_path);
		}
		else {
			E('插件' . $addon . '的模型' . $model . '文件找不到');
		}
	}

	return new $class($model);
}

function check_server()
{
	return true;
}


function msgetUrl($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '');
	$data = curl_exec($ch);
	return $data;
}

function msCurl($url, $data, $type = 0)
{
	debug(array('url' => $url, 'parm' => $data, 'type' => $type), 'msCurl start');
	$data = array_merge(array('MSCODE' => MSCODE), $data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	$data = curl_exec($ch);
	debug($data, 'msCurl res');

	if ($type) {
		return $data;
	}

	$res = json_decode($data, true);

	if (!$res) {
		msMes('30001');
	}

	return $res;
}

function msMes($msg)
{
	debug($msg, 'Auth_RES');

	if (S('servers_url')) {
		echo time();
	}
	else {
		if (MODULE_NAME == 'Admin') {
			$url = U('Admin/Index/index');
		}
		else {
			$url = U('Home/Index/index');
		}

		redirect($url);
	}

	exit();
}

function checkstr($strsql)
{   
	//检测字符串是否有注入风险
    $strsql = str_replace("'","",$strsql);
	$strsql=trim($strsql);
	$check=preg_match('/select|SELECT|or|OR|and|AND|char|CHAR|create|CREATR|drop|DROP|database|DATABASE|table|TABLE|insert|INSERT|script|SCRIPT|function|FUNCTION|update|UPDATE|delete|DELETE|exec|EXEC|system|SYSTEM|passthru|PASSTHRU|shell_exec|SHELL_EXEC|<|\`|\%|\"|\'|\/\*|\*|\.\.\/|\.\/|union|UNION|into|INTO|load_file|LOAD_FILE|outfile|OUTFILE/i',$strsql);
  
	if($check)
	{   
		return 1;
	}        		          
			   
}

function smssend($mobile){
	$sms = new \Common\Ext\AliSms("LTAId9dBu3YRvdBE","7vQ7yhKu23PKaqcEEu5foNv2ruL7AV");
	$mobile_code = $sms->random(6,1);
	$response = $sms->sendSms(
		"币付在线", // 短信签名
		"SMS_93455001", // 短信模板编号
		$mobile, // 短信接收者
		Array(  // 短信模板中字段的值
			"number"=>$mobile_code,
		)
	);
	
	if($response->Code == "OK"){
		return $mobile_code;
	}else{
		return 0;
	}
}

function send_email($to, $name, $subject = '', $body = '', $attachment = null){

	$config = C('think_email');

	Vendor("PHPMailer.phpmailer","",".php");
	
	$mail = new PHPMailer(); //PHPMailer对象

	$mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码

	$mail->IsSMTP(); // 设定使用SMTP服务

	$mail->SMTPDebug = 0; // 关闭SMTP调试功能

	$mail->SMTPAuth = true; // 启用 SMTP 验证功能

	$mail->SMTPSecure = 'ssl'; // 使用安全协议

	$mail->Host = $config['smtp_host']; // SMTP 服务器

	$mail->Port = $config['smtp_port']; // SMTP服务器的端口号

	$mail->Username = $config['smtp_user']; // SMTP服务器用户名

	$mail->Password = $config['smtp_pass']; // SMTP服务器密码

	$mail->SetFrom($config['from_email'], $config['from_name']);

	$replyEmail = $config['reply_email']?$config['reply_email']:$config['from_email'];

	$replyName = $config['reply_name']?$config['reply_name']:$config['from_name'];

	$mail->AddReplyTo($replyEmail, $replyName);

	$mail->Subject = $subject;

	$mail->AltBody = "为了查看该邮件，请切换到支持 HTML 的邮件客户端"; 

	$mail->MsgHTML($body);

	$mail->AddAddress($to, $name);

	if(is_array($attachment)){
		foreach ($attachment as $file){
			is_file($file) && $mail->AddAttachment($file);
		}
	}
	$result = $mail->send();
	if($result){
		return true;
	}else{
		return false;
	}
}

function set_token($name) {
	$token_name = $name.'token';
	$token_value = md5(microtime(true));
	session($token_name,$token_value);
	return $token_value;
}
function valid_token($name,$token) {
	$token_name = $name.'token';
	$return = $token === session($token_name) ? true : false;
	set_token($name);
	return $return;
}
function remove_ling($number){
	if(empty($number)){
		return 0;
	}
	$number_arr = explode(".",$number);
	$k=0;
	for($i=1;$i<strlen($number_arr[1]);$i++){
		if(substr($number_arr[1],$i-1,$i)>0){
			$k=$i;
		}
	}
	if($k>0){
		$baoliu = substr($number_arr[1],0,$k);
		$result = $number_arr[0].".".$baoliu;
	}else{
		$result = $number_arr[0];
	}
	return $result;
}
function chkchuanhao($session_id,$userid){
	$result = M('user_log')->where(array('session_key'=>$session_id))->order('id desc')->limit(1)->find();
	if(!empty($result) && $result['userid']!=$userid && $result['state']==1){
		return true;
	}else{
		return false;
	}
}
function getbi_frommarket($market){
	$arr = explode("_",$market);
	$coin_info = M('coin')->where(array('name'=>$arr[0]))->find();
	if(!empty($coin_info)){
		return $coin_info;
	}else{
		return array();
	}
}
function getmarket_frombi($coin,$rmb){
	if(empty($rmb)){
		$rmb='btc';
	}
	$market = $coin."_".$rmb;
	$market_info = M('market')->where(array('name'=>$market))->find();
	if(!empty($market_info)){
		return $market_info;
	}else{
		return array();
	}
}
function shiming($uid){
	$res = 0;
	$user = M('User')->where(array('id'=>$uid))->find();
	if(!empty($user)){
		if(!empty($user['idcard']) && !empty($user['truename'])){
			$res++;
		}
		if(!empty($user['idcard_zheng']) && !empty($user['idcard_fan']) && !empty($user['idcard_shouchi'])){
			$res++;
		}
		if(!empty($user['is_agree']) && $user['is_agree']==1){
			$res++;
		}
	}
	return $res;
}
function curl_get($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	//参数为1表示传输数据，为0表示直接输出显示
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//参数为0表示不带头文件，为1表示带头文件
	curl_setopt($ch, CURLOPT_HEADER,0);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}
function addstar($str,$type){
	if(empty($type) || (!empty($type) && $type=="mobile")){
		$result = substr_replace($str, '****', 3, 4);
	}else{
		$result = substr_replace($str, '***', 1, 3);
	}
	return $result;
}
//获取交易标的币种名数组
function getrmbarr(){
	$markets = M('Market')->where(array('status'=>1))->select();
	$rmb = array();
	foreach($markets as $val){
		$rmb[] = explode("_",$val['name'])[1];
	}
	if(!empty($rmb)){
		array_unique($rmb);
	}
	return $rmb;
}
//获取单位转换倍数
function getoperator($rmb,$jjcoin){
	if($rmb == $jjcoin){
		return 1;
	}else{
		if($jjcoin=="rmb"){
			$field = $rmb."_rmb";
			$operator = M('Config')->where('id=1')->getField($field);
		}elseif($jjcoin=="btc"){
			$eth_rmb = M('Config')->where('id=1')->getField('eth_rmb');
			$btc_rmb = M('Config')->where('id=1')->getField('btc_rmb');
			$operator = round($eth_rmb/$btc_rmb,4);
		}elseif($jjcoin=="usd"){
			$field = $rmb."_rmb";
			$res = M('Config')->where('id=1')->getField($field);
			$exchange = M('Config')->where('id=1')->getField('usd_rmb');
			$operator = round($res/$exchange,4);
		}
		return $operator;
	}
}
//获取货币符号
function getsymbol($jjcoin){
	if($jjcoin=="rmb"){
		return "￥";
	}elseif($jjcoin=="usd"){
		return "$";
	}elseif($jjcoin=="btc"){
		return "B";
	}
}
function gettodayche($market){
	if(!$market){
		return 0;
	}else{
		$start=strtotime(date("Y-m-d H:i:s"))-24*3600;
		$tot=M('trade_log')->where("market='".$market."' and addtime>".$start)->sum('mum');
		return $tot;
	}
}
function huansuan($val,$from,$to){
	$btc_rmb = M('Config')->where('id=1')->getField('btc_rmb');
	$usd_rmb = M('Config')->where('id=1')->getField('usd_rmb');
	$eth_rmb = M('Config')->where('id=1')->getField('eth_rmb');
	if($from=='btc'&&$to=='rmb'){
		$new_val=$val*$btc_rmb;
		return $new_val;
	}elseif($from=='btc'&&$to=='usd'){
		$new_val=$val*$btc_rmb*(1/$usd_rmb);
		return $new_val;
	}elseif($from=='eth'&&$to=='rmb'){
		$new_val=$val*$eth_rmb;
		return $new_val;
	}elseif($from=='eth'&&$to=='usd'){
		$new_val=$val*$eth_rmb*(1/$usd_rmb);
		return $new_val;
	}else{
		if($to=='btc'||$to=='eth'){
			$market=$from.'_'.$to;
			$market_info = M('market')->where(array('name'=>$market))->find();
			$new_val=$val*$market_info['new_price'];
			return $new_val;
		}else{
			$market=$from.'_btc';
			$market_info = M('market')->where(array('name'=>$market))->find();
			if(!empty($market_info)){
				if($to=='rmb'){
					$new_val=$val*$market_info['new_price']*$btc_rmb;
					return $new_val;
				}
				if($to=='usd'){
					$new_val=$val*$market_info['new_price']*$btc_rmb*(1/$usd_rmb);
					return $new_val;
				}
			}else{
				$market=$from.'_eth';
				$market_info = M('market')->where(array('name'=>$market))->find();
				if(!empty($market_info)){
					if($to=='rmb'){
						$new_val=$val*$market_info['new_price']*$eth_rmb;
						return $new_val;
					}
					if($to=='usd'){
						$new_val=$val*$market_info['new_price']*$eth_rmb*(1/$usd_rmb);
						return $new_val;
					}
				}
			}
		}
	}
	return $val;
}
function convmarket($market){
	$arr = explode("_",$market);
	$str = implode("/",$arr);
	return strtoupper($str);
}

/**
     * @length 要生成的随机字符串长度
	     
     */
function randomkeys($length)   
{   
   $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    for($i=0;$i<$length;$i++)   
    {   
        $key .= $pattern{mt_rand(0,35)};    //生成php随机数   
    }   
    return $key;   
}


/**
     * @brief 使用HMAC-SHA256算法生成oauth_signature签名值
     *
     * @param $key 密钥
     * @param $str 源串
     *
     * @return 签名值
     */
function getSignature($str, $key) {
	$signature = "";
	if (function_exists('hash_hmac')) {
		$signature = base64_encode(hash_hmac("sha256", $str, $key, true));
	} else {
		$blocksize = 64;
		$hashfunc = 'sha1';
		if (strlen($key) > $blocksize) {
			$key = pack('H*', $hashfunc($key));
		}
		$key = str_pad($key, $blocksize, chr(0x00));
		$ipad = str_repeat(chr(0x36), $blocksize);
		$opad = str_repeat(chr(0x5c), $blocksize);
		$hmac = pack(
				'H*', $hashfunc(
						($key ^ $opad) . pack(
								'H*', $hashfunc(
										($key ^ $ipad) . $str
								)
						)
				)
		);
		$signature = base64_encode($hmac);
	}
	return $signature;
}

/*
	*@param code 返回参数 0 失败   1 成功
	*@param msg 返回消息
	*@param data 返回数据
*/
 function return_json($code = 1,$msg = 'success',$data = NULL){

 	$data = array(
 		'code'=> $code,
 		'msg'=> $msg,
 		'data'=> $data
 		);

 	 echo json_encode($data,JSON_UNESCAPED_UNICODE);
 	 exit;

 }

 /*
	返回选择的时间
 */
function get_switch_time($type){
	
		switch ($type)
		{
			case "1min":
			$time = 1;
				break;
			case "3min":
				$time = 3;
				break;
			case "5min":
				$time = 5;
				break;
			case "15min":
				$time = 15;
				break;
			case "30min":
				$time = 30;
				break;
			case "1hour":
				$time = 60;
				break;
			case "2hour":
				$time = 120;
				break;
			case "4hour":
				$time = 240;
				break;
			case "6hour":
				$time = 360;
				break;
			case "12hour":
				$time = 720;
				break;
			case "1day":
				$time = 1440;
				break;
			case "3day":
				$time = 4320;
				break;
			case "1week":
				$time = 10080;
				break;
			default:
				$time = 15;
				break;
		}

		return $time;
}

/*
	获取ip地址
*/
function getIp(){
	$ip='未知IP';
	
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		return is_ip($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:$ip;
	}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		return is_ip($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$ip;
	}else{
		return is_ip($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:$ip;
	}
}

/*
	判断其是否属于ip
*/
function is_ip($str){
	$ip=explode('.',$str);
	for($i=0;$i<count($ip);$i++){  
		if($ip[$i]>255){  
			return false;  
		}  
	}  
	return preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/',$str);  
}




?>