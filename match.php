<?php
header("Content-Type: text/html; charset=UTF-8");
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
	$fp = fopen("/var/www/html/bibijiaoyi/match.txt", "w+");
	if(flock($fp,LOCK_EX | LOCK_NB))
	{
		http_gets("http://www.bibijiaoyi.com/Home/Queue/checkUsercoin");
		flock($fp,LOCK_UN);
	}
	fclose($fp);
	echo "本次执行完毕";
}
?>