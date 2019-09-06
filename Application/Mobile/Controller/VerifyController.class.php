<?php
namespace Mobile\Controller;

class VerifyController extends \Think\Controller
{
	protected function _initialize(){
		$allow_action=array("code","regss","regssemail","mytx","paypass","pass","mibao","mibaoemail","mobilebd","emailbd","findpwd","findpaypwd","myzc","paypwdemail","passemail","findpwdemail","findpaypwdemail","mytxemail","myzcemail");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("非法操作！");
		}
	}
	
	public function __construct() {
		parent::__construct();
	}

	public function code()
	{
		$config['useNoise'] = false;
		$config['useCurve'] = false;
		$config['length'] = 4;
		$config['codeSet'] = 'abcdfghkrstuxyz23456789';
		ob_clean();
		$verify = new \Think\Verify($config);
		$verify->entry(1);
	}

	public function regss($mobile, $verify)
	{
		// 过滤非法字符----------------S

		if (checkstr($verify)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!check_verify(strtoupper($verify),"1")) {
			$this->error('图形验证码错误!');
		}
		
		if (!check($mobile, 'mobile')) {
			$this->error('手机号码格式错误！');
		}

		if (M('User')->where(array('mobile' => $mobile))->find()) {
			$this->error('手机号码已存在！');
		}

		session('chkmobile',$mobile);
		$code = smssend($mobile);
		
		if ($code>0) {
			session('mobileregss_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}
	
	public function regssemail($email, $verify){
		// 过滤非法字符----------------S

		if (checkstr($verify)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!check_verify(strtoupper($verify),"1")) {
			$this->error(L('verify_err'));
		}
		
		if (!check($email, 'email')) {
			$this->error(L('g_yx_gsput_error'));
		}

		if (M('User')->where(array('email' => $email))->find()) {
			$this->error(L('g_gx_huan'));
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailregss_verify', $code);
		$content = "<p>尊敬的用户：</p><p>您好！</p><p>您正在coinonline.club</p><p>注册帐号，请输入此验证信息进行验证：".$code."。该验证信息非常重要，请勿将此邮件泄露给任何人。</p><p>币付在线运营团队</p><p>系统发言，请勿回信。</p><p>币付在线官方网址：http://coinonline.club</p>";
		$result = send_email($email, '币付在线', '币付在线注册验证', $content);
		if ($result) {
			$this->success(L('g_yzm_chashou'));
		}
		else {
			$this->error(L('g_yzm_fail'));
		}
	}

	public function mytx()
	{
		if (!userid()) {
			$this->error('请先登录');
		}

		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');

		if (!$mobile) {
			$this->error('你的手机没有认证');
		}

		session('chkmobile',$mobile);
		$code = smssend($mobile);
		if ($code>0) {
			session('mytx_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}

	public function paypass()
	{
		if (!userid()) {
			$this->error('请先登录');
		}

		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');

		if (!$mobile) {
			$this->error('你的手机没有认证');
		}

		session('chkmobile',$mobile);
		$code = smssend($mobile);

		if ($code>0) {
			session('paypass_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}

	public function pass()
	{
		if (!userid()) {
			$this->error('请先登录');
		}

		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');

		if (!$mobile) {
			$this->error('你的手机没有认证');
		}

		session('chkmobile',$mobile);
		$code = smssend($mobile);

		if ($code>0) {
			session('pass_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}

	public function mibao()
	{
		if (!userid()) {
			$this->error('请先登录');
		}

		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');

		if (!$mobile) {
			$this->error('您没有绑定手机');
		}

		session('chkmobile',$mobile);
		$code=smssend($mobile);

		if ($code>0) {
			session('mibao_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}
	
	public function mibaoemail()
	{
		if (!userid()) {
			$this->error(L('sCommon_qxdl'));
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error('您没有绑定邮箱');
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailmibao_verify', $code);
		$content = "<p>尊敬的用户：</p><p>您好！</p><p>您正在coinonline.club</p><p>修改密保，请输入此验证信息进行验证：".$code."。该验证信息非常重要，请勿将此邮件泄露给任何人。</p><p>币付在线运营团队</p><p>系统发言，请勿回信。</p><p>币付在线官方网址：http://coinonline.club</p>";

		if (send_email($email, '币付在线', '币付在线设置密保问题', $content)) {
			$this->success(L('g_yx_send_sucess'));
		}
		else {
			$this->error(L('g_yx_send_fail'));
		}
	}
	public function paypwdemail()
	{
		if (!userid()) {
			$this->error(L('sCommon_qxdl'));
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error('您没有绑定邮箱');
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailpaypwd_verify', $code);
		$content = "<p>尊敬的用户：</p><p>您好！</p><p>您正在coinonline.club</p><p>修改交易密码，请输入此验证信息进行验证：".$code."。该验证信息非常重要，请勿将此邮件泄露给任何人。</p><p>币付在线运营团队</p><p>系统发言，请勿回信。</p><p>币付在线官方网址：http://coinonline.club</p>";

		if (send_email($email, '币付在线', '币付在线修改交易密码', $content)) {
			$this->success(L('g_yx_send_sucess'));
		}
		else {
			$this->error(L('g_yx_send_fail'));
		}
	}

	public function passemail()
	{
		if (!userid()) {
			$this->error(L('sCommon_qxdl'));
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error('您没有绑定邮箱');
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailpass_verify', $code);
		$content = "<p>尊敬的用户：</p><p>您好！</p><p>您正在coinonline.club</p><p>修改登陆密码，请输入此验证信息进行验证：".$code."。该验证信息非常重要，请勿将此邮件泄露给任何人。</p><p>币付在线运营团队</p><p>系统发言，请勿回信。</p><p>币付在线官方网址：http://coinonline.club</p>";

		if (send_email($email, '币付在线', '币付在线修改登录密码', $content)) {
			$this->success(L('g_yx_send_sucess'));
		}
		else {
			$this->error(L('g_yx_send_fail'));
		}
	}

	public function mytxemail()
	{
		if (!userid()) {
			$this->error(L('sCommon_qxdl'));
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error('您没有绑定邮箱');
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailmytx_verify', $code);
		$content = "<p>尊敬的用户：</p><p>您好！</p><p>您正在coinonline.club</p><p>申请提现，请输入此验证信息进行验证：".$code."。该验证信息非常重要，请勿将此邮件泄露给任何人。</p><p>币付在线运营团队</p><p>系统发言，请勿回信。</p><p>币付在线官方网址：http://coinonline.club</p>";

		if (send_email($email, '币付在线', '币付在线人民币提现', $content)) {
			$this->success(L('g_yx_send_sucess'));
		}
		else {
			$this->error(L('g_yx_send_fail'));
		}
	}

	public function myzcemail()
	{
		if (!userid()) {
			$this->error(L('sCommon_qxdl'));
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error('您没有绑定邮箱');
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailmyzc_verify', $code);
		$content = "<p>尊敬的用户：</p><p>您好！</p><p>您正在coinonline.club</p><p>申请转出虚拟币，请输入此验证信息进行验证：".$code."。该验证信息非常重要，请勿将此邮件泄露给任何人。</p><p>币付在线运营团队</p><p>系统发言，请勿回信。</p><p>币付在线官方网址：http://coinonline.club</p>";

		if (send_email($email, '币付在线', '币付在线虚拟币提现', $content)) {
			$this->success(L('g_yx_send_sucess'));
		}
		else {
			$this->error(L('g_yx_send_fail'));
		}
	}

	public function mobilebd($mobile)
	{

		// 过滤非法字符----------------S

		if (checkstr($mobile)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			$this->error('请先登录');
		}
		
		if (!check($mobile, 'mobile')) {
			$this->error('手机号码格式错误！');
		}

		if (M('User')->where(array('mobile' => $mobile))->find()) {
			$this->error('手机号码已存在！');
		}

		session('chkmobile',$mobile);
		$code = smssend($mobile);

		if ($code>0) {
			session('mobilebd_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}
	
	public function emailbd($email)
	{

		// 过滤非法字符----------------S

		if (checkstr($email)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			$this->error('请先登录');
		}
		
		if (!check($email, 'email')) {
			$this->error('邮箱格式错误！');
		}

		if (M('User')->where(array('email' => $email))->find()) {
			$this->error('邮箱已存在！');
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailbd_verify', $code);
		$content = "<p>尊敬的用户：</p><p>您好！</p><p>您正在coinonline.club</p><p>设置密保，请输入此验证信息进行验证：".$code."。该验证信息非常重要，请勿将此邮件泄露给任何人。</p><p>币付在线运营团队</p><p>系统发言，请勿回信。</p><p>币付在线官方网址：http://coinonline.club</p>";

		if (send_email($email, '币付在线', '币付在线绑定邮箱', $content)) {
			$this->success('邮箱验证码已发送，请查收');
		}
		else {
			$this->error('邮箱验证码发送失败，请重新点击发送');
		}
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

			if (!check_verify(strtoupper($input['verify']),"1")) {
				$this->error('图形验证码错误!');
			}

			if (!check($input['mobile'], 'mobile')) {
				$this->error('手机号码格式错误！');
			}

			$user = M('User')->where(array('mobile' => $input['mobile']))->find();

			if (!$user) {
				$this->error('用户不存在！');
			}

			if ($user['mobile'] != $input['mobile']) {
				$this->error('手机号码错误！');
			}

			session('chkmobile',$user['mobile']);
			$code = smssend($user['mobile']);

			if ($code>0) {
				session('findpwd_verify', $code);
				$this->success('短信验证码已发送到你的手机，请查收');
			}
			else {
				$this->error('短信验证码发送失败，请重新点击发送');
			}
		}
	}
	
	public function findpwdemail()
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

			if (!check_verify(strtoupper($input['verify']),"1")) {
				$this->error(L('verify_err'));
			}

			if (!check($input['email'], 'email')) {
				$this->error(L('g_yx_gsput_error'));
			}

			$user = M('User')->where(array('email' => $input['email']))->find();

			if (!$user) {
				$this->error(L('user_no'));
			}

			if ($user['email'] != $input['email']) {
				$this->error(L('sVerify_dzyxcw'));
			}

			$code = rand(111111, 999999);
			session('chkemail',$user['email']);
			session('emailfindpwd_verify', $code);
			$content = "<p>尊敬的用户：</p><p>您好！</p><p>您正在coinonline.club</p><p>找回登陆密码，请输入此验证信息进行验证：".$code."。该验证信息非常重要，请勿将此邮件泄露给任何人。</p><p>币付在线运营团队</p><p>系统发言，请勿回信。</p><p>币付在线官方网址：http://coinonline.club</p>";
			if (send_email($user['email'], '币付在线', '币付在线找回登录密码', $content)) {
				$this->success(L('g_yx_send_sucess'));
			}
			else {
				$this->error(L('g_yx_send_fail'));
			}
		}
	}
	
	public function findpaypwdemail(){
		if (IS_POST) {
			$input = I('post.');

			foreach ($input as $k => $v) {
				// 过滤非法字符----------------S

				if (checkstr($v)) {
					$this->error('您输入的信息有误！');
				}

				// 过滤非法字符----------------E
			}

			if (!check_verify(strtoupper($input['verify']),"1")) {
				$this->error(L('verify_err'));
			}

			if (!check($input['email'], 'email')) {
				$this->error(L('g_yx_gsput_error'));
			}

			$user = M('User')->where(array('email' => $input['email']))->find();

			if (!$user) {
				$this->error(L('user_no'));
			}

			if ($user['email'] != $input['email']) {
				$this->error(L('sVerify_dzyxcw'));
			}

			$code = rand(111111, 999999);
			session('chkemail',$user['email']);
			session('emailfindpaypwd_verify', $code);
			$content = "<p>尊敬的用户：</p><p>您好！</p><p>您正在coinonline.club</p><p>找回交易密码，请输入此验证信息进行验证：".$code."。该验证信息非常重要，请勿将此邮件泄露给任何人。</p><p>币付在线运营团队</p><p>系统发言，请勿回信。</p><p>币付在线官方网址：http://coinonline.club</p>";
			if (send_email($user['email'], '币付在线', '币付在线找回交易密码', $content)) {
				$this->success(L('g_yx_send_sucess'));
			}
			else {
				$this->error(L('g_yx_send_fail'));
			}
		}
	}

	public function findpaypwd()
	{
		$input = I('post.');

		foreach ($input as $k => $v) {
			// 过滤非法字符----------------S

			if (checkstr($v)) {
				$this->error('您输入的信息有误！');
			}

			// 过滤非法字符----------------E
		}

		if (!check_verify(strtoupper($input['verify']),"1")) {
				$this->error('图形验证码错误!');
		}

		if (!check($input['mobile'], 'mobile')) {
			$this->error('手机号码格式错误！');
		}

		$user = M('User')->where(array('id' => userid()))->find();

		if (!$user) {
			$this->error('用户名不存在！');
		}

		if ($user['mobile'] != $input['mobile']) {
			$this->error('手机号码错误！');
		}

		session('chkmobile',$user['mobile']);
		$code = smssend($user['mobile']);

		if ($code>0) {
			session('findpaypwd_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}

	public function myzc()
	{
		if (!userid()) {
			$this->error('您没有登录请先登录!');
		}

		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');

		if (!$mobile) {
			$this->error('你的手机没有认证');
		}

		session('chkmobile',$mobile);
		$code = smssend($mobile);

		if ($code>0) {
			session('myzc_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}
}

?>