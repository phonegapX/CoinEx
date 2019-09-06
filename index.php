<?php
    // 定义系统编码
    header("Content-Type: text/html;charset=utf-8");
	
    // 定义应用路径
    define('APP_PATH', './Application/');
    // 定义缓存路径
    define('RUNTIME_PATH', './Runtime/');
    // 定义备份路径
    define('DATABASE_PATH', './Database/');
    // 定义钱包路径
    define('COIN_PATH', './Coin/');
    // 定义备份路径
    define('UPLOAD_PATH', './Upload/');
    // 定义数据库类型
    define('DB_TYPE', 'mysql');
    // 定义数据库地址
    define('DB_HOST', '127.0.0.1');
    // 定义数据库名
    define('DB_NAME', 'yingbt');
    // 定义数据库账号
    define('DB_USER', 'root');
    // 定义数据库密码
    define('DB_PWD', 'root');
    // 定义数据库端口
    define('DB_PORT', '3306');
    // 开启演示模式
    define('APP_DEMO',0);
    // 开始调试模式
    // define('M_DEBUG', 1);
    define('APP_DEBUG', true); 
    
    // 后台安全入口
    define('ADMIN_KEY', 'yourcoin');
    //定义授权码
    define('MSCODE', '95D3A7E98EE9F913B462B87C73DS');

	if(!empty($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']=="m.bibi.com" ||wherecome() || $_SERVER['HTTP_HOST']=="192.168.1.136"){
		define('WHERECOME','Mobile');
	}else{
		define('WHERECOME','Home');
	}
        function wherecome()
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
    // 引入入口文件
    require './ThinkPHP/ThinkPHP.php';
?>
