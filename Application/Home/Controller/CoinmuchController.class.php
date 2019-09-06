<?php
namespace Home\Controller;

class CoinmuchController extends HomeController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error(L('sCommon_ffcz'));
		}
	}
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index(){
		$this->display();
	}

	
}

?>