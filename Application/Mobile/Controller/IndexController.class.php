<?php
namespace Mobile\Controller;

class IndexController extends MobileController
{


	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","article","coin_list","qq");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("非法操作！");
		}
	}

	public function __construct() {
		parent::__construct();
		$this->common_content();
	}

	// 首页界面
	public function index(){

		// 幻灯片信息------S

		$indexAdver = (APP_DEBUG ? null : S('mobile_index_indexAdver'));

		if (!$indexAdver) {
			if(LANG_SET == 'zh-cn'){
				$indexAdver = M('Adver')->where(array('status' => 1,'look' => 1,'fenlei'=>0))->order('sort asc')->select();
			}else{
				$indexAdver = M('Adver')->where(array('status' => 1,'look' => 1,'fenlei'=>1))->order('sort asc')->select();
			}
			// $indexAdver = M('Adver')->where(array('status' => 1,'look'=>1))->order('sort asc')->select();
			S('mobile_index_indexAdver', $indexAdver);
		}

		$this->assign('indexAdver', $indexAdver);

		// 幻灯片信息------E
		



		// 获取最新公告信息------S

		$indexArticle = (APP_DEBUG ? null : S('mobile_index_indexArticle'));

		if (!$indexArticle) {
			$indexArticle = M('Article')->where(array('type' => 'notice', 'status' => 1, 'index' => 1))->order('id desc')->find();
			S('mobile_index_indexArticleType', $indexArticle);
		}

		$this->assign('indexArticle', $indexArticle);

		// 获取最新公告信息------E

		$this->assign('data', $data);

		$this->display();
	}

	// 充提币链接
	public function article()

	{

		if (!userid()) {

			redirect('/Login/index');

		}		

		$this->display();

	}

	// 充提币链接
	public function coin_list()

	{

		if (!userid()) {

			redirect('/Login/index');

		}		

		$this->display();

	}

	// 在线客服
	public function qq()

	{

		if (!userid()) {

			redirect('/Login/index');

		}		

		$this->display();

	}
}
