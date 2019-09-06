<?php
namespace Admin\Controller;

class ConfigController extends AdminController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","edit","image","mobile","mobileEdit","contact","contactEdit","coin","coinEdit","coinStatus","textStatus","coinInfo","coinUser","coinQing","coinImage","text","textEdit","qita","qitaEdit","daohang","daohangEdit","daohangStatus");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("页面不存在！");
		}
	}
	
	public function index()
	{
		$config_info = M('Config')->where(array('id' => 1))->find();
		$config_info['web_logo'] = stripslashes($config_info['web_logo']);
		$this->data = $config_info;
		$this->display();
	}

	public function edit()
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}
		$_POST['web_logo'] = !empty($_POST['img']) ? addslashes($_POST['img']) : "";
		unset($_POST['img']);
		$_POST['web_reg']=!empty($_POST['ueditorcontent']) ? addslashes($_POST['ueditorcontent']) : "";
		unset($_POST['ueditorcontent']);
		$_POST['en_web_reg']=!empty($_POST['en_ueditorcontent']) ? addslashes($_POST['en_ueditorcontent']) : "";
		unset($_POST['en_ueditorcontent']);
		if (M('Config')->where(array('id' => 1))->save($_POST)) {
			$this->success('修改成功！');
		}
		else {
			$this->error('修改失败');
		}
	}

	public function mobile()
	{
		$this->data = M('Config')->where(array('id' => 1))->find();
		$this->display();
	}

	public function mobileEdit()
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (M('Config')->where(array('id' => 1))->save($_POST)) {
			$this->success('修改成功！');
		}
		else {
			$this->error('修改失败');
		}
	}

	public function contact()
	{
		$this->data = M('Config')->where(array('id' => 1))->find();
		$this->display();
	}

	public function contactEdit()
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (M('Config')->where(array('id' => 1))->save($_POST)) {
			$this->success('修改成功！');
		}
		else {
			$this->error('修改失败');
		}
	}

	public function coin($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}

		$count = M('Coin')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Coin')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function coinEdit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$data = array();
			}
			else {
				$data = M('Coin')->where(array('id' => trim($_GET['id'])))->find();
			}
			$zcmin = $data['zc_min'];
			$zcmin_arr = explode(".",$zcmin);
			$k=0;
			for($i=1;$i<strlen($zcmin_arr[1]);$i++){
				if(substr($zcmin_arr[1],$i-1,$i)>0){
					$k=$i;
				}
			}
			if($k>0){
				$baoliu = substr($zcmin_arr[1],0,$k);
				$data['zc_min'] = $zcmin_arr[0].".".$baoliu;
			}else{
				$data['zc_min'] = $zcmin_arr[0];
			}
			$zcmax = $data['zc_max'];
			$zcmax_arr = explode(".",$zcmax);
			$k=0;
			for($i=1;$i<strlen($zcmax_arr[1]);$i++){
				if(substr($zcmax_arr[1],$i-1,$i)>0){
					$k=$i;
				}
			}
			if($k>0){
				$baoliu = substr($zcmax_arr[1],0,$k);
				$data['zc_max'] = $zcmax_arr[0].".".$baoliu;
			}else{
				$data['zc_max'] = $zcmax_arr[0];
			}
			$this->assign('data',$data);
			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}
			$_POST['js_sm'] = $_POST['ueditorcontent'];
			unset($_POST['ueditorcontent']);
			$_POST['img'] = !empty($_POST['img']) ? addslashes($_POST['img']) : '';
			$_POST['fee_bili'] = floatval($_POST['fee_bili']);

			if ($_POST['fee_bili'] && (($_POST['fee_bili'] < 0.01) || (100 < $_POST['fee_bili']))) {
				$this->error('挂单比例只能是0.01--100之间(不用填写%)！');
			}

			$_POST['zr_dz'] = intval($_POST['zr_dz']);
			$_POST['zc_fee'] = floatval($_POST['zc_fee']);

			if ($_POST['zc_fee'] && ($_POST['zc_fee'] < 0.00001)) {
				$this->error('转出手续费只能是大于0.00001！');
			}

			if ($_POST['zc_user']) {
				if (!check($_POST['zc_user'], 'dw')) {
					$this->error('官方手续费地址格式不正确！');
				}

				$ZcUser = M('UserCoin')->where(array($_POST['name'] . 'b' => $_POST['zc_user']))->find();

				if (!$ZcUser) {
					$this->error('在系统中查询不到[官方手续费地址],请务必填写正确！');
				}
			}

			$_POST['zc_min'] = floatval($_POST['zc_min']);
			$_POST['zc_max'] = floatval($_POST['zc_max']);

			if ($_POST['id']) {
				$rs = M('Coin')->save($_POST);
			}
			else {
				if (!check($_POST['name'], 'n')) {
					$this->error('币种简称只能是小写字母！');
				}

				$_POST['name'] = strtolower($_POST['name']);

				if (check($_POST['name'], 'username')) {
					$this->error('币种名称格式不正确！');
				}

				if (M('Coin')->where(array('name' => $_POST['name']))->find()) {
					$this->error('币种存在！');
				}

				$rea = M()->execute('ALTER TABLE  `tw_user_coin` ADD  `' . $_POST['name'] . '` DECIMAL(20,8) UNSIGNED NOT NULL');
				$reb = M()->execute('ALTER TABLE  `tw_user_coin` ADD  `' . $_POST['name'] . 'd` DECIMAL(20,8) UNSIGNED NOT NULL ');
				$rec = M()->execute('ALTER TABLE  `tw_user_coin` ADD  `' . $_POST['name'] . 'b` VARCHAR(200) NOT NULL ');
				$rs = M('Coin')->add($_POST);
			}

			if ($rs) {
				$this->success('操作成功！');
			}
			else {
				$this->error('数据未修改！');
			}
		}
	}

	public function coinStatus()
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (IS_POST) {
			$id = array();
			$id = implode(',', $_POST['id']);
		}
		else {
			$id = $_GET['id'];
		}

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$where['id'] = array('in', $id);
		$method = $_GET['type'];

		switch (strtolower($method)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;

		case 'delete':
			$rs = M('Coin')->where($where)->select();

			foreach ($rs as $k => $v) {
				$rs[] = M()->execute('ALTER TABLE  `tw_user_coin` DROP COLUMN ' . $v['name']);
				$rs[] = M()->execute('ALTER TABLE  `tw_user_coin` DROP COLUMN ' . $v['name'] . 'd');
				$rs[] = M()->execute('ALTER TABLE  `tw_user_coin` DROP COLUMN ' . $v['name'] . 'b');
			}

			if (M('Coin')->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('参数非法');
		}

		if (M('Coin')->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}
	public function textStatus()
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (IS_POST) {
			$id = array();
			$id = implode(',', $_POST['id']);
		}
		else {
			$id = $_GET['id'];
		}

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$where['id'] = array('in', $id);
		$method = $_GET['type'];

		switch (strtolower($method)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;
		default:
			$this->error('参数非法');
		}

		if (M('text')->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function coinInfo($coin)
	{
		$dj_username = C('coin')[$coin]['dj_yh'];
		$dj_password = C('coin')[$coin]['dj_mm'];
		$dj_address = C('coin')[$coin]['dj_zj'];
		$dj_port = C('coin')[$coin]['dj_dk'];
		$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port);

		if (!$CoinClient) {
			$this->error('钱包对接失败！');
		}

		$info['b'] = $CoinClient->getinfo() ;
		$info['num'] = M('UserCoin')->sum($coin) + M('UserCoin')->sum($coin . 'd');
		$info['coin'] = $coin;
		$this->assign('data', $info);
		$this->display();
	}

	public function coinUser($coin)
	{
		$dj_username = C('coin')[$coin]['dj_yh'];
		$dj_password = C('coin')[$coin]['dj_mm'];
		$dj_address = C('coin')[$coin]['dj_zj'];
		$dj_port = C('coin')[$coin]['dj_dk'];
		
		$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port,3,null,false,$coin);

		if (!$CoinClient) {
			$this->error('钱包对接失败！');
		}

		$arr = $CoinClient->listaccounts();

		foreach ($arr as $k => $v) {
			if ($k) {
				if ($v < 1.0000000000000001E-5) {
					$v = 0;
				}

				$list[$k]['num'] = $v;
				$str = '';
				$addr = $CoinClient->getaddressesbyaccount($k);

				foreach ($addr as $kk => $vv) {
					$str .= $vv . '<br>';
				}

				$list[$k]['addr'] = $str;
				$userid = M('User')->where(array('username' => $k))->getField('id');
				$user_coin = M('UserCoin')->where(array('userid' => $userid))->find();

				$list[$k]['id'] = $userid;
				$list[$k]['xnb'] = $user_coin[$coin];
				$list[$k]['xnbd'] = $user_coin[$coin . 'd'];
				$list[$k]['zj'] = $list[$k]['xnb'] + $list[$k]['xnbd'];
				$list[$k]['xnbb'] = $user_coin[$coin . 'b'];
				unset($str);
			}
		}
		$this->assign('list', $list);
		$this->display();
	}

	public function coinQing($coin)
	{
		if (!C('coin')[$coin]) {
			$this->error('参数错误！');
		}

		$info = M()->execute('UPDATE `tw_user_coin` SET `' . trim($coin) . 'b`=\'\' ;');

		if ($info) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function coinImage()
	{
		$upload = new \Think\Upload();
		$upload->maxSize = 3145728;
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg');
		$upload->rootPath = './Upload/coin/';
		$upload->autoSub = false;
		$info = $upload->upload();

		foreach ($info as $k => $v) {
			$path = $v['savepath'] . $v['savename'];
			echo $path;
			exit();
		}
	}

	public function text($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}

		$count = M('Text')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Text')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function textEdit($id = NULL)
	{
		if (empty($_POST)) {
			if ($id) {
				$this->data = M('Text')->where(array('id' => trim($id)))->find();
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
			
			$_POST['content'] = $_POST['ueditorcontent'];
			unset($_POST['ueditorcontent']);
			
			if ($_POST['id']) {
				$rs = M('Text')->save($_POST);
			}
			else {
				$_POST['adminid'] = session('admin_id');
				$rs = M('Text')->add($_POST);
			}

			if ($rs) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
		}
	}

	public function qita()
	{
		$this->data = M('Config')->where(array('id' => 1))->find();
		$this->display();
	}

	public function qitaEdit()
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (M('Config')->where(array('id' => 1))->save($_POST)) {
			$this->success('修改成功！');
		}
		else {
			$this->error('修改失败');
		}
	}

	public function daohang($name = NULL, $field = NULL, $status = NULL)
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

		$count = M('Daohang')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Daohang')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function daohangEdit($id = NULL)
	{
		if (empty($_POST)) {
			if ($id) {
				$this->data = M('Daohang')->where(array('id' => trim($id)))->find();
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

			if ($_POST['id']) {
				$rs = M('Daohang')->save($_POST);
			}
			else {
				$_POST['addtime'] = time();
				$rs = M('Daohang')->add($_POST);
			}

			if ($rs) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
		}
	}

	public function daohangStatus($id = NULL, $type = NULL, $mobile = 'Daohang')
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

		case 'del':
			$data = array('status' => -1);
			break;

		case 'delete':
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