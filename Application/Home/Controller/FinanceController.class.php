<?php
namespace Home\Controller;

class FinanceController extends HomeController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","myzr","myzc","upmyzc","mywt","mycj","upmyzr","mytj","mywd","myjp");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("页面不存在！");
		}
	}
	
	public function __construct() {
		parent::__construct();
		$display_action=array("index","myzr","myzc","mywt","mycj","mytj","mywd","myjp");
		if(in_array(ACTION_NAME,$display_action)){
			$this->common_content();
		}
	}
	
	public function index()
	{
		if (!userid()) {
			redirect('/Login/index.html');
		}

		$coinlist = M('Coin')->where(array('status' => 1))->select();
		$UserCoin = M('UserCoin')->where(array('userid' => userid()))->find();

		$new_coinlist = array();

		foreach ($coinlist as $k => $v) {
			$market_info = M('market')->where(array('name'=>$v['name']."_btc"))->find();
			$number_round = 8-$market_info['round'];
			$jia = !empty($market_info['new_price']) ? $market_info['new_price'] : 1;
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['jia'] = $jia;
			$round = pow(10,$number_round);
			$user_coin_xnb = floor($UserCoin[$v['name']]*$round)/$round;
			$user_coin_xnbz = floor(($UserCoin[$v['name']]+$UserCoin[$v['name'] . 'd'])*$round)/$round;
			$new_coinlist[$v['name']]['xnb'] = !empty($user_coin_xnb) ? number_format($user_coin_xnb, $number_round,'.','') : 0;
			$new_coinlist[$v['name']]['xnbd'] = !empty($UserCoin[$v['name'] . 'd']) ? number_format($UserCoin[$v['name'] . 'd'], $number_round,'.','') : 0;
			$new_coinlist[$v['name']]['xnbz'] = !empty($UserCoin[$v['name']]) || !empty($UserCoin[$v['name'] . 'd']) ? number_format($user_coin_xnbz, $number_round,'.','') : 0;
			$new_coinlist[$v['name']]['zhehe'] = !empty($new_coinlist[$v['name']]['xnbz']) && !empty($jia) ? number_format(($UserCoin[$v['name']] + $UserCoin[$v['name'] . 'd'])*$jia,$market_info['round'],'.','') : 0;
			$new_coinlist[$v['name']]['xnb_rmb'] = !empty($user_coin_xnb) ? number_format(huansuan($user_coin_xnb,$v['name'],'rmb'), $number_round,'.','') : 0;
			$new_coinlist[$v['name']]['xnb_usd'] = !empty($user_coin_xnb) ? number_format(huansuan($user_coin_xnb,$v['name'],'usd'), $number_round,'.','') : 0;
			$new_coinlist[$v['name']]['xnbd_rmb'] = !empty($UserCoin[$v['name'] . 'd']) ? number_format(huansuan($UserCoin[$v['name'] . 'd'],$v['name'],'rmb'), $number_round,'.','') : 0;
			$new_coinlist[$v['name']]['xnbd_usd'] = !empty($UserCoin[$v['name'] . 'd']) ? number_format(huansuan($UserCoin[$v['name'] . 'd'],$v['name'],'usd'), $number_round,'.','') : 0;
			$new_coinlist[$v['name']]['xnbz_rmb'] = !empty($UserCoin[$v['name']]) || !empty($UserCoin[$v['name'] . 'd']) ? number_format(huansuan($user_coin_xnbz,$v['name'],'rmb'), $number_round,'.','') : 0;
			$new_coinlist[$v['name']]['xnbz_usd'] = !empty($UserCoin[$v['name']]) || !empty($UserCoin[$v['name'] . 'd']) ? number_format(huansuan($user_coin_xnbz,$v['name'],'usd'), $number_round,'.','') : 0;
		}

		$this->assign('coinlist', $new_coinlist);
		$this->display();
	}

	public function myzr($coin = NULL)
	{
		
		// 过滤非法字符----------------S

		if (checkstr($coin)) {
			$this->error(L('sCommon_nsrdxxyw'));
		}
		if(!$coin){
			$coin='bcc';
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			redirect('/#login');
		}

		if (C('coin')[$coin]) {
			$coin = trim($coin);
		}
		else {
			$coin = C('xnb_mr');
		}
		$this->assign('xnb', $coin);
		$coin_rmb=$coin.'_rmb';
		$coin_usd=$coin.'_usd';
		$this->assign('xnb_rmb', $coin_rmb);
		$this->assign('xnb_usd', $coin_usd);
		
		$Coins = M('Coin')->where(array(
			'status' => 1,
			'type' => 'qbb'
			))->select();

		foreach ($Coins as $k => $v) {
			$coin_list[$v['name']] = $v;
		}
		$this->assign('coin_list', $coin_list);
		
		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();
		$market_info = getmarket_frombi($coin,$rmb='btc');
		$round = pow(10,8-$market_info['round']);
		$user_coin[$coin] = floor($user_coin[$coin]*$round)/$round;
		$user_coin[$coin] = number_format($user_coin[$coin], 8-$market_info['round'],'.','');
		$user_coin[$coin.'_rmb'] = number_format(huansuan($user_coin[$coin],$coin,'rmb'),8-$market_info['round'],'.','');
		$user_coin[$coin.'_usd'] = number_format(huansuan($user_coin[$coin],$coin,'usd'),8-$market_info['round'],'.','');
		$this->assign('user_coin', $user_coin);
		
		$user_info = M('User')->where(array('id'=>userid()))->find();
		$this->assign('user', $user_info);
		
		$Coins = M('Coin')->where(array('name' => $coin))->find();
		$this->assign('zr_jz', $Coins['zr_jz']);

		if (!$Coins['zr_jz']) {
			$qianbao = L('sFinance_myzr_dqjfjzzr');
		}
		else {
			$qbdz = $coin . 'b';

			if (!$user_coin[$qbdz]) {
				if ($Coins['type'] == 'rgb') {
					$qianbao = md5(username() . $coin);
					$rs = M('UserCoin')->where(array('userid' => userid()))->save(array($qbdz => $qianbao));

					if (!$rs) {
						$this->error(L('sFinance_myzr_scqbdzcc'));
					}
				}

				if ($Coins['type'] == 'qbb') {
					
						$dj_username = $Coins['dj_yh'];
						$dj_password = $Coins['dj_mm'];
						$dj_address = $Coins['dj_zj'];
						$dj_port = $Coins['dj_dk'];
						$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1,$coin);
						$json = $CoinClient->getinfo() ;

						if (!isset($json['version']) || !$json['version']) {
							$this->error(L('sFinance_myzr_qbljsb'));
						}

						$qianbao_addr = $CoinClient->getaddressesbyaccount(username());

						if (!is_array($qianbao_addr)) {
							$qianbao_ad = $CoinClient->getnewaddress(username());

							if (!$qianbao_ad) {
								$this->error(L('sFinance_myzr_scqbdzcc'));
							}
							else {
								$qianbao = $qianbao_ad;
							}
						}
						else {
							$qianbao = $qianbao_addr[0];
						}

						if (!$qianbao) {
							$this->error(L('sFinance_myzr_scqbdzcc'));
						}

						$mo = M();
						$rs = $mo->table('tw_user_coin')->where(array('userid' => userid()))->save(array($qbdz => $qianbao));

						if (!$rs) {
							$this->error(L('sFinance_myzr_qbdztjcc'));
						}
					
				}
			}
			else {
				$qianbao = $user_coin[$coin . 'b'];
			}
		}

		$this->assign('qianbao', $qianbao);
		
		//生成token
		$myzr_token = set_token('myzr');
		$this->assign('myzr_token',$myzr_token);
		
		$where['userid'] = userid();
		$where['coinname'] = $coin;
		$where['from_user'] = '0';
		$count = M('Myzr')->where($where)->count();
		$Page = new \Think\Page($count, 10);
		if(LANG_SET == 'en-us'){
			$Page->config = array('header'=>'total','prev'=>'Previous','next'=>'Next','first'=>'First','last'=>'Last','theme'=>' %totalRow% %header% %nowPage%/%totalPage% Page %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		}
		$show = $Page->show();
		$list = M('Myzr')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $key => $value) {
			$list[$key]['num']=number_format($value['num'],8-$market_info['round'],'.','');
			$list[$key]['mum']=number_format($value['mum'],8-$market_info['round'],'.','');
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function myzc($coin = NULL)
	{
		// 过滤非法字符----------------S

		if (checkstr($coin)) {
			$this->error(L('sCommon_nsrdxxyw'));
		}

		// 过滤非法字符----------------E

		if (!userid()) {
			redirect('/#login');
		}
		$user_info = M('user')->where(array('id'=>userid()))->find();
		if(shiming($user_info['id']) < 3){
			// $this->error(L('sCommon_qxwcsmrz'),'/User/nameauth.html');
			echo "<script>alert('".L('sCommon_qxwcsmrz')."');location.href='/User/nameauth';</script>";
			return ;

		}

		if (C('coin')[$coin]) {
			$coin = trim($coin);
		}
		else {
			$coin = C('xnb_mr');
		}
		$coin_info = M('Coin')->where(array('name' => $coin))->find();
		
		$myzc_min = $coin_info['zc_min']>0 ? $coin_info['zc_min'] : 0.0001;
		$myzc_max = ($coin_info['zc_max']>0 && $coin_info['zc_max'] > $coin_info['zc_min']) ? $coin_info['zc_max'] : 10000000;
		$this->assign('myzc_min', remove_ling($myzc_min));
		$this->assign('myzc_max', remove_ling($myzc_max));
		$this->assign('myzc_fee', $coin_info['zc_fee']);
		
		$this->assign('xnb', $coin);
		$coin_rmb=$coin.'_rmb';
		$coin_usd=$coin.'_usd';
		$this->assign('xnb_rmb', $coin_rmb);
		$this->assign('xnb_usd', $coin_usd);
		
		$Coins = M('Coin')->where(array('status' => 1))->select();
		foreach ($Coins as $k => $v) {
			$coin_list[$v['name']] = $v;
		}
		$this->assign('coin_list', $coin_list);
		
		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();
		$market_info = getmarket_frombi($coin,$rmb='btc');
		$market_info['round']=6;
		$round = pow(10,8-$market_info['round']);
		$user_coin[$coin] = floor($user_coin[$coin]*$round)/$round;
		$user_coin[$coin] = number_format($user_coin[$coin],12-$market_info['round'],'.','');
		$user_coin[$coin.'_rmb'] = number_format(huansuan($user_coin[$coin],$coin,'rmb'),12-$market_info['round'],'.','');
		$user_coin[$coin.'_usd'] = number_format(huansuan($user_coin[$coin],$coin,'usd'),12-$market_info['round'],'.','');
		$this->assign('user_coin', $user_coin);

		if (!$coin_list[$coin]['zc_jz']) {
			$this->assign('zc_jz', L('sFinance_myzc_dqjfjzzc'));
		}
		else {
			$userQianbaoList = M('UserQianbao')->where(array('userid' => userid(), 'status' => 1, 'coinname' => $coin))->order('id desc')->select();
			$this->assign('userQianbaoList', $userQianbaoList);
			$email = M('User')->where(array('id' => userid()))->getField('email');
			if(empty($email)){
				$this->error(L('sFinance_myzc_nmybdyx'));
			}
			$user = M('User')->where(array('id' => userid()))->find();
			$this->assign('user', $user);
		}

		$where['userid'] = userid();
		$where['coinname'] = $coin;
		$where['to_user'] = array('neq','1' );
		$Mzc = M('Myzc');
		$count = $Mzc->where($where)->count();
		$Page = new \Think\Page($count, 10);
		if(LANG_SET == 'en-us'){
			$Page->config = array('header'=>'total','prev'=>'Previous','next'=>'Next','first'=>'First','last'=>'Last','theme'=>' %totalRow% %header% %nowPage%/%totalPage% Page %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		}
		$show = $Page->show();
		$list = $Mzc->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $key => $value) {
			$list[$key]['num']=number_format($value['num'],12-$market_info['round'],'.','');
			$list[$key]['mum']=number_format($value['mum'],12-$market_info['round'],'.','');
			$list[$key]['fee']=number_format($value['fee'],12-$market_info['round'],'.','');
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		
		//生成token
		$myzc_token = set_token('myzc');
		$this->assign('myzc_token',$myzc_token);

		$this->display();
	}

	public function upmyzc($coin, $num, $addr, $paypassword, $token, $email_verify)
	{

		$extra='';
		
		// 过滤非法字符----------------S

		if (checkstr($coin) || checkstr($num) || checkstr($email_verify)) {
			$this->error(L('sCommon_nsrdxxyw'),$extra);
		}

		// 过滤非法字符----------------E

		if (!userid()) {
			$this->error(L('sCommon_qxdl'),$extra);
		}
		
		if(!session('myzctoken')) {
			set_token('myzc');
		}
		if(!empty($token)){
			$res = valid_token('myzc',$token);
			if(!$res){
				$this->error(L('sCommon_qbypftj'),session('myzctoken'));
			}
		}
		$extra=session('myzctoken');

		if (!check($email_verify, 'd')) {
			$this->error(L('sCommon_yxyzmgscw'),$extra);
		}
		
		$user_info = M('user')->where(array('id'=>userid()))->find();
		
		if ($user_info['email'] != session('chkemail')) {
			$this->error(L('sCommon_yxyzmcw'),$extra);
		}

		if ($email_verify != session('emailmyzc_verify')) {
			$this->error(L('sCommon_yxyzmcw'),$extra);
		}

		$num = abs($num);

		if (!check($num, 'currency')) {
			$this->error(L('sTrade_index_jyslgscw'),$extra);
		}

		if (!check($addr, 'dw')) {
			$this->error(L('sFinance_myzc_qbdzgscw'),$extra);
		}

		if (!check($paypassword, 'password')) {
			$this->error(L('sCommon_mmgs'),$extra);
		}

		if (!check($coin, 'n')) {
			$this->error(L('sCommon_jfgscw'),$extra);
		}

		if (!C('coin')[$coin]) {
			$this->error(L('sCommon_jfcw'),$extra);
		}

		$Coins = M('Coin')->where(array('name' => $coin))->find();

		if (!$Coins) {
			$this->error(L('sCommon_jfcw'),$extra);
		}

		$myzc_min = ($Coins['zc_min'] ? abs($Coins['zc_min']) : 0.0001);
		$myzc_max = ($Coins['zc_max'] ? abs($Coins['zc_max']) : 10000000);

		if ($num < $myzc_min) {
			$this->error(L('sFinance_myzc_zcslcgxtzxxz'),$extra);
		}

		if ($myzc_max < $num) {
			$this->error(L('sFinance_myzc_zcslcgxtzdxz'),$extra);
		}

		$user = M('User')->where(array('id' => userid()))->find();
		if(shiming($user['id']) < 3){
			$this->error(L('sCommon_qxwcsmrz'),$extra);
		}
		if (md5($paypassword) != $user['paypassword']) {
			$this->error(L('sCommon_jymmcw'),$extra);
		}

		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();

		if ($user_coin[$coin] < $num) {
			$this->error(L('sCommon_yebz'),$extra);
		}

		$qbdz = $coin . 'b';
		$fee_user = M('UserCoin')->where(array($qbdz => $Coins['zc_user']))->find();

		if ($fee_user) {
			debug('手续费地址: ' . $Coins['zc_user'] . ' 存在,有手续费');
			
			$fee = round($num*$Coins['zc_fee']/100, 8);
			$mum = round($num - $fee, 8);

			if ($mum < 0) {
				$this->error(L('sFinance_myzc_zcsxfcw'),$extra);
			}

			if ($fee < 0) {
				$this->error(L('sFinance_myzc_zcsxfszcw'),$extra);
			}
		}
		else {
			debug('手续费地址: ' . $Coins['zc_user'] . ' 不存在,无手续费');
			$fee = 0;
			$mum = $num;
		}
		
		
		if ($Coins['type'] == 'rgb') {
			debug($Coins, '开始认购积分转出');
			$peer = M('UserCoin')->where(array($qbdz => $addr))->find();

			if (!$peer) {
				$this->error('转出认购积分地址不存在！',$extra);
			}

			$mo = M();
			$mo->startTrans();

			$rs = array();
			$rs[] = $mo->table('tw_user_coin')->where(array('userid' => userid()))->setDec($coin, $num);
			$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $peer['userid']))->setInc($coin, $mum);

			if ($fee) {
				if ($mo->table('tw_user_coin')->where(array($qbdz => $Coins['zc_user']))->find()) {
					$rs[] = $mo->table('tw_user_coin')->where(array($qbdz => $Coins['zc_user']))->setInc($coin, $fee);
					debug(array('msg' => '转出收取手续费' . $fee), 'fee');
				}
				else {
					$rs[] = $mo->table('tw_user_coin')->add(array($qbdz => $Coins['zc_user'], $coin => $fee));
					debug(array('msg' => '转出收取手续费' . $fee), 'fee');
				}
			}

			$rs[] = $mo->table('tw_myzc')->add(array('userid' => userid(), 'username' => $addr, 'coinname' => $coin, 'txid' => md5($addr . $user_coin[$coin . 'b'] . time()), 'num' => $num, 'fee' => $fee, 'mum' => $mum, 'addtime' => time(), 'status' => 1));
			$rs[] = $mo->table('tw_myzr')->add(array('userid' => $peer['userid'], 'username' => $user_coin[$coin . 'b'], 'coinname' => $coin, 'txid' => md5($user_coin[$coin . 'b'] . $addr . time()), 'num' => $num, 'fee' => $fee, 'mum' => $mum, 'addtime' => time(), 'status' => 1));

			if ($fee_user) {
				$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $fee_user['userid'], 'username' => $Coins['zc_user'], 'coinname' => $coin, 'txid' => md5($user_coin[$coin . 'b'] . $Coins['zc_user'] . time()), 'num' => $num, 'fee' => $fee, 'type' => 1, 'mum' => $mum, 'addtime' => time(), 'status' => 1));
			}

			// 处理资金变更日志-----------------S

			$user_zj_coin = $mo->table('tw_user_coin')->where(array('userid' => userid()))->find();

			// 转出人记录
			$mo->table('tw_finance_log')->add(array('username' => $user['username'], 'adminname' => $user['username'], 'addtime' => time(), 'plusminus' => 0, 'amount' => $num, 'optype' => 6, 'position' => 1, 'cointype' => $Coins['id'], 'old_amount' => $user_coin[$coin], 'new_amount' => $user_zj_coin[$coin], 'userid' => userid(), 'adminid' => userid(),'addip'=>get_client_ip()));

			// 获取用户信息
			$user_info = $mo->table('tw_user')->where(array('id' => $peer['userid']))->find();
			$user_peer_coin = $mo->table('tw_user_coin')->where(array('userid' => $peer['userid']))->find();

			// 接受人记录
			$mo->table('tw_finance_log')->add(array('username' => $user_info['username'], 'adminname' => $user['username'], 'addtime' => time(), 'plusminus' => 1, 'amount' => $mum, 'optype' => 7, 'position' => 1, 'cointype' => $Coins['id'], 'old_amount' => $peer[$coin], 'new_amount' => $user_peer_coin[$coin], 'userid' => $peer['userid'], 'adminid' => userid(),'addip'=>get_client_ip()));

			// 处理资金变更日志-----------------E

			if (check_arr($rs)) {
				$mo->commit();
				session('myzc_verify', null);
				session('chkmobile', null);
				$this->success('转账成功！',$extra);
			}
			else {
				$mo->rollback();
				$this->error('转账失败!',$extra);
			}
		}

		if ($Coins['type'] == 'qbb') {
			$mo = M();
			if ($mo->table('tw_user_coin')->where(array($qbdz => $addr))->find()) {
				$peer = M('UserCoin')->where(array($qbdz => $addr))->find();
				if (!$peer) {
					$this->error(L('sFinance_myzc_zcdzbcz'),$extra);
				}
				try{
					$mo = M();
					$mo->startTrans();

					$rs = array();
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' => userid()))->setDec($coin, $num);
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $peer['userid']))->setInc($coin, $mum);

					if ($fee) {
						if ($mo->table('tw_user_coin')->where(array($qbdz => $Coins['zc_user']))->find()) {
							$rs[] = $mo->table('tw_user_coin')->where(array($qbdz => $Coins['zc_user']))->setInc($coin, $fee);
						}
						else {
							$rs[] = $mo->table('tw_user_coin')->add(array($qbdz => $Coins['zc_user'], $coin => $fee));
						}
					}

					$rs[] = $mo->table('tw_myzc')->add(array('userid' => userid(), 'username' => $addr, 'coinname' => $coin, 'txid' => md5($addr . $user_coin[$coin . 'b'] . time()), 'num' => $num, 'fee' => $fee, 'mum' => $mum, 'addtime' => time(), 'status' => 1));
					$rs[] = $mo->table('tw_myzr')->add(array('userid' => $peer['userid'], 'username' => $user_coin[$coin . 'b'], 'coinname' => $coin, 'txid' => md5($user_coin[$coin . 'b'] . $addr . time()), 'num' => $num, 'fee' => $fee, 'mum' => $mum, 'addtime' => time(), 'status' => 1));

					if ($fee_user) {
						$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $fee_user['userid'], 'username' => $Coins['zc_user'], 'coinname' => $coin, 'txid' => md5($user_coin[$coin . 'b'] . $Coins['zc_user'] . time()), 'num' => $num, 'fee' => $fee, 'type' => 1, 'mum' => $mum, 'addtime' => time(), 'status' => 1));
					}

					// 处理资金变更日志-----------------S

					$user_zj_coin = $mo->table('tw_user_coin')->where(array('userid' => userid()))->find();

					// 转出人记录
					$mo->table('tw_finance_log')->add(array('username' => $user['username'], 'adminname' => $user['username'], 'addtime' => time(), 'plusminus' => 0, 'amount' => $num, 'optype' => 6, 'position' => 1, 'cointype' => $Coins['id'], 'old_amount' => $user_coin[$coin], 'new_amount' => $user_zj_coin[$coin], 'userid' => userid(), 'adminid' => userid(),'addip'=>get_client_ip()));

					// 获取用户信息
					$user_info = $mo->table('tw_user')->where(array('id' => $peer['userid']))->find();
					$user_peer_coin = $mo->table('tw_user_coin')->where(array('userid' => $peer['userid']))->find();

					// 接受人记录
					$mo->table('tw_finance_log')->add(array('username' => $user_info['username'], 'adminname' => $user['username'], 'addtime' => time(), 'plusminus' => 1, 'amount' => $mum, 'optype' => 7, 'position' => 1, 'cointype' => $Coins['id'], 'old_amount' => $peer[$coin], 'new_amount' => $user_peer_coin[$coin], 'userid' => $peer['userid'], 'adminid' => userid(),'addip'=>get_client_ip()));

					// 处理资金变更日志-----------------E

					if (check_arr($rs)) {
						$mo->commit();
						session('myzc_verify', null);
						$this->success(L('sFinance_myzc_zzcg'),$extra);
					}else {
						throw new \Think\Exception(L('sFinance_myzc_zzsb'));
					}
				}catch(\Think\Exception $e){
					$mo->rollback();
					$this->error(L('sFinance_myzc_zzsb'),$extra);
				}
			}
			else {
				// if($coin == 'eth' || $coin == 'ETH'){
				// 	echo 1;
				// }else{
					$dj_username = $Coins['dj_yh'];
					$dj_password = $Coins['dj_mm'];
					$dj_address = $Coins['dj_zj'];
					$dj_port = $Coins['dj_dk'];
					$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1,$coin);
					$json = $CoinClient->getinfo() ;

					if (!isset($json['version']) || !$json['version']) {
						$this->error(L('sFinance_myzr_qbljsb'),$extra);
					}

					$valid_res = $CoinClient->validateaddress($addr);

					if (!$valid_res['isvalid']) {
						$this->error($addr . L('sFinance_myzc_bsygyxdqbdz'),$extra);
					}

					$auto_status = ($Coins['zc_zd'] && ($num < $Coins['zc_zd']) ? 1 : 0);

					if ($json['balance'] < $num && $coin != 'eth') {
						$this->error(L('sFinance_myzc_qbyebz'),$extra);
					}
					try{

						$mo = M();
						$mo->startTrans();

						$rs = array();
						$rs[] = $r = $mo->table('tw_user_coin')->where(array('userid' => userid()))->setDec($coin, $num);
						$rs[] = $aid = $mo->table('tw_myzc')->add(array('userid' => userid(), 'username' => $addr, 'coinname' => $coin, 'num' => $num, 'fee' => $fee, 'mum' => $mum, 'addtime' => time(), 'status' => $auto_status));

						if ($fee && $auto_status) {
							$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $fee_user['userid'], 'username' => $Coins['zc_user'], 'coinname' => $coin, 'num' => $num, 'fee' => $fee, 'mum' => $mum, 'type' => 2, 'addtime' => time(), 'status' => 1));

							if ($mo->table('tw_user_coin')->where(array($qbdz => $Coins['zc_user']))->find()) {
								$rs[] = $r = $mo->table('tw_user_coin')->where(array($qbdz => $Coins['zc_user']))->setInc($coin, $fee);
								debug(array('res' => $r, 'lastsql' => $mo->table('tw_user_coin')->getLastSql()), '新增费用');
							}
							else {
								$rs[] = $r = $mo->table('tw_user_coin')->add(array($qbdz => $Coins['zc_user'], $coin => $fee));
							}
						}

						// 处理资金变更日志-----------------S

						$user_zj_coin = $mo->table('tw_user_coin')->where(array('userid' => userid()))->find();

						// 转出人记录
						
						$mo->table('tw_finance_log')->add(array('username' => $user['username'], 'adminname' => $user['username'], 'addtime' => time(), 'plusminus' => 0, 'amount' => $num, 'optype' => 6, 'position' => 1, 'cointype' => $Coins['id'], 'old_amount' => $user_coin[$coin], 'new_amount' => $user_zj_coin[$coin], 'userid' => userid(), 'adminid' => userid(),'addip'=>get_client_ip()));

						// 处理资金变更日志-----------------E

						if (check_arr($rs)) {
							if ($auto_status) {
								$sendrs = $CoinClient->sendtoaddress($addr, floatval($mum));
								if ($sendrs) {
									$res = $mo->table('tw_myzc')->where(array('id'=>$aid))->save(array('txid'=>$sendrs));
									$mo->commit();
								}else{
									throw new \Think\Exception(L('sFinance_myzc_zcsb'));
								}
							}else {
								$mo->commit();
								session('myzc_verify', null);
								session('chkmobile', null);
								$this->success(L('sFinance_myzc_zcsqcgqddsh'),$extra);
							}
						}else {
							throw new \Think\Exception(L('sFinance_myzc_zzsb'));
						}
					}catch(\Think\Exception $e){
						$mo->rollback();
						$this->error(L('sFinance_myzc_zzsb'),$extra);
					}
					if(!$auto_status){
						$flag = 1;
					}else if ($auto_status && $sendrs) {
						$flag = 1;
						$arr = json_decode($sendrs, true);
						if (isset($arr['status']) && ($arr['status'] == 0)) {
							$flag = 0;
						}
					}else {
						$flag = 0;
					}

					if (!$flag) {
						$this->error(L('sFinance_myzc_zcsb'),$extra);
					}
					else {
						$this->success(L('sFinance_myzc_zccg'),$extra);
					}
				// }
			}
		}
		
	}

	public function mywt($market = NULL, $type = NULL, $status = NULL, $starttime = NULL, $endtime = NULL)
	{

		// 过滤非法字符----------------S

		if (checkstr($market) || checkstr($type) || checkstr($status)) {
			$this->error(L('sCommon_nsrdxxyw'));
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			redirect('/#login');
		}

		$this->assign('prompt_text', D('Text')->get_content('finance_mywt'));
		check_server();
		$Coins = M('Coin')->where(array('status' => 1))->select();

		foreach ($Coins as $k => $v) {
			$coin_list[$v['name']] = $v;
		}

		$this->assign('coin_list', $coin_list);
		$Market = M('Market')->where(array('status' => 1))->select();

		foreach ($Market as $k => $v) {
			$v['xnb'] = explode('_', $v['name'])[0];
			$v['rmb'] = explode('_', $v['name'])[1];
			$market_list[$v['name']] = $v;
		}

		$this->assign('market_list', $market_list);

		if (!$market_list[$market]) {
			$market = $Market[0]['name'];
		}

		$where['market'] = $market;

		if (($type == 1) || ($type == 2)) {
			$where['type'] = $type;
		}

		if (($status == 1) || ($status == 2) || ($status == 3)) {
			$where['status'] = $status - 1;
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
		if(!empty($starttime)){
			$this->assign('starttime',date("Y-m-d H:i:s",$starttime));
		}
		if(!empty($endtime)){
			$this->assign('endtime',date("Y-m-d H:i:s",$endtime));
		}

		$where['userid'] = userid();
		$this->assign('market', $market);
		$this->assign('type', $type);
		$this->assign('status', $status);
		$Mobile = M('Trade');
		$count = $Mobile->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$Page->parameter .= 'type=' . $type . '&status=' . $status . '&market=' . $market . '&';
		if(LANG_SET == 'en-us'){
			$Page->config = array('header'=>'total','prev'=>'Previous','next'=>'Next','first'=>'First','last'=>'Last','theme'=>' %totalRow% %header% %nowPage%/%totalPage% Page %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		}
		$show = $Page->show();
		$list = $Mobile->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$xnb = explode('_', $market)[0];
		$rmb = explode('_', $market)[1];
		foreach ($list as $k => $v) {
			$market_round=M('market')->where(array('name'=>$v['market']))->field('round')->find();
			$list[$k]['num'] = number_format($v['num'],12-$market_round['round'],'.','');
			$list[$k]['price'] = number_format($v['price'],$market_round['round'],'.','');
			$list[$k]['deal'] = number_format($v['deal'],6,'.','');
			$list[$k]['price_rmb'] = number_format(huansuan($v['price'],$rmb,'rmb'),$market_round['round'],'.','');
			$list[$k]['price_usd'] = number_format(huansuan($v['price'],$rmb,'usd'),$market_round['round'],'.','');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		
		$cancel_token = set_token('cancel');
		$this->assign('cancel_token',$cancel_token);
		$this->display();
	}

	public function mycj($market = NULL, $type = NULL, $starttime = NULL, $endtime = NULL)
	{

		// 过滤非法字符----------------S

		if (checkstr($market) || checkstr($type)) {
			$this->error(L('sCommon_nsrdxxyw'));
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			redirect('/#login');
		}

		$Coins = M('Coin')->where(array('status' => 1))->select();

		foreach ($Coins as $k => $v) {
			$coin_list[$v['name']] = $v;
		}

		$this->assign('coin_list', $coin_list);
		$Market = M('Market')->where(array('status' => 1))->select();

		foreach ($Market as $k => $v) {
			$v['xnb'] = explode('_', $v['name'])[0];
			$v['rmb'] = explode('_', $v['name'])[1];
			$market_list[$v['name']] = $v;
		}

		$this->assign('market_list', $market_list);

		if (!$market_list[$market]) {
			$market = $Market[0]['name'];
		}

		if ($type == 1) {
			$where = 'userid=' . userid() . ' && market=\'' . $market . '\'';
		}
		else if ($type == 2) {
			$where = 'peerid=' . userid() . ' && market=\'' . $market . '\'';
		}
		else {
			$where = '((userid=' . userid() . ') || (peerid=' . userid() . ')) && market=\'' . $market . '\'';
		}

		// 时间--条件

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where .= ' and addtime >= '.$starttime;
		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where .= ' and addtime <= '.$endtime;
		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where .= ' and addtime >= '.$starttime.' and addtime <= '.$endtime;
		}
		if(!empty($starttime)){
			$this->assign('starttime',date("Y-m-d H:i:s",$starttime));
		}
		if(!empty($endtime)){
			$this->assign('endtime',date("Y-m-d H:i:s",$endtime));
		}
		
		$this->assign('market', $market);
		$this->assign('type', $type);
		$this->assign('userid', userid());
		$Mobile = M('TradeLog');
		$count = $Mobile->where($where)->count();
		$Page = new \Think\Page($count, 15);
		if(LANG_SET == 'en-us'){
			$Page->config = array('header'=>'total','prev'=>'Previous','next'=>'Next','first'=>'First','last'=>'Last','theme'=>' %totalRow% %header% %nowPage%/%totalPage% Page %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		}
		$Page->parameter .= 'type=' . $type . '&market=' . $market . '&';
		$show = $Page->show();
		$list = $Mobile->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$market_info = M('market')->where(array('name'=>$v['market']))->field('round')->find();
			$xnb = explode('_', $v['market'])[0];
			$rmb = explode('_', $v['market'])[1];
			$list[$k]['num'] = number_format($v['num'],12-$market_info['round'],'.','');
			$list[$k]['price'] = number_format($v['price'],$market_info['round'],'.','');
			$list[$k]['mum'] = number_format($v['mum'],6,'.','');
			$list[$k]['fee_buy'] = number_format($v['fee_buy'],6,'.','');
			$list[$k]['fee_sell'] = number_format($v['fee_sell'],6,'.','');
			$list[$k]['price_rmb'] = number_format(huansuan($v['price'],$rmb,'rmb'),$market_info['round'],'.','');
			$list[$k]['price_usd'] = number_format(huansuan($v['price'],$rmb,'usd'),$market_info['round'],'.','');
			$list[$k]['mum_rmb'] = number_format(huansuan($v['mum'],$rmb,'rmb'),$market_info['round'],'.','');
			$list[$k]['mum_usd'] = number_format(huansuan($v['mum'],$rmb,'usd'),$market_info['round'],'.','');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}
	
	public function mytj()
	{
		if (!userid()) {
			redirect('/#login');
		}
		$rmbarr = getrmbarr();
		$yjarr = array();
		foreach($rmbarr as $rmb){
			$yjsum = M('Invit')->where(array('userid'=>userid(),'rmb'=>$rmb))->sum('fee');
			$yjarr[$rmb][0] = $rmb;
			$yjarr[$rmb][1] = floatval($yjsum);
		}
		$this->assign('yjarr',$yjarr);
		$rssum = M('User')->where(array('invit_1'=>userid()))->count();
		$this->assign('rssum',$rssum);

		$user = M('User')->where(array('id' => userid()))->find();

		if (!$user['invit']) {
			for (; true; ) {
				$tradeno = tradenoa();

				if (!M('User')->where(array('invit' => $tradeno))->find()) {
					break;
				}
			}

			M('User')->where(array('id' => userid()))->save(array('invit' => $tradeno));
			$user = M('User')->where(array('id' => userid()))->find();
		}

		$this->assign('user', $user);
		$this->display();
	}
	
	public function mywd()
	{
		if (!userid()) {
			redirect('/#login');
		}

		$where['invit_1'] = userid();
		$count = M('User')->where($where)->count();
		$Page = new \Think\Page($count, 10);
		if(LANG_SET == 'en-us'){
			$Page->config = array('header'=>'total','prev'=>'Previous','next'=>'Next','first'=>'First','last'=>'Last','theme'=>' %totalRow% %header% %nowPage%/%totalPage% Page %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		}
		$show = $Page->show();
		$list = M('User')->where($where)->order('id asc')->field('id,username,mobile,addtime,invit_1,status')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['invits'] = M('User')->where(array('invit_1' => $v['id']))->order('id asc')->field('id,username,mobile,addtime,invit_1,status')->select();
			$list[$k]['invitss'] = count($list[$k]['invits']);
			foreach ($list[$k]['invits'] as $kk => $vv) {
				$list[$k]['invits'][$kk]['invits'] = M('User')->where(array('invit_1' => $vv['id']))->order('id asc')->field('id,username,mobile,addtime,invit_1,status')->select();
				$list[$k]['invits'][$kk]['invitss'] = count($list[$k]['invits'][$kk]['invits']);
			}
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function myjp()
	{
		if (!userid()) {
			redirect('/#login');
		}

		$where['userid'] = userid();
		$count = M('Invit')->where($where)->count();
		$Page = new \Think\Page($count, 10);
		$show = $Page->show();
		$list = M('Invit')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$coin_info = M('Coin')->field('id,title')->select();
		$coin_arr = array();
		foreach($coin_info as $arr){
			$coin_arr[$arr['id']] = $arr['title'];
		}
		foreach ($list as $k => $v) {
			$list[$k]['invit'] = M('User')->where(array('id' => $v['invit']))->getField('username');
			$list[$k]['mum'] = remove_ling($v['mum']);
			$list[$k]['fee'] = remove_ling($v['fee']);
			if($v['name'] == 1){
				$list[$k]['name'] = "一代";
			}elseif($v['name'] == 2){
				$list[$k]['name'] = "二代";
			}elseif($v['name'] == 3){
				$list[$k]['name'] = "三代";
			}
			$list[$k]['type'] = $coin_arr[$v['type']];
			if($v['buysell'] == 1){
				$list[$k]['buysell'] = "买入";
			}elseif($v['buysell'] == 2){
				$list[$k]['buysell'] = "卖出";
			}
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

}

?>