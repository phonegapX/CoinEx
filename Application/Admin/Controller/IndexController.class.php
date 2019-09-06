<?php
namespace Admin\Controller;

class IndexController extends AdminController
{
	public function index()
	{
		$arr = array();
		$arr['reg_sum'] = M('User')->count();
		$arr['btc_num'] = M('UserCoin')->sum('btc') + M('UserCoin')->sum('btcd');
		$arr['eth_num'] = M('UserCoin')->sum('eth') + M('UserCoin')->sum('ethd');
		$arr['trance_mum'] = M('TradeLog')->sum('mum');

		if (10000 < $arr['trance_mum']) {
			$arr['trance_mum'] = round($arr['trance_mum'] / 10000) . '万';
		}

		if (100000000 < $arr['trance_mum']) {
			$arr['trance_mum'] = round($arr['trance_mum'] / 100000000) . '亿';
		}

		$arr['art_sum'] = M('Article')->count();
		$data = array();
		$time = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - (29 * 24 * 60 * 60);
		$i = 0;

		for (; $i < 30; $i++) {
			$a = $time;
			$time = $time + (60 * 60 * 24);
			$date = addtime($time - (60 * 60), 'Y-m-d');
			$mycz = M('Mycz')->where(array(
				'status'  => 2,
				'addtime' => array(
					array('gt', $a),
					array('lt', $time)
					)
				))->sum('num');
			$mytx = M('Mytx')->where(array(
				'status'  => 1,
				'addtime' => array(
					array('gt', $a),
					array('lt', $time)
					)
				))->sum('num');

			if ($mycz || $mytx) {
				$data['cztx'][] = array('date' => $date, 'charge' => $mycz, 'withdraw' => $mytx);
			}
		}

		$time = time() - (30 * 24 * 60 * 60);
		$i = 0;

		for (; $i < 60; $i++) {
			$a = $time;
			$time = $time + (60 * 60 * 24);
			$date = addtime($time, 'Y-m-d');
			$user = M('User')->where(array(
				'addtime' => array(
					array('gt', $a),
					array('lt', $time)
					)
				))->count();

			if ($user) {
				$data['reg'][] = array('date' => $date, 'sum' => $user);
			}
		}

		$this->assign('cztx', json_encode($data['cztx']));
		$this->assign('reg', json_encode($data['reg']));
		$this->assign('arr', $arr);

		$this->display();
	}

	public function coin($coinname = NULL)
	{
		if (!$coinname) {
			$coinname = C('xnb_mr');
		}

		if (empty($coinname)) {
			echo '请去设置--其他设置里面设置默认币种';
			exit();
		}

		if (!M('Coin')->where(array('name' => $coinname))->find()) {
			echo '币种不存在,请去设置里面添加币种，并清理缓存';
			exit();
		}

		$this->assign('coinname', $coinname);
		$data = array();
		$data['trance_b'] = M('UserCoin')->sum($coinname);
		$data['trance_s'] = M('UserCoin')->sum($coinname . 'd');
		$data['trance_num'] = $data['trance_b'] + $data['trance_s'];
		$data['trance_song'] = M('Myzr')->where(array('coinname' => $coinname))->sum('fee');
		$data['trance_fee'] = M('Myzc')->where(array('coinname' => $coinname))->sum('fee');

		if (C('coin')[$coinname]['type'] == 'qbb') {
			$dj_username = C('coin')[$coinname]['dj_yh'];
			$dj_password = C('coin')[$coinname]['dj_mm'];
			$dj_address = C('coin')[$coinname]['dj_zj'];
			$dj_port = C('coin')[$coinname]['dj_dk'];
			$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);
			$json = $CoinClient->getinfo() ;

			if (!isset($json['version']) || !$json['version']) {
				$this->error('钱包链接失败！');
			}

			$data['trance_mum'] = $json['balance'];
		}
		else {
			$data['trance_mum'] = 0;
		}

		$this->assign('data', $data);
		$market_json = M('CoinJson')->where(array('name' => $coinname))->order('id desc')->find();

		if ($market_json) {
			$addtime = $market_json['addtime'] + 60;
		}
		else {
			$addtime = M('Myzr')->where(array('name' => $coinname))->order('id asc')->find()['addtime'];
		}

		if (!$addtime) {
			$addtime = time();
		}

		$t = $addtime;
		$start = mktime(0, 0, 0, date('m', $t), date('d', $t), date('Y', $t));
		$end = mktime(23, 59, 59, date('m', $t), date('d', $t), date('Y', $t));

		if ($addtime) {
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

		$tradeJson = M('CoinJson')->where(array('name' => $coinname))->order('id asc')->limit(100)->select();

		foreach ($tradeJson as $k => $v) {
			if ((addtime($v['addtime']) != '---') && (14634049 < $v['addtime'])) {
				$date = addtime($v['addtime'], 'Y-m-d H:i:s');
				$json_data = json_decode($v['data'], true);
				$cztx[] = array('date' => $date, 'num' => $json_data[0], 'mum' => $json_data[1], 'fee_buy' => $json_data[2], 'fee_sell' => $json_data[3]);
			}
		}

		$this->assign('cztx', json_encode($cztx));
		$this->display();
	}

	public function coinSet($coinname = NULL)
	{
		if (!$coinname) {
			$this->error('参数错误！');
		}

		if (M('CoinJson')->where(array('name' => $coinname))->delete()) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function market($market = NULL)
	{
		if (!$market) {
			$market = C('market_mr');
		}

		if (!$market) {
			echo '请去设置--其他设置里面设置默认市场';
			exit();
		}

		$market = trim($market);
		$xnb = explode('_', $market)[0];
		$rmb = explode('_', $market)[1];
		$this->assign('xnb', $xnb);
		$this->assign('rmb', $rmb);
		$this->assign('market', $market);
		$data = array();
		$data['trance_num'] = M('TradeLog')->where(array('market' => $market))->sum('num');
		$data['trance_buyfee'] = M('TradeLog')->where(array('market' => $market))->sum('fee_buy');
		$data['trance_sellfee'] = M('TradeLog')->where(array('market' => $market))->sum('fee_sell');
		$data['trance_fee'] = $data['trance_buyfee'] + $data['trance_sellfee'];
		$data['trance_mum'] = M('TradeLog')->where(array('market' => $market))->sum('mum');
		$data['trance_ci'] = M('TradeLog')->where(array('market' => $market))->count();
		$market_json = M('MarketJson')->where(array('name' => $market))->order('id desc')->find();

		if ($market_json) {
			$addtime = $market_json['addtime'] + 60;
		}
		else {
			$addtime = M('TradeLog')->where(array('market' => $market))->order('addtime asc')->find()['addtime'];
		}

		if (!$addtime) {
			$addtime = time();
		}

		if ($addtime) {
			if ($addtime < (time() + (60 * 60 * 24))) {
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

					if (M('MarketJson')->where(array('name' => $market, 'addtime' => $end))->find()) {
						M('MarketJson')->where(array('name' => $market, 'addtime' => $end))->save(array('data' => json_encode($d)));
					}
					else {
						M('MarketJson')->add(array('name' => $market, 'data' => json_encode($d), 'addtime' => $end));
					}
				}
				else {
					$d = null;

					if (M('MarketJson')->where(array('name' => $market, 'data' => ''))->find()) {
						M('MarketJson')->where(array('name' => $market, 'data' => ''))->save(array('addtime' => $end));
					}
					else {
						M('MarketJson')->add(array('name' => $market, 'data' => '', 'addtime' => $end));
					}
				}
			}
		}

		$tradeJson = M('MarketJson')->where(array('name' => $market))->order('id asc')->limit(100)->select();

		foreach ($tradeJson as $k => $v) {
			if ((addtime($v['addtime']) != '---') && (14634049 < $v['addtime'])) {
				$date = addtime($v['addtime'] - (60 * 60 * 24), 'Y-m-d H:i:s');
				$json_data = json_decode($v['data'], true);
				$cztx[] = array('date' => $date, 'num' => $json_data[0], 'mum' => $json_data[1], 'fee_buy' => $json_data[2], 'fee_sell' => $json_data[3]);
			}
		}

		$this->assign('cztx', json_encode($cztx));
		$this->assign('data', $data);
		$this->display();
	}

	public function marketSet($market = NULL)
	{
		if (!$market) {
			$this->error('参数错误！');
		}

		if (M('MarketJson')->where(array('name' => $market))->delete()) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}
}

?>