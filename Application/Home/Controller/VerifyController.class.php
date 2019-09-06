<?php
namespace Home\Controller;

class VerifyController extends \Think\Controller
{
	protected function _initialize(){
		$allow_action=array("code","regssemail","mibaoemail","emailbd","paypwdemail","passemail","findpwdemail","findpaypwdemail","mytxemail","myzcemail");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error(L('illega'));
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
	
	public function regssemail($email, $verify){
		// 过滤非法字符----------------S

		if (checkstr($verify)) {
			$this->error(L('info_error'));
		}

		// 过滤非法字符----------------E


		if (!check_verify(strtoupper($verify),"1")) {
			$this->error(L('verify_err') );
		}
		
		if (!check($email, 'email')) {
			$this->error(L('g_yx_gsput_error') );
		}

		if (M('User')->where(array('email' => $email))->find()) {
			$this->error(L('g_gx_huan') );
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailregss_verify', $code);
		$content = "<p>".L('sVerify_zjdyh')."</p><p>".L('sVerify_nh')."</p><p>".L('sVerify_nzz')."ebtc.ren</p><p>".L('sVerify_zczhqsrcyzxx').$code.L('sVerify_cnjh').L('sVerify_gyzxxfczy')."</p><p>".L('sVerify_hyjyyytd')."</p><p>".L('sVerify_xtfyqwhx')."</p><p>".L('sVerify_hyjygfwz')."http://ebtc.ren</p>";
		$result = send_email($email, L('sVerify_hyjy'), L('sVerify_hyjyzcyz'), $content);
		if ($result) {
			$this->success(L('g_yzm_chashou') );
		}
		else {
			$this->error(L('g_yzm_fail') );
		}
	}
	
	public function mibaoemail()
	{
		if (!userid()) {
			$this->error(L('login_first'));
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error(L('g_pwd_no_bindyx'));
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailmibao_verify', $code);
		$content = "<p>".L('sVerify_zjdyh')."</p><p>".L('sVerify_nh')."</p><p>".L('sVerify_nzz')."ebtc.ren</p><p>".L('sVerify_xgmb').$code.L('sVerify_cnjh').L('sVerify_gyzxxfczy')."</p><p>".L('sVerify_hyjyyytd')."</p><p>".L('sVerify_xtfyqwhx')."</p><p>".L('sVerify_hyjygfwz')."http://ebtc.ren</p>";

		if (send_email($email, L('sVerify_hyjy'), L('sVerify_hyjyszmbwt'), $content)) {
			$this->success(L('g_yx_send_sucess'));
		}
		else {
			$this->error(L('g_yx_send_fail'));
		}
	}
	public function paypwdemail()
	{
		if (!userid()) {
			$this->error( L('login_first') );
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error(L('g_yx_not_bind') );
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailpaypwd_verify', $code);
		$content = "<p>".L('sVerify_zjdyh')."</p><p>".L('sVerify_nh')."</p><p>".L('sVerify_nzz')."ebtc.ren</p><p>".L('sVerify_xgjymm').$code.L('sVerify_cnjh').L('sVerify_gyzxxfczy')."</p><p>".L('sVerify_hyjyyytd')."</p><p>".L('sVerify_xtfyqwhx')."</p><p>".L('sVerify_hyjygfwz')."http://ebtc.ren</p>";

		if (send_email($email, L('sVerify_hyjy'), L('sVerify_hyjyxgjymm'), $content)) {
			$this->success(L('g_yx_send_sucess'));
		}
		else {
			$this->error(L('g_yx_send_fail'));
		}
	}

	public function passemail()
	{
		if (!userid()) {
			$this->error( L('login_first'));
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error( L('g_yx_not_bind'));
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailpass_verify', $code);
		$content = "<p>".L('sVerify_zjdyh')."</p><p>".L('sVerify_nh')."</p><p>".L('sVerify_nzz')."ebtc.ren</p><p>".L('sVerify_xgdlmm').$code.L('sVerify_cnjh').L('sVerify_gyzxxfczy')."</p><p>".L('sVerify_hyjyyytd')."</p><p>".L('sVerify_xtfyqwhx')."</p><p>".L('sVerify_hyjygfwz')."http://ebtc.ren</p>";

		if (send_email($email, L('sVerify_hyjy'), L('sVerify_hyjyxgjymm'), $content)) {
			$this->success(L('g_yx_send_sucess'));
		}
		else {
			$this->error(L('g_yx_send_fail'));
		}
	}

	public function mytxemail()
	{
		if (!userid()) {
			$this->error(L('login_first'));
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error(L('g_pwd_no_bindyx'));
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailmytx_verify', $code);
		$content = "<p>".L('sVerify_zjdyh')."</p><p>".L('sVerify_nh')."</p><p>".L('sVerify_nzz')."ebtc.ren</p><p>".L('sVerify_sqtx').$code.L('sVerify_cnjh').L('sVerify_gyzxxfczy')."</p><p>".L('sVerify_hyjyyytd')."</p><p>".L('sVerify_xtfyqwhx')."</p><p>".L('sVerify_hyjygfwz')."http://ebtc.ren</p>";

		if (send_email($email, L('sVerify_hyjy'), L('sVerify_hyjyxgjymm'), $content)) {
			$this->success(L('g_yx_send_sucess'));
		}
		else {
			$this->error(L('g_yx_send_fail'));
		}
	}

	public function myzcemail()
	{
		if (!userid()) {
			$this->error(L('login_first'));
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error(L('g_pwd_no_bindyx'));
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailmyzc_verify', $code);
		$content = "<p>".L('sVerify_zjdyh')."</p><p>".L('sVerify_nh')."</p><p>".L('sVerify_nzz')."ebtc.ren</p><p>".L('sVerify_sqzcxnb').$code.L('sVerify_cnjh').L('sVerify_gyzxxfczy')."</p><p>".L('sVerify_hyjyyytd')."</p><p>".L('sVerify_xtfyqwhx')."</p><p>".L('sVerify_hyjygfwz')."http://ebtc.ren</p>";

		if (send_email($email, L('sVerify_hyjy'), L('sVerify_hyjyxnbtx'), $content)) {
			$this->success(L('g_yx_send_sucess'));
		}
		else {
			$this->error(L('g_yx_send_fail'));
		}
	}
	
	public function emailbd($email)
	{

		// 过滤非法字符----------------S

		if (checkstr($email)) {
			$this->error(L('info_error'));
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			$this->error(L('login_first'));
		}
		
		if (!check($email, 'email')) {
			$this->error(L('g_yx_gs_error'));
		}

		if (M('User')->where(array('email' => $email))->find()) {
			$this->error(L('g_yx_has_cunzai'));
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailbd_verify', $code);
		$content = "<p>".L('sVerify_zjdyh')."</p><p>".L('sVerify_nh')."</p><p>".L('sVerify_nzz')."ebtc.ren</p><p>".L('sVerify_szmb').$code.L('sVerify_cnjh').L('sVerify_gyzxxfczy')."</p><p>".L('sVerify_hyjyyytd')."</p><p>".L('sVerify_xtfyqwhx')."</p><p>".L('sVerify_hyjygfwz')."http://ebtc.ren</p>";

		if (send_email($email, L('sVerify_hyjy'), L('sVerify_hyjybdyx'), $content)) {
			$this->success(L('g_yx_send_sucess'));
		}
		else {
			$this->error(L('g_yx_send_fail'));
		}
	}
	
	public function findpwdemail()
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
			$content = "<p>".L('sVerify_zjdyh')."</p><p>".L('sVerify_nh')."</p><p>".L('sVerify_nzz')."ebtc.ren</p><p>".L('sVerify_zhdlmm').$code.L('sVerify_cnjh').L('sVerify_gyzxxfczy')."</p><p>".L('sVerify_hyjyyytd')."</p><p>".L('sVerify_xtfyqwhx')."</p><p>".L('sVerify_hyjygfwz')."http://ebtc.ren</p>";
			if (send_email($user['email'], L('sVerify_hyjy'), L('sVerify_hyjyzhdlmm'), $content)) {
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
					$this->error(L('info_error'));
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
			$content = "<p>".L('sVerify_zjdyh')."</p><p>".L('sVerify_nh')."</p><p>".L('sVerify_nzz')."ebtc.ren</p><p>".L('sVerify_zhjymm').$code.L('sVerify_cnjh').L('sVerify_gyzxxfczy')."</p><p>".L('sVerify_hyjyyytd')."</p><p>".L('sVerify_xtfyqwhx')."</p><p>".L('sVerify_hyjygfwz')."http://ebtc.ren</p>";
			if (send_email($user['email'], L('sVerify_hyjy'), L('sVerify_hyjyzhjymm'), $content)) {
				$this->success(L('g_yx_send_sucess'));
			}
			else {
				$this->error(L('g_yx_send_fail'));
			}
		}
	}
}

?>