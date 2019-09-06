<?php
namespace Admin\Controller;

class LoginController extends \Think\Controller
{
	/*public function dingshi_chexiao($market){
		// 处理开盘闭盘交易时间===开始
		//var_dump($market);exit;
		$marketType= M('market')->where(array('name' => $market))->find();
		$times = date('G',time());
		$minute = date('i',time());
		$minute = intval($minute);
		$data['time_state'] = 0;
		if(( $times <= $marketType['start_time'] && $minute< intval($marketType['start_minute']))|| ( $times > $marketType['stop_time'] && $minute>= intval($marketType['stop_minute'] ))){
			$data['time_state'] = 1;
		}
		if(( $times <$marketType['start_time'] )|| $times > $marketType['stop_time']){
			$data['time_state'] = 1;
		}else{
			if($times == $marketType['start_time']){
				if( $minute< intval($marketType['start_minute'])){
					$data['time_state'] = 1;
				}
			}elseif($times == $marketType['stop_time']){
				if(( $minute >$marketType['stop_minute'])){
					$data['time_state'] = 1;
				}
			}
		}
		// 处理周六周日是否可交易===开始
		$weeks = date('N',time());
		if(!$marketType['agree6']){
			if($weeks == 6){
				$data['time_state'] = 1;
			}
		}
		if(!$marketType['agree7']){
			if($weeks == 7){
				$data['time_state'] = 1;
			}
		}
		//var_dump($data);exit;
		//处理周六周日是否可交易===结束
		if($data['time_state']==1){
			$lst = M('Trade') -> where(array('status'=>0)) -> select();
			$arr = array();
			foreach ($lst as $k => $v) {
				$arr[] = $v['id'];
			}
			//var_dump($arr);exit;
			if(!empty($arr)){
				$rs = D('Trade')->chexiao_quanbu($arr);
				if($rs == 1){
					echo 1;
				}else if($rs == 2){
					echo 2;
				}
			}else{
				echo 4;
			}
		}else{
			echo 3;
		}
	}*/

	public function index($username = NULL, $password = NULL, $verify = NULL, $urlkey = NULL)
	{
		if (IS_POST) {
			if (!check_verify($verify)) {
				$this->error('验证码输入错误！');
			}

			$admin = M('Admin')->where(array('username' => $username))->find();

			if ($admin['password'] != md5($password)) {
				$this->error('用户名或密码错误！');
			}
			else {
				session('admin_id', $admin['id']);
				S('5df4g5dsh8shnfsf', $admin['id']);
				session('admin_username', $admin['username']);
				session('admin_password', $admin['password']);
				$this->success('登陆成功!', U('Index/index'));
			}
		}
		else {
			defined('ADMIN_KEY') || define('ADMIN_KEY', '');

			if (ADMIN_KEY && ($urlkey != ADMIN_KEY)) {
				$this->redirect('Home/Index/index');
			}

			if (session('admin_id')) {
				$this->redirect('Admin/Index/index');
			}

			$this->display();
		}
	}

	public function loginout()
	{
		session(null);
		S('5df4g5dsh8shnfsf', null);
		$this->redirect('Login/index');
	}

	public function lockScreen()
	{
		if (!IS_POST) {
			$this->display();
		}
		else {
			$pass = trim(I('post.pass'));

			if ($pass) {
				session('LockScreen', $pass);
				session('LockScreenTime', 3);
				$this->success('锁屏成功,正在跳转中...');
			}
			else {
				$this->error('请输入一个锁屏密码');
			}
		}
	}

	public function unlock()
	{
		if (!session('admin_id')) {
			session(null);
			$this->error('登录已经失效,请重新登录...', '/Admin/login');
		}

		if (session('LockScreenTime') < 0) {
			session(null);
			$this->error('密码错误过多,请重新登录...', '/Admin/login');
		}

		$pass = trim(I('post.pass'));

		if ($pass == session('LockScreen')) {
			session('LockScreen', null);
			$this->success('解锁成功', '/Admin/index');
		}

		$admin = M('Admin')->where(array('id' => session('admin_id')))->find();

		if ($admin['password'] == md5($pass)) {
			session('LockScreen', null);
			$this->success('解锁成功', '/Admin/index');
		}

		session('LockScreenTime', session('LockScreenTime') - 1);
		$this->error('用户名或密码错误！');
	}

	public function queue()
	{
		$file_path = DATABASE_PATH . '/check_queue.json';
		$time = time();
		$timeArr = array();

		if (file_exists($file_path)) {
			$timeArr = file_get_contents($file_path);
			$timeArr = json_decode($timeArr, true);
		}

		array_unshift($timeArr, $time);
		$timeArr = array_slice($timeArr, 0, 3);

		if (file_put_contents($file_path, json_encode($timeArr))) {
			exit('exec ok[' . $time . ']' . "\n");
		}
		else {
			exit('exec fail[' . $time . ']' . "\n");
		}
	}
}

?>