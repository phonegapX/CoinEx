<?php
namespace Admin\Controller;

class FinanceController extends AdminController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","myzr","myzc","myzcQueren","myzcBatch","myzcBatchLog");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("页面不存在！");
		}
	}
	
	public function index($field = NULL, $name = NULL)
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

		$count = M('Mytx')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Mytx')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$name_list = array('mycz' => '人民币充值', 'mytx' => '人民币提现', 'trade' => '委托交易', 'tradelog' => '成功交易', 'issue' => '用户认购');
		$nameid_list = array('mycz' => U('Mycz/index'), 'mytx' => U('Mytx/index'), 'trade' => U('Trade/index'), 'tradelog' => U('Tradelog/index'), 'issue' => U('Issue/index'));

		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
			$list[$k]['num_a'] = Num($v['num_a']);
			$list[$k]['num_b'] = Num($v['num_b']);
			$list[$k]['num'] = Num($v['num']);
			$list[$k]['fee'] = Num($v['fee']);
			$list[$k]['type'] = ($v['fee'] == 1 ? '收入' : '支出');
			$list[$k]['name'] = ($name_list[$v['name']] ? $name_list[$v['name']] : $v['name']);
			$list[$k]['nameid'] = ($name_list[$v['name']] ? $nameid_list[$v['name']] . '?id=' . $v['nameid'] : '');
			$list[$k]['mum_a'] = Num($v['mum_a']);
			$list[$k]['mum_b'] = Num($v['mum_b']);
			$list[$k]['mum'] = Num($v['mum']);
			$list[$k]['addtime'] = addtime($v['addtime']);
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function myzr($field = NULL, $name = NULL, $coinname = NULL, $time_type = 'addtime', $starttime = NULL, $endtime = NULL, $num_start = NULL, $num_stop = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			elseif($field == 'zcaddr'){
				$where['username'] = $name;
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($coinname) {
			$where['coinname'] = $coinname;
		}

		// 转入数量--条件
		if(is_numeric($num_start) && !is_numeric($num_stop)){

			$where['num'] = array('EGT',$num_start);

		}else if(!is_numeric($num_start) && is_numeric($num_stop)){

			$where['num'] = array('ELT',$num_stop);

		}else if(is_numeric($num_start) && is_numeric($num_stop)){

			$where['num'] = array(array('EGT',$num_start),array('ELT',$num_stop));
			
		}


		// 时间--条件

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where[$time_type] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where[$time_type] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where[$time_type] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}else{

			// 无时间查询，显示申请时间类型十天以内数据
			$now_time = time() - 10*24*60*60;
			$where['addtime'] =  array('EGT',$now_time);
		}




		$count = M('Myzr')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Myzr')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['usernamea'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function myzc($field = NULL, $name = NULL, $coinname = NULL, $time_type = 'addtime', $starttime = NULL, $endtime = NULL, $num_start = NULL, $num_stop = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}elseif($field == 'zcaddr'){
				$where['username'] = $name;
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($coinname) {
			$where['coinname'] = $coinname;
		}

		// 转入数量--条件
		if(is_numeric($num_start) && !is_numeric($num_stop)){

			$where['num'] = array('EGT',$num_start);

		}else if(!is_numeric($num_start) && is_numeric($num_stop)){

			$where['num'] = array('ELT',$num_stop);

		}else if(is_numeric($num_start) && is_numeric($num_stop)){

			$where['num'] = array(array('EGT',$num_start),array('ELT',$num_stop));
			
		}


		// 时间--条件

		if($time_type == 'endtimes'){
			$time_type = 'endtime';
		}

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where[$time_type] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where[$time_type] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where[$time_type] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}else{

			// 无时间查询，显示申请时间类型十天以内数据
			$now_time = time() - 1000*24*60*60;
			$where['addtime'] =  array('EGT',$now_time);
		}

		$count = M('Myzc')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Myzc')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['usernamea'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function myzcQueren($id = NULL)
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		$myzc = M('Myzc')->where(array('id' => trim($id)))->find();

		if (!$myzc) {
			$this->error('转出错误！');
		}

		if ($myzc['status']) {
			$this->error('已经处理过！');
		}
		$user = M('User')->where(array('id' => $myzc['userid']))->find();
		$username = $user['username'];
		$coin = $myzc['coinname'];

		$dj_username = C('coin')[$coin]['dj_yh'];
		$dj_password = C('coin')[$coin]['dj_mm'];
		$dj_address = C('coin')[$coin]['dj_zj'];
		$dj_port = C('coin')[$coin]['dj_dk'];
		$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);
		$json = $CoinClient->getinfo() ;

		if (!isset($json['version']) || !$json['version']) {
			$this->error('钱包链接失败！');
		}

		$Coin = M('Coin')->where(array('name' => $myzc['coinname']))->find();
		$fee_user = M('UserCoin')->where(array($coin . 'b' => $Coin['zc_user']))->find();
		$user_coin = M('UserCoin')->where(array('userid' => $myzc['userid']))->find();
		$zhannei = M('UserCoin')->where(array($coin . 'b' => $myzc['username']))->find();
		$mo = M();
		$mo->execute('set autocommit=0');
		$mo->execute('lock tables  tw_user_coin write  , tw_myzc write  , tw_myzr write , tw_myzc_fee write');
		$rs = array();

		if ($zhannei) {
			$rs[] = $mo->table('tw_myzr')->add(array('userid' => $zhannei['userid'], 'username' => $myzc['username'], 'coinname' => $coin, 'txid' => md5($myzc['username'] . $user_coin[$coin . 'b'] . time()), 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'addtime' => time(), 'status' => 1));
			$rs[] = $r = $mo->table('tw_user_coin')->where(array('userid' => $zhannei['userid']))->setInc($coin, $myzc['mum']);
		}

		if (!$fee_user['userid']) {
			$fee_user['userid'] = 0;
		}

		if (0 < $myzc['fee']) {
			$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $fee_user['userid'], 'username' => $Coin['zc_user'], 'coinname' => $coin, 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'type' => 2, 'addtime' => time(), 'status' => 1));

			if ($mo->table('tw_user_coin')->where(array($coin . 'b' => $Coin['zc_user']))->find()) {
				$rs[] = $mo->table('tw_user_coin')->where(array($coin . 'b' => $Coin['zc_user']))->setInc($coin, $myzc['fee']);
				debug(array('lastsql' => $mo->table('tw_user_coin')->getLastSql()), '新增费用');
			}
			else {
				$rs[] = $mo->table('tw_user_coin')->add(array($coin . 'b' => $Coin['zc_user'], $coin => $myzc['fee']));
			}
		}

		$rs[] = $mo->table('tw_myzc')->where(array('id' => trim($id)))->save(array('status' => 1,'endtime'=>time()));

		if (check_arr($rs)) {
			$sendrs = $CoinClient->sendtoaddress($myzc['username'], (double) $myzc['mum']);
			if ($sendrs) {
				$mo->table('tw_myzc')->where(array('id'=>trim($id)))->save(array('txid'=>$sendrs));
				$flag = 1;
				$arr = json_decode($sendrs, true);

				if (isset($arr['status']) && ($arr['status'] == 0)) {
					$flag = 0;
				}
			}
			else {
				$flag = 0;
			}

			if (!$flag) {
				$mo->execute('rollback');
				$mo->execute('unlock tables');
				$this->error('钱包服务器转出币失败!');
			}
			else {
				$mo->execute('commit');
				$mo->execute('unlock tables');
				$this->success('转账成功！');
			}
		}
		else {
			$mo->execute('rollback');
			$mo->execute('unlock tables');
			$this->error('转出失败!' . implode('|', $rs) . $myzc['fee']);
		}
	}
	
	public function myzcBatch($id){
		if(!empty($id)){
			foreach($id as $zcid){
				$myzc = M('Myzc')->where(array('id' => $zcid))->find();

				if (!$myzc) {
					M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>"记录不存在"));
					continue;
				}

				if ($myzc['status']) {
					M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>"已经处理过",'userid'=>$myzc['userid'],'username'=>$myzc['username']));
					continue;
				}
				$user = M('User')->where(array('id' => $myzc['userid']))->find();
				$username = $user['username'];
				$coin = $myzc['coinname'];

				$dj_username = C('coin')[$coin]['dj_yh'];
				$dj_password = C('coin')[$coin]['dj_mm'];
				$dj_address = C('coin')[$coin]['dj_zj'];
				$dj_port = C('coin')[$coin]['dj_dk'];
				$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);
				$json = $CoinClient->getinfo() ;

				if (!isset($json['version']) || !$json['version']) {
					M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>"钱包连接失败",'userid'=>$myzc['userid'],'username'=>$myzc['username']));
					continue;
				}

				$Coin = M('Coin')->where(array('name' => $myzc['coinname']))->find();
				$fee_user = M('UserCoin')->where(array($coin . 'b' => $Coin['zc_user']))->find();
				$user_coin = M('UserCoin')->where(array('userid' => $myzc['userid']))->find();
				$zhannei = M('UserCoin')->where(array($coin . 'b' => $myzc['username']))->find();
				$mo = M();
				$mo->execute('set autocommit=0');
				$mo->execute('lock tables  tw_user_coin write  , tw_myzc write  , tw_myzr write , tw_myzc_fee write');
				$rs = array();

				if ($zhannei) {
					$rs[] = $mo->table('tw_myzr')->add(array('userid' => $zhannei['userid'], 'username' => $myzc['username'], 'coinname' => $coin, 'txid' => md5($myzc['username'] . $user_coin[$coin . 'b'] . time()), 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'addtime' => time(), 'status' => 1));
					$rs[] = $r = $mo->table('tw_user_coin')->where(array('userid' => $zhannei['userid']))->setInc($coin, $myzc['mum']);
				}

				if (!$fee_user['userid']) {
					$fee_user['userid'] = 0;
				}

				if (0 < $myzc['fee']) {
					$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $fee_user['userid'], 'username' => $Coin['zc_user'], 'coinname' => $coin, 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'type' => 2, 'addtime' => time(), 'status' => 1));

					if ($mo->table('tw_user_coin')->where(array($coin . 'b' => $Coin['zc_user']))->find()) {
						$rs[] = $mo->table('tw_user_coin')->where(array($coin . 'b' => $Coin['zc_user']))->setInc($coin, $myzc['fee']);
						debug(array('lastsql' => $mo->table('tw_user_coin')->getLastSql()), '新增费用');
					}
					else {
						$rs[] = $mo->table('tw_user_coin')->add(array($coin . 'b' => $Coin['zc_user'], $coin => $myzc['fee']));
					}
				}

				$rs[] = $mo->table('tw_myzc')->where(array('id' => trim($zcid)))->save(array('status' => 1,'endtime'=>time()));

				if (check_arr($rs)) {
					$sendrs = $CoinClient->sendtoaddress($myzc['username'], (double) $myzc['mum']);
					if ($sendrs) {
						$mo->table('tw_myzc')->where(array('id'=>trim($zcid)))->save(array('txid'=>$sendrs));
						$flag = 1;
						$arr = json_decode($sendrs, true);

						if (isset($arr['status']) && ($arr['status'] == 0)) {
							$flag = 0;
						}
					}
					else {
						$flag = 0;
					}

					if (!$flag) {
						$mo->execute('rollback');
						$mo->execute('unlock tables');
						M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>"钱包服务器转出币失败",'userid'=>$myzc['userid'],'username'=>$myzc['username']));
					}
					else {
						$mo->execute('commit');
						$mo->execute('unlock tables');
					}
				}
				else {
					$mo->execute('rollback');
					$mo->execute('unlock tables');
					M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>'转出失败!' . implode('|', $rs) . $myzc['fee'],'userid'=>$myzc['userid'],'username'=>$myzc['username']));
				}
			}
		}
		$this->success("执行完毕！");
	}
	
	public function myzcBatchLog($starttime='',$endtime='',$username=''){
		$where = array();

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
			$now_time = time() - 1000*24*60*60;
			$where['addtime'] =  array('EGT',$now_time);
			
		}
		
		if(!empty($username)){
			$where['username'] = $username;
		}
		
		$count = M('Zcbatch_error')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Zcbatch_error')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['uname'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}
}

?>