<?php
namespace Home\Controller;

class UserController extends HomeController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","login","nameauth","upauth","password","mibao","upmibao","uppassword","paypassword","uppaypassword","ga","mobile","upmobile","email","upemail","bank","upbank","delbank","qianbao","upqianbao","delqianbao","log","api","delcache","dodelcache","rmdirr","feedback","subfeedback","feedbacklist","addreply","feedbackdetail","upauth2");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("非法操作！");
		}
	}

	public function __construct() {
		parent::__construct();
		$display_action=array("index","login","nameauth","password","mibao","paypassword","ga","mobile","email","bank","qianbao","log","api","delcache","feedback","feedbacklist","feedbackdetail");
		if(in_array(ACTION_NAME,$display_action)){
			$this->common_content();
		}
	}

	public function feedback(){
		if (!userid()) {
			redirect('/Login/index.html');
		}
		$this->display();
	}

	public function subfeedback(){
		if (!userid()) {
			$this->error("请先登录！");
		}
		$update = array();
		$upload = new \Think\Upload();//实列化上传类
		$upload->maxSize=3145728;//设置上传文件最大，大小
		$upload->exts= array('jpg','gif','png','jpeg');//后缀
		$upload->rootPath ='./Upload/lanch/pic/';//上传目录
		$upload->savePath      =  ''; // 设置附件上传（子）目录
		$upload->autoSub     = true;
		$upload->subName     = array('date','Ymd');
		$upload->saveName = array('uniqid','');//设置上传文件规则
		$info= $upload->upload();//执行上传方法
		if($info){
			$image = new \Think\Image();
			foreach($info as $key=>$file){
				if(!empty($file)){
					$image->open('./Upload/lanch/pic/'.$file['savepath'].$file['savename']);
					$width = $image->width();
					$height = $image->height();
					if(empty($width) || empty($height)){
						$bili = 1;
					}else{
						$bili = intval($width/$height);
					}
					$new_width = 600;
					$new_height = intval($new_width/$bili);
					// 按照原图的比例生成一个最大宽度为600像素的缩略图并删除原图
					$image->thumb($new_width, $new_height)->save('./Upload/lanch/pic/'.$file['savepath']."s_".$file['savename']);
					unlink('./Upload/lanch/pic/'.$file['savepath'].$file['savename']);
				}
			}
		}
		if(!empty($info['attachone'])){
			$update['attachone'] = "http://tebtc.oss-ap-southeast-1.aliyuncs.com/pic/".$info['attachone']['savepath']."s_".$info['attachone']['savename'];
		}
		if(!empty($info['attachtwo'])){
			$update['attachtwo'] = "http://tebtc.oss-ap-southeast-1.aliyuncs.com/pic/".$info['attachtwo']['savepath']."s_".$info['attachtwo']['savename'];
		}

		$update['title'] = trim($_POST['title']);
		if (!preg_match('/^[\\x{4e00}-\\x{9fa5}0-9a-zA-Z，。？\s]{2,50}$/u',$update['title'])) {
			$this->error('标题只能写中英文数字和空格，长度2-50字！');
		}
		$update['content'] = trim($_POST['content']);
		if (!preg_match('/^[\\x{4e00}-\\x{9fa5}0-9a-zA-Z，。？\s]{2,200}$/u',$update['content'])) {
			$this->error('描述只能写中英文数字和空格，长度2-200字！');
		}
		if(!empty($_POST['txid'])){
			$txid = trim($_POST['txid']);
			if(!preg_match('/^[a-zA-Z0-9]{10,100}$/u',$txid)){
				$this->error('TxID格式错误！');
			}
			$update['txid'] = $txid;
		}
		$update['subject'] = $_POST['subject'];
		if(!in_array($update['subject'],array('用户注册','人民币充值','人民币提现','虚拟币充值','虚拟币提现','虚拟币交易','绑定安全措施','修改账号资料','被盗找回','API问题','其它'))){
			$this->error('问题类型错误！');
		}
		$time = time();
		$update['userid'] = userid();
		$update['username'] = username();
		$update['addtime'] = $time;
		$update['freshtime'] = $time;
		$update['userstatus'] = 0;
		$update['adminstatus'] = 1;
		$update['recordno'] = $time.userid();
		$result = M('Feedback')->add($update);
		if($result){
			$this->success('提交成功！','/User/feedbacklist.html');
		}else{
			$this->error('提交失败！');
		}
	}

	public function feedbacklist(){
		if(!userid()){
			redirect('/Login/index.html');
		}
		$mo = M('Feedback');
		$where=array();
		$where['userid'] = userid();
		$count = $mo->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = $mo->where($where)->order('freshtime desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['addtime'] = date("Y-m-d H:i:s",$v['addtime']);
			$list[$k]['freshtime'] = !empty($v['freshtime']) ? date("Y-m-d H:i:s",$v['freshtime']) : "---";
			if(!empty($v['userstatus'])){
				$list[$k]['status'] = "<span style='color:#e55600;'>有新回复</span>";
			}else{
				if(!empty($v['adminstatus'])){
					$list[$k]['status'] = "<span style='color:#e55600;'>等待管理员回复</span>";
				}else{
					$list[$k]['status'] = "<span style='color:#e55600;'>---</span>";
				}
			}
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function feedbackdetail($id){
		if (!userid()) {
			redirect('/Login/index.html');
		}
		if(empty($id)){
			$this->error('参数错误！');
		}
		if(!check($id,'d')){
			$this->error('参数错误！');
		}
		$feedback_record = M('Feedback')->where(array('id'=>$id,'userid'=>userid()))->find();
		if(!empty($feedback_record)){
			if(!empty($feedback_record['adminstatus'])){
				$feedback_record['status'] = "<span style='color:#e55600;'>等待管理员回复</span>";
			}elseif(!empty($feedback_record['userstatus'])){
				$feedback_record['status'] = "<span style='color:#e55600;'>有新回复</span>";
			}else{
				$feedback_record['status'] = "---";
			}
			$this->assign('feedback_record',$feedback_record);
			$feedback_reply = M('Feedback_reply')->where(array('fid'=>$feedback_record['id']))->order('addtime asc')->select();
			if(!empty($feedback_reply)){
				$this->assign('feedback_reply',$feedback_reply);
			}
			$mo = M();
			$mo->table('tw_feedback')->where(array('id'=>$id))->save(array('userstatus'=>0));
		}
		$this->display();
	}

	public function addreply(){
		if (!userid()) {
			$this->error("请先登录！");
		}
		if(empty($_POST['fid'])){
			$this->error("参数错误！");
		}
		if(empty($_POST['content'])){
			$this->error("请填写回复内容！");
		}
		$data['fid'] = $_POST['fid'];
		if(!check($data['fid'],'d')){
			$this->error("参数错误！");
		}
		$feedback_record = M('Feedback')->where(array('id'=>$data['fid']))->find();
		if(empty($feedback_record) || $feedback_record['userid'] != userid()){
			$this->error("参数错误！");
		}
		$data['content'] = $_POST['content'];
		if (!preg_match('/^[\\x{4e00}-\\x{9fa5}0-9a-zA-Z，。？\s]{2,200}$/u',$data['content'])) {
			$this->error('回复只能写中英文数字和空格，长度2-200字！');
		}
		$data['userid']=userid();
		$data['username'] = $feedback_record['username'];
		$data['addtime'] = time();
		$result = M('Feedback_reply')->add($data);
		if($result){
			$update['id'] = $_POST['fid'];
			$update['adminstatus'] = 1;
			$update['userstatus'] = 0;
			$update['freshtime'] = time();
			$update['isread'] = 0;
			$res = M('Feedback')->save($update);
			if(!$res){
				$this->error("更新留言状态失败！");
			}else{
				$this->success("留言成功！","/User/feedbackdetail.html?id=".$feedback_record['id']);
			}
		}else{
			$this->error("留言失败！");
		}
	}

	public function index()
	{
		if (!userid()) {
			redirect('/Login/index.html');
		}

		$user = M('User')->where(array('id' => userid()))->find();
		$this->assign('user', $user);
		$this->display();
	}


	public function login()
	{
		$link= M('Link')->where(array('status' => 1))->select();
		$this->assign('link', $link);
		$this->display();
	}

	public function nameauth()
	{
		if (!userid()) {
			redirect('/#login');
		}

		$arr = array('hz'=>L('g_hz'),'mgsfz'=>L('g_mgsfz'),'sfz'=>L('g_sfz'),'qz'=>L('g_qz'),'qt'=>L('g_qt'));
		$idcardtype = array(
			array('hz',L('g_hz')),
			array('mgsfz',L('g_mgsfz')),
			array('sfz',L('g_sfz')),
			array('qz',L('g_qz')),
			array('qt',L('g_qt'))
		);
		$this->assign('idcardtype',$idcardtype);

		$user = M('User')->where(array('id' => userid()))->find();
		if ($user['idcard']) {
			$user['idcard'] = substr_replace($user['idcard'], '********', 6, 8);
		}
		$user['zhengjian'] = $arr[$user['zhengjian']];
		$this->assign('user', $user);
		$shiming = shiming($user['id']);
		$this->assign('shiming',$shiming);
		$this->display();
	}

	public function upauth(){
		if (!userid()) {
			redirect('/#login');
		}
		$user = M('User')->where(array('id' => userid()))->find();
		$shiming = shiming($user['id']);
		if($shiming>0){
			$this->error(L('g_has_tjsmrzsf'));
		}
		$update = array();
		$update['truename'] = trim($_POST['truename']);
		if (!check($update['truename'], 'truename')) {
			$this->error(L('g_error_realname'));
		}
		$update['idcard'] = trim($_POST['idcard']);
		if (!check($update['idcard'], 'idcard')) {
			$this->error(L('g_error_zj'));
		}
		$update['zhengjian'] = $_POST['zhengjian'];
		if(!in_array($update['zhengjian'],array('hz','sfz','mgsfz','qz','qt'))){
			$this->error(L('g_error_zjtype'));
		}
		$result = M('User')->where(array('id'=>userid()))->save($update);
		if($result){
			$this->success(L('sub_suc'));
		}else{
			$this->error(L('sub_fail'));
		}
	}

	public function upauth2(){
		if (!userid()) {
			redirect('/#login');
		}
		$user = M('User')->where(array('id' => userid()))->find();
		$shiming = shiming($user['id']);
		if($shiming<1){
			$this->error(L('g_up_qian'));
		}
		if($shiming>1){
			$this->error(L('g_has_tjsf'));
		}
		$upload = new \Think\Upload();//实列化上传类
		$upload->maxSize=3145728;//设置上传文件最大，大小
		$upload->exts= array('jpg','gif','png','jpeg');//后缀
		$upload->rootPath ='./Upload/lanch/idcard/';//上传目录
		$upload->savePath      =  ''; // 设置附件上传（子）目录
		$upload->autoSub     = true;
		$upload->subName     = array('date','Ymd');
		$upload->saveName = array('uniqid','');//设置上传文件规则
		$info= $upload->upload();//执行上传方法
		if(!$info){
			$this->error($upload->getError());
		}else{
			$image = new \Think\Image();
			foreach($info as $key=>$file){
				$image->open('./Upload/lanch/idcard/'.$file['savepath'].$file['savename']);
				$width = $image->width();
				$height = $image->height();
				if(empty($width) || empty($height)){
					$bili = 1;
				}else{
					$bili = intval($width/$height);
				}
				$new_width = 600;
				$new_height = intval($new_width/$bili);
				// 按照原图的比例生成一个最大宽度为600像素的缩略图并删除原图
				$image->thumb($new_width, $new_height)->save('./Upload/lanch/idcard/'.$file['savepath']."s_".$file['savename']);
				unlink('./Upload/lanch/idcard/'.$file['savepath'].$file['savename']);
			}
		}
		$update = array();
		$update['is_agree'] = 0;
		$update['idcard_zheng'] = "/Upload/lanch/idcard/" . $info['idcard_zheng']['savepath'] . "s_" . $info['idcard_zheng']['savename'];
		$update['idcard_fan'] = "/Upload/lanch/idcard/" . $info['idcard_fan']['savepath'] . "s_" . $info['idcard_fan']['savename'];
		$update['idcard_shouchi'] = "/Upload/lanch/idcard/" . $info['idcard_shouchi']['savepath'] . "s_" . $info['idcard_shouchi']['savename'];
		$result = M('User')->where(array('id' => userid()))->save($update);

		if($result){
			$this->success(L('sub_suc'));
		}else{
			$this->error(L('sub_fail'));
		}
	}

	public function password()
	{
		if (!userid()) {
			redirect('/#login');
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');
		if(empty($email)){
			$this->error("请先绑定邮箱！");
		}
		$user = M('User')->where(array('id' => userid()))->find();
		$this->assign('user', $user);
		$this->assign('mobile', $mobile);

		$this->display();
	}

	public function mibao()
	{
		if (!userid()) {
			redirect('/#login');
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');
		if(empty($email)){
			$this->error("请先绑定邮箱！");
		}

		$user = M('User')->where(array('id' => userid()))->find();
		$this->assign('user', $user);
		$this->display();
	}

	public function upmibao($email_verify, $mibao_question, $mibao_answer, $new_mibao_question, $new_mibao_answer, $findpwd_mibao, $findpaypwd_mibao)
	{

		// 过滤非法字符----------------S

		if (checkstr($email_verify) || checkstr($mibao_question) || checkstr($mibao_answer) || checkstr($new_mibao_question) || checkstr($new_mibao_answer)) {
			$this->error(L('info_error') );
		}

		// 过滤非法字符----------------E

		if (!userid()) {
			$this->error(L('login_first') );
		}

		if(empty($findpwd_mibao)){
			$findpwd_mibao = 0;
		}else{
			$findpwd_mibao = 1;
		}

		if(empty($findpaypwd_mibao)){
			$findpaypwd_mibao = 0;
		}else{
			$findpaypwd_mibao = 1;
		}

		if (!check($email_verify, 'd')) {
			$this->error(L('g_yx_verfygs_error') );
		}

		$user = M('User')->where(array('id' => userid()))->find();

		if ($user['email'] != session('chkemail')) {
			$this->error(L('g_yx_verfy_error') );
		}

		if ($email_verify != session('emailmibao_verify')) {
			$this->error(L('g_yx_verfy_error') );
		}

		if (($user['mibao_question']!=''||$user['mibao_question']!=NULL)&&$user['mibao_question'] != $mibao_question) {

			$this->error(L('code_que_err') );

		}

		if (($user['mibao_answer']!=''||$user['mibao_answer']!=NULL)&&$user['mibao_answer'] != $mibao_answer) {

			$this->error(L('code_ans_err') );

		}

		if(empty($user['mibao_question'])){
			$rs = M('User')->where(array('id' => userid()))->save(array('mibao_question' => $new_mibao_question,'mibao_answer'=>$new_mibao_answer,'findpwd_mibao'=>$findpwd_mibao,'findpaypwd_mibao'=>$findpaypwd_mibao));
		}else{
			if(empty($new_mibao_answer)){
				$rs = M('User')->where(array('id' => userid()))->save(array('findpwd_mibao'=>$findpwd_mibao,'findpaypwd_mibao'=>$findpaypwd_mibao));
			}else{
				$rs = M('User')->where(array('id' => userid()))->save(array('mibao_question' => $new_mibao_question,'mibao_answer'=>$new_mibao_answer,'findpwd_mibao'=>$findpwd_mibao,'findpaypwd_mibao'=>$findpaypwd_mibao));
			}
		}

		if ($rs) {
			session('mibao_verify', null);
			session('emailmibao_verify', null);
			session('chkemail', null);
			$this->success(L('modi_suc') );
		}
		else {
			$this->error(L('modi_fail') );
		}
	}

	public function uppassword($oldpassword, $newpassword, $repassword, $email_verify)
	{

		// 过滤非法字符----------------S

		if (checkstr($oldpassword) || checkstr($newpassword) || checkstr($repassword) || checkstr($email_verify)) {
			$this->error(L('info_error'));
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			$this->error( L('login_first'));
		}

		$user_info = M('user')->where(array('id'=>userid()))->find();

		if (!check($email_verify, 'd')) {
			$this->error( L('g_yx_verfygs_error'));
		}
		if ($user_info['email'] != session('chkemail')) {
			$this->error(L('g_yx_verfy_error'));
		}

		if ($email_verify != session('emailpass_verify')) {
			$this->error(L('g_yx_verfy_error'));
		}

		if (!check($oldpassword, 'password')) {
			$this->error(L('code_format'));
		}

		if (strlen($newpassword) > 16 || strlen($newpassword) < 6) {
			$this->error(L('code_format'));
		}

		if (!check($newpassword, 'password')) {
			$this->error(L('code_format'));
		}

		if ($newpassword != $repassword) {
			$this->error(L('User_lcmmbyz'));
		}

		$password = M('User')->where(array('id' => userid()))->getField('password');
		$paypasswords = M('User')->where(array('id' => userid()))->getField('paypassword');

		if (md5($oldpassword) != $password) {
			$this->error(L('old_code_err'));
		}

		if (md5($newpassword) == $paypasswords) {
			$this->error(L('login_deal_cant_same'));
		}

		if (md5($newpassword) == $password) {
			$this->error(L('new_old_same_fail'));
		}

		$rs = M('User')->where(array('id' => userid()))->save(array('password' => md5($newpassword)));

		if ($rs) {
			session('pass_verify', null);
			$this->success(L('modi_suc'));
		}
		else {
			$this->error(L('modi_fail') );
		}
	}

	public function paypassword()
	{
		if (!userid()) {
			redirect('/#login');
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');
		if(empty($email)){
			$this->error("请先绑定邮箱！");
		}
		$paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');
		if($paypassword==NULL){
			$paypwd=0;
		}else{
			$paypwd=1;
		}
		$user = M('User')->where(array('id' => userid()))->find();
		$this->assign('user', $user);
		$this->assign('paypwd', $paypwd);
		$this->assign('mobile', $mobile);

		$this->display();
	}

	public function uppaypassword($oldpaypassword, $newpaypassword, $repaypassword, $email_verify)
	{

		// 过滤非法字符----------------S

		if (checkstr($oldpaypassword) || checkstr($newpaypassword) || checkstr($repaypassword) || checkstr($email_verify)) {
			$this->error(L('info_error'));
		}

		// 过滤非法字符----------------E

		if (!userid()) {
			$this->error(L('login_first'));
		}

		$user_info = M('user')->where(array('id'=>userid()))->find();
		$orgempty = !empty($user_info['paypassword']) ? false : true;

		if ($user_info['email'] != session('chkemail')) {
			$this->error(L('g_yx_verfy_error') );
		}

		if (!check($email_verify, 'd')) {
			$this->error(L('g_yx_verfygs_error') );
		}

		if ($email_verify != session('emailpaypwd_verify')) {
			$this->error(L('g_yx_verfy_error') );
		}

		if (($user_info['paypassword']!=''||$user_info['paypassword']!=NULL)&&(!check($oldpaypassword, 'password'))) {
			$this->error(L('code_format'));
		}

		if (strlen($newpaypassword) > 16 || strlen($newpaypassword) < 6) {
			$this->error(L('code_format') );
		}

		if (!check($newpaypassword, 'password')) {
			$this->error(L('code_format') );
		}

		if ($newpaypassword != $repaypassword) {
			$this->error(L('User_lcmmbyz'));
		}

		$user = M('User')->where(array('id' => userid()))->find();

		if (($user_info['paypassword']!=''||$user_info['paypassword']!=NULL)&&(md5($oldpaypassword) != $user['paypassword'])) {
			$this->error(L('old_deal_code_err') );
		}

		if (($user_info['paypassword']!=''||$user_info['paypassword']!=NULL)&&(md5($newpaypassword) == $user['paypassword'])) {
			$this->error(L('g_pwd_new_old'));
		}

		if (md5($newpaypassword) == $user['password']) {
			$this->error(L('code_cant_same') );
		}

		$rs = M('User')->where(array('id' => userid()))->save(array('paypassword' => md5($newpaypassword)));

		if ($rs) {
			session('paypass_verify', null);
			if($orgempty){
				$this->success(L('g_pwd_set_success') );
			}else{
				$this->success(L('modi_suc') );
			}
		}
		else {
			$this->error(L('modi_fail') );
		}
	}

	public function ga()
	{
		if (empty($_POST)) {
			if (!userid()) {
				redirect('/#login');
			}

			$this->assign('prompt_text', D('Text')->get_content('user_ga'));
			$user = M('User')->where(array('id' => userid()))->find();
			$is_ga = ($user['ga'] ? 1 : 0);
			$this->assign('is_ga', $is_ga);

			if (!$is_ga) {
				$ga = new \Common\Ext\GoogleAuthenticator();
				$secret = $ga->createSecret();
				session('secret', $secret);
				$this->assign('Asecret', $secret);
				$zhanghu=$user['username'].' - '.$_SERVER['HTTP_HOST'];
				$this->assign('zhanghu', $zhanghu);
				$qrCodeUrl = $ga->getQRCodeGoogleUrl($user['username'] . '%20-%20' . $_SERVER['HTTP_HOST'], $secret);
				$this->assign('qrCodeUrl', $qrCodeUrl);
				$this->display();
			}
			else {
				$arr = explode('|', $user['ga']);
				$this->assign('ga_login', $arr[1]);
				$this->assign('ga_transfer', $arr[2]);
				$this->display();
			}
		}
		else {


			foreach ($_POST as $k => $v) {
				// 过滤非法字符----------------S

				if (checkstr($v)) {
					$this->error(L('info_error'));
				}

				// 过滤非法字符----------------E
			}


			if (!userid()) {
				$this->error(L('relogin'));
			}

			$delete = '';
			$gacode = trim(I('ga'));
			$type = trim(I('type'));
			$ga_login = (I('ga_login') == false ? 0 : 1);
			$ga_transfer = (I('ga_transfer') == false ? 0 : 1);

			if (!$gacode) {
				$this->error(L('input_verify'));
			}

			if ($type == 'add') {
				$secret = session('secret');

				if (!$secret) {
					$this->error(L('refresh') );
				}
			}
			else if (($type == 'updat') || ($type == 'delet')) {
				$user = M('User')->where('id = ' . userid())->find();

				if (!$user['ga']) {
					$this->error( L('not_set_guge_code'));
				}

				$arr = explode('|', $user['ga']);
				$secret = $arr[0];
				$delete = ($type == 'delet' ? 1 : 0);
			}
			else {
				$this->error(L('opera_not_define') );
			}

			$ga = new \Common\Ext\GoogleAuthenticator();

			if ($ga->verifyCode($secret, $gacode, 1)) {
				$ga_val = ($delete == '' ? $secret . '|' . $ga_login . '|' . $ga_transfer : '');
				$mo=M();
				//
				$rs=$mo->table('tw_user')->save(array('id' => userid(), 'ga' => $ga_val));
				if($rs){
					$this->success(L('opera_suc') );
				}else{
					$this->error(L('opera_fail') );
				}
			}
			else {
				$this->error(L('verify_fail') );
			}
		}
	}

	public function mobile()
	{
		if (!userid()) {
			redirect('/#login');
		}

		$user = M('User')->where(array('id' => userid()))->find();

		if ($user['mobile']) {
			$user['mobile'] = substr_replace($user['mobile'], '****', 3, 4);
		}

		$this->assign('user', $user);
		$this->display();
	}

	public function upmobile($mobile, $mobile_verify)
	{

		// 过滤非法字符----------------S

		if (checkstr($mobile) || checkstr($mobile_verify)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!userid()) {
			$this->error('您没有登录请先登录！');
		}

		if (!check($mobile, 'mobile')) {
			$this->error('手机号码格式错误！');
		}

		if ($mobile != session('chkmobile')) {
			$this->error('短信验证码错误！');
		}

		if (!check($mobile_verify, 'd')) {
			$this->error('短信验证码格式错误！');
		}

		if ($mobile_verify != session('mobilebd_verify')) {
			$this->error('短信验证码错误！');
		}

		if (M('User')->where(array('mobile' => $mobile))->find()) {
			$this->error('手机号码已存在！');
		}

		$rs = M('User')->where(array('id' => userid()))->save(array('mobile' => $mobile, 'mobiletime' => time()));

		if ($rs) {
			$this->success('手机认证成功！');
		}
		else {
			$this->error('手机认证失败！');
		}
	}

	public function email()
	{
		if (!userid()) {
			redirect('/#login');
		}

		D('User')->check_update();

		$user = M('User')->where(array('id' => userid()))->find();
		if(!empty($user['email'])){
			$user['email'] = substr_replace($user['email'], '***', 1, 3);
		}
		$this->assign('user', $user);
		$this->display();
	}

	public function upemail($email = NULL, $email_verify)
	{

		// 过滤非法字符----------------S

		if (checkstr($email) || checkstr($email_verify)) {
			$this->error(L('info_error') );
		}

		// 过滤非法字符----------------E

		if (!userid()) {
			$this->error(L('login_first'));
		}

		if (!check($email, 'email')) {
			$this->error(L('g_yx_gs_error'));
		}

		$user = M('User')->where(array('email' => $email))->find();

		if(!empty($user)){
			$this->error(L('g_yx_has_cunzai'));
		}

		if (!check($email_verify, 'd')) {
			$this->error(L('g_yx_verfygs_error') );
		}

		if ($email_verify != session('emailbd_verify')) {
			$this->error( L('g_yx_verfy_error'));
		}

		$rs = M('User')->where(array('id' => userid()))->save(array('email' => $email));

		if ($rs) {
			$this->success(L('g_yx_bind_succ') );
		}
		else {
			$this->error(L('g_yx_bind_fail'));
		}
	}

	public function tpwdset()
	{
		if (!userid()) {
			redirect('/#login');
		}

		$user = M('User')->where(array('id' => userid()))->find();

		$this->assign('user', $user);
		$this->display();
	}

	public function tpwdsetting()
	{
		if (userid()) {
			$tpwdsetting = M('User')->where(array('id' => userid()))->getField('tpwdsetting');
			exit($tpwdsetting);
		}
	}

	public function uptpwdsetting($paypassword, $tpwdsetting)
	{

		// 过滤非法字符----------------S

		if (checkstr($paypassword) || checkstr($tpwdsetting)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E



		if (!userid()) {
			$this->error('请先登录！');
		}

		if (!check($paypassword, 'password')) {
			$this->error('密码格式为6~16位，不含特殊符号！');
		}

		if (($tpwdsetting != 1) && ($tpwdsetting != 2) && ($tpwdsetting != 3)) {
			$this->error('选项错误！' . $tpwdsetting);
		}

		$user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');



		if (md5($paypassword) != $user_paypassword) {
			$this->error('交易密码错误！');
		}

		$rs = M('User')->where(array('id' => userid()))->save(array('tpwdsetting' => $tpwdsetting));

		if ($rs) {
			$this->success('成功！');
		}
		else {
			$this->error('失败！');
		}
	}

	public function bank()
	{
		if (!userid()) {
			redirect('/#login');
		}

		$UserBankType = M('UserBankType')->where(array('status' => 1))->order('id desc')->select();
		$this->assign('UserBankType', $UserBankType);
		$user = M('User')->where(array('id' => userid()))->find();
		$this->assign('user', $user);
		$UserBank = M('UserBank')->where(array('userid' => userid(), 'status' => 1))->order('id desc')->select();
		$this->assign('UserBank', $UserBank);
		$this->display();
	}

	public function upbank($name, $bank, $bankprov, $bankcity, $bankaddr, $bankcard, $paypassword)
	{

		// 过滤非法字符----------------S

		if (checkstr($name) || checkstr($bank) || checkstr($bankprov) || checkstr($bankcity) || checkstr($bankaddr) || checkstr($bankcard) || checkstr($paypassword)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			redirect('/#login');
		}

		if (!check($name, 'a')) {
			$this->error('备注名称格式错误！');
		}

		if (!check($bank, 'a')) {
			$this->error('开户银行格式错误！');
		}

		if (!check($bankprov, 'c')) {
			$this->error('开户省市格式错误！');
		}

		if (!check($bankcity, 'c')) {
			$this->error('开户省市格式错误2！');
		}

		if (!check($bankaddr, 'a')) {
			$this->error('开户行地址格式错误！');
		}

		if (!check($bankcard, 'd')) {
			$this->error('银行账号格式错误！');
		}

		if (!preg_match('/^\d{13,}$/',$bankcard)) {
			$this->error('银行账号不低于13位数字！');
		}

		if (!check($paypassword, 'password')) {
			$this->error('密码格式为6~16位，不含特殊符号！');
		}

		$user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

		if (md5($paypassword) != $user_paypassword) {
			$this->error('交易密码错误！');
		}

		if (!M('UserBankType')->where(array('title' => $bank))->find()) {
			$this->error('开户银行错误！');
		}

		$userBank = M('UserBank')->where(array('userid' => userid()))->select();

		foreach ($userBank as $k => $v) {
			if ($v['name'] == $name) {
				$this->error('请不要使用相同的备注名称！');
			}

			if ($v['bankcard'] == $bankcard) {
				$this->error('银行卡号已存在！');
			}
		}

		if (10 <= count($userBank)) {
			$this->error('每个用户最多只能添加10个地址！');
		}

		if (M('UserBank')->add(array('userid' => userid(), 'name' => $name, 'bank' => $bank, 'bankprov' => $bankprov, 'bankcity' => $bankcity, 'bankaddr' => $bankaddr, 'bankcard' => $bankcard, 'addtime' => time(), 'status' => 1))) {
			$this->success('银行添加成功！');
		}
		else {
			$this->error('银行添加失败！');
		}
	}

	public function delbank($id, $paypassword)
	{

		// 过滤非法字符----------------S

		if (checkstr($id) || checkstr($paypassword)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			redirect('/#login');
		}

		if (!check($paypassword, 'password')) {
			$this->error('密码格式为6~16位，不含特殊符号！');
		}

		if (!check($id, 'd')) {
			$this->error('参数错误！');
		}

		$user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

		if (md5($paypassword) != $user_paypassword) {
			$this->error('交易密码错误！');
		}

		if (!M('UserBank')->where(array('userid' => userid(), 'id' => $id))->find()) {
			$this->error('非法访问！');
		}
		else if (M('UserBank')->where(array('userid' => userid(), 'id' => $id))->delete()) {
			$this->success('删除成功！');
		}
		else {
			$this->error('删除失败！');
		}
	}

	public function qianbao($coin = NULL)
	{

		// 过滤非法字符----------------S

		if (checkstr($coin)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			redirect('/#login');
		}

		$Coin = M('Coin')->where(array('status' => 1))->select();

		if (!$coin) {
			$coin = $Coin[0]['name'];
		}

		$this->assign('xnb', $coin);

		foreach ($Coin as $k => $v) {
			$coin_list[$v['name']] = $v;
		}

		$this->assign('coin_list', $coin_list);
		$userQianbaoList = M('UserQianbao')->where(array('userid' => userid(), 'status' => 1, 'coinname' => $coin))->order('id desc')->select();
		$this->assign('userQianbaoList', $userQianbaoList);

		$user = M('User')->where(array('id' => userid()))->find();
		$this->assign('user', $user);

		$this->display();
	}

	public function upqianbao($coin, $name, $addr, $paypassword)
	{


		// 过滤非法字符----------------S

		if (checkstr($coin) || checkstr($name)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			redirect('/#login');
		}

		if (!check($name, 'a')) {
			$this->error('备注名称格式错误！');
		}

		if (!check($addr, 'dw')) {
			$this->error('钱包地址格式错误！');
		}

		if (!check($paypassword, 'password')) {
			$this->error('密码格式为6~16位，不含特殊符号！');
		}

		$user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

		if (md5($paypassword) != $user_paypassword) {
			$this->error('交易密码错误！');
		}

		if (!M('Coin')->where(array('name' => $coin))->find()) {
			$this->error('积分错误！');
		}

		$userQianbao = M('UserQianbao')->where(array('userid' => userid(), 'coinname' => $coin))->select();

		foreach ($userQianbao as $k => $v) {
			if ($v['name'] == $name) {
				$this->error('请不要使用相同的钱包标识！');
			}

			if ($v['addr'] == $addr) {
				$this->error('钱包地址已存在！');
			}
		}

		if (10 <= count($userQianbao)) {
			$this->error('每个人最多只能添加10个地址！');
		}

		if (M('UserQianbao')->add(array('userid' => userid(), 'name' => $name, 'addr' => $addr, 'coinname' => $coin, 'addtime' => time(), 'status' => 1))) {
			$this->success('添加成功！');
		}
		else {
			$this->error('添加失败！');
		}
	}

	public function delqianbao($id, $paypassword)
	{

		// 过滤非法字符----------------S

		if (checkstr($id) || checkstr($paypassword)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			redirect('/#login');
		}

		if (!check($paypassword, 'password')) {
			$this->error('密码格式为6~16位，不含特殊符号！');
		}

		if (!check($id, 'd')) {
			$this->error('参数错误！');
		}

		$user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

		if (md5($paypassword) != $user_paypassword) {
			$this->error('交易密码错误！');
		}

		if (!M('UserQianbao')->where(array('userid' => userid(), 'id' => $id))->find()) {
			$this->error('非法访问！');
		}
		else if (M('UserQianbao')->where(array('userid' => userid(), 'id' => $id))->delete()) {
			$this->success('删除成功！');
		}
		else {
			$this->error('删除失败！');
		}
	}

	public function goods()
	{
		if (!userid()) {
			redirect('/#login');
		}

		$userGoodsList = M('UserGoods')->where(array('userid' => userid(), 'status' => 1))->order('id desc')->select();

		foreach ($userGoodsList as $k => $v) {
			$userGoodsList[$k]['mobile'] = substr_replace($v['mobile'], '****', 3, 4);
			$userGoodsList[$k]['idcard'] = substr_replace($v['idcard'], '********', 6, 8);
		}

		$this->assign('userGoodsList', $userGoodsList);
		$this->assign('prompt_text', D('Text')->get_content('user_goods'));
		$this->display();
	}

	public function upgoods($name, $truename, $idcard, $mobile, $addr, $paypassword)
	{


		// 过滤非法字符----------------S

		if (checkstr($name) || checkstr($truename) || checkstr($idcard) || checkstr($mobile) || checkstr($paypassword)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!userid()) {
			redirect('/#login');
		}

		if (!check($name, 'a')) {
			$this->error('备注名称格式错误！');
		}

		if (!check($truename, 'truename')) {
			$this->error('联系姓名格式错误！');
		}

		if (!check($idcard, 'idcard')) {
			$this->error('身份证号格式错误！');
		}

		if (!check($mobile, 'mobile')) {
			$this->error('联系电话格式错误！');
		}

		if (!check($addr, 'a')) {
			$this->error('联系地址格式错误！');
		}

		$user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

		if (md5($paypassword) != $user_paypassword) {
			$this->error('交易密码错误！');
		}

		$userGoods = M('UserGoods')->where(array('userid' => userid()))->select();

		foreach ($userGoods as $k => $v) {
			if ($v['name'] == $name) {
				$this->error('请不要使用相同的地址标识！');
			}
		}

		if (10 <= count($userGoods)) {
			$this->error('每个人最多只能添加10个地址！');
		}

		if (M('UserGoods')->add(array('userid' => userid(), 'name' => $name, 'addr' => $addr, 'idcard' => $idcard, 'truename' => $truename, 'mobile' => $mobile, 'addtime' => time(), 'status' => 1))) {
			$this->success('添加成功！');
		}
		else {
			$this->error('添加失败！');
		}
	}

	public function delgoods($id, $paypassword)
	{


		// 过滤非法字符----------------S

		if (checkstr($id) || checkstr($paypassword)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			redirect('/#login');
		}

		if (!check($paypassword, 'password')) {
			$this->error('密码格式为6~16位，不含特殊符号！');
		}

		if (!check($id, 'd')) {
			$this->error('参数错误！');
		}

		$user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

		if (md5($paypassword) != $user_paypassword) {
			$this->error('交易密码错误！');
		}

		if (!M('UserGoods')->where(array('userid' => userid(), 'id' => $id))->find()) {
			$this->error('非法访问！');
		}
		else if (M('UserGoods')->where(array('userid' => userid(), 'id' => $id))->delete()) {
			$this->success('删除成功！');
		}
		else {
			$this->error('删除失败！');
		}
	}

	public function log()
	{
		if (!userid()) {
			redirect('/#login');
		}

		$where['status'] = array('egt', 0);
		$where['userid'] = userid();
		$Model = M('UserLog');
		$count = $Model->where($where)->count();
		$Page = new \Think\Page($count, 10);
		if(LANG_SET == 'en-us'){
			$Page->config = array('header'=>'total','prev'=>'Previous','next'=>'Next','first'=>'First','last'=>'Last','theme'=>' %totalRow% %header% %nowPage%/%totalPage% Page %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		}
		$show = $Page->show();
		$list = $Model->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);

		$this->display();
	}

	public function delcache(){
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}
		if (!userid()) {
			redirect('/#login');
		}
		$this_user=userid();
		if($this_user!=6271){
			$this->error('页面不存在！');
		}
		if(!empty($_POST)){
			$paypassword = $_POST['paypassword'];
			$googlepassword = $_POST['googlepassword'];
			if (checkstr($googlepassword)) {
				$this->error('您输入的信息有误！');
			}
			$user = M('User')->where('id = ' . $this_user)->find();
			if (empty($paypassword) || md5($paypassword) != $user['paypassword']) {
				$this->error('交易密码错误！');
			}
			if (!$user['ga']) {
				$this->error('还未设置谷歌验证码!');
			}
			if(empty($googlepassword)){
				$this->error('请输入谷歌验证码');
			}
			$arr = explode('|', $user['ga']);
			$secret = $arr[0];
			$ga = new \Common\Ext\GoogleAuthenticator();
			if ($ga->verifyCode($secret, $googlepassword, 1)) {
				session('delcach',1);
				$this->dodelcache();
				$this->success('验证通过！');
			}else{
				$this->error('谷歌验证码错误！');
			}
		}else{
			$is_verified=session('delcach');
			if(!empty($is_verified)){
				$this->assign('verified',1);
				$this->dodelcache();
			}
			$this->display();
		}
	}


	public function api(){

		if (!userid()) {
			redirect('/#login');
		}

		$info = M('apiKey')->where(array('user_id'=>userid()))->select();

		$this->assign('info', $info);


		$this->display();

	}

	private function dodelcache(){
		$this_user=userid();
		if($this_user!=6271){
			return false;
		}
		$dirs = array('./Runtime/');
		@(mkdir('Runtime', 511, true));

		foreach ($dirs as $value) {
			$this->rmdirr($value);
		}

		@(mkdir('Runtime', 511, true));
	}

	private function rmdirr($dirname){
		$this_user=userid();
		if($this_user!=6271){
			return false;
		}
		if (!file_exists($dirname)) {
			return false;
		}

		if (is_file($dirname) || is_link($dirname)) {
			return unlink($dirname);
		}

		$dir = dir($dirname);

		if ($dir) {
			while (false !== $entry = $dir->read()) {
				if (($entry == '.') || ($entry == '..')) {
					continue;
				}

				$this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
			}
		}

		$dir->close();
		return rmdir($dirname);
	}
}

?>