<?php
namespace Home\Controller;

class QueueController extends HomeController
{	
	public function updateprice(){
		Vendor("OKCoin.OKCoin","",".php");
		$client=new \OKCoin(new \OKCoin_ApiKeyAuthentication("93321050-6ba0-4202-97bf-2c37b14ca16c", "511834026920B324D140B0C7EB8F4560"));
		$btc_params = array('symbol' => 'btc_cny');
		$btc_cny = $client -> tickerApi($btc_params);
		$btc_price = $btc_cny->ticker->last;
		$eth_params = array('symbol' => 'eth_cny');
		$eth_cny = $client -> tickerApi($eth_params);
		$eth_price = $eth_cny->ticker->last;
		$res = M('Config')->where(array('id'=>1))->save(array('btc_rmb'=>$btc_price,'eth_rmb'=>$eth_price));
		if($res){
			echo 'OK';
		}
	}
	
	public function checkYichang()
	{
		$mo = M();
		$mo->startTrans();
		//$mo->execute('lock tables tw_trade write');
		$Trade = M('Trade')->where('deal > num')->order('id desc')->find();

		if ($Trade) {
			if ($Trade['status'] == 0) {
				$mo->table('tw_trade')->where(array('id' => $Trade['id']))->save(array('deal' => Num($Trade['num']), 'status' => 1));
			}
			else {
				$mo->table('tw_trade')->where(array('id' => $Trade['id']))->save(array('deal' => Num($Trade['num'])));
			}

			$mo->commit();
		}
		else {
			$mo->rollback();
		}
	}

	public function checkDapan()
	{
		// 处理开盘闭盘交易时间===开始
		$times = date('G',time());
		$minute = date('i',time());
		$minute = intval($minute);
		
		foreach (C('market') as $k => $v) {
			if(( $times <= $v['start_time'] && $minute< intval($v['start_minute'])) || ( $times > $v['stop_time'] && $minute>= intval($v['stop_minute'] ))){
				continue;
			}
			if(( $times <$v['start_time'] ) || $times > $v['stop_time']){
				continue;
			}else{
				if($times == $v['start_time']){
					if( $minute< intval($v['start_minute'])){
						continue;
					}
				}elseif($times == $v['stop_time']){
					if(( $minute > $v['stop_minute'])){
						continue;
					}
				}
			}
			// 处理周六周日是否可交易===开始
			$weeks = date('N',time());
			if(!$v['agree6']){
				if($weeks == 6){
					continue;
				}
			}
			if(!$v['agree7']){
				if($weeks == 7){
					continue;
				}
			}
			//处理周六周日是否可交易===结束
			$root_path = $_SERVER['DOCUMENT_ROOT']."/trading.txt";

			$fp = fopen($root_path, "w+");
			
			if(flock($fp,LOCK_EX | LOCK_NB))
			{
				A('Trade')->matchingTrade($v['name']);
				flock($fp,LOCK_UN);
			}
			fclose($fp);
		}
	}

	public function checkUsercoin()
	{
		$market_list = M('Market')->where(array('status'=>1))->select();
		foreach ($market_list as $market) {
			$new_price = round(M('TradeLog')->where(array('market' => $market['name'], 'status' => 1))->order('id desc')->getField('price'), 6);

			$buy_price = round(M('Trade')->where(array('type' => 1, 'market' => $market['name'], 'status' => 0))->order('price desc')->getField('price'), 6);
			if(empty($buy_price)){
				$buy_price = round(M('TradeLog')->where(array('market' => $market['name'], 'status' => 1))->order('addtime desc')->getField('price'), 6);
			}

			$sell_price = round(M('Trade')->where(array('type' => 2, 'market' => $market['name'], 'status' => 0))->order('price asc')->getField('price'), 6);
			if(empty($sell_price)){
				$sell_price = round(M('TradeLog')->where(array('market' => $market['name'], 'status' => 1))->order('addtime desc')->getField('price'), 6);
			}

			$min_price = round(M('TradeLog')->where(array('market'  => $market['name'],'addtime' => array('gt', time() - (60 * 60 * 24))))->min('price'), 6);

			$max_price = round(M('TradeLog')->where(array('market'  => $market['name'],'addtime' => array('gt', time() - (60 * 60 * 24))))->max('price'), 6);

			$volume = round(M('TradeLog')->where(array('market'  => $market['name'],'addtime' => array('gt', time() - (60 * 60 * 24))))->sum('num'), 6);

			$sta_price = round(M('TradeLog')->where(array('market'  => $market['name'],'status'  => 1,'addtime' => array('gt', time() - (60 * 60 * 24))))->order('id asc')->getField('price'), 6);
			
			$cjamount = round(M('TradeLog')->where(array('market'  => $market['name'],'addtime' => array('gt', time() - (60 * 60 * 24)),'status'=>1))->sum('mum'),6);

			if ($market['new_price'] != $new_price) {
				$upCoinData['new_price'] = $new_price;
			}

			if ($market['buy_price'] != $buy_price) {
				$upCoinData['buy_price'] = $buy_price;
			}

			if ($market['sell_price'] != $sell_price) {
				$upCoinData['sell_price'] = $sell_price;
			}

			if ($market['min_price'] != $min_price) {
				$upCoinData['min_price'] = $min_price;
			}

			if ($market['max_price'] != $max_price) {
				$upCoinData['max_price'] = $max_price;
			}

			if ($market['volume'] != $volume) {
				$upCoinData['volume'] = $volume;
			}
			
			if ($market['cjamount'] != $cjamount) {
				$upCoinData['cjamount'] = $cjamount;
			}

			$change = round((($new_price - $market['hou_price']) / $market['hou_price']) * 100, 2);

			$upCoinData['change'] = $change;

			if ($upCoinData) {
				$moo = M();
				$moo->table('tw_market')->where(array('name' => $market['name']))->save($upCoinData);
				S('home_market', null);
			}
		}
	}

	public function yichang()
	{			
		foreach (C('market') as $k => $v) {
			$this->setMarket($v['name']);
		}

		foreach (C('coin_list') as $k => $v) {
			$this->setcoin($v['name']);
		}

		$this->chack_dongjie_coin();
		
		echo 'ok';
	}

	public function move_yichang()
	{
	}

	public function chack_dongjie_coin()
	{
		$max_userid = S('queue_max_userid');

		if (!$max_userid) {
			$max_userid = M('User')->max('id');
			S('queue_max_userid', $max_userid);
		}

		$zuihou_userid = S('queue_zuihou_userid');

		if (!$zuihou_userid) {
			$zuihou_userid = M('User')->min('id');
		}
		
		$rmbarr = getrmbarr();

		$x = 0;

		for (; $x <= 30; $x++) {
			if ($max_userid < ($zuihou_userid + $x)) {
				S('queue_zuihou_userid', null);
				S('queue_max_userid', null);
				break;
			}
			else {
				S('queue_zuihou_userid', $zuihou_userid + $x + 1);
			}

			$user = M('UserCoin')->where(array('userid' => $zuihou_userid + $x))->find();

			if (is_array($user)) {
				foreach (C('coin_list') as $k => $v) {
					if (0 < $user[$v['name'] . 'd']) {
						foreach($rmbarr as $rmb){
							$rs = array();
							$rs = M('Trade')->where(array(
								'market' => $v['name']."_".$rmb,
								'status' => 0,
								'userid' => $user['userid']
								))->count();

							if (empty($rs)) {
								M('UserCoin')->where(array('userid' => $user['userid']))->setField($v['name'] . 'd', 0);
							}
						}
					}
				}
			}
		}
	}

	public function move()
	{
		M('Trade')->where(array('status' => '-1'))->setField('status', 1);

		foreach (C('market') as $k => $v) {
			$this->setMarket($v['name']);
		}

		foreach (C('coin_list') as $k => $v) {
			$this->setcoin($v['name']);
		}
		echo 'ok';
	}

	public function setMarket($market = NULL)
	{
		if (!$market) {
			return null;
		}

		$market_json = M('Market_json')->where(array('name' => $market))->order('id desc')->find();

		if ($market_json) {
			$addtime = $market_json['addtime'] + 60;
		}
		else {
			$addtime = M('TradeLog')->where(array('market' => $market))->order('addtime asc')->find()['addtime'];
		}

		$t = $addtime;
		$start = mktime(0, 0, 0, date('m', $t), date('d', $t), date('Y', $t));
		$end = mktime(23, 59, 59, date('m', $t), date('d', $t), date('Y', $t));
		$trade_num = M('TradeLog')->where(array(
			'market'  => $market,
			'addtime' => array(
				array('egt', $start),
				array('elt', $end)
				)
			))->sum('num');

		if ($trade_num) {
			$trade_mum = M('TradeLog')->where(array(
				'market'  => $market,
				'addtime' => array(
					array('egt', $start),
					array('elt', $end)
					)
				))->sum('mum');
			$trade_fee_buy = M('TradeLog')->where(array(
				'market'  => $market,
				'addtime' => array(
					array('egt', $start),
					array('elt', $end)
					)
				))->sum('fee_buy');
			$trade_fee_sell = M('TradeLog')->where(array(
				'market'  => $market,
				'addtime' => array(
					array('egt', $start),
					array('elt', $end)
					)
				))->sum('fee_sell');
			$d = array($trade_num, $trade_mum, $trade_fee_buy, $trade_fee_sell);

			if (M('Market_json')->where(array('name' => $market, 'addtime' => $end))->find()) {
				M('Market_json')->where(array('name' => $market, 'addtime' => $end))->save(array('data' => json_encode($d)));
			}
			else {
				M('Market_json')->add(array('name' => $market, 'data' => json_encode($d), 'addtime' => $end));
			}
		}
		else {
			$d = null;

			if (M('Market_json')->where(array('name' => $market, 'data' => ''))->find()) {
				M('Market_json')->where(array('name' => $market, 'data' => ''))->save(array('addtime' => $end));
			}
			else {
				M('Market_json')->add(array('name' => $market, 'data' => '', 'addtime' => $end));
			}
		}
	}

	public function setcoin($coinname = NULL)
	{
		if (!$coinname) {
			return null;
		}

		if (C('coin')[$coinname]['type'] == 'qbb') {
			$dj_username = C('coin')[$coinname]['dj_yh'];
			$dj_password = C('coin')[$coinname]['dj_mm'];
			$dj_address = C('coin')[$coinname]['dj_zj'];
			$dj_port = C('coin')[$coinname]['dj_dk'];
			$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);
			$json = $CoinClient->getinfo() ;

			if (!isset($json['version']) || !$json['version']) {
				return null;
			}

			$data['trance_mum'] = $json['balance'];
		}
		else {
			$data['trance_mum'] = 0;
		}

		$market_json = M('CoinJson')->where(array('name' => $coinname))->order('id desc')->find();

		if ($market_json) {
			$addtime = $market_json['addtime'] + 60;
		}
		else {
			$addtime = M('Myzr')->where(array('name' => $coinname))->order('id asc')->find()['addtime'];
		}

		$t = $addtime;
		$start = mktime(0, 0, 0, date('m', $t), date('d', $t), date('Y', $t));
		$end = mktime(23, 59, 59, date('m', $t), date('d', $t), date('Y', $t));

		if ($addtime) {
			if ((time() + (60 * 60 * 24)) < $addtime) {
				return null;
			}

			$trade_num = M('UserCoin')->where(array(
				'addtime' => array(
					array('egt', $start),
					array('elt', $end)
					)
				))->sum($coinname);
			$trade_mum = M('UserCoin')->where(array(
				'addtime' => array(
					array('egt', $start),
					array('elt', $end)
					)
				))->sum($coinname . 'd');
			$aa = $trade_num + $trade_mum;

			if (C($coinname)['type'] == 'qbb') {
				$bb = $json['balance'];
			}
			else {
				$bb = 0;
			}

			$trade_fee_buy = M('Myzr')->where(array(
				'name'    => $coinname,
				'addtime' => array(
					array('egt', $start),
					array('elt', $end)
					)
				))->sum('fee');
			$trade_fee_sell = M('Myzc')->where(array(
				'name'    => $coinname,
				'addtime' => array(
					array('egt', $start),
					array('elt', $end)
					)
				))->sum('fee');
			$d = array($aa, $bb, $trade_fee_buy, $trade_fee_sell);

			if (M('CoinJson')->where(array('name' => $coinname, 'addtime' => $end))->find()) {
				M('CoinJson')->where(array('name' => $coinname, 'addtime' => $end))->save(array('data' => json_encode($d)));
			}
			else {
				M('CoinJson')->add(array('name' => $coinname, 'data' => json_encode($d), 'addtime' => $end));
			}
		}
	}

	public function paicuo()
	{
		$this_miniute = date("i");
		if(!is_file("./Database/lastzc.txt")){
			if($this_miniute%4==0){
				file_put_contents("./Database/lastzc.txt",time());
			}
		}
		echo 'ok';
	}

	public function houprice()
	{
		foreach (C('market') as $k => $v) {
			if (!$v['hou_price'] || (date('H', time()) == '00')) {
				$t = time();
				$start = mktime(0, 0, 0, date('m', $t), date('d', $t), date('Y', $t));
				$hou_price = M('TradeLog')->where(array(
					'market'  => $v['name'],
					'addtime' => array('lt', $start)
					))->order('id desc')->getField('price');

				if (!$hou_price) {
					$hou_price = M('TradeLog')->where(array('market' => $v['name']))->order('id asc')->getField('price');
				}

				M('Market')->where(array('name' => $v['name']))->setField('hou_price', $hou_price);
				S('home_market', null);
			}
		}
		echo "ok";
	}

	public function qianbao()
	{
		$coinList = M('Coin')->where(array('status' => 1,'type'=>'qbb','name'=>array('neq','eth')))->select();
		$time = time();
		foreach ($coinList as $k => $v) {
			$coin = $v['name'];
			if (empty($coin)) {
				continue;
			}

			$dj_username = C('coin')[$coin]['dj_yh'];
			$dj_password = C('coin')[$coin]['dj_mm'];
			$dj_address = C('coin')[$coin]['dj_zj'];
			$dj_port = C('coin')[$coin]['dj_dk'];
			$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);
			$json = $CoinClient->getinfo() ;
			if (!isset($json['version']) || !$json['version']) {
				continue;
			}

			$listtransactions = $CoinClient->listtransactions('*', 5000, 0);
			if($listtransactions!="nodata"){
				krsort($listtransactions);
				foreach ($listtransactions as $trans) {
					if (!$trans['account']) {
						continue;
					}

					if (!($user = M('User')->where(array('username' => $trans['account']))->find())) {
						continue;
					}

					if (M('Myzr')->where(array('txid' => $trans['txid'], 'status' => '1'))->find()) {
						continue;
					}

					if ($trans['category'] == 'receive') {
						$sfee = 0;
						$true_amount = $trans['amount'];

						try{
							if ($trans['confirmations'] < C('coin')[$coin]['zr_dz']) {
								if ($res = M('myzr')->where(array('txid' => $trans['txid']))->find()) {
									M('myzr')->save(array('id' => $res['id'], 'addtime' => $time, 'status' => intval($trans['confirmations'] - C('coin')[$coin]['zr_dz'])));
								}
								else {
									M('myzr')->add(array('userid' => $user['id'], 'username' => $trans['address'], 'coinname' => $coin, 'fee' => $sfee, 'txid' => $trans['txid'], 'num' => $true_amount, 'mum' => $trans['amount'], 'addtime' => $time, 'status' => intval($trans['confirmations'] - C('coin')[$coin]['zr_dz'])));
								}
								continue;
							}
						
							$mo = M();
							$mo->startTrans();
							//$mo->execute('lock tables  tw_user_coin write , tw_myzr  write ');
							
							$rs = array();
							$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $user['id']))->setInc($coin, $trans['amount']);

							if ($res = $mo->table('tw_myzr')->where(array('txid' => $trans['txid']))->find()) {
								$rs[] = $mo->table('tw_myzr')->save(array('id' => $res['id'], 'addtime' => $time, 'status' => 1));
							}
							else {
								$rs[] = $mo->table('tw_myzr')->add(array('userid' => $user['id'], 'username' => $trans['address'], 'coinname' => $coin, 'fee' => $sfee, 'txid' => $trans['txid'], 'num' => $true_amount, 'mum' => $trans['amount'], 'addtime' => $time, 'status' => 1));
							}

							if (check_arr($rs)) {
								$mo->commit();
							}
							else {
								throw new \Think\Exception('write databses fail');
							}
						}catch(\Think\Exception $e){
							file_put_contents("./Database/zrdebug.txt"," - ".$trans['txid']."|".$time." + ",FILE_APPEND);
							$mo->rollback();
						}
					}
				}
			}
			usleep(100000);
		}
	}

	public function syn_qianbao()
	{
	}

	public function tendency()
	{
		foreach (C('market') as $k => $v) {
			echo '----计算趋势----' . $v['name'] . '------------';
			$tendency_time = 4;
			$t = time();
			$tendency_str = $t - (24 * 60 * 60 * 3);
			$x = 0;

			for (; $x <= 18; $x++) {
				$na = $tendency_str + (60 * 60 * $tendency_time * $x);
				$nb = $tendency_str + (60 * 60 * $tendency_time * ($x + 1));
				$b = M('TradeLog')->where('addtime >=' . $na . ' and addtime <' . $nb . ' and market =\'' . $v['name'] . '\'')->max('price');

				if (!$b) {
					$b = 0;
				}

				$rs[] = array($na, $b);
			}
			$mo = M();
			$mo->table('tw_market')->where(array('name' => $v['name']))->setField('tendency', json_encode($rs));
			unset($rs);
			echo '计算成功!';
			echo "\n";
		}

		echo '趋势计算0k ' . "\n";
	}

	public function chart()
	{
		$time = time();
		foreach (C('market') as $k => $v) {
			$this->setTradeJson($v['name'],$time);
		}
		echo '计算行情0k ' . "\n";
	}

	public function setTradeJson($market,$time)
	{
		$timearr = array(1, 3, 5, 15, 30, 60, 120, 240, 360, 720, 1440, 4320, 10080);
		foreach ($timearr as $type){
			//先计算一分钟的
			if($type == 1){
				$na_one_start = $time-60;
				$na_one_end = $time;
				$sum_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->sum('num');
				if ($sum_one) {
					$sta_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
					$max_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->max('price');
					$min_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->min('price');
					$end_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
					
				}else{
					$last_record = M('TradeJson')->where(array('market' => $market, 'addtime' => array('lt',$na_one_start), 'type' => 1))->order('id desc')->find();
					if(!empty($last_record)){
						$last_data = json_decode($last_record['data'],true);
						$sum_one = 0.00;
						$sta_one = $max_one = $min_one = $end_one = $last_data[5];
					}
				}
				if(!empty($sta_one)){
					$d_one = array($na_one_start, $sum_one, $sta_one, $max_one, $min_one, $end_one);

					if (M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 1))->find()) {
						M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 1))->save(array('data' => json_encode($d_one)));
					}
					else {
						M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d_one), 'addtime' => $time, 'type' => 1));
					}
				}
			}elseif($type == 3){
				$this_minute = date("i",$time);
				if($this_minute%3 == 0){
					$na_one_start = $time-180;
					$na_one_end = $time;
					$sum_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->sum('num');
					if ($sum_one) {
						$sta_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->max('price');
						$min_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->min('price');
						$end_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						
					}else{
						$last_record = M('TradeJson')->where(array('market' => $market, 'addtime' => array('lt',$na_one_start), 'type' => 3))->order('id desc')->find();
						if(!empty($last_record)){
							$last_data = json_decode($last_record['data'],true);
							$sum_one = 0.00;
							$sta_one = $max_one = $min_one = $end_one = $last_data[5];
						}
					}
					if(!empty($sta_one)){
						$d_one = array($na_one_start, $sum_one, $sta_one, $max_one, $min_one, $end_one);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 3))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 3))->save(array('data' => json_encode($d_one)));
						}
						else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d_one), 'addtime' => $time, 'type' => 3));
						}
					}
				}
			}elseif($type == 5){
				$this_minute = date("i",$time);
				if($this_minute%5 == 0){
					$na_one_start = $time-300;
					$na_one_end = $time;
					$sum_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->sum('num');
					if ($sum_one) {
						$sta_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->max('price');
						$min_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->min('price');
						$end_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						
					}else{
						$last_record = M('TradeJson')->where(array('market' => $market, 'addtime' => array('lt',$na_one_start), 'type' => 5))->order('id desc')->find();
						if(!empty($last_record)){
							$last_data = json_decode($last_record['data'],true);
							$sum_one = 0.00;
							$sta_one = $max_one = $min_one = $end_one = $last_data[5];
						}
					}
					if(!empty($sta_one)){
						$d_one = array($na_one_start, $sum_one, $sta_one, $max_one, $min_one, $end_one);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 5))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 5))->save(array('data' => json_encode($d_one)));
						}
						else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d_one), 'addtime' => $time, 'type' => 5));
						}
					}
				}
			}elseif($type == 15){
				$this_minute = date("i",$time);
				if($this_minute%15 == 0){
					$na_one_start = $time-900;
					$na_one_end = $time;
					$sum_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->sum('num');
					if ($sum_one) {
						$sta_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->max('price');
						$min_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->min('price');
						$end_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						
					}else{
						$last_record = M('TradeJson')->where(array('market' => $market, 'addtime' => array('lt',$na_one_start), 'type' => 15))->order('id desc')->find();
						if(!empty($last_record)){
							$last_data = json_decode($last_record['data'],true);
							$sum_one = 0.00;
							$sta_one = $max_one = $min_one = $end_one = $last_data[5];
						}
					}
					if(!empty($sta_one)){
						$d_one = array($na_one_start, $sum_one, $sta_one, $max_one, $min_one, $end_one);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 15))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 15))->save(array('data' => json_encode($d_one)));
						}
						else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d_one), 'addtime' => $time, 'type' => 15));
						}
					}
				}
			}elseif($type == 30){
				$this_minute = date("i",$time);
				if($this_minute%30 == 0){
					$na_one_start = $time-1800;
					$na_one_end = $time;
					$sum_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->sum('num');
					if ($sum_one) {
						$sta_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->max('price');
						$min_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->min('price');
						$end_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						
					}else{
						$last_record = M('TradeJson')->where(array('market' => $market, 'addtime' => array('lt',$na_one_start), 'type' => 30))->order('id desc')->find();
						if(!empty($last_record)){
							$last_data = json_decode($last_record['data'],true);
							$sum_one = 0.00;
							$sta_one = $max_one = $min_one = $end_one = $last_data[5];
						}
					}
					if(!empty($sta_one)){
						$d_one = array($na_one_start, $sum_one, $sta_one, $max_one, $min_one, $end_one);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 30))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 30))->save(array('data' => json_encode($d_one)));
						}
						else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d_one), 'addtime' => $time, 'type' => 30));
						}
					}
				}
			}elseif($type == 60){
				$this_minute = date("i",$time);
				if($this_minute == 0){
					$na_one_start = $time-3600;
					$na_one_end = $time;
					$sum_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->sum('num');
					if ($sum_one) {
						$sta_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->max('price');
						$min_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->min('price');
						$end_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						
					}else{
						$last_record = M('TradeJson')->where(array('market' => $market, 'addtime' => array('lt',$na_one_start), 'type' => 60))->order('id desc')->find();
						if(!empty($last_record)){
							$last_data = json_decode($last_record['data'],true);
							$sum_one = 0.00;
							$sta_one = $max_one = $min_one = $end_one = $last_data[5];
						}
					}
					if(!empty($sta_one)){
						$d_one = array($na_one_start, $sum_one, $sta_one, $max_one, $min_one, $end_one);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 60))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 60))->save(array('data' => json_encode($d_one)));
						}
						else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d_one), 'addtime' => $time, 'type' => 60));
						}
					}
				}
			}elseif($type == 120){
				$this_hour = date("H",$time);
				$this_minute = date("i",$time);
				if($this_hour%2==0 && $this_minute==0){
					$na_one_start = $time-7200;
					$na_one_end = $time;
					$sum_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->sum('num');
					if ($sum_one) {
						$sta_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->max('price');
						$min_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->min('price');
						$end_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						
					}else{
						$last_record = M('TradeJson')->where(array('market' => $market, 'addtime' => array('lt',$na_one_start), 'type' => 120))->order('id desc')->find();
						if(!empty($last_record)){
							$last_data = json_decode($last_record['data'],true);
							$sum_one = 0.00;
							$sta_one = $max_one = $min_one = $end_one = $last_data[5];
						}
					}
					if(!empty($sta_one)){
						$d_one = array($na_one_start, $sum_one, $sta_one, $max_one, $min_one, $end_one);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 120))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 120))->save(array('data' => json_encode($d_one)));
						}
						else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d_one), 'addtime' => $time, 'type' => 120));
						}
					}
				}
			}elseif($type == 240){
				$this_hour = date("H",$time);
				$this_minute = date("i",$time);
				if($this_hour%4==0 && $this_minute==0){
					$na_one_start = $time-14400;
					$na_one_end = $time;
					$sum_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->sum('num');
					if ($sum_one) {
						$sta_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->max('price');
						$min_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->min('price');
						$end_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						
					}else{
						$last_record = M('TradeJson')->where(array('market' => $market, 'addtime' => array('lt',$na_one_start), 'type' => 240))->order('id desc')->find();
						if(!empty($last_record)){
							$last_data = json_decode($last_record['data'],true);
							$sum_one = 0.00;
							$sta_one = $max_one = $min_one = $end_one = $last_data[5];
						}
					}
					if(!empty($sta_one)){
						$d_one = array($na_one_start, $sum_one, $sta_one, $max_one, $min_one, $end_one);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 240))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 240))->save(array('data' => json_encode($d_one)));
						}
						else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d_one), 'addtime' => $time, 'type' => 240));
						}
					}
				}
			}elseif($type == 360){
				$this_hour = date("H",$time);
				$this_minute = date("i",$time);
				if($this_hour%6==0 && $this_minute==0){
					$na_one_start = $time-21600;
					$na_one_end = $time;
					$sum_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->sum('num');
					if ($sum_one) {
						$sta_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->max('price');
						$min_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->min('price');
						$end_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						
					}else{
						$last_record = M('TradeJson')->where(array('market' => $market, 'addtime' => array('lt',$na_one_start), 'type' => 360))->order('id desc')->find();
						if(!empty($last_record)){
							$last_data = json_decode($last_record['data'],true);
							$sum_one = 0.00;
							$sta_one = $max_one = $min_one = $end_one = $last_data[5];
						}
					}
					if(!empty($sta_one)){
						$d_one = array($na_one_start, $sum_one, $sta_one, $max_one, $min_one, $end_one);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 360))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 360))->save(array('data' => json_encode($d_one)));
						}
						else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d_one), 'addtime' => $time, 'type' => 360));
						}
					}
				}
			}elseif($type == 720){
				$this_hour = date("H",$time);
				$this_minute = date("i",$time);
				if($this_hour%12==0 && $this_minute==0){
					$na_one_start = $time-43200;
					$na_one_end = $time;
					$sum_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->sum('num');
					if ($sum_one) {
						$sta_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->max('price');
						$min_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->min('price');
						$end_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						
					}else{
						$last_record = M('TradeJson')->where(array('market' => $market, 'addtime' => array('lt',$na_one_start), 'type' => 720))->order('id desc')->find();
						if(!empty($last_record)){
							$last_data = json_decode($last_record['data'],true);
							$sum_one = 0.00;
							$sta_one = $max_one = $min_one = $end_one = $last_data[5];
						}
					}
					if(!empty($sta_one)){
						$d_one = array($na_one_start, $sum_one, $sta_one, $max_one, $min_one, $end_one);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 720))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 720))->save(array('data' => json_encode($d_one)));
						}
						else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d_one), 'addtime' => $time, 'type' => 720));
						}
					}
				}
			}elseif($type == 1440){
				$this_hour = date("H",$time);
				$this_minute = date("i",$time);
				if($this_hour==8 && $this_minute==0){
					$na_one_start = $time-86400;
					$na_one_end = $time;
					$sum_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->sum('num');
					if ($sum_one) {
						$sta_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->max('price');
						$min_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->min('price');
						$end_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						
					}else{
						$last_record = M('TradeJson')->where(array('market' => $market, 'addtime' => array('lt',$na_one_start), 'type' => 1440))->order('id desc')->find();
						if(!empty($last_record)){
							$last_data = json_decode($last_record['data'],true);
							$sum_one = 0.00;
							$sta_one = $max_one = $min_one = $end_one = $last_data[5];
						}
					}
					if(!empty($sta_one)){
						$d_one = array($na_one_start, $sum_one, $sta_one, $max_one, $min_one, $end_one);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 1440))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 1440))->save(array('data' => json_encode($d_one)));
						}
						else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d_one), 'addtime' => $time, 'type' => 1440));
						}
					}
				}
			}elseif($type == 4320){
				$biaodi = strtotime("2017-08-30 08:00:00");
				$this_hour = date("H",$time);
				$this_minute = date("i",$time);
				if($this_hour==8 && $this_minute==0 && intval(($time-$biaodi)/259200)%3==0){
					$na_one_start = $time-259200;
					$na_one_end = $time;
					$sum_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->sum('num');
					if ($sum_one) {
						$sta_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->max('price');
						$min_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->min('price');
						$end_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						
					}else{
						$last_record = M('TradeJson')->where(array('market' => $market, 'addtime' => array('lt',$na_one_start), 'type' => 4320))->order('id desc')->find();
						if(!empty($last_record)){
							$last_data = json_decode($last_record['data'],true);
							$sum_one = 0.00;
							$sta_one = $max_one = $min_one = $end_one = $last_data[5];
						}
					}
					if(!empty($sta_one)){
						$d_one = array($na_one_start, $sum_one, $sta_one, $max_one, $min_one, $end_one);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 4320))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 4320))->save(array('data' => json_encode($d_one)));
						}
						else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d_one), 'addtime' => $time, 'type' => 4320));
						}
					}
				}
			}elseif($type == 10080){
				$this_week = date("w",$time);
				$this_hour = date("H",$time);
				$this_minute = date("i",$time);
				if($this_week==0 && $this_hour==8 && $this_minute==0){
					$na_one_start = $time-604800;
					$na_one_end = $time;
					$sum_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->sum('num');
					if ($sum_one) {
						$sta_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->max('price');
						$min_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->min('price');
						$end_one = M('TradeLog')->where('addtime >=' . $na_one_start . ' and addtime <' . $na_one_end . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						
					}else{
						$last_record = M('TradeJson')->where(array('market' => $market, 'addtime' => array('lt',$na_one_start), 'type' => 10080))->order('id desc')->find();
						if(!empty($last_record)){
							$last_data = json_decode($last_record['data'],true);
							$sum_one = 0.00;
							$sta_one = $max_one = $min_one = $end_one = $last_data[5];
						}
					}
					if(!empty($sta_one)){
						$d_one = array($na_one_start, $sum_one, $sta_one, $max_one, $min_one, $end_one);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 10080))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $time, 'type' => 10080))->save(array('data' => json_encode($d_one)));
						}
						else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d_one), 'addtime' => $time, 'type' => 10080));
						}
					}
				}
			}
		}
	}
	
	public function myzcQueue()
	{
		if(!is_file("./Database/lastzc.txt")){
			return;
		}
		$last_run = file_get_contents("./Database/lastzc.txt");
		if(time()-$last_run<10){
			return;
		}
		file_put_contents("./Database/lastzc.txt",time());
		
		// $myzc = M('Myzc')->where(array('status' => 0,'coinname'=>array('neq','eth')))->order('addtime asc')->limit(10)->select();

		//查询是否有需要转账的
		$myzc = M('Myzc')->field('tw_myzc.*,tw_user_coin.ethb,tw_user.username as name')->join("tw_user_coin on tw_user_coin.userid=tw_myzc.userid")->join('tw_user on tw_user.id = tw_user_coin.userid')->where(array('tw_myzc.status' => 0))->order('addtime asc')->limit(10)->select();

		

		if (empty($myzc)) {
			return;
		}


		
		foreach($myzc as $val){
			if ($val['status']==1) {
				continue;
			}


			$username = M('User')->where(array('id' => $val['userid']))->getField('username');
			$coin = $val['coinname'];


			$dj_username = C('coin')[$coin]['dj_yh'];
			$dj_password = C('coin')[$coin]['dj_mm'];
			$dj_address = C('coin')[$coin]['dj_zj'];
			$dj_port = C('coin')[$coin]['dj_dk'];
			$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1,$coin);

			
			$json = $CoinClient->getinfo() ;

			if (!isset($json['version']) || !$json['version']) {
				echo '钱包链接失败！';
				continue;
			}

			$Coin = M('Coin')->where(array('name' => $val['coinname']))->find();

			if(empty($Coin['sh_zd']) && $coin != 'eth'){
				continue;
			}
			if(empty($Coin['status'])){
				continue;
			}
			$fee_user = M('UserCoin')->where(array($coin . 'b' => $Coin['zc_user']))->find();
			$user_coin = M('UserCoin')->where(array('userid' => $val['userid']))->find();
			$zhannei = M('UserCoin')->where(array($coin . 'b' => $val['username']))->find();
			$mo = M();
			$mo->startTrans();
			//$mo->execute('lock tables  tw_user_coin write  , tw_myzc write  , tw_myzr write , tw_myzc_fee write');
			$rs = array();

			if ($zhannei) {
				$rs[] = $mo->table('tw_myzr')->add(array('userid' => $zhannei['userid'], 'username' => $val['username'], 'coinname' => $coin, 'txid' => md5($val['username'] . $user_coin[$coin . 'b'] . time()), 'num' => $val['num'], 'fee' => $val['fee'], 'mum' => $val['mum'], 'addtime' => time(), 'status' => 1));
				$rs[] = $r = $mo->table('tw_user_coin')->where(array('userid' => $zhannei['userid']))->setInc($coin, $val['mum']);
			}

			if (!$fee_user['userid']) {
				$fee_user['userid'] = 0;
			}

			if (0 < $val['fee']) {
				$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $fee_user['userid'], 'username' => $Coin['zc_user'], 'coinname' => $coin, 'num' => $val['num'], 'fee' => $val['fee'], 'mum' => $val['mum'], 'type' => 2, 'addtime' => time(), 'status' => 1));

				if ($mo->table('tw_user_coin')->where(array($coin . 'b' => $Coin['zc_user']))->find()) {
					$rs[] = $mo->table('tw_user_coin')->where(array($coin . 'b' => $Coin['zc_user']))->setInc($coin, $val['fee']);
					debug(array('lastsql' => $mo->table('tw_user_coin')->getLastSql()), '新增费用');
				}
				else {
					$rs[] = $mo->table('tw_user_coin')->add(array($coin . 'b' => $Coin['zc_user'], $coin => $val['fee']));
				}
			}

			$rs[] = $mo->table('tw_myzc')->where(array('id' => $val['id']))->save(array('status' => 1));

			

			if (check_arr($rs)) {
				
				
				if($coin == 'eth'){

					$sendrs = $CoinClient->transaction($val['ethb'],$val['username'], (double) $val['mum'],$val['name']);
				}else{
					$sendrs = $CoinClient->sendtoaddress($val['username'], (double) $val['mum']);
				}
				


				if ($sendrs) {
					$mo->table('tw_myzc')->where(array('id'=>$val['id']))->save(array('txid'=>$sendrs));
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
					$mo->rollback();
					unlink("./Database/lastzc.txt");
					exit;
				}
				else {
					$mo->commit();
					echo '转账成功！';
				}
			}
			else {
				$mo->rollback();
				unlink("./Database/lastzc.txt");
				exit;
			}
		}
	}


	public function huilvgenxin(){
		$ch = curl_init();
	    $url = 'http://apis.baidu.com/netpopo/exchange/single?currency=CNY';
	    $header = array(
	        'apikey: 6e1d2a9927d592c40238c7b3268886c9',
	    );
	    // 添加apikey到header
	    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    // 执行HTTP请求
	    curl_setopt($ch , CURLOPT_URL , $url);
	    $res = curl_exec($ch);

	    dump($res);

	    $arr=json_decode($res, true);
	    // $arr=json_encode($arr);
	    // $usd=$arr['result']['list']['USD']['rate'];
	    $usd=$arr['result']['list']['USD']['rate'];
	    $usd_rmb=1/$usd;
	    $_POST['usd_rmb']=$usd_rmb;
	    if (M('Config')->where(array('id' => 1))->save($_POST)) {
			echo '修改成功！';
		}
		else {
			echo '修改失败';
		}
	    var_dump(json_decode($res));
	}


	//ETH区块查询
	public function eth_query(){

		ignore_user_abort(true);
		set_time_limit(0);

		//查询区块当前高度

		$CoinClient = CoinClient(null, null, '192.168.1.148', 8485,3,null,null,'eth');

		$height_obj = json_decode($CoinClient->blockNumber());
		
		$height = base_convert($height_obj->result,16,10);


		//获取区块上次已经写入的高度
		$start_height = file_get_contents('./Database/eth_height.txt');

		if(empty($start_height)){
			$start_height = 0;
		}

		$user = M('user_coin')->field('userid,ethb')->where("ethb !=''")->select();

		foreach ($user as $key => $value) {
			$user_info[$value['ethb']]=$value['userid'];
		}

		
		//循环以获取指定区块的详情
		for($i = $start_height;$i < $height;$i++){

			$result_json = json_decode($CoinClient->getBlockByNumber(array('0x'.dechex($i),true)));


			$result = $result_json->result;

			$num = count($result->transactions);


			//如果这个区块有交易记录 就循环并记录入库
			for($j = 0;$j < $num; $j++){

				$info['userid'] = $user_info[$result->transactions[$j]->to];
				$info['username']=$result->transactions[$j]->to;

				$info['fee'] = base_convert($result->transactions[$j]->gasPrice,16,10)/1000000000000000000;

				$info['coinname']='eth';
				$info['txid']=$result->transactions[$j]->hash;

				$info['num']=base_convert($result->transactions[$j]->value,16,10)/1000000000000000000;
				$info['mum']=base_convert($result->transactions[$j]->value,16,10)/1000000000000000000;
				$info['addtime'] = base_convert($result->timestamp,16,10);
				$info['status'] = 1;

				//如果这个地址是我们的地址
				if(!empty($info['userid'])){
					M('user_coin')->where(array('userid' => $info['userid']))->setInc('eth',$info['num']);

					M('myzr')->where()->add($info);
				}
				

			}

		}

		//写入高度
		file_put_contents('./Database/eth_height.txt', $height);


	}


}
?>