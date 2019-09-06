<?php
namespace Admin\Controller;

class ArticleController extends AdminController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","edit","editen","status","type","typeEdit","typeStatus","adver","adverEdit","adverStatus","adverImage","youqing","youqingEdit","youqingStatus");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("页面不存在！");
		}
	}
	
	public function index($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else if ($field == 'title') {
				$where['title'] = array('like', '%' . $name . '%');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}

		$count = M('Article')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Article')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['adminid'] = M('Admin')->where(array('id' => $v['adminid']))->getField('username');
			$list[$k]['type'] = M('ArticleType')->where(array('name' => $v['type']))->getField('title');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function edit($id = NULL, $type = NULL)
	{
		if (empty($_POST)) {
			$list = M('ArticleType')->select();

			foreach ($list as $k => $v) {
				$listType[$v['name']] = $v['title'];
			}

			$this->assign('list', $listType);

			if ($id) {
				$this->data = M('Article')->where(array('id' => trim($id)))->find();
			
				// $eng=htmlspecialchars_decode(html_entity_decode($this->data['content_en']));
				

			}
			else {
				$this->data = null;
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}
			$_POST['content']=!empty($_POST['ueditorcontent']) ? addslashes($_POST['ueditorcontent']) : "";
			unset($_POST['ueditorcontent']);

			
			

			$_POST['img']=!empty($_POST['img']) ? addslashes($_POST['img']) : '';
			if ($_POST['addtime']) {
				if (addtime(strtotime($_POST['addtime'])) == '---') {
					$this->error('添加时间格式错误');
				}
				else {
					$_POST['addtime'] = strtotime($_POST['addtime']);
				}
			}
			else {
				$_POST['addtime'] = time();
			}

			if ($_POST['endtime']) {
				if (addtime(strtotime($_POST['endtime'])) == '---') {
					$this->error('编辑时间格式错误');
				}
				else {
					$_POST['endtime'] = strtotime($_POST['endtime']);
				}
			}
			else {
				$_POST['endtime'] = time();
			}

			if ($_POST['id']) {
				$rs = M('Article')->save($_POST);
				// var_dump(M('Article')->getLastSql());

			}
			else {
				$_POST['addtime'] = time();
				$_POST['adminid'] = session('admin_id');
				$rs = M('Article')->add($_POST);
			}

			if ($rs) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
			
		}
	}
		public function editen($id = NULL, $type = NULL)
	{
		if (empty($_POST)) {
			$list = M('ArticleType')->select();

			foreach ($list as $k => $v) {
				$listType[$v['name']] = $v['title'];
			}

			$this->assign('list', $listType);

			if ($id) {
				$this->data = M('Article')->where(array('id' => trim($id)))->find();
			
				// $eng=htmlspecialchars_decode(html_entity_decode($this->data['content_en']));
				

			}
			else {
				$this->data = null;
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}
			$_POST['content_en']=!empty($_POST['ueditorcontent']) ? addslashes($_POST['ueditorcontent']) : "";
			unset($_POST['ueditorcontent']);

			
			

			$_POST['img']=!empty($_POST['img']) ? addslashes($_POST['img']) : '';
			

			if ($_POST['id']) {
				$rs = M('Article')->save($_POST);
				// var_dump(M('Article')->getLastSql());

			}
			else {
				$_POST['addtime'] = time();
				$_POST['adminid'] = session('admin_id');
				$rs = M('Article')->add($_POST);
			}

			if ($rs) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
			
		}
	}

	public function status($id = NULL, $type = NULL, $mobile = 'Article')
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (empty($id)) {
			$this->error('参数错误！');
		}

		if (empty($type)) {
			$this->error('参数错误1！');
		}

		if (strpos(',', $id)) {
			$id = implode(',', $id);
		}

		$where['id'] = array('in', $id);

		switch (strtolower($type)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;

		case 'repeal':
			$data = array('status' => 2, 'endtime' => time());
			break;

		case 'delete':
			$data = array('status' => -1);
			break;

		case 'del':
			if (M($mobile)->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('操作失败！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function type($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else if ($field == 'title') {
				$where['title'] = array('like', '%' . $name . '%');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}

		$count = M('ArticleType')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('ArticleType')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['adminid'] = M('Admin')->where(array('id' => $v['adminid']))->getField('username');
			$list[$k]['shang'] = M('ArticleType')->where(array('name' => $v['shang']))->getField('title');

			if (!$list[$k]['shang']) {
				$list[$k]['shang'] = '顶级';
			}
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function typeEdit($id = NULL, $type = NULL)
	{
		$list = M('ArticleType')->select();

		foreach ($list as $k => $v) {
			// 仅显示一级分类
			if(!$v['shang']){
				$listType[$v['name']] = $v['title'];
			}
		}

		$this->assign('list', $listType);

		if (empty($_POST)) {
			if ($id) {
				$this->data = M('ArticleType')->where(array('id' => trim($id)))->find();
			}
			else {
				$this->data = null;
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}
			
			$_POST['content']=$_POST['ueditorcontent'];
			unset($_POST['ueditorcontent']);
			
			if ($_POST['addtime']) {
				if (addtime(strtotime($_POST['addtime'])) == '---') {
					$this->error('添加时间格式错误');
				}
				else {
					$_POST['addtime'] = strtotime($_POST['addtime']);
				}
			}
			else {
				$_POST['addtime'] = time();
			}

			if ($_POST['endtime']) {
				if (addtime(strtotime($_POST['endtime'])) == '---') {
					$this->error('编辑时间格式错误');
				}
				else {
					$_POST['endtime'] = strtotime($_POST['endtime']);
				}
			}
			else {
				$_POST['endtime'] = time();
			}

			if ($_POST['id']) {
				$where_t = array();
				$namess = trim($_POST['name']);
				$namess = strtolower($namess);
				$where_t['name'] = $namess;
				$where_t['id'] = array('NEQ',trim($_POST['id']));

				if(empty($namess)){
					$this->error('类型标识内容不能为空！');
				}

				if(preg_match('/null|false/i',$namess)){
					$this->error('类型标识内容不符合，请重新填写！');
				}

				if(!preg_match('/^\w+$/',$namess)){
					$this->error('类型标识格式错误！');
				}

				$ty_name = M('ArticleType')->where($where_t)->find();

				if($ty_name){
					$this->error('类型标识已存在！');
				}

				$rs = M('ArticleType')->save($_POST);
			}
			else {
				$where_t = array();
				$namess = trim($_POST['name']);
				$namess = strtolower($namess);
				$where_t['name'] = $namess;

				if(empty($namess)){
					$this->error('类型标识内容不能为空！');
				}

				if(preg_match('/null|false/i',$namess)){
					$this->error('类型标识内容不符合，请重新填写！');
				}

				if(!preg_match('/^\w+$/',$namess)){
					$this->error('类型标识格式错误！');
				}

				$ty_name = M('ArticleType')->where($where_t)->find();

				if($ty_name){
					$this->error('类型标识已存在！');
				}

				$_POST['adminid'] = session('admin_id');
				$rs = M('ArticleType')->add($_POST);
			}

			if ($rs) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
		}
	}

	public function typeStatus($id = NULL, $type = NULL, $mobile = 'ArticleType')
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (empty($id)) {
			$this->error('参数错误！');
		}

		if (empty($type)) {
			$this->error('参数错误1！');
		}

		if (strpos(',', $id)) {
			$id = implode(',', $id);
		}

		$where['id'] = array('in', $id);

		switch (strtolower($type)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;

		case 'repeal':
			$data = array('status' => 2, 'endtime' => time());
			break;

		case 'delete':
			$data = array('status' => -1);
			break;

		case 'del':
			if (M($mobile)->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('操作失败！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function adver($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else if ($field == 'title') {
				$where['title'] = array('like', '%' . $name . '%');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}

		$count = M('Adver')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Adver')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function adverEdit($id = NULL)
	{
		if (empty($_POST)) {
			if ($id) {
				$this->data = M('Adver')->where(array('id' => trim($id)))->find();
			}
			else {
				$this->data = null;
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}
			// $ima=$_POST['img'];
			// var_dump($ima);
			// exit;
			$_POST['img'] = addslashes($_POST['img']);
			if ($_POST['addtime']) {
				if (addtime(strtotime($_POST['addtime'])) == '---') {
					$this->error('添加时间格式错误');
				}
				else {
					$_POST['addtime'] = strtotime($_POST['addtime']);
				}
			}
			else {
				$_POST['addtime'] = time();
			}

			if ($_POST['endtime']) {
				if (addtime(strtotime($_POST['endtime'])) == '---') {
					$this->error('编辑时间格式错误');
				}
				else {
					$_POST['endtime'] = strtotime($_POST['endtime']);
				}
			}
			else {
				$_POST['endtime'] = time();
			}

			if ($_POST['id']) {
				$rs = M('Adver')->save($_POST);
			}
			else {
				$_POST['adminid'] = session('admin_id');
				$rs = M('Adver')->add($_POST);
			}

			if ($rs) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
		}
	}

	public function adverStatus($id = NULL, $type = NULL, $mobile = 'Adver')
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (empty($id)) {
			$this->error('参数错误！');
		}

		if (empty($type)) {
			$this->error('参数错误1！');
		}

		if (strpos(',', $id)) {
			$id = implode(',', $id);
		}

		$where['id'] = array('in', $id);

		switch (strtolower($type)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;

		case 'repeal':
			$data = array('status' => 2, 'endtime' => time());
			break;

		case 'delete':
			$data = array('status' => -1);
			break;

		case 'del':
			if (M($mobile)->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('操作失败！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function adverImage()
	{
		$upload = new \Think\Upload();
		$upload->maxSize = 3145728;
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg');
		$upload->rootPath = './Upload/ad/';
		$upload->autoSub = false;
		$info = $upload->upload();

		foreach ($info as $k => $v) {
			$path = $v['savepath'] . $v['savename'];
			echo $path;
			exit();
		}
	}

	public function youqing($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else if ($field == 'title') {
				$where['title'] = array('like', '%' . $name . '%');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}

		$count = M('Link')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Link')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function youqingEdit($id = NULL)
	{
		if (empty($_POST)) {
			if ($id) {
				$this->data = M('Link')->where(array('id' => trim($id)))->find();
			}
			else {
				$this->data = null;
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}
			$_POST['img']=addslashes($_POST['img']);
			if ($_POST['addtime']) {
				if (addtime(strtotime($_POST['addtime'])) == '---') {
					$this->error('添加时间格式错误');
				}
				else {
					$_POST['addtime'] = strtotime($_POST['addtime']);
				}
			}
			else {
				$_POST['addtime'] = time();
			}

			if ($_POST['endtime']) {
				if (addtime(strtotime($_POST['endtime'])) == '---') {
					$this->error('编辑时间格式错误');
				}
				else {
					$_POST['endtime'] = strtotime($_POST['endtime']);
				}
			}
			else {
				$_POST['endtime'] = time();
			}

			if ($_POST['id']) {
				$rs = M('Link')->save($_POST);
			}
			else {
				$rs = M('Link')->add($_POST);
			}

			if ($rs) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
		}
	}

	public function youqingStatus($id = NULL, $type = NULL, $mobile = 'Link')
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (empty($id)) {
			$this->error('参数错误！');
		}

		if (empty($type)) {
			$this->error('参数错误1！');
		}

		if (strpos(',', $id)) {
			$id = implode(',', $id);
		}

		$where['id'] = array('in', $id);

		switch (strtolower($type)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;

		case 'repeal':
			$data = array('status' => 2, 'endtime' => time());
			break;

		case 'delete':
			$data = array('status' => -1);
			break;

		case 'del':
			if (M($mobile)->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('操作失败！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}
}

?>