<?php
class RandEx {
	public function random($length=6, $type='string', $convert=0){
		$config = array(
			'number'=>'1234567890',
			'letter'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'string'=>'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
			'all'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
		);
		
		if(!isset($config[$type])) $type = 'string';
		$string = $config[$type];
		
		$code = '';
		$strlen = strlen($string) -1;
		for($i = 0; $i < $length; $i++){
			$code .= $string{mt_rand(0, $strlen)};
		}
		if(!empty($convert)){
			$code = ($convert > 0)? strtoupper($code) : strtolower($code);
		}
		return $code;
	}
}