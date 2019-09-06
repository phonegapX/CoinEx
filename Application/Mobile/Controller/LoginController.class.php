<?php

namespace Mobile\Controller;

class LoginController extends MobileController

{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","register","upregister","register4","jiancega","chkUser","chkmobile","submit","loginout","findpwd","findpaypwd","getBrowser","chkemail","chkusemibao","chkusepaymibao");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("非法操作！");
		}
	}
	
	public function chkusemibao($mobile = '', $email = ''){
		if(empty($mobile) && empty($email)){
			$this->error('参数错误！');
		}
		if(!empty($mobile)){
			$user = M('User')->where(array('mobile'=>$mobile))->find();
			if(empty($user)){
				$this->error('此手机号没有注册！');
			}else{
				echo json_encode(array('usemibao'=>$user['findpwd_mibao'],'status'=>1));
				exit;
			}
		}
		if(!empty($email)){
			$user = M('User')->where(array('email'=>$email))->find();
			if(empty($user)){
				$this->error(L('Login_yxwzc'));
			}else{
				echo json_encode(array('usemibao'=>$user['findpwd_mibao'],'status'=>1));
				exit;
			}
		}
	}

	public function chkusepaymibao($mobile = '', $email = ''){
		if(empty($mobile) && empty($email)){
			$this->error('参数错误！');
		}
		if(!empty($mobile)){
			$user = M('User')->where(array('mobile'=>$mobile))->find();
			if(empty($user)){
				$this->error('此手机号没有注册！');
			}else{
				echo json_encode(array('usemibao'=>$user['findpaypwd_mibao'],'status'=>1));
				exit;
			}
		}
		if(!empty($email)){
			$user = M('User')->where(array('email'=>$email))->find();
			if(empty($user)){
				$this->error(L('Login_yxwzc'));
			}else{
				echo json_encode(array('usemibao'=>$user['findpaypwd_mibao'],'status'=>1));
				exit;
			}
		}
	}
	
	public function __construct() {
		parent::__construct();
		$display_action=array("index","register","register4","findpwd","findpaypwd");
		if(in_array(ACTION_NAME,$display_action)){
			$this->common_content();
		}
	}
	
	public function chkmobile($mobile)

	{

		// 过滤非法字符----------------S

		if (!check($mobile,'mobile')) {
			$this->error('您输入的手机号码格式错误！');
		}

		// 过滤非法字符----------------E
		
		if (M('User')->where(array('username' => $mobile))->find()) {

			$this->error('用户已存在，请登录','/Login/index.html');

		}else{

			if (M('User')->where(array('mobile' => $mobile))->find()) {

				$this->error('手机号已存在');

			}
		}

		$this->success('');

	}
	
	public function chkemail($email){
		// 过滤非法字符----------------S

		if (!check($email,'email')) {
			$this->error(L('g_yx_gsput_error'));
		}

		// 过滤非法字符----------------E
		
		if (M('User')->where(array('username' => $email))->find()) {

			$this->error(L('g_exist_login'),'/Login/index.html');

		}else{

			if (M('User')->where(array('email' => $email))->find()) {

				$this->error(L('g_gx_huan'));

			}
		}

		$this->success('');
	}

	// 注册协议
	public function webreg()
	{

		$data = M('ArticleType')->where(array('name' => 'agreement'))->find();
		
		$this->assign('data',$data);

		$this->display();
	}

	public function register()
	{
		if(!empty($_SESSION['reguserId'])){
			$user=M('User')->where(array('id' => $_SESSION['reguserId']))->find();
			if (!empty($user)) {
				header("Location:/Login/register4");
			}
		}
		
		$this->display();
	}

	public function upregister($reg_type, $mobile='', $email, $password, $repassword, $verify, $mobilecode='', $emailcode, $invit){
		// 过滤非法字符----------------S

		if (checkstr($password) || checkstr($repassword) || checkstr($verify) || checkstr($mobilecode) || checkstr($reg_type) || checkstr($emailcode)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E
		
		if(empty($reg_type) || ($reg_type != 'phone' && $reg_type != 'email_a')){
			$this->error('参数错误!');
		}
		$mobile = trim($mobile);
		$email = trim($email);
		if($reg_type == 'phone'){
			$reg_type = 'mobile';
		}else{
			$reg_type = 'email';
		}
		if (!check_verify(strtoupper($verify),'1')) {

			$this->error(L('verify_err'));

		}
		
		if ($reg_type == 'mobile' && !check($mobile, 'mobile')) {
			$this->error('手机格式错误！');
		}
		
		if ($reg_type == 'email' && !check($email, 'email')) {
			$this->error(L('g_yx_gsput_error'));
		}
		if (strlen($password) > 16 || strlen($password) < 6) {

			$this->error(L('sCommon_mmgs'));

		}

		if (!check($password, 'password')) {
			$this->error(L('sCommon_mmgs'));
		}
		
		if ($reg_type == 'mobile' && $mobile != session('chkmobile')) {
			$this->error('短信验证码错误！');
		}

		if ($reg_type == 'mobile' && !check($mobilecode, 'd')) {
			$this->error('短信验证码格式错误！');
		}
		
		if ($reg_type == 'mobile' && $mobilecode != session('mobileregss_verify')) {
			$this->error('短信验证码错误！');
		}

		if ($reg_type == 'email' && $email != session('chkemail')) {
			$this->error(L('g_yx_verfy_error'));
		}

		if ($reg_type == 'email' && !check($emailcode, 'd')) {
			$this->error(L('sCommon_yxyzmgscw'));
		}
		
		if ($reg_type == 'email' && $emailcode != session('emailregss_verify')) {
			$this->error(L('g_yx_verfy_error'));
		}

		if ($password != $repassword) {

			$this->error(L('two_code_diff'));

		}
		if($reg_type == 'mobile'){
			$registed_user = M('User')->where(array('username' => $mobile))->find();
		}elseif($reg_type == 'email'){
			$registed_user = M('User')->where(array('username' => $email))->find();
		}
		if (!empty($registed_user)) {

			$this->error(L('user_have_reg'),'/Login/index.html');

		}

		if (!$invit) {

			$invit = session('invit');

		}

		$invituser = M('User')->where(array('invit' => $invit))->find();

		if (!$invituser) {

			$invituser = M('User')->where(array('id' => $invit))->find();

		}

		if (!$invituser) {

			$invituser = M('User')->where(array('username' => $invit))->find();

		}

		if (!$invituser) {

			$invituser = M('User')->where(array('mobile' => $invit))->find();

		}

		if ($invituser) {

			$invit_1 = $invituser['id'];

			$invit_2 = $invituser['invit_1'];

			$invit_3 = $invituser['invit_2'];

		}

		else {

			$invit_1 = 0;

			$invit_2 = 0;

			$invit_3 = 0;

		}

		for (; true; ) {

			$tradeno = tradenoa();

			if (!M('User')->where(array('invit' => $tradeno))->find()) {

				break;

			}

		}	

		$mo = M();

		$mo->startTrans();

		//$mo->execute('lock tables tw_user write , tw_user_coin write ');

		$rs = array();

		if($reg_type == 'mobile'){
			$rs[] = $mo->table('tw_user')->add(array('username' => $mobile, 'mobile'=>$mobile, 'mobiletime'=>time(), 'password' => md5($password), 'invit' => $tradeno, 'tpwdsetting' => 1, 'invit_1' => $invit_1, 'invit_2' => $invit_2, 'invit_3' => $invit_3, 'addip' => get_client_ip(), 'addr' => get_city_ip(), 'addtime' => time(), 'status' => 1));
		}elseif($reg_type == 'email'){
			$rs[] = $mo->table('tw_user')->add(array('username' => $email, 'email'=>$email, 'mobiletime'=>time(), 'password' => md5($password), 'invit' => $tradeno, 'tpwdsetting' => 1, 'invit_1' => $invit_1, 'invit_2' => $invit_2, 'invit_3' => $invit_3, 'addip' => get_client_ip(), 'addr' => get_city_ip(), 'addtime' => time(), 'status' => 1));
		}
		$rs[] = $mo->table('tw_user_coin')->add(array('userid' => $rs[0]));
		
		if (check_arr($rs)) {

			$mo->commit();

			session('reguserId', $rs[0]);
			
			session('mobileregss_verify', null);
			
			session('chkmobile', null);
			
			session('chkemail', null);
			
			session('emailregss_verify', null);
			
			$this->success(L('reg_suc'));

		}

		else {

			$mo->rollback();

			$this->error(L('reg_fail'));

		}

	}

	public function register4()

	{
		$time = time();
		
		$user = M('User')->where(array('id' => session('reguserId')))->find();
		if(empty($user['mobile'])){
			$user['mobile'] = "未绑定";
		}
		if(empty($user['email'])){
			$user['email'] = "未绑定";
		}

		session('userId', $user['id']);

		session('userName', $user['username']);
		
		session('loginTime', $time);
		
		session('saveTime', $time);
		
		$member_session_id = M()->table('tw_user_log')->add(array('userid' => $user['id'], 'type' => 'login', 'remark' => "注册完成后自动登陆", 'addtime' => $time, 'endtime'=>$time, 'addip' => get_client_ip(), 'addr' => get_city_ip(), 'status' => 1, 'session_key'=>session_id(),'state'=>1));
		
		if(!empty($member_session_id)){
			session('sessionId',$member_session_id);
		}

		$this->assign('user', $user);

		$this->display();

	}



	public function chkUser($username)

	{

		// 过滤非法字符----------------S

		if (checkstr($username)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!check($username, 'username')) {

			$this->error(L('user_format_err'));

		}



		if (M('User')->where(array('username' => $username))->find()) {

			$this->error(L('user_have'));

		}



		$this->success('');

	}

	public function submit($username, $password, $verify = NULL, $ga='')

	{

		$time=time();
	
		// 过滤非法字符----------------S

		if (checkstr($username) || checkstr($password) || checkstr($verify) || checkstr($ga)) {
			$this->error(L('info_error'));
		}

		// 过滤非法字符----------------E

		if (C('login_verify')) {

			if (!check_verify(strtoupper($verify),'1')) {

				$this->error(L('verify_err'));

			}

		}


		if (empty($user)) {

			$user = M('User')->where(array('username' => $username))->find();
			
			$browser=$this->getBrowser();
			$remark = '手机端'.$browser['os']."，".$browser['browser'].'登录';
		}


		if (empty($user)) {

			$this->error(L('user_no'));

		}



		if (strlen($password) > 16 || strlen($password) < 6) {

			$this->error(L('code_format'));

		}

		if (!check($password, 'password')) {
			$this->error(L('code_format'));
		}


		if (md5($password) != $user['password']){

			$this->error(L('login_code_err'));

		}


		// 处理谷歌身份验证器-------------------S

		if($user['ga']){
			$ga_n = new \Common\Ext\GoogleAuthenticator();
			$arr = explode('|', $user['ga']);
			// 存储的信息为谷歌密钥
			$secret = $arr[0];
			// 存储的登录状态为1需要验证，0不需要验证
			$ga_is_login = $arr[1];
			// 判断是否需要验证
			if($ga_is_login){
				if(!$ga){
					$this->error(L('input_guge_code'));
				}
				if(!check($ga,'d')){
					$this->error(L('guge_code_format_err'));
				}
				// 判断登录有无验证码
				$aa = $ga_n->verifyCode($secret, $ga, 1);
				if (!$aa){
					$this->error(L('guge_code_err'));
				}
			}
		}

		// 处理谷歌身份验证器-------------------E

		

		if (isset($user['status'])&&$user['status'] != 1) {

			$this->error(L('user_frozen_admin'));

		}

		if(chkchuanhao(session_id(),$user['id'])){
			$this->error(L('clear_cache'));
		}

		$mo = M();

		$mo->startTrans();

		//$mo->execute('lock tables tw_user write , tw_user_log write ');

		$rs = array();

		$rs[] = $mo->table('tw_user')->where(array('id' => $user['id']))->setInc('logins', 1);

		$rs[] = $member_session_id = $mo->table('tw_user_log')->add(array('userid' => $user['id'], 'type' => 'login', 'remark' => $remark, 'addtime' => $time, 'endtime'=>$time, 'addip' => get_client_ip(), 'addr' => get_city_ip(), 'status' => 1, 'session_key'=>session_id(),'state'=>1));

		if (check_arr($rs)) {

			$mo->commit();

			session('userId', $user['id']);

			session('userName', $user['username']);

			session('loginTime', $time);
			
			session('saveTime', $time);

			if (!$user['paypassword']) {

				session('regpaypassword', $rs[0]);

				session('reguserId', $user['id']);

			}

			if (!$user['truename']) {

				session('regtruename', $rs[0]);

				session('reguserId', $user['id']);

			}
			
			if(!empty($member_session_id)){
				session('sessionId',$member_session_id);
			}

			$this->success(L('login_suc'));

		}

		else {

			$mo->rollback();

			$this->error(L('login_fail'));

		}

	}



	public function loginout()

	{
		M('user_log')->where(array('userid'=>session('userId'),'type' => 'login','state'=>1))->save(array('state'=>0,'endtime'=>time()));

		session(null);

		redirect('/');

	}

	public function findpwd()

	{

		if (IS_POST) {

			$input = I('post.');

			foreach ($input as $k => $v) {
				// 过滤非法字符----------------S

				if (checkstr($v)) {
					$this->error('您输入的信息有误！');
				}

				// 过滤非法字符----------------E
			}


			if (!check_verify(strtoupper($input['verify']),'1')) {

				$this->error(L('verify_err'));

			}
			
			$fpw_type = $input['fpw_type'];
			if($fpw_type != "phone" && $fpw_type != "emailaddr"){
				$this->error('参数错误！');
			}


			if ($fpw_type == 'phone' && !check($input['mobile'], 'mobile')) {

				$this->error('手机号码格式错误！');

			}

			if ($fpw_type == 'phone' && $input['mobile'] != session('chkmobile')) {

				$this->error('短信验证码错误！');

			}

			if ($fpw_type == 'phone' && !check($input['mobile_verify'], 'd')) {

				$this->error('短信验证码格式错误！');

			}

			if ($fpw_type == 'phone' && $input['mobile_verify'] != session('findpwd_verify')) {

				$this->error('短信验证码错误！');

			}

			if ($fpw_type == 'emailaddr' && !check($input['email'], 'email')) {

				$this->error(L('g_yx_gsput_error'));

			}

			if ($fpw_type == 'emailaddr' && $input['email'] != session('chkemail')) {

				$this->error(L('sCommon_yxyzmcw'));

			}

			if ($fpw_type == 'emailaddr' && !check($input['emailcode'], 'd')) {

				$this->error(L('sCommon_yxyzmgscw'));

			}

			if ($fpw_type == 'emailaddr' && $input['emailcode'] != session('emailfindpwd_verify')) {

				$this->error(L('g_yx_verfy_error'));

			}

			if($fpw_type == 'emailaddr'){
				$user = M('User')->where(array('email' => $input['email']))->find();
			}elseif($fpw_type == 'phone'){
				$user = M('User')->where(array('mobile' => $input['mobile']))->find();
			}
			
			if (!$user) {

				$this->error(L('user_no'));

			}
			
			$isusemibao = $input['isusemibao'];
			if(!empty($isusemibao) && $isusemibao != 1){
				$this->error('参数错误！');
			}

			if (!empty($isusemibao) && $user['mibao_question'] != $input['mibao_question']) {

				$this->error(L('code_que_err'));

			}

			if (!empty($isusemibao) && $user['mibao_answer'] != $input['mibao_answer']) {

				$this->error(L('code_ans_err'));

			}

			if (strlen($input['password']) > 16 || strlen($input['password']) < 6) {

				$this->error(L('Finance_mmjc'));

			}

			if (!check($input['password'], 'password')) {

				$this->error(L('Finance_mmjc'));

			}

			if ($input['password'] != $input['repassword']) {

				$this->error(L('User_lcmmbyz'));

			}


			if($user['paypassword'] == md5($input['password'])){

				$this->error(L('login_deal_cant_same'));
				
			}

			if($user['password'] == md5($input['password'])){

				$this->error(L('new_old_same'));
				
			}


			$rs = M('User')->where(array('id' => $user['id']))->save(array('password' => md5($input['password'])));
			
			if ($rs) {
				
				session('findpwd_verify', null);
				
				session('chkmobile', null);
				
				session('emailfindpwd_verify', null);
				
				session('chkemail', null);

				$this->success(L('modi_suc'));

			}

			else {

				$this->error(L('modi_fail'));

			}

		}

		else {

			$this->display();

		}

	}

	public function findpaypwd()

	{

		if (IS_POST) {

			$input = I('post.');


			foreach ($input as $k => $v) {
				// 过滤非法字符----------------S

				if (checkstr($v)) {
					$this->error(L('info_error'));
				}

				// 过滤非法字符----------------E
			}
			
			if (!check_verify(strtoupper($input['verify']),'1')) {

				$this->error(L('verify_err'));

			}
			
			$fpw_type = $input['fpw_type'];
			if($fpw_type != "phone" && $fpw_type != "emailaddr"){
				$this->error(L('para_error'));
			}

			if ($fpw_type == 'phone' && !check($input['mobile'], 'mobile')) {

				$this->error('手机号码格式错误！');

			}
			
			if ($fpw_type == 'phone' && $input['mobile'] != session('chkmobile')) {

				$this->error('短信验证码错误！');

			}

			if ($fpw_type == 'phone' && !check($input['mobile_verify'], 'd')) {

				$this->error('短信验证码格式错误！');

			}

			if ($fpw_type == 'phone' && $input['mobile_verify'] != session('findpaypwd_verify')) {

				$this->error('短信验证码错误！');

			}
			
			if ($fpw_type == 'emailaddr' && !check($input['email'], 'email')) {

				$this->error(L('g_yx_gsput_error'));

			}

			if ($fpw_type == 'emailaddr' && $input['email'] != session('chkemail')) {

				$this->error(L('sCommon_yxyzmcw'));

			}

			if ($fpw_type == 'emailaddr' && !check($input['emailcode'], 'd')) {

				$this->error(L('sCommon_yxyzmgscw'));

			}

			if ($fpw_type == 'emailaddr' && $input['emailcode'] != session('emailfindpaypwd_verify')) {

				$this->error(L('sCommon_yxyzmcw'));

			}

			$user = M('User')->where(array('id' => userid()))->find();

			if (!$user) {

				$this->error(L('user_no'));

			}

			if ($fpw_type == 'phone' && $user['mobile'] != $input['mobile']) {

				$this->error('用户名或手机号码错误！');

			}
			
			if ($fpw_type == 'emailaddr' && $user['email'] != $input['email']) {

				$this->error(L('Login_yhyxcw'));

			}
			
			$isusemibao = $input['isusemibao'];
			if(!empty($isusemibao) && $isusemibao != 1){
				$this->error(L('para_error'));
			}

			if (!empty($isusemibao) && $user['mibao_question'] != $input['mibao_question']) {

				$this->error(L('code_que_err'));

			}

			if (!empty($isusemibao) && $user['mibao_answer'] != $input['mibao_answer']) {

				$this->error(L('code_ans_err'));

			}


			if (strlen($input['password']) > 16 || strlen($input['password']) < 6) {

				$this->error(L('Finance_mmjc'));

			}



			if (!check($input['password'], 'password')) {

				$this->error(L('Finance_mmjc'));

			}

			// if (!check($input['password'], 'password')) {

			// 	$this->error('新交易密码格式错误！');

			// }
			if(empty($user['paypassword'])){

				$this->error(L('Login_wszjymm'));
				
			}


			if ($input['password'] != $input['repassword']) {

				$this->error(L('two_code_diff'));

			}

			if($user['password'] == md5($input['password'])){

				$this->error(L('code_cant_same'));
				
			}

			if($user['paypassword'] == md5($input['password'])){

				$this->error(L('Login_jymmyz'));
				
			}
			$paypassword=$input['password'];
			$mo = M();
			// $rs = M('User')->where(array('id' => $user['id']))->save(array('paypassword' => md5($paypassword)));
			$rs = $mo-> table('tw_user')->where(array('id' => $user['id']))->save(array('paypassword' => md5($paypassword)));
			// var_dump($mo->getLastSql());
			if ($rs) {
				
				session('findpaypwd_verify', null);
				
				session('chkmobile', null);
				
				session('emailfindpaypwd_verify', null);
				
				session('chkemail', null);

				$this->success(L('modi_suc'));

			}

			else {

				$this->error(L('modi_fail'));

			}

		}

		else {

			$this->display();

		}

	}
	
	function getBrowser(){
		$flag=$_SERVER['HTTP_USER_AGENT'];
		$para=array();
		
		// 检查操作系统
		if(preg_match('/Windows[\d\. \w]*/',$flag, $match)) $para['os']=$match[0];
		
		if(preg_match('/Chrome\/[\d\.\w]*/',$flag, $match)){
			// 检查Chrome
			$para['browser']=$match[0];
		}elseif(preg_match('/Safari\/[\d\.\w]*/',$flag, $match)){
			// 检查Safari
			$para['browser']=$match[0];
		}elseif(preg_match('/MSIE [\d\.\w]*/',$flag, $match)){
			// IE
			$para['browser']=$match[0];
		}elseif(preg_match('/Opera\/[\d\.\w]*/',$flag, $match)){
			// opera
			$para['browser']=$match[0];
		}elseif(preg_match('/Firefox\/[\d\.\w]*/',$flag, $match)){
			// Firefox
			$para['browser']=$match[0];
		}elseif(preg_match('/OmniWeb\/(v*)([^\s|;]+)/i',$flag, $match)){
			//OmniWeb
			$para['browser']=$match[2];
		}elseif(preg_match('/Netscape([\d]*)\/([^\s]+)/i',$flag, $match)){
			//Netscape
			$para['browser']=$match[2];
		}elseif(preg_match('/Lynx\/([^\s]+)/i',$flag, $match)){
			//Lynx
			$para['browser']=$match[1];
		}elseif(preg_match('/360SE/i',$flag, $match)){
			//360SE
			$para['browser']='360安全浏览器';
		}elseif(preg_match('/SE 2.x/i',$flag, $match)) {
			//搜狗
			$para['browser']='搜狗浏览器';
		}else{
			$para['browser']='unkown';
		}
		return $para;
	}
	public function jiancega($username){
        if(checkstr($username)){
            $this->error(0);
        }
        $user = M('User')->where(array('username' => $username))->find();
        if(!$user){
            $this->error(0);
        }
        if(empty($user['ga'])){
            $this->error(0);
        }else{
             $this->success(1);
        }
    }
}
?>