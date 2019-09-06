<?php
namespace Admin\Controller;

class UserController extends AdminController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","edit","status","admin","adminEdit","adminStatus","auth","authEdit","authStatus","authStart","authAccess","updateRules","authAccessUp","authUser","authUserAdd","authUserRemove","log","logEdit","logStatus","qianbao","qianbaoEdit","qianbaoStatus","bank","bankEdit","bankStatus","coin","coinEdit","coinLog","goods","goodsEdit","goodsStatus","setpwd","amountlog","userExcel","feedback","feedbackstatus","feedbackinfo","nameauth","nameauthdetail","tradePrize","invittree");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("页面不存在！");
		}
	}
	
	public function invittree($userid = NULL){
		if(empty($userid)){
			$invitids = M('User')->distinct(true)->field('invit_1')->select();
			$leaderids = '';
			foreach($invitids as $i1){
				if($i1['invit_1']>0){
					$leaderids .= $i1['invit_1'].",";
				}
			}
			if(!empty($leaderids)){
				$leaderids = substr($leaderids,0,strlen($leaderids)-1);
			}
			$count = M('User')->where(array('invit_1'=>0,'id'=>array('in',$leaderids)))->count();
			$Page = new \Think\Page($count, 15);
			$show = $Page->show();
			$userlist = M('User')->where(array('invit_1'=>0,'id'=>array('in',$leaderids)))->field('id,username,truename,mobile,addtime')->order('id asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
			foreach($userlist as $key=>$user){
				$below = M('User')->where(array('invit_1'=>$user['id']))->count();
				$userlist[$key]['below'] = intval($below);
				if(empty($user['mobile'])){
					$userlist[$key]['mobile'] = "-";
				}
				if(empty($user['email'])){
					$userlist[$key]['email'] = "-";
				}
				if(empty($user['truename'])){
					$userlist[$key]['truename'] = "-";
				}
			}
			$this->assign('userlist',$userlist);
			$this->assign('page', $show);
			$this->display();
		}else{
			$userlist = M('User')->where(array('invit_1'=>$userid))->field('id,username,truename,mobile,email,addtime')->order('id asc')->select();
			foreach($userlist as $key=>$user){
				$below = M('User')->where(array('invit_1'=>$user['id']))->count();
				$userlist[$key]['below'] = intval($below);
				$userlist[$key]['addtime'] = date("Y-m-d H:i:s",$user['addtime']);
				if(empty($user['mobile'])){
					$userlist[$key]['mobile'] = "-";
				}
				if(empty($user['email'])){
					$userlist[$key]['email'] = "-";
				}
				if(empty($user['truename'])){
					$userlist[$key]['truename'] = "-";
				}
			}
			echo json_encode($userlist);
			exit;
		}
		
	}
	
	public function feedback($name = NULL, $addtime = NULL, $isread = 0, $field=NULL, $starttime=NULL, $endtime=NULL, $subject=NULL){
		$where = array();
		if(!empty($field)&&!empty($name)){
			if($field=='username'){
				$where[$field] = $name;
			}elseif($field=='truename'){
				$list_name = M('user')->where(array('truename'=>$name))->order('id desc')->select();
				$hehe=0;
				foreach ($list_name as $key => $value) {
					$hehe++;
					if($hehe==1){
						$userid=$value['id'];
					}else{
						$userid.=','.$value['id'];
					}
				}
				$where['userid']=array('in',$userid );
			}
		}
		if($isread>0){
			$where['isread'] = $isread-1;
		}
		if(!empty($subject)){
			$where['subject'] = $subject;
		}

		// 时间--条件

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where['addtime'] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where['addtime'] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where['addtime'] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}

		$count = M('Feedback')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Feedback')->where($where)->order('freshtime desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['truename'] =M('user')->where(array('id'=>$v['userid']))->getfield('truename');
			if($v['isread']==0){
				$list[$k]['isread'] = '未读';
			}else{
				$list[$k]['isread'] = '已读';
			}
			if($v['adminstatus']==1){
				$list[$k]['status'] = "等待处理";
			}else{
				$list[$k]['status'] = "已处理";
			}
		}

		$opstype = opstype('',88);

		$this->assign('opstype', $opstype);
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function feedbackstatus($id = NULL, $type = NULL, $mobile = 'feedback')
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
		$where1['userid'] = array('in', $id);
		$mobile_coin=$mobile.'_coin';
		switch (strtolower($type)) {
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
	}

	public function feedbackinfo()
	{
		if (empty($_POST)) {
			if (empty($_GET['id'])) {
				$this->error('缺少参数！');
			}
			else {
				$id = $_GET['id'];
				$feedback_record = M('Feedback')->where(array('id' => trim($id)))->find();
				$data = array('isread' => 1);
				$where['id']=$id;
				M('Feedback')->where($where)->save($data);
				if(!empty($feedback_record)){
					if(!empty($feedback_record['adminstatus'])){
						$feedback_record['status'] = "<span style='color:blue;'>等待处理</span>";
					}else{
						$feedback_record['status'] = "<span style='color:greeb;'>已处理</span>";
					}
					$this->assign('data',$feedback_record);
					$feedback_reply = M('Feedback_reply')->where(array('fid'=>$feedback_record['id']))->order('addtime asc')->select();
					if(!empty($feedback_reply)){
						$this->assign('list',$feedback_reply);
					}
				}
			}

			$this->display();
		}else{
			$fid = $_POST['fid'];
			$content = $_POST['content'];
			if(empty($fid) || empty($content)){
				$this->error("参数错误！".$fid."|".$content);
			}
			$feedback_record = M('Feedback')->where(array('id'=>$fid))->find();
			if(empty($feedback_record)){
				$this->error("参数错误！");
			}
			$data['fid'] = $fid;
			$data['content'] = $content;
			$data['userid'] = 1;
			$data['username'] = "admin";
			$data['addtime'] = time();
			$result = M('Feedback_reply')->add($data);
			if($result){
				$update['userstatus'] = 1;
				$update['adminstatus'] = 0;
				$update['freshtime'] = time();
				$res = M('Feedback')->where(array('id'=>$feedback_record['id']))->save($update);
				if($res){
					$this->success('回复成功！');
				}else{
					$this->error('回复状态更新失败');
				}
			}else{
				$this->error('回复失败');
			}
		}
	}

	public function nameauth($name=NULL,$field=NULL){
		$where=array();
		if ($field && $name) {
			$where[$field] = $name;
		}
		$where['idcard_zheng'] = array('neq','');
		$where['idcard_fan'] = array('neq','');
		$where['idcard_shouchi'] = array('neq','');
		$where['is_agree'] = 0;
		$count = M('User')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('User')->where($where)->order('id asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
		
	}
	
	public function nameauthdetail(){
		if (empty($_POST)) {
			if(empty($_GET['uid'])){
				$this->error('缺少参数');
			}
			$uid = $_GET['uid'];
			$arr = array('sfz'=>'内地身份证','gsfz'=>'港澳台身份证','hz'=>'护照','qt'=>'其它');
			$user = M('User')->where(array('id' => $uid))->find();
			if(empty($user)){
				$this->error('参数错误');
			}
			$user['zhengjian'] = $arr[$user['zhengjian']];
			$this->assign('user', $user);
			$this->display();
		}else{
			$uid = $_POST['uid'];
			$user = M('User')->where(array('id' => $uid))->find();
			if(empty($user)){
				$this->error('缺少参数');
			}
			$pass = $_POST['pass'];
			if($pass!=1 && $pass!=2){
				$this->error('请选择是否审核通过！');
			}
			if($pass==1){
				$rs = M('User')->where(array('id' => $uid))->save(array('is_agree'=>1));
				if($rs){
					$this->success("审核成功！","/Admin/User/nameauth.html");
				}else{
					$this->error('审核失败！');
				}
			}
			if($pass==2){
				$rs = M('User')->where(array('id' => $uid))->save(array('is_agree'=>2,'idcard_zheng'=>'','idcard_fan'=>'','idcard_shouchi'=>''));
				if($rs){
					$this->success("审核成功！","/Admin/User/nameauth.html");
				}else{
					$this->error('审核失败！');
				}
			}
		}
	}

	// 资金变更日志
	public function amountlog($position = 'all', $plusminus = 'all', $name = NULL, $field = NULL, $cointype = NULL, $optype = NULL, $starttime = NULL, $endtime = NULL)
	{
		$where = array();

		if ($field && $name) {
			$where[$field] = $name;
		}

		if($cointype){
			$where['cointype'] = $cointype;
		}

		if($optype){
			$where['optype'] = $optype - 1;
		}

		if($plusminus != 'all'){
			if($plusminus == 'jia'){
				$where['plusminus'] = '1';
			}else if($plusminus == 'jian'){
				$where['plusminus'] = '0';
			}
		}

		if($position != 'all'){
			if($position == 'hou'){
				$where['position'] = '0';
			}else if($position == 'qian'){
				$where['position'] = '1';
			}
		}


		// 时间--条件

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where['addtime'] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where['addtime'] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where['addtime'] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}else{

			// 无时间查询，显示申请时间类型十天以内数据
			$now_time = time() - 10*24*60*60;
			$where['addtime'] =  array('EGT',$now_time);
		}

		$count = M('FinanceLog')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('FinanceLog')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$coin_info = M('Coin')->where(array('id'=>$v['cointype']))->find();
			$list[$k]['cointype'] = $coin_info['name'];
			$list[$k]['optype'] = opstype($v['optype'],2);
			$list[$k]['old_amount'] = $v['old_amount']*1;
			$list[$k]['amount'] = $v['amount']*1;
			$list[$k]['new_amount'] = $v['new_amount']*1;
			if($v['plusminus']){
				$list[$k]['plusminus'] = '增加';
			}else{
				$list[$k]['plusminus'] = '减少';
			}
			if($v['position']){
				$list[$k]['position'] = '前台';
			}else{
				$list[$k]['position'] = '后台';
			}
		}

		$opstype = opstype('',88);

		$this->assign('opstype', $opstype);
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}
	
	public function index($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();

		if ($field && $name) {
			$where[$field] = $name;
		}

		if ($status) {
			$where['status'] = $status - 1;
		}

		$count = M('User')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('User')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['invit_1'] = M('User')->where(array('id' => $v['invit_1']))->getField('username');
			$list[$k]['invit_2'] = M('User')->where(array('id' => $v['invit_2']))->getField('username');
			$list[$k]['invit_3'] = M('User')->where(array('id' => $v['invit_3']))->getField('username');
			$user_login_state=M('user_log')->where(array('userid'=>$v['id'],'type' => 'login'))->order('id desc')->find();
			$list[$k]['state']=$user_login_state['state'];
			$user_coin = M('User_coin')->where(array('userid'=>$v['id']))->find();
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function edit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = null;
			}
			else {
				$this->data = M('User')->where(array('id' => trim($id)))->find();
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}

			if ($_POST['password']) {
				$_POST['password'] = md5($_POST['password']);
			}
			else {
				unset($_POST['password']);
			}

			if ($_POST['paypassword']) {
				$_POST['paypassword'] = md5($_POST['paypassword']);
			}
			else {
				unset($_POST['paypassword']);
			}
			if ($_POST['mibao_question']) {
				$_POST['mibao_question'] =$_POST['mibao_question'];
			}
			else {
				unset($_POST['mibao_question']);
			}
			if ($_POST['mibao_answer']) {
				$_POST['mibao_answer'] =$_POST['mibao_answer'];
			}
			else {
				unset($_POST['mibao_answer']);
			}

			$_POST['mobiletime'] = strtotime($_POST['mobiletime']);

			$result = M('User')->where(array('username'=>$_POST['username']))->find();
			
			if (empty($result)) {
				$_POST['addtime'] = time();
				$mo = M();
				$mo->execute('set autocommit=0');
				$mo->execute('lock tables tw_user write , tw_user_coin write ');
				$rs = array();
				$rs[] = $mo->table('tw_user')->add($_POST);
				$rs[] = $mo->table('tw_user_coin')->add(array('userid' => $rs[0]));
				if(check_arr($rs)){
					$mo->execute('commit');
					$mo->execute('unlock tables');
					$this->success('编辑成功！');
				}else{
					$this->error('编辑失败！');
				}
			}
			else {
				if(M('User')->save($_POST)){
					$this->success('编辑成功！');
				}else{
					$this->error('编辑失败！');
				}
			}
		}
	}

	public function status($id = NULL, $type = NULL, $mobile = 'User')
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
		$where1['userid'] = array('in', $id);
		$mobile_coin=$mobile.'_coin';
		switch (strtolower($type)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1, 'pwd_err' =>0);
			break;

		case 'repeal':
			$data = array('status' => 2, 'endtime' => time());
			break;

		case 'delete':
			$data = array('status' => -1);
			break;

		case 'del':
			if (M($mobile)->where($where)->delete()&&M($mobile_coin)->where($where1)->delete()) {
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

	public function admin($name = NULL, $field = NULL, $status = NULL)
	{
		$DbFields = M('Admin')->getDbFields();

		if (!in_array('email', $DbFields)) {
			M()->execute('ALTER TABLE `tw_admin` ADD COLUMN `email` VARCHAR(200)  NOT NULL   COMMENT \'\' AFTER `id`;');
		}

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

		$count = M('Admin')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Admin')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function adminEdit()
	{
		if (empty($_POST)) {
			if (empty($_GET['id'])) {
				$this->data = null;
			}
			else {
				$this->data = M('Admin')->where(array('id' => trim($_GET['id'])))->find();
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}

			$input = I('post.');

			if ($input['nickname'] && !check($input['nickname'], 'A')) {
				$this->error('昵称格式错误！');
			}

			if ($input['password'] && !check($input['password'], 'password')) {
				$this->error('登录密码格式错误！');
			}

			if ($input['mobile'] && !check($input['mobile'], 'mobile')) {
				$this->error('手机号码格式错误！');
			}

			if ($input['email'] && !check($input['email'], 'email')) {
				$this->error('邮箱格式错误！');
			}

			if ($input['password']) {
				$input['password'] = md5($input['password']);
			}
			else {
				unset($input['password']);
			}

			if ($_POST['id']) {
				$rs = M('Admin')->save($input);
			}
			else {
				$_POST['addtime'] = time();
				$rs = M('Admin')->add($input);
			}

			if ($rs) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
		}
	}

	public function adminStatus($id = NULL, $type = NULL, $mobile = 'Admin')
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

	public function auth()
	{
		$list = $this->lists('AuthGroup', array('module' => 'admin'), 'id asc');
		$list = int_to_string($list);
		$this->assign('_list', $list);
		$this->assign('_use_tip', true);
		$this->meta_title = '权限管理';
		$this->display();
	}

	public function authEdit()
	{
		if (empty($_POST)) {
			if (empty($_GET['id'])) {
				$this->data = null;
			}
			else {
				$this->data = M('AuthGroup')->where(array('module' => 'admin', 'type' => \Common\Model\AuthGroupModel::TYPE_ADMIN))->find((int) $_GET['id']);
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}

			if (isset($_POST['rules'])) {
				sort($_POST['rules']);
				$_POST['rules'] = implode(',', array_unique($_POST['rules']));
			}

			$_POST['module'] = 'admin';
			$_POST['type'] = \Common\Model\AuthGroupModel::TYPE_ADMIN;
			$AuthGroup = D('AuthGroup');
			$data = $AuthGroup->create();

			if ($data) {
				if (empty($data['id'])) {
					$r = $AuthGroup->add();
				}
				else {
					$r = $AuthGroup->save();
				}

				if ($r === false) {
					$this->error('操作失败' . $AuthGroup->getError());
				}
				else {
					$this->success('操作成功!');
				}
			}
			else {
				$this->error('操作失败' . $AuthGroup->getError());
			}
		}
	}

	public function authStatus($id = NULL, $type = NULL, $mobile = 'AuthGroup')
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

	public function authStart()
	{
		if (M('AuthRule')->where(array('status' => 1))->delete()) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function authAccess()
	{
		$this->updateRules();
		$auth_group = M('AuthGroup')->where(array(
			'status' => array('egt', '0'),
			'module' => 'admin',
			'type'   => \Common\Model\AuthGroupModel::TYPE_ADMIN
			))->getfield('id,id,title,rules');
		$node_list = $this->returnNodes();
		$map = array('module' => 'admin', 'type' => \Common\Model\AuthRuleModel::RULE_MAIN, 'status' => 1);
		$main_rules = M('AuthRule')->where($map)->getField('name,id');
		$map = array('module' => 'admin', 'type' => \Common\Model\AuthRuleModel::RULE_URL, 'status' => 1);
		$child_rules = M('AuthRule')->where($map)->getField('name,id');
		$this->assign('main_rules', $main_rules);
		$this->assign('auth_rules', $child_rules);
		$this->assign('node_list', $node_list);
		$this->assign('auth_group', $auth_group);
		$this->assign('this_group', $auth_group[(int) $_GET['group_id']]);
		$this->meta_title = '访问授权';
		$this->display();
	}

	protected function updateRules()
	{
		$nodes = $this->returnNodes(false);
		$AuthRule = M('AuthRule');
		$map = array(
			'module' => 'admin',
			'type'   => array('in', '1,2')
			);
		$rules = $AuthRule->where($map)->order('name')->select();
		$data = array();

		foreach ($nodes as $value) {
			$temp['name'] = $value['url'];
			$temp['title'] = $value['title'];
			$temp['module'] = 'admin';

			if (0 < $value['pid']) {
				$temp['type'] = \Common\Model\AuthRuleModel::RULE_URL;
			}
			else {
				$temp['type'] = \Common\Model\AuthRuleModel::RULE_MAIN;
			}

			$temp['status'] = 1;
			$data[strtolower($temp['name'] . $temp['module'] . $temp['type'])] = $temp;
		}

		$update = array();
		$ids = array();

		foreach ($rules as $index => $rule) {
			$key = strtolower($rule['name'] . $rule['module'] . $rule['type']);

			if (isset($data[$key])) {
				$data[$key]['id'] = $rule['id'];
				$update[] = $data[$key];
				unset($data[$key]);
				unset($rules[$index]);
				unset($rule['condition']);
				$diff[$rule['id']] = $rule;
			}
			else if ($rule['status'] == 1) {
				$ids[] = $rule['id'];
			}
		}

		if (count($update)) {
			foreach ($update as $k => $row) {
				if ($row != $diff[$row['id']]) {
					$AuthRule->where(array('id' => $row['id']))->save($row);
				}
			}
		}

		if (count($ids)) {
			$AuthRule->where(array(
				'id' => array('IN', implode(',', $ids))
				))->save(array('status' => -1));
		}

		if (count($data)) {
			$AuthRule->addAll(array_values($data));
		}

		if ($AuthRule->getDbError()) {
			trace('[' . 'Admin\\Controller\\UserController::updateRules' . ']:' . $AuthRule->getDbError());
			return false;
		}
		else {
			return true;
		}
	}

	public function authAccessUp()
	{
		if (isset($_POST['rules'])) {
			sort($_POST['rules']);
			$_POST['rules'] = implode(',', array_unique($_POST['rules']));
		}

		$_POST['module'] = 'admin';
		$_POST['type'] = \Common\Model\AuthGroupModel::TYPE_ADMIN;
		$AuthGroup = D('AuthGroup');
		$data = $AuthGroup->create();

		if ($data) {
			if (empty($data['id'])) {
				$r = $AuthGroup->add();
			}
			else {
				$r = $AuthGroup->save();
			}

			if ($r === false) {
				$this->error('操作失败' . $AuthGroup->getError());
			}
			else {
				$this->success('操作成功!');
			}
		}
		else {
			$this->error('操作失败' . $AuthGroup->getError());
		}
	}

	public function authUser($group_id)
	{
		if (empty($group_id)) {
			$this->error('参数错误');
		}

		$auth_group = M('AuthGroup')->where(array(
			'status' => array('egt', '0'),
			'module' => 'admin',
			'type'   => \Common\Model\AuthGroupModel::TYPE_ADMIN
			))->getfield('id,title,rules');
		$prefix = C('DB_PREFIX');
		$l_table = $prefix . \Common\Model\AuthGroupModel::MEMBER;
		$r_table = $prefix . \Common\Model\AuthGroupModel::AUTH_GROUP_ACCESS;
		$model = M()->table($l_table . ' m')->join($r_table . ' a ON m.id=a.uid');
		$_REQUEST = array();
		$list = $this->lists($model, array(
			'a.group_id' => $group_id,
			'm.status'   => array('egt', 0)
			), 'm.id asc', null, 'm.id,m.username,m.nickname,m.last_login_time,m.last_login_ip,m.status');
		int_to_string($list);
		$this->assign('_list', $list);
		$this->assign('auth_group', $auth_group);
		$this->assign('this_group', $auth_group[(int) $_GET['group_id']]);
		$this->meta_title = '成员授权';
		$this->display();
	}

	public function authUserAdd()
	{
		$uid = I('uid');

		if (empty($uid)) {
			$this->error('请输入后台成员信息');
		}

		if (!check($uid, 'd')) {
			$user = M('Admin')->where(array('username' => $uid))->find();

			if (!$user) {
				$user = M('Admin')->where(array('nickname' => $uid))->find();
			}

			if (!$user) {
				$user = M('Admin')->where(array('mobile' => $uid))->find();
			}

			if (!$user) {
				$this->error('用户不存在(id 用户名 昵称 手机号均可)');
			}

			$uid = $user['id'];
		}

		$gid = I('group_id');

		if ($res = M('AuthGroupAccess')->where(array('uid' => $uid))->find()) {
			if ($res['group_id'] == $gid) {
				$this->error('已经存在,请勿重复添加');
			}
			else {
				$res = M('AuthGroup')->where(array('id' => $gid))->find();

				if (!$res) {
					$this->error('当前组不存在');
				}

				$this->error('已经存在[' . $res['title'] . ']组,不可重复添加');
			}
		}

		$AuthGroup = D('AuthGroup');

		if (is_numeric($uid)) {
			if (is_administrator($uid)) {
				$this->error('该用户为超级管理员');
			}

			if (!M('Admin')->where(array('id' => $uid))->find()) {
				$this->error('管理员用户不存在');
			}
		}

		if ($gid && !$AuthGroup->checkGroupId($gid)) {
			$this->error($AuthGroup->error);
		}

		if ($AuthGroup->addToGroup($uid, $gid)) {
			$this->success('操作成功');
		}
		else {
			$this->error($AuthGroup->getError());
		}
	}

	public function authUserRemove()
	{
		$uid = I('uid');
		$gid = I('group_id');

		/*if ($uid == UID) {
			$this->error('不允许解除自身授权');
		}*/

		if (empty($uid) || empty($gid)) {
			$this->error('参数有误');
		}

		$AuthGroup = D('AuthGroup');

		if (!$AuthGroup->find($gid)) {
			$this->error('用户组不存在');
		}

		if ($AuthGroup->removeFromGroup($uid, $gid)) {
			$this->success('操作成功');
		}
		else {
			$this->error('操作失败');
		}
	}

	public function log($name = NULL, $field = NULL, $status = NULL)
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

		$count = M('UserLog')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('UserLog')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function logEdit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = null;
			}
			else {
				$this->data = M('UserLog')->where(array('id' => trim($id)))->find();
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}

			$_POST['addtime'] = strtotime($_POST['addtime']);

			if (M('UserLog')->save($_POST)) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
		}
	}

	public function logStatus($id = NULL, $type = NULL, $mobile = 'UserLog')
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

	public function qianbao($name = NULL, $field = NULL, $coinname = NULL, $status = NULL)
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

		if ($coinname) {
			$where['coinname'] = trim($coinname);
		}

		$count = M('UserQianbao')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('UserQianbao')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function qianbaoEdit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = null;
			}
			else {
				$this->data = M('UserQianbao')->where(array('id' => trim($id)))->find();
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}

			$_POST['addtime'] = strtotime($_POST['addtime']);

			if (M('UserQianbao')->save($_POST)) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
		}
	}

	public function qianbaoStatus($id = NULL, $type = NULL, $mobile = 'UserQianbao')
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

	public function bank($name = NULL, $field = NULL, $status = NULL)
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

		$count = M('UserBank')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('UserBank')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function bankEdit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = null;
			}
			else {
				$this->data = M('UserBank')->where(array('id' => trim($id)))->find();
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}

			$_POST['addtime'] = strtotime($_POST['addtime']);

			if (M('UserBank')->save($_POST)) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
		}
	}

	public function bankStatus($id = NULL, $type = NULL, $mobile = 'UserBank')
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

	public function coin($name = NULL, $field = NULL)
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

		$count = M('UserCoin')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('UserCoin')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function coinEdit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = null;
			}
			else {
				$this->data = M('UserCoin')->where(array('id' => trim($id)))->find();
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}

			try{

				$mo = M();

				$mo->execute('set autocommit=0');

				$mo->execute('lock tables tw_user_coin write ,tw_finance_log write ,tw_coin read ,tw_user read');

				// 获取该用户信息
				$user_coin_info = $mo->table('tw_user_coin')->where(array('id' => $_POST['id']))->find();
				$user_info = $mo->table('tw_user')->where(array('id' => $user_coin_info['userid']))->find();

				$coin_list = $mo->table('tw_coin')->where(array('status' => 1))->select();

				$rs = array();
				
				foreach ($coin_list as $k => $v) {

					// 判断那些币种账户发生变化
					if($user_coin_info[$v['name']] != $_POST[$v['name']]){
						if($user_coin_info[$v['name']] > $_POST[$v['name']]){
							// 账户数目减少---0减少1增加
							$plusminus = 0;
						}else{
							$plusminus = 1;
						}

						$amount = abs($user_coin_info[$v['name']] - $_POST[$v['name']]);

						$rs[] = $mo->table('tw_finance_log')->add(array('username' => $user_info['username'], 'adminname' => session('admin_username'), 'addtime' => time(), 'plusminus' => $plusminus, 'amount' => $amount, 'optype' => 3, 'cointype' => $v['id'], 'old_amount' => $user_coin_info[$v['name']], 'new_amount' => $_POST[$v['name']], 'userid' => $user_info['id'], 'adminid' => session('admin_id'),'addip'=>get_client_ip()));
					}
					
				}



				// 更新用户账户数据
				$rs[] = $mo->table('tw_user_coin')->save($_POST);


				if (check_arr($rs)) {

					$mo->execute('commit');

					$mo->execute('unlock tables');

				}else {
					throw new \Think\Exception('编辑失败！');
				}

				$this->success('编辑成功！');


			}catch(\Think\Exception $e){
			
				$mo->execute('rollback');
				$mo->execute('unlock tables');
				$this->error('编辑失败！');
			}
		}
	}

	public function coinLog($userid = NULL, $coinname = NULL)
	{
		$data['userid'] = $userid;
		$data['username'] = M('User')->where(array('id' => $userid))->getField('username');
		$data['coinname'] = $coinname;
		$data['zhengcheng'] = M('UserCoin')->where(array('userid' => $userid))->getField($coinname);
		$data['dongjie'] = M('UserCoin')->where(array('userid' => $userid))->getField($coinname . 'd');
		$data['zongji'] = $data['zhengcheng'] + $data['dongjie'];
		$data['chongzhicny'] = M('Mycz')->where(array(
			'userid' => $userid,
			'status' => array('neq', '0')
			))->sum('num');
		$data['tixiancny'] = M('Mytx')->where(array('userid' => $userid, 'status' => 1))->sum('num');
		$data['tixiancnyd'] = M('Mytx')->where(array('userid' => $userid, 'status' => 0))->sum('num');

		if ($coinname != 'btc' && $coinname != 'eth') {
			$data['chongzhi'] = M('Myzr')->where(array(
				'userid' => $userid,
				'status' => array('neq', '0')
				))->sum('num');
			$data['tixian'] = M('Myzc')->where(array('userid' => $userid, 'status' => 1))->sum('num');
		}

		$this->assign('data', $data);
		$this->display();
	}

	public function goods($name = NULL, $field = NULL, $status = NULL)
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

		$count = M('UserGoods')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('UserGoods')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function goodsEdit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = null;
			}
			else {
				$this->data = M('UserGoods')->where(array('id' => trim($id)))->find();
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}

			$_POST['addtime'] = strtotime($_POST['addtime']);

			if (M('UserGoods')->save($_POST)) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
		}
	}

	public function goodsStatus($id = NULL, $type = NULL, $mobile = 'UserGoods')
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

	public function setpwd()
	{
		if (IS_POST) {
			defined('APP_DEMO') || define('APP_DEMO', 0);

			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}

			$oldpassword = $_POST['oldpassword'];
			$newpassword = $_POST['newpassword'];
			$repassword = $_POST['repassword'];

			if (!check($oldpassword, 'password')) {
				$this->error('旧密码格式错误！');
			}

			if (md5($oldpassword) != session('admin_password')) {
				$this->error('旧密码错误！');
			}

			if (!check($newpassword, 'password')) {
				$this->error('新密码格式错误！');
			}

			if ($newpassword != $repassword) {
				$this->error('确认密码错误！');
			}

			if (D('Admin')->where(array('id' => session('admin_id')))->save(array('password' => md5($newpassword)))) {
				$this->success('登陆密码修改成功！', U('Login/loginout'));
			}
			else {
				$this->error('登陆密码修改失败！');
			}
		}

		$this->display();
	}
	
	public function userExcel(){
		if (IS_POST) {
			$id = implode(',', $_POST['id']);
		}
		else {
			$id = $_GET['id'];
		}

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$where['id'] = array('in', $id);
		// 处理搜索的数据=================================================

		$list = M('User')->where($where)->select();
		foreach ($list as $k => $v) {
			$list[$k]['addtime'] = addtime($v['addtime']);

			if ($list[$k]['status'] == 1) {
				$list[$k]['status'] = '正常';
			}
			else {
				$list[$k]['status'] = '禁止';
			}
		}

		$zd = M('User')->getDbFields();
		array_splice($zd, 3, 7);
		array_splice($zd, 5, 5);
		array_splice($zd, 6, 1);
		array_splice($zd, 7, 7);
		$xlsName = 'cade';
		$xls = array();

		foreach ($zd as $k => $v) {
			$xls[$k][0] = $v;
			$xls[$k][1] = $v;
		}

		$xls[0][2] = 'ID';
		$xls[1][2] = '用户名';
		$xls[2][2] = '手机号';
		$xls[3][2] = '真实姓名';
		$xls[4][2] = '身份证号';
		$xls[5][2] = '注册时间';
		$xls[6][2] = '状态';

		$this->cz_exportExcel($xlsName, $xls, $list);
	}
}

?>