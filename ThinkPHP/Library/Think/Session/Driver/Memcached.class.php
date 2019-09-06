<?php
// +----------------------------------------------------------------------+
// | ThinkPHP [ Memcached Session 驱动 ]
// +----------------------------------------------------------------------+
// | PHP version 5.5                                                       
// +----------------------------------------------------------------------+
// | Copyright (c) 2013-2015                              
// +----------------------------------------------------------------------+
// | Authors: Original Author <admin@ipingtai.com>                     
// |          Sinda <QQ316998743>                               
// +----------------------------------------------------------------------+
//
namespace Think\Session\Driver;
class Memcached{
	
	protected $lifeTime     = 7200;
	protected $sessionName  = '';
	protected $handle       = null;	
	
	
    /**
     * 打开Session
	 * @Sinda admin@ipingtai.com
     * @access public 
     * @param string $savePath 
     * @param mixed $sessName  
     */

	public function open($savePath, $sessName) {
		$this->lifeTime     = C('SESSION_EXPIRE') ? C('SESSION_EXPIRE') : $this->lifeTime;
        $options            = array(
            'timeout'       => C('SESSION_TIMEOUT') ? C('SESSION_TIMEOUT') : 1,
            'persistent'    => C('SESSION_PERSISTENT') ? C('SESSION_PERSISTENT') : 0
        );
		$this->handle       = new \Memcached();
		//dump($this->handle);exit;
        $hosts              = explode(',', C('MEMCACHE_HOST'));
        $ports              = explode(',', C('MEMCACHE_PORT'));
        //$usernames           = explode(',', C('MEMCACHE_USERNAME'));
        //$passwords           = explode(',', C('MEMCACHE_PASSWORD'));
		
		//循环缓存信息，主要是用于集群部署
		foreach ($hosts as $i=>$host) {
            $port           = isset($ports[$i]) ? $ports[$i] : $ports[0];
            //$username       = isset($usernames[$i]) ? $usernames[$i] : $usernames[0];
            //$password       = isset($passwords[$i]) ? $passwords[$i] : $passwords[0];
            $this->handle->addServer($host, $port);
			$this->handle->setOption(\Memcached::OPT_COMPRESSION, false); //关闭压缩功能
			$this->handle->setOption(\Memcached::OPT_BINARY_PROTOCOL, true); //使用binary二进制协议
			//$this->handle->setSaslAuthData($username,$password); 			
        }
		return true;
	}

    /**
     * 关闭Session 
     * @access public 
	 * 感谢@muyuto 已经TP官方朋友的提出
     */
	public function close() {
		$this->handle->close();
		return true;
	}

    /**
     * 读取Session 
     * @access public 
     * @param string $sessID 
     */
	public function read($sessID) {
        return $this->handle->get($this->sessionName.$sessID);
	}

    /**
     * 写入Session 
     * @access public 
     * @param string $sessID 
     * @param String $sessData  
     */
	public function write($sessID, $sessData) {
		return $this->handle->set($this->sessionName.$sessID, $sessData, 0, $this->lifeTime);
	}

    /**
     * 删除Session 
     * @access public 
     * @param string $sessID 
     */
	public function destroy($sessID) {
		return $this->handle->delete($this->sessionName.$sessID);
	}

    /**
     * Session 垃圾回收
     * @access public 
     * @param string $sessMaxLifeTime 
     */
	public function gc($sessMaxLifeTime) {
		return true;
	}
}//End
?>