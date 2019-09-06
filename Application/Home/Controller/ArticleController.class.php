<?php
namespace Home\Controller;

class ArticleController extends HomeController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","detail","type","download");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("页面不存在！");
		}
	}
	
	public function __construct() {
		parent::__construct();
		$this->common_content();
	}
	
	public function index($id = null)
	{

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E



		$where_j = array();
		$where_j['status'] = 1;
		//$where_j['index'] = 1;
		$where_j['shang'] = '';

		// 筛选的条件 正常、首页显示底部不显示的 文章类型列表
		$ArticleTypeList = M('ArticleType')->where($where_j)->order('sort asc ,id asc')->select();

		
		foreach ($ArticleTypeList as $k => $v) {
			if($v['name'] == 'aaa'){
				$ArticleTypeList[$k]['img1'] = 'helpicon1.png';
				$ArticleTypeList[$k]['img11'] = 'helpicon12.png';
			}else if($v['name'] == 'bbb'){
				$ArticleTypeList[$k]['img1'] = 'helpicon2.png';
				$ArticleTypeList[$k]['img11'] = 'helpicon22.png';
			}else if($v['name'] == 'ccc'){
				$ArticleTypeList[$k]['img1'] = 'helpicon3.png';
				$ArticleTypeList[$k]['img11'] = 'helpicon32.png';
			}else{
				$ArticleTypeList[$k]['img1'] = 'helpicon2.png';
				$ArticleTypeList[$k]['img11'] = 'helpicon22.png';
			}
			$where_j1 = array();
			$where_j1['status'] = 1;
			//$where_j1['index'] = 1;
			$where_j1['shang'] = $v['name'];
			$ArticleTypeList[$k]['child']= M('ArticleType')->where($where_j1)->order('sort asc ,id asc')->select();
			$ArticleTypeList[$k]['childnum']= count($ArticleTypeList[$k]['child']);
		}

		$this->assign('ArticleTypeList', $ArticleTypeList);

		if (empty($id) || !check($id, 'd')) {
			// 获取第一个默认文章类型，将其文章在左侧显示
			$Article_first_info = M('ArticleType')->where(array('status' => 1, 'index' => 1))->order('sort asc ,id asc')->find();
			$id = $Article_first_info['id'];
		}else{
			$Article_first_info = M('ArticleType')->where(array('id' => $id))->find();
		}

		

		$this->assign('Article_first_info', $Article_first_info);
		$where_type=array();
		$where_type['status'] = 1;
		//$where_type['index'] = 1;
		$where_type['shang']=$Article_first_info['name'];
		$type=M('ArticleType')->field('name')->where($where_type)->order('sort asc ,id asc')->select();
		// 文章条件
		$type1=$Article_first_info['name'];
		foreach ($type as $value) {
			$type1.=','.$value['name'];
		}

		// 文章条件
		$wheres = array();
		$wheres['status'] = 1;
		$wheres['type'] =array('in', $type1);

		$Model = M('Article');
		$count = $Model->where($wheres)->count();
		$Page = new \Think\Page($count, 10);
		$show = $Page->show();

		// 将获取第一个类型 具体文章列出
		$article_list = $Model->where($wheres)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($article_list as $k => $v) {
			$article_list[$k]['content'] = strip_tags($v['content']);
		}

		$this->assign('article_list', $article_list);

		$this->assign('idss', $id);
		$this->assign('page', $show);
		$this->display();
	}

	public function detail_fb($id = null)
	{

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		$where_j = array();
		$where_j['status'] = 1;
		//$where_j['index'] = 1;
		$where_j['shang'] = '';

		// 筛选的条件 正常、首页显示底部不显示的 文章类型列表
		$ArticleTypeList = M('ArticleType')->where($where_j)->order('sort asc ,id asc')->select();

		
		foreach ($ArticleTypeList as $k => $v) {
			if($v['name'] == 'aaa'){
				$ArticleTypeList[$k]['img1'] = 'helpicon1.png';
				$ArticleTypeList[$k]['img11'] = 'helpicon12.png';
			}else if($v['name'] == 'bbb'){
				$ArticleTypeList[$k]['img1'] = 'helpicon2.png';
				$ArticleTypeList[$k]['img11'] = 'helpicon22.png';
			}else if($v['name'] == 'ccc'){
				$ArticleTypeList[$k]['img1'] = 'helpicon3.png';
				$ArticleTypeList[$k]['img11'] = 'helpicon32.png';
			}else{
				$ArticleTypeList[$k]['img1'] = 'helpicon2.png';
				$ArticleTypeList[$k]['img11'] = 'helpicon22.png';
			}
			$where_j1 = array();
			$where_j1['status'] = 1;
			//$where_j1['index'] = 1;
			$where_j1['shang'] = $v['name'];
			$ArticleTypeList[$k]['child']= M('ArticleType')->where($where_j1)->order('sort asc ,id asc')->select();
			$ArticleTypeList[$k]['childnum']= count($ArticleTypeList[$k]['child']);
		}

		$this->assign('ArticleTypeList', $ArticleTypeList);


		if (empty($id)) {
			// $id = 1;
			 $datass = M('Article')->find();
			 $id=$datass['id'];
		}
		var_dump($id);exit;
		if (!check($id, 'd')) {
			$id = 1;
		}

		$data = M('Article')->where(array('id' => $id))->find();

		$a_info = M('ArticleType')->where(array('name'=>$data['type']))->find();

		$a_id = $a_info['id'];

		$this->assign('a_id', $a_id);
		$this->assign('data', $data);

		$this->display();
	}

	public function detail($id = NULL,$type= NULL)
	{

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		$where_j = array();
		$where_j['status'] = 1;
		//$where_j['index'] = 1;
		$where_j['shang'] = '';

		// 筛选的条件 正常、首页显示底部不显示的 文章类型列表
		$ArticleTypeList = M('ArticleType')->db(1,'DB_Read')->where($where_j)->order('sort asc ,id asc')->select();

		
		foreach ($ArticleTypeList as $k => $v) {
			if($v['name'] == 'aaa'){
				$ArticleTypeList[$k]['img1'] = 'helpicon1.png';
				$ArticleTypeList[$k]['img11'] = 'helpicon12.png';
			}else if($v['name'] == 'bbb'){
				$ArticleTypeList[$k]['img1'] = 'helpicon2.png';
				$ArticleTypeList[$k]['img11'] = 'helpicon22.png';
			}else if($v['name'] == 'ccc'){
				$ArticleTypeList[$k]['img1'] = 'helpicon3.png';
				$ArticleTypeList[$k]['img11'] = 'helpicon32.png';
			}else{
				$ArticleTypeList[$k]['img1'] = 'helpicon2.png';
				$ArticleTypeList[$k]['img11'] = 'helpicon22.png';
			}
			$where_j1 = array();
			$where_j1['status'] = 1;
			//$where_j1['index'] = 1;
			$where_j1['type'] = $v['name'];
			$ArticleTypeList[$k]['child']= M('Article')->where($where_j1)->order('sort asc ,id asc')->select();
			$ArticleTypeList[$k]['childnum']= count($ArticleTypeList[$k]['child']);
		}

		$this->assign('ArticleTypeList', $ArticleTypeList);


		if (empty($id)) {
			if (!empty($type)) {
				$where_j11 = array();
				$where_j11['type'] = $type;
				$id1= M('Article')->where($where_j11)->order('sort asc ,id asc')->find();
				$id = $id1['id'];
				$sql= M('Article')->getlastsql();
				// var_dump($sql);exit;
			}else{
				$ArticleTypes = M('ArticleType')->db(1,'DB_Read')->where(array('status'=>'1'))->order('sort asc ,id asc')->getField('name');
			 	$datass = M('Article')->where(array('status'=>'1','type'=>$ArticleTypes))->find();
			 	$id=$datass['id'];
			}
		}

		if (!check($id, 'd')) {
			$id = 1;
		}

		$data = M('Article')->db(1,'DB_Read')->where(array('id' => $id))->find();

		$a_info = M('ArticleType')->db(1,'DB_Read')->where(array('name'=>$data['type']))->find();

		$a_id = $a_info['id'];

		$this->assign('a_info', $a_info);
		$this->assign('a_id', $a_id);
		$this->assign('data', $data);

		$this->display();
	}

	public function type($id = NULL)
	{

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		
		if (empty($id)) {
			$id = 1;
		}

		if (!check($id, 'd')) {
			$id = 1;
		}

		$Article = M('ArticleType')->where(array('id' => $id))->find();

		if ($Article['shang']) {
			$shang = M('ArticleType')->where(array('name' => $Article['shang']))->find();
			$ArticleType = M('ArticleType')->where(array('status' => 1, 'shang' => $Article['shang']))->order('sort asc ,id desc')->select();
			$Articleaa = $Article;
		}
		else {
			$shang = M('ArticleType')->where(array('id' => $id))->find();
			$ArticleType = M('ArticleType')->where(array('status' => 1, 'shang' => $Article['name']))->order('sort asc ,id desc')->select();
			$id = $ArticleType[0]['id'];
			$Articleaa = M('ArticleType')->where(array('id' => $ArticleType[0]['id']))->find();
		}

		$this->assign('shang', $shang);

		foreach ($ArticleType as $k => $v) {
			$ArticleTypeList[$v['name']] = $v;
		}

		$this->assign('a_id', $id);


		$this->assign('ArticleTypeList', $ArticleTypeList);
		$this->assign('data', $Articleaa);
		$this->display();
	}
	
	public function download(){
		$file = urldecode($_GET['file']);
		$name = urldecode($_GET['name']);
		if(empty($file)){
			$this->error("文件不存在，请联系管理员");
		}
		$url = "./Upload/lanch/upload/".$file;
		if(empty($name)){
			$name = "file_".time().".docx";
		}
		import('Org.Net.Http');
		\Org\Net\Http::download($url,$name);
	}
}

?>