<?php
namespace Mobile\Controller;

class ArticleController extends MobileController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("about","about_detail","notice","notice_detail","news","index","detail","help_list","jieshao","jieshao_detail");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("非法操作！");
		}
	}
	
	public function __construct() {
		parent::__construct();
		$this->common_content();
	}

	public function help_list()
	{

		$where = array();
		$where['status'] = 1;

		$list = M('ArticleType')->where($where)->select();

		$list_f = array();
		$list_z = array();
		$list_shang = array();

		foreach ($list as $k => $v) {
			if($v['name'] == 'notice' || !$v['shang']){
				continue;
			}

			$list_z[] = $v;

			$list_shang[] = $v['shang'];
		}

		$list_shang = array_unique($list_shang);

		foreach ($list as $k => $v) {

			if($v['shang'] || $v['name'] == 'notice'){
				continue;
			}

			if(in_array($v['name'], $list_shang)){
				$list[$k]['is_shang'] = 1;
			}else{
				$list[$k]['is_shang'] = 0;
			}

			$list_f[] = $list[$k];
		}

		// echo '<pre>';
		// var_dump($list);
		// echo '</pre>';die();
		
		$this->assign('list', $list_f);
		$this->assign('list_z', $list_z);
		$this->display();
	}
	
	public function index($id = NULL)
	{
		if (empty($id)) {
			redirect(U('Article/detail'));
		}

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!check($id, 'd')) {
			redirect(U('Article/detail'));
		}

		$Articletype = M('ArticleType')->where(array('id' => $id))->find();
		$ArticleTypeList = M('ArticleType')->where(array('status' => 1, 'index' => 1, 'shang' => $Articletype['shang']))->order('sort asc ,id asc')->select();
		$Articleaa = M('Article')->where(array('id' => $ArticleTypeList[0]['id']))->find();
		$this->assign('shang', $Articletype);

		foreach ($ArticleTypeList as $k => $v) {
			$ArticleTypeLista[$v['name']] = $v;
		}

		$this->assign('ArticleTypeList', $ArticleTypeLista);
		$this->assign('data', $Articleaa);
		$where = array('type' => $Articletype['name']);
		$Model = M('Article');
		$count = $Model->where($where)->count();
		$Page = new \Think\Page($count, 10);
		$show = $Page->show();
		$list = $Model->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function news($id = NULL)
	{

		// 热门新闻id固定了19，如需变动在改id
		$id = 20;

		$Articletype = M('ArticleType')->where(array('id' => $id))->find();
		$where = array('type' => $Articletype['name']);
		$Model = M('Article');
		$count = $Model->where($where)->count();

		$Page = new \Think\Page1($count, 10);
		$show = $Page->show();
		$list = $Model->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			// 踢出内容里的html标签
			$list[$k]['content'] = strip_tags($v['content']);
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function about($id = NULL)
	{
		// 关于平台shang字段固定了aboutus
		$shang = 'aboutus';

		$list = M('ArticleType')->where(array('shang' => $shang))->select();

		foreach ($list as $k => $v) {
			// 踢出内容里的html标签
			$list[$k]['content'] = strip_tags($v['content']);
		}

		$this->assign('list', $list);
		$this->display();
	}


	public function about_detail($id = NULL)
	{
		if (empty($id)) {
			$id = 1;
		}

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!check($id, 'd')) {
			$id = 1;
		}

		$data = M('ArticleType')->where(array('id' => $id))->find();
		$this->assign('data', $data);
		$this->display();
	}



	/*
	*
	*****官方公告
	*
	*/

	// public function notice($id = NULL)
	// {

	// 	// 官方公告标识固定了notice
	// 	$name = 'notice';
	// 	$id = 19;	
	// 	//$where = array('type' => $name);
	// 	$Article_first_info = M('ArticleType')->where(array('id' => $id))->find();
	// 	$Model = M('Article');
	// 	$wheres = array();
	// 	$wheres['status'] = 1;
	// 	$wheres['type'] = $Article_first_info['name'];
	// 	$count = $Model->where($wheres)->count();

	// 	$Page = new \Think\Page1($count, 10);
	// 	$show = $Page->show();
	// 	$list = $Model->where($wheres)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

	// 	foreach ($list as $k => $v) {
	// 		// 踢出内容里的html标签
	// 		$list[$k]['content'] = strip_tags($v['content']);
	// 	}

	// 	$this->assign('list', $list);
	// 	$this->assign('page', $show);
	// 	$this->display();
	// }
	public function notice($name = NULL)
	{
		// 过滤非法字符----------------S

		if (checkstr($name)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if(!$name){
			$this->error('暂无该信息1');
		}

		$Article_first_info = M('ArticleType')->where(array('name' => $name))->find();

		if(!$Article_first_info){
			$this->error('暂无该信息2');
		}

		$this->assign('Article_first_info', $Article_first_info);

		$Model = M('Article');
		$wheres = array();
		$wheres['status'] = 1;
		$wheres['type'] = $Article_first_info['name'];
		$count = $Model->where($wheres)->count();

		$Page = new \Think\Page1($count, 10);
		$show = $Page->show();
		$list = $Model->where($wheres)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			// 踢出内容里的html标签
			$list[$k]['content'] = strip_tags($v['content']);
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function notice_detail($id = NULL)
	{
		if (empty($id)) {
			$id = 1;
		}

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!check($id, 'd')) {
			$id = 1;
		}

		$data = M('Article')->where(array('id' => $id))->find();
		$data_s = M('ArticleType')->where(array('name' => $data['type']))->find();
		$this->assign('data', $data);
		$this->assign('data_s', $data_s);
		$this->display();
	}
	public function jieshao($name = NULL)
	{
		// 过滤非法字符----------------S

		if (checkstr($name)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if(!$name){
			$this->error('暂无该信息1');
		}

		$Article_first_info = M('ArticleType')->where(array('name' => $name))->find();

		if(!$Article_first_info){
			$this->error('暂无该信息2');
		}

		$this->assign('Article_first_info', $Article_first_info);

		$Model = M('Article');
		$wheres = array();
		$wheres['status'] = 1;
		$wheres['type'] = $Article_first_info['name'];
		$count = $Model->where($wheres)->count();

		$Page = new \Think\Page1($count, 10);
		$show = $Page->show();
		$list = $Model->where($wheres)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			// 踢出内容里的html标签
			$list[$k]['content'] = strip_tags($v['content']);
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}
		public function jieshao_detail($id = NULL)
	{
		if (empty($id)) {
			$id = 1;
		}

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!check($id, 'd')) {
			$id = 1;
		}

		$data = M('Article')->where(array('id' => $id))->find();
		$data_s = M('ArticleType')->where(array('name' => $data['type']))->find();
		$this->assign('data', $data);
		$this->assign('data_s', $data_s);
		$this->display();
	}
	public function detail($id = NULL)
	{
		if (empty($id)) {
			$id = 1;
		}

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!check($id, 'd')) {
			$id = 1;
		}

		$data = M('Article')->where(array('id' => $id))->find();
		$ArticleType = M('ArticleType')->where(array('status' => 1, 'index' => 1))->order('sort asc ,id desc')->select();

		foreach ($ArticleType as $k => $v) {
			$ArticleTypeList[$v['name']] = $v;
		}

		$this->assign('ArticleTypeList', $ArticleTypeList);
		$this->assign('data', $data);
		$this->assign('type', $data['type']);
		$this->assign('username', username());
		$where = array('articleid' => $id,'state' => 1,'recycleState' => 1);
		$Model = M('Article_comment');
		$count = $Model->where($where)->count();
		$Page = new \Think\Page($count, 10);
		$show = $Page->show();
		$list = $Model->where($where)->order('comtdate desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

}

?>