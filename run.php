<?php
header("Content-Type: text/html; charset=UTF-8");

var_dump(function_exists("curl_init"));

function http_gets($url){
	$oCurl = curl_init();
	if(stripos($url,"https://")!==FALSE){
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
	}
	curl_setopt($oCurl, CURLOPT_URL, $url);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	$sContent = curl_exec($oCurl);
	$aStatus = curl_getinfo($oCurl);
	curl_close($oCurl);
	if(intval($aStatus["http_code"])==200){
		return true;
	}else{
		return false;
	}
}
if(PHP_SAPI == 'cli'){
	$domain="www.bibijiaoyi.com";//填写网站域名
	$queues = array(
		'Admin/Login/queue',      //记录最后执行时间
		'Home/Queue/tendency',      //计算趋势
		'Home/Queue/houprice',      //更新市场价格
		'Home/Queue/paicuo',      //自动匹配交易
		'Home/Queue/qianbao',      //同步钱包转入记录
		'Home/Queue/move',         //处理交易状态:正常
		'Home/Queue/yichang',      //处理交易状态:异常
		'Home/Queue/myzcQueue',    //自动处理转账
		'Home/Queue/eth_query',	   //自动抓取eth区块交易记录
	);
	// $fp = fopen("/data/thyjy/lockrun.txt", "w+");

	$fp = fopen("/var/www/html/bibijiaoyi/lockrun.txt", "w+");
	if(flock($fp,LOCK_EX | LOCK_NB))
	{
		for($i=0;$i<count($queues);$i++){
			http_gets("http://".$domain."/".$queues[$i]);
		}
		flock($fp,LOCK_UN);
	}
	fclose($fp);
	echo "本次执行完毕";
}
?>