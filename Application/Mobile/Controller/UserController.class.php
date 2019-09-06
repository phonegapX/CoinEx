<?php
namespace Mobile\Controller;

class UserController extends MobileController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","login","nameauth","password","mibao","upmibao","uppassword","paypassword","uppaypassword","ga","mobile","upmobile","tpwdset","tpwdsetting","uptpwdsetting","bankadd","bank","upbank","delbank","qianbao","qianbaoadd","qianbao_coin_list","upqianbao","delqianbao","log","safety","upauth","upauth2","email","upemail","feedback","subfeedback","feedbacklist","addreply","feedbackdetail");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("非法操作！");
		}
	}
	
	public function __construct() {
		parent::__construct();
		$display_action=array("index","login","nameauth","password","mibao","paypassword","ga","mobile","alipay","bank","qianbao","log","delcache","feedback","feedbacklist","feedbackdetail");
		if(in_array(ACTION_NAME,$display_action)){
			$this->common_content();
		}
	}
	
	public function upemail($email = NULL, $email_verify)
	{

		// 过滤非法字符----------------S

		if (checkstr($email) || checkstr($email_verify)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!userid()) {
			$this->error('您没有登录请先登录！');
		}

		if (!check($email, 'email')) {
			$this->error('邮箱格式错误！');
		}

		$user = M('User')->where(array('email' => $email))->find();

		if(!empty($user)){
			$this->error('邮箱已存在！');
		}

		if (!check($email_verify, 'd')) {
			$this->error('邮箱验证码格式错误！');
		}

		if ($email_verify != session('emailbd_verify')) {
			$this->error('邮箱验证码错误！');
		}

		$rs = M('User')->where(array('id' => userid()))->save(array('email' => $email));

		if ($rs) {
			$this->success('邮箱绑定成功！');
		}
		else {
			$this->error('邮箱绑定失败！');
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

	/*
	****币种列表页
	*/
	public function qianbao_coin_list(){

		// 获取币种列表信息------S

		$map = array();
		$map['status'] = 1;

		$coin_list = M('Coin')->where($map)->select();

		$this->assign('coin_list', $coin_list);

		// 获取币种列表信息------E


		$this->display();
	}


	public function qianbaoadd($coin = NULL)
	{
		if (!userid()) {
			redirect("/Login/index");
		}

		$Coin = M('Coin')->where(array(
			'status' => 1,
			'name'   => array('neq', 'becc')
			))->select();

		if (!$coin) {
			$coin = $Coin[0]['name'];
		}

		$this->assign('xnb', $coin);

		foreach ($Coin as $k => $v) {
			$coin_list[$v['name']] = $v;
		}
		$this->assign('coin', $coin);
		$this->assign('coin_list', $coin_list);
		$userQianbaoList = M('UserQianbao')->where(array('userid' => userid(), 'status' => 1, 'coinname' => $coin))->order('id desc')->select();
		$this->assign('userQianbaoList', $userQianbaoList);
		$this->display();
	}

	public function index()
	{
		if (!userid()) {
			redirect('/Login/index.html');
		}

		// 处理总财产--------------S

		$CoinList = M('Coin')->where(array('status' => 1))->select();
		$UserCoin = M('UserCoin')->where(array('userid' => userid()))->find();
		$Market = M('Market')->where(array('status' => 1))->select();

		foreach ($Market as $k => $v) {
			$Market[$v['name']] = $v;
		}

		$becc['zj'] = 0;

		foreach ($CoinList as $k => $v) {
			if ($v['name'] == 'becc') {
				$becc['ky'] = round($UserCoin[$v['name']], 2) * 1;
				$becc['dj'] = round($UserCoin[$v['name'] . 'd'], 2) * 1;
				$becc['zj'] = $becc['zj'] + $becc['ky'] + $becc['dj'];
			}
			else {
				if ($Market[$v['name'] . '_becc']['new_price']) {
					$jia = $Market[$v['name'] . '_becc']['new_price'];
				}
				else {
					$jia = 1;
				}

				$coinList[$v['name']] = array('name' => $v['name'], 'img' => $v['img'], 'title' => $v['title'] . '(' . strtoupper($v['name']) . ')', 'xnb' => round($UserCoin[$v['name']], 6) * 1, 'xnbd' => round($UserCoin[$v['name'] . 'd'], 6) * 1, 'xnbz' => round($UserCoin[$v['name']] + $UserCoin[$v['name'] . 'd'], 6), 'jia' => $jia * 1, 'zhehe' => round(($UserCoin[$v['name']] + $UserCoin[$v['name'] . 'd']) * $jia, 2));
				$becc['zj'] = round($becc['zj'] + (($UserCoin[$v['name']] + $UserCoin[$v['name'] . 'd']) * $jia), 2) * 1;
			}
		}

		$this->assign('becc', $becc);

		// 处理总财产--------------E

		$user = M('User')->where(array('id' => userid()))->find();
		$this->assign('user', $user);
		$this->display();
	}
	public function safety()
	{
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
			redirect("/Login/index.html");
		}

		$arr = array('hz'=>L('Login_huzhao'),'mgsfz'=>L('g_mgsfz'),'sfz'=>L('g_sfz'),'qz'=>L('g_qz'),'qt'=>L('g_qt'));
		$idcardtype = array(
			array('hz',L('Login_huzhao')),
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

	public function password()
	{
		if (!userid()) {
			redirect("/Login/index.html");
		}
		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');
		$email = M('User')->where(array('id' => userid()))->getField('email');
		if(empty($mobile) && empty($email)){
			$this->error("请先绑定手机或邮箱中的一种！");
		}
		elseif ($mobile) {
			$mobile = substr_replace($mobile, '****', 3, 4);
		}
		$user = M('User')->where(array('id' => userid()))->find();
		$this->assign('user', $user);
		$this->assign('mobile', $mobile);

		$this->display();
	}

	public function mibao()
	{
		if (!userid()) {
			redirect("/Login/index.html");
		}

		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');
		$email = M('User')->where(array('id' => userid()))->getField('email');
		if(empty($mobile) && empty($email)){
			$this->error("请先绑定手机或邮箱中的一种！");
		}

		$user = M('User')->where(array('id' => userid()))->find();
		$this->assign('user', $user);
		$this->display();
	}

	public function upmibao($chkstyle, $mobile_verify='', $email_verify, $mibao_question, $mibao_answer, $new_mibao_question, $new_mibao_answer, $findpwd_mibao, $findpaypwd_mibao)
	{

		// 过滤非法字符----------------S

		if (checkstr($mobile_verify) || checkstr($email_verify) || checkstr($mibao_question) || checkstr($mibao_answer) || checkstr($new_mibao_question) || checkstr($new_mibao_answer)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!userid()) {
			$this->error('请先登录！');
		}

		if(empty($chkstyle) || (!empty($chkstyle) && $chkstyle!='mobile' && $chkstyle!='email')){
			$this->error('请选择验证方式！');
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

		if ($chkstyle == 'mobile' && !check($mobile_verify, 'd')) {
			$this->error('短信验证码格式错误！');
		}
		if ($chkstyle == 'email' && !check($email_verify, 'd')) {
			$this->error(L('g_yx_verfygs_error'));
		}

		$user = M('User')->where(array('id' => userid()))->find();

		if ($chkstyle == 'mobile' && $user['mobile'] != session('chkmobile')) {
			$this->error('短信验证码错误！');
		}

		if ($chkstyle == 'mobile' && $mobile_verify != session('mibao_verify')) {
			$this->error('短信验证码错误！');
		}

		if ($chkstyle == 'email' && $user['email'] != session('chkemail')) {
			$this->error(L('g_yx_verfy_error'));
		}

		if ($chkstyle == 'email' && $email_verify != session('emailmibao_verify')) {
			$this->error(L('g_yx_verfy_error'));
		}

		if (($user['mibao_question']!=''||$user['mibao_question']!=NULL)&&$user['mibao_question'] != $mibao_question) {

			$this->error(L('code_que_err'));

		}

		if (($user['mibao_answer']!=''||$user['mibao_answer']!=NULL)&&$user['mibao_answer'] != $mibao_answer) {

			$this->error(L('code_ans_err'));

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
			session('chkmobile', null);
			session('emailmibao_verify', null);
			session('chkemail', null);
			$this->success(L('modi_suc'));
		}
		else {
			$this->error(L('modi_fail'));
		}
	}

	public function uppassword($mobile_verify='', $oldpassword, $newpassword, $repassword, $chkstyle, $email_verify)
	{

		// 过滤非法字符----------------S

		if (checkstr($oldpassword) || checkstr($newpassword) || checkstr($repassword) || checkstr($mobile_verify) || checkstr($chkstyle) || checkstr($email_verify)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			$this->error('请先登录！');
		}

		$user_info = M('user')->where(array('id'=>userid()))->find();
		if($chkstyle=='mobile'){
			if (!check($mobile_verify, 'd')) {
				$this->error('短信验证码格式错误！');
			}
			if ($user_info['mobile'] != session('chkmobile')) {
				$this->error('短信验证码错误！');
			}

			if ($mobile_verify != session('pass_verify')) {
				$this->error('短信验证码错误！');
			}
		}elseif($chkstyle=='email'){
			if (!check($email_verify, 'd')) {
				$this->error(L('g_yx_verfygs_error'));
			}
			if ($user_info['email'] != session('chkemail')) {
				$this->error(L('g_yx_verfy_error'));
			}

			if ($email_verify != session('emailpass_verify')) {
				$this->error(L('g_yx_verfy_error'));
			}
		}

		if (!check($oldpassword, 'password')) {
			$this->error(L('sCommon_mmgs'));
		}

		if (strlen($newpassword) > 16 || strlen($newpassword) < 6) {

			$this->error(L('sCommon_mmgs'));

		}

		if (!check($newpassword, 'password')) {
			$this->error(L('sCommon_mmgs'));
		}

		if ($newpassword != $repassword) {
			$this->error(L('two_code_diff'));
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
			session('chkmobile', null);
			$this->success(L('modi_suc'));
		}
		else {
			$this->error(L('modi_fail'));
		}
	}

	public function paypassword()
	{
		if (!userid()) {
			redirect("/Login/index.html");
		}

		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');
		$email = M('User')->where(array('id' => userid()))->getField('email');
		if(empty($mobile) && empty($email)){
			$this->error("请先绑定手机或邮箱中的一种！");
		}
		$paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');
		if ($mobile) {
			$mobile = substr_replace($mobile, '****', 3, 4);
		}
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

	public function uppaypassword($mobile_verify='', $oldpaypassword, $newpaypassword, $repaypassword, $email_verify, $chkstyle)
	{

		// 过滤非法字符----------------S

		if (checkstr($mobile_verify) || checkstr($oldpaypassword) || checkstr($newpaypassword) || checkstr($repaypassword) || checkstr($email_verify) || checkstr($chkstyle)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!userid()) {
			$this->error('请先登录！');
		}
		if(empty($chkstyle) || (!empty($chkstyle) && $chkstyle!='mobile' && $chkstyle!='email')){
			$this->error('请选择验证方式！');
		}
		$user_info = M('user')->where(array('id'=>userid()))->find();
		$orgempty = !empty($user_info['paypassword']) ? false : true;
		if($chkstyle=='mobile'){
			if ($user_info['mobile'] != session('chkmobile')) {
				$this->error('短信验证码错误！');
			}

			if (!check($mobile_verify, 'd')) {
				$this->error('短信验证码格式错误！');
			}

			if ($mobile_verify != session('paypass_verify')) {
				$this->error('短信验证码错误！');
			}
		}elseif($chkstyle=='email'){
			if ($user_info['email'] != session('chkemail')) {
				$this->error(L('g_yx_verfy_error'));
			}

			if (!check($email_verify, 'd')) {
				$this->error(L('sCommon_yxyzmgscw'));
			}

			if ($email_verify != session('emailpaypwd_verify')) {
				$this->error(L('g_yx_verfy_error'));
			}
		}

		if (($user_info['paypassword']!=''||$user_info['paypassword']!=NULL)&&(!check($oldpaypassword, 'password'))) {
			$this->error(L('sCommon_mmgs'));
		}

		if (strlen($newpaypassword) > 16 || strlen($newpaypassword) < 6) {

			$this->error(L('sCommon_mmgs'));

		}

		if (!check($newpaypassword, 'password')) {
			$this->error(L('sCommon_mmgs'));
		}

		if ($newpaypassword != $repaypassword) {
			$this->error(L('two_code_diff'));
		}

		$user = M('User')->where(array('id' => userid()))->find();

		if (($user_info['paypassword']!=''||$user_info['paypassword']!=NULL)&&(md5($oldpaypassword) != $user['paypassword'])) {
			$this->error(L('old_deal_code_err'));
		}

		if (($user_info['paypassword']!=''||$user_info['paypassword']!=NULL)&&(md5($newpaypassword) == $user['paypassword'])) {
			$this->error(L('new_old_deal_same_fail'));
		}

		if (md5($newpaypassword) == $user['password']) {
			$this->error(L('code_cant_same'));
		}

		$rs = M('User')->where(array('id' => userid()))->save(array('paypassword' => md5($newpaypassword)));

		if ($rs) {
			session('chkmobile', null);
			session('paypass_verify', null);
			if($orgempty){
				$this->success(L('g_pwd_set_success'));
			}else{
				$this->success(L('modi_suc'));
			}
		}
		else {
			$this->error(L('modi_fail'));
		}
	}

	public function ga()
	{
		if (empty($_POST)) {
			if (!userid()) {
				redirect("/Login/index.html");
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
					$this->error(L('refresh'));
				}
			}
			else if (($type == 'updat') || ($type == 'delet')) {
				$user = M('User')->where('id = ' . userid())->find();

				if (!$user['ga']) {
					$this->error(L('not_set_guge_code'));
				}

				$arr = explode('|', $user['ga']);
				$secret = $arr[0];
				$delete = ($type == 'delet' ? 1 : 0);
			}
			else {
				$this->error(L('opera_not_define'));
			}

			$ga = new \Common\Ext\GoogleAuthenticator();

			if ($ga->verifyCode($secret, $gacode, 1)) {
				$ga_val = ($delete == '' ? $secret . '|' . $ga_login . '|' . $ga_transfer : '');
				M('User')->save(array('id' => userid(), 'ga' => $ga_val));
				$this->success(L('opera_suc'));
			}
			else {
				$this->error(L('verify_fail'));
			}
		}
	}

	public function mobile()
	{
		if (!userid()) {
			redirect("/Login/index.html");
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
		if (!userid()) {
			$this->error('您没有登录请先登录！');
		}

		// 过滤非法字符----------------S

		if (checkstr($mobile)||checkstr($mobile_verify)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

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

	public function alipay()
	{
		if (!userid()) {
			redirect("/Login/index.html");
		}

		D('User')->check_update();

		$user = M('User')->where(array('id' => userid()))->find();
		$this->assign('user', $user);
		$this->display();
	}

	public function upalipay($alipay = NULL, $paypassword = NULL)
	{
		if (!userid()) {
			$this->error('您没有登录请先登录！');
		}

		// 过滤非法字符----------------S

		if (checkstr($alipay)||checkstr($paypassword)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!check($alipay, 'mobile')) {
			if (!check($alipay, 'email')) {
				$this->error('支付宝账号格式错误！');
			}
		}

		if (!check($paypassword, 'password')) {
			$this->error('密码格式为6~16位，不含特殊符号！');
		}

		$user = M('User')->where(array('id' => userid()))->find();

		if (md5($paypassword) != $user['paypassword']) {
			$this->error('交易密码错误！');
		}

		$rs = M('User')->where(array('id' => userid()))->save(array('alipay' => $alipay));

		if ($rs) {
			$this->success('支付宝认证成功！');
		}
		else {
			$this->error('支付宝认证失败！');
		}
	}

	public function tpwdset()
	{
		if (!userid()) {
			redirect("/Login/index.html");
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
		if (!userid()) {
			$this->error('请先登录！');
		}

		// 过滤非法字符----------------S

		if (checkstr($paypassword)||checkstr($tpwdsetting)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

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
			redirect("/Login/index.html");
		}

		$UserBankType = M('UserBankType')->where(array('status' => 1))->order('id desc')->select();
		$this->assign('UserBankType', $UserBankType);
		$truename = M('User')->where(array('id' => userid()))->getField('truename');
		$this->assign('truename', $truename);
		$UserBank = M('UserBank')->where(array('userid' => userid(), 'status' => 1))->order('id desc')->select();
		$this->assign('UserBank', $UserBank);
		$this->display();
	}

	public function bankadd()
	{
		if (!userid()) {
			redirect('/Login/index');
		}

		$UserBankType = M('UserBankType')->where(array('status' => 1))->order('id desc')->select();
		$this->assign('UserBankType', $UserBankType);
		$truename = M('User')->where(array('id' => userid()))->getField('truename');
		$this->assign('truename', $truename);
		$UserBank = M('UserBank')->where(array('userid' => userid(), 'status' => 1))->order('id desc')->select();
		$this->assign('UserBank', $UserBank);
		$this->display();
	}

	public function upbank($name, $bank, $bankprov, $bankcity, $bankaddr, $bankcard, $paypassword)
	{
		if (!userid()) {
			redirect("/Login/index.html");
		}

		// 过滤非法字符----------------S

		if (checkstr($name)||checkstr($bank)||checkstr($bankprov)||checkstr($bankaddr)||checkstr($bankcard)||checkstr($paypassword)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

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
			$this->error('银行卡号不低于13位数字！');
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
		if (!userid()) {
			redirect("/Login/index.html");
		}

		// 过滤非法字符----------------S

		if (checkstr($paypassword)||checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

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
		if (!userid()) {
			redirect("/Login/index.html");
		}

		$Coin = M('Coin')->where(array(
			'status' => 1,
			'name'   => array('neq', 'becc')
			))->select();

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
		$this->display();
	}

	public function upqianbao($coin, $name, $addr, $paypassword)
	{
		if (!userid()) {
			redirect("/Login/index.html");
		}

		// 过滤非法字符----------------S

		if (checkstr($name)||checkstr($addr)||checkstr($paypassword)||checkstr($coin)) {
			$this->error(L('sCommon_nsrdxxyw'));
		}

		// 过滤非法字符----------------E

		if (!check($name, 'a')) {
			$this->error(L('beizhu_format_err'));
		}

		if (!check($addr, 'dw')) {
			$this->error(L('sFinance_myzc_qbdzgscw'));
		}

		if (!check($paypassword, 'password')) {
			$this->error(L('sCommon_mmgs'));
		}

		$user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

		if (md5($paypassword) != $user_paypassword) {
			$this->error(L('deal_code_err'));
		}

		if (!M('Coin')->where(array('name' => $coin))->find()) {
			$this->error(L('coin_err'));
		}

		$userQianbao = M('UserQianbao')->where(array('userid' => userid(), 'coinname' => $coin))->select();

		foreach ($userQianbao as $k => $v) {
			if ($v['name'] == $name) {
				$this->error(L('g_notusesamebiaoshi'));
			}

			if ($v['addr'] == $addr) {
				$this->error(L('wallet_add_have'));
			}
		}

		if (10 <= count($userQianbao)) {
			$this->error(L('g_addtenaddress'));
		}

		if (M('UserQianbao')->add(array('userid' => userid(), 'name' => $name, 'addr' => $addr, 'coinname' => $coin, 'addtime' => time(), 'status' => 1))) {
			$this->success(L('add_suc'));
		}
		else {
			$this->error(L('add_fail'));
		}
	}

	public function delqianbao($id, $paypassword)
	{
		if (!userid()) {
			redirect("/Login/index.html");
		}

		// 过滤非法字符----------------S

		if (checkstr($id)||checkstr($paypassword)) {
			$this->error(L('sCommon_nsrdxxyw'));
		}

		// 过滤非法字符----------------E

		if (!check($paypassword, 'password')) {
			$this->error(L('sCommon_mmgs'));
		}

		if (!check($id, 'd')) {
			$this->error(L('para_error'));
		}

		$user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

		if (md5($paypassword) != $user_paypassword) {
			$this->error(L('deal_code_err'));
		}

		if (!M('UserQianbao')->where(array('userid' => userid(), 'id' => $id))->find()) {
			$this->error(L('illega_visit'));
		}
		else if (M('UserQianbao')->where(array('userid' => userid(), 'id' => $id))->delete()) {
			$this->success(L('cancel_suc'));
		}
		else {
			$this->error(L('cancel_fail'));
		}
	}

	public function log()
	{
		if (!userid()) {
			redirect("/Login/index.html");
		}
		
		if(!empty($_GET['starttime']) && empty($_GET['endtime'])){
			$where['addtime'] = array('gt',strtotime($_GET['starttime']));
			$this->assign('starttime',$_GET['starttime']);
		}
		if(empty($_GET['starttime']) && !empty($_GET['endtime'])){
			$where['addtime'] = array('lt',strtotime($_GET['endtime']));
			$this->assign('endtime',$_GET['endtime']);
		}
		if(!empty($_GET['starttime']) && !empty($_GET['endtime'])){
			$stime = strtotime($_GET['starttime']);
			$etime = strtotime($_GET['endtime']);
			$where['addtime'] = array('between',"$stime,$etime");
			$this->assign('starttime',$_GET['starttime']);
			$this->assign('endtime',$_GET['endtime']);
		}

		$where['status'] = array('egt', 0);
		$where['userid'] = userid();
		$Model = M('UserLog');
		$count = $Model->where($where)->count();
		$Page = new \Think\Page1($count, 10);
		if(LANG_SET == 'en-us'){
			$Page->config = array('header'=>'Total','prev'=>'Previous page','next'=>'Next Page','first'=>'First','last'=>'Last','theme'=>' %totalRow% %header% %nowPage%/%totalPage% Page</br> %upPage% %downPage% %first%  %end%');
		}
		$show = $Page->show();
		$list = $Model->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
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
		if(!in_array($update['zhengjian'],array('sfz','gsfz','hz','qt'))){
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
	public function email()
	{
		if (!userid()) {
			redirect('/#login');
		}

		$user = M('User')->where(array('id' => userid()))->find();
		if(!empty($user['email'])){
			$user['email'] = substr_replace($user['email'], '***', 1, 3);
		}
		$this->assign('user', $user);
		$this->display();
	}
}

?>