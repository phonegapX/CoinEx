<?php

namespace Mobile\Controller;

class TradeController extends MobileController

{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","trade_coin_list","trade_page","market_condition","info","upTrade","specialty","chexiao","kline");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("非法操作！");
		}
	}
	
	public function __construct() {
		parent::__construct();
		$display_action=array("index","info","trade_coin_list","trade_page","market_condition");
		if(in_array(ACTION_NAME,$display_action)){
			$this->common_content();
		}
	}

	/*
	****币种交易界面
	*/
	public function trade_page($coin_name = NULL){

		// 过滤非法字符----------------S

		if (checkstr($coin_name)) {
			$this->error(L('sCommon_nsrdxxyw'));
		}

		// 过滤非法字符----------------E
		if(empty($coin_name)){
			$this -> error(L('sCommon_cscw'));
		}
		$market=$coin_name;
		$this->assign('market', $market);
		$xnb = explode('_', $market)[0];
		$rmb = explode('_', $market)[1];
		
		$xnbname=M('coin')->where(array('name'=>$xnb))->getField('title');

		$rmbname=M('coin')->where(array('name'=>$rmb))->getField('title');
		$yue=0;
		$xnbyue=0;
		if(userid()){
			$yue=M('user_coin')->where('userid='.userid())->getField($rmb);
			$xnbyue=M('user_coin')->where('userid='.userid())->getField($xnb);
		}
		$operator =1;
		
		$buy = M('trade')->where(array('status'=>0,'type'=>1,'market'=>$market))->field('price')->order('price desc')->limit(1)->find();
		$sell = M('trade')->where(array('status'=>0,'type'=>2,'market'=>$market))->field('price')->order('price asc')->limit(1)->find();

		$market_info=M('market')->where(array('name'=>$market))->find();
		
		if(empty($buy['price'])){
			$cjmr = M('TradeLog')->where(array('market' => $market, 'status' => 1))->order('addtime desc')->find();
			$this->assign('buy', number_format($cjmr['price']*$operator,$market_info['round'],'.',''));
			$this->assign('market_buy_price',number_format($cjmr['price']*$operator,$market_info['round'],'.',''));
		}else{
			$this->assign('buy', number_format($buy['price']*$operator,$market_info['round'],'.',''));
			$this->assign('market_buy_price',number_format($buy['price']*$operator,$market_info['round'],'.',''));
		}
		if(empty($sell['price'])){
			$cjmc = M('TradeLog')->where(array('market' => $market, 'status' => 1))->order('addtime desc')->find();
			$this->assign('sell', number_format($cjmc['price']*$operator,$market_info['round'],'.',''));
			$this->assign('market_sell_price',number_format($cjmc['price']*$operator,$market_info['round'],'.',''));
		}else{
			$this->assign('sell', number_format($sell['price']*$operator,$market_info['round'],'.',''));
			$this->assign('market_sell_price',number_format($sell['price']*$operator,$market_info['round'],'.',''));
		}

		$market = $market_info['name'];
		if ($market) {
			$topdata['info']['img'] = C('market')[$market]['xnbimg'];
			$topdata['info']['title'] = C('market')[$market]['title'];
			$topdata['info']['new_price'] = number_format(C('market')[$market]['new_price'],$market_info['round'],'.','');
			$topdata['info']['max_price'] = number_format(C('market')[$market]['max_price'],$market_info['round'],'.','');
			$topdata['info']['min_price'] = number_format(C('market')[$market]['min_price'],$market_info['round'],'.','');
			$topdata['info']['buy_price'] = number_format(C('market')[$market]['buy_price'],$market_info['round'],'.','');
			$topdata['info']['sell_price'] = number_format(C('market')[$market]['sell_price'],$market_info['round'],'.','');
			$topdata['info']['volume'] =number_format(C('market')[$market]['volume'],6,'.','');// C('market')[$market]['volume'];
			$topdata['info']['change'] = C('market')[$market]['change'];
		}
		$topdata['info']['cje'] = number_format($topdata['info']['volume']*$topdata['info']['new_price'],4,'.','');
		if ($topdata['info']['volume']) {
			if($topdata['info']['volume'] <= 1000){
				// $topdata['info']['volume'] = number_format($topdata['info']['volume'],8-$market_info['round'],'.','');
				$topdata['info']['volume'] = number_format($topdata['info']['volume'],6,'.','');
			}
			if ($topdata['info']['volume'] > 10000) {
				$topdata['info']['volume'] = (intval($topdata['info']['volume'] / 10000*100)/100) . "万";
			}
			if ($topdata['info']['volume'] > 100000000) {
				$topdata['info']['volume'] = (intval($topdata['info']['volume'] / 100000000*100)/100) . "亿";
			}
		}
		$this->assign('data', $topdata['info']);
		
		

		$this->assign('market_info', $market_info);
		$this->assign('market', $market);

		$this->assign('xnb', $xnb);
		$this->assign('xnbname', $xnbname);
		$this->assign('yue', $yue);
		$this->assign('xnbyue', $xnbyue);

		$this->assign('rmb', $rmb);
		$this->assign('rmbname', $rmbname);

		/*
		// 获取币种列表信息------S

		$map['name'] = $coin_name;
		$map['status'] = 1;

		$coin_info = M('Coin')->where($map)->find();
		$coin_info['market'] = $coin_info['name'].'_becc';
		$this->assign('coin_info', $coin_info);

		$userc = M('UserCoin')->where(array('userid' => userid()))->find();
		$userc['becc'] = intval($userc['becc']*100)/100;
		$this->assign('userc', $userc);
		
		$market_info=M('market')->where(array('name'=>$coin_info['market']))->find();
		$this->assign('market_info',$market_info);
		
		$buy = M('trade')->where(array('status'=>0,'type'=>1,'market'=>$market_info['name']))->field('price')->order('price desc')->limit(1)->find();
		$sell = M('trade')->where(array('status'=>0,'type'=>2,'market'=>$market_info['name']))->field('price')->order('price asc')->limit(1)->find();
		if(empty($buy['price'])){
			$cjmr = M('TradeLog')->where(array('market' => $market_info['name'], 'status' => 1))->order('addtime desc')->find();
			$this->assign('buy', number_format($cjmr['price'],$market_info['round'],'.',''));
			$this->assign('market_buy_price',number_format($cjmr['price'],$market_info['round'],'.',''));
		}else{
			$this->assign('buy', number_format($buy['price'],$market_info['round'],'.',''));
			$this->assign('market_buy_price',number_format($buy['price'],$market_info['round'],'.',''));
		}
		if(empty($sell['price'])){
			$cjmc = M('TradeLog')->where(array('market' => $market_info['name'], 'status' => 1))->order('addtime desc')->find();
			$this->assign('sell', number_format($cjmc['price'],$market_info['round'],'.',''));
			$this->assign('market_sell_price',number_format($cjmc['price'],$market_info['round'],'.',''));
		}else{
			$this->assign('sell', number_format($sell['price'],$market_info['round'],'.',''));
			$this->assign('market_sell_price',number_format($sell['price'],$market_info['round'],'.',''));
		}
		
		$market = $coin_info['market'];
		//顶部价格信息
		if ($market) {
			$xnb = explode('_', $market)[0];
			$rmb = explode('_', $market)[1];

			foreach (C('market') as $k => $v) {
				$v['xnb'] = explode('_', $v['name'])[0];
				$v['rmb'] = explode('_', $v['name'])[1];
				$topdata['list'][$k]['name'] = $v['name'];
				$topdata['list'][$k]['img'] = $v['xnbimg'];
				$topdata['list'][$k]['title'] = $v['title'];
				$topdata['list'][$k]['new_price'] = $v['new_price'];
			}

			$topdata['info']['img'] = C('market')[$market]['xnbimg'];
			$topdata['info']['title'] = C('market')[$market]['title'];
			$topdata['info']['new_price'] = C('market')[$market]['new_price'];
			$topdata['info']['max_price'] = C('market')[$market]['max_price'];
			$topdata['info']['min_price'] = C('market')[$market]['min_price'];
			$topdata['info']['buy_price'] = C('market')[$market]['buy_price'];
			$topdata['info']['sell_price'] = C('market')[$market]['sell_price'];
			$topdata['info']['volume'] = C('market')[$market]['volume'];
			$topdata['info']['change'] = C('market')[$market]['change'];
		}
		
		if ($topdata) {
			if ($topdata['info']['new_price']) {
				$this->assign('market_new_price',$topdata['info']['new_price']);
			}
			if ($topdata['info']['buy_price']) {
				$this->assign('sell_best_price',$topdata['info']['buy_price']);
			}
			if ($topdata['info']['sell_price']) {
				$this->assign('buy_best_price',$topdata['info']['sell_price']);
			}
			if ($topdata['info']['max_price']) {
				$this->assign('market_max_price',$topdata['info']['max_price']);
			}
			if ($topdata['info']['min_price']) {
				$this->assign('market_min_price',$topdata['info']['min_price']);
			}
			if ($topdata['info']['volume']) {
				if ($topdata['info']['volume'] <= 10000) {
					$topdata['info']['volume'] = number_format($topdata['info']['volume'], 8-C('market')[$market]['round'],'.','');
				}
				if ($topdata['info']['volume'] > 10000) {
					$topdata['info']['volume'] = (intval($topdata['info']['volume'] / 10000*100)/100) . "万";
				}
				if ($topdata['info']['volume'] > 100000000) {
					$topdata['info']['volume'] = (intval($topdata['info']['volume'] / 100000000*100)/100) . "亿";
				}
				$this->assign('market_volume',$topdata['info']['volume']);
			}
			if ($topdata['info']['change']) {
				$this->assign('market_change',$topdata['info']['change'] . "%");
			}
		}
		
		*/
		$user_info = M('User')->where(array('id'=>userid()))->find();
		$this->assign('user_info',$user_info);

		//生成token
		$mbuycoin_token = set_token('mbuycoin');
		$msellcoin_token = set_token('msellcoin');
		$mcancel_token = set_token('mcancel');
		$this->assign('mbuycoin_token',$mbuycoin_token);
		$this->assign('msellcoin_token',$msellcoin_token);
		$this->assign('mcancel_token',$mcancel_token);

		$this->display();
	}

	/*
	****币种列表页
	*/
	public function trade_coin_list(){

		// 获取币种列表信息------S
		/*
		$map = array();
		$map['name'] = array('NEQ','becc');
		$map['status'] = 1;
		
		$coin_list = M('Coin')->where($map)->order('id desc')->select();
		foreach($coin_list as $cname=>$coin){
			$market = $coin['name']."_becc";
			$market_info = M('market')->where(array('name'=>$market))->find();

			if ($market) {
				$xnb = explode('_', $market)[0];
				$rmb = explode('_', $market)[1];

				foreach (C('market') as $k => $v) {
					$v['xnb'] = explode('_', $v['name'])[0];
					$v['rmb'] = explode('_', $v['name'])[1];
					$topdata['list'][$k]['name'] = $v['name'];
					$topdata['list'][$k]['img'] = $v['xnbimg'];
					$topdata['list'][$k]['title'] = $v['title'];
					$topdata['list'][$k]['new_price'] = $v['new_price'];
				}

				$topdata['info']['img'] = C('market')[$market]['xnbimg'];
				$topdata['info']['title'] = C('market')[$market]['title'];
				$topdata['info']['new_price'] = number_format(C('market')[$market]['new_price'],$market_info['round'],'.','');
				$topdata['info']['max_price'] = number_format(C('market')[$market]['max_price'],$market_info['round'],'.','');
				$topdata['info']['min_price'] = number_format(C('market')[$market]['min_price'],$market_info['round'],'.','');
				$topdata['info']['buy_price'] = number_format(C('market')[$market]['buy_price'],$market_info['round'],'.','');
				$topdata['info']['sell_price'] = number_format(C('market')[$market]['sell_price'],$market_info['round'],'.','');
				$topdata['info']['volume'] = C('market')[$market]['volume'];
				$topdata['info']['change'] = C('market')[$market]['change'];
			}
			
			if ($topdata) {
				if ($topdata['info']['new_price']) {
					$coin_list[$cname]['market_new_price'] = $topdata['info']['new_price'];
				}
				if ($topdata['info']['max_price']) {
					$coin_list[$cname]['market_max_price'] = $topdata['info']['max_price'];
				}
				if ($topdata['info']['min_price']) {
					$coin_list[$cname]['market_min_price'] = $topdata['info']['min_price'];
				}
				$coin_list[$cname]['cje'] = number_format($topdata['info']['volume']*$topdata['info']['new_price'],4,'.','');
				if ($topdata['info']['volume']) {
					if ($topdata['info']['volume'] <= 10000) {
						$topdata['info']['volume'] = number_format($topdata['info']['volume'], 7-$market_info['round'],'.','');
					}
					if ($topdata['info']['volume'] > 10000) {
						$topdata['info']['volume'] = (intval($topdata['info']['volume'] / 10000*100)/100) . "万";
					}
					if ($topdata['info']['volume'] > 100000000) {
						$topdata['info']['volume'] = (intval($topdata['info']['volume'] / 100000000*100)/100) . "亿";
					}
				}
				$coin_list[$cname]['market_volume'] = $topdata['info']['volume'];
				$coin_list[$cname]['market_change'] = $topdata['info']['change'];
			}
		}
		$this->assign('coin_list', $coin_list);
		*/
		// 获取币种列表信息------E
		$market='ltc_btc';
		if ($market) {
			$xnb = explode('_', $market)[0];
			$rmb = explode('_', $market)[1];

			foreach (C('market') as $k => $v) {
				$v['xnb'] = explode('_', $v['name'])[0];
				$v['rmb'] = explode('_', $v['name'])[1];
				$topdata['list'][$k]['name'] = $v['name'];
				$topdata['list'][$k]['img'] = $v['xnbimg'];
				$topdata['list'][$k]['title'] = $v['title'];
				$topdata['list'][$k]['new_price'] = $v['new_price'];
			}

			$topdata['info']['img'] = C('market')[$market]['xnbimg'];
			$topdata['info']['title'] = C('market')[$market]['title'];
			$topdata['info']['new_price'] = number_format(C('market')[$market]['new_price'],$market_info['round'],'.','');
			$topdata['info']['max_price'] = number_format(C('market')[$market]['max_price'],$market_info['round'],'.','');
			$topdata['info']['min_price'] = number_format(C('market')[$market]['min_price'],$market_info['round'],'.','');
			$topdata['info']['buy_price'] = number_format(C('market')[$market]['buy_price'],$market_info['round'],'.','');
			$topdata['info']['sell_price'] = number_format(C('market')[$market]['sell_price'],$market_info['round'],'.','');
			$topdata['info']['volume'] = C('market')[$market]['volume'];
			$topdata['info']['change'] = C('market')[$market]['change'];
			$tmp_cjamount = C('market')[$market]['cjamount'];
			$tmp_rmb = explode("_",$market)[1];
			$topdata['info']['cjamount_btc'] = getoperator($tmp_rmb,'btc');
			$topdata['info']['cjamount_usd'] = getoperator($tmp_rmb,'usd');
			$topdata['info']['cjamount_rmb'] = getoperator($tmp_rmb,'rmb');
		}
		
		if ($topdata) {

			if ($topdata['info']['new_price']) {
				$this->assign('market_new_price',$topdata['info']['new_price']);

			}
			if ($topdata['info']['buy_price']) {
				$this->assign('sell_best_price',$topdata['info']['buy_price']);
			}
			if ($topdata['info']['sell_price']) {
				$this->assign('buy_best_price',$topdata['info']['sell_price']);
			}
			if ($topdata['info']['max_price']) {
				$this->assign('market_max_price',$topdata['info']['max_price']);
			}
			if ($topdata['info']['min_price']) {
				$this->assign('market_min_price',$topdata['info']['min_price']);
			}
			if ($topdata['info']['volume']) {
				$topdata['info']['volume'] = number_format($topdata['info']['volume'], 8-C('market')[$market]['round'],'.','');
				$this->assign('market_volume',$topdata['info']['volume']);
			}
			if ($topdata['info']['change']) {
				$this->assign('market_change',$topdata['info']['change'] . "%");
			}
			if ($topdata['info']['cjamount_btc']) {
				$this->assign('cjamount_btc',$topdata['info']['cjamount_btc']);
			}
			if ($topdata['info']['cjamount_usd']) {
				$this->assign('cjamount_usd',$topdata['info']['cjamount_usd']);
			}
			if ($topdata['info']['cjamount_rmb']) {
				$this->assign('cjamount_rmb',$topdata['info']['cjamount_rmb']);
			}
		}
		$this->display();
	}



	/*
	****币种交易行情界面
	*/
	public function market_condition($coin_name = null){

		if(empty($coin_name)){
			$this -> error(L('sCommon_cscw'));
		}

		$market=$coin_name;
		$market_info=M('market')->where(array('name'=>$market,'status'=>1))->find();
		//最新成交记录
		$tradeLog = M('TradeLog')->where(array('status' => 1, 'market' => $market))->order('id desc')->limit(20)->select();

		if ($tradeLog) {
			foreach ($tradeLog as $k => $v) {
				$data['tradelog'][$k]['addtime'] = date('m-d H:i:s', $v['addtime']);
				$data['tradelog'][$k]['type'] = $v['type'];
				$data['tradelog'][$k]['price'] = $v['price'] * 1;
				$data['tradelog'][$k]['num'] = round($v['num'], 6);
				$data['tradelog'][$k]['mum'] = round($v['mum'], 6);
			}
		}
		
		if($data['tradelog']){
			$list = '';
			foreach($data['tradelog'] as $val){
				if($val['type']==1){
					$list .= '<tr class="buy" title="'.L('sTrade_index_yzgjgmc').'" onclick="autotrust(this,\'buy\',2)"><td>'.L('sTrade_index_mr').'</td><td>'.$val['addtime'].'</td><td>'.(intval($val['price']*1000)/1000).'</td><td>'.(intval($val['num']*100)/100).'</td></tr>';
					// $list .= '<tr class="buy" title="'.L('sTrade_index_yzgjgmc').'" onclick="autotrust(this,\'buy\',2)"><td>'.L('sTrade_index_mr').'</td><td>'.$val['addtime'].'</td><td>'.(intval($val['price']*1000)/1000).'</td><td>'.(intval($val['num']*100)/100).'</td><td>'.(intval($val['mum']*100)/100).'</td></tr>';
				}else{
					$list .= '<tr class="sell" title="'.L('sTrade_index_yzgjgmr').'" onclick="autotrust(this,\'sell\',2)"><td>'.L('sTrade_index_mc').'</td><td>'.$val['addtime'].'</td><td>'.(intval($val['price']*1000)/1000).'</td><td>'.(intval($val['num']*100)/100).'</td></tr>';
					// $list .= '<tr class="sell" title="'.L('sTrade_index_yzgjgmr').'" onclick="autotrust(this,\'sell\',2)"><td>'.L('sTrade_index_mc').'</td><td>'.$val['addtime'].'</td><td>'.(intval($val['price']*1000)/1000).'</td><td>'.(intval($val['num']*100)/100).'</td><td>'.(intval($val['mum']*100)/100).'</td></tr>';
				}
			}
			$this->assign('orderlist',$list);
		}
		$this->assign('market',$market);
		$this->assign('market_info',$market_info);
		/*
		// 获取币种列表信息------S
		$map = array();
		$map['name'] = $coin_name;
		$map['status'] = 1;
		$coin_info = M('Coin')->where($map)->find();
		if(!$coin_info){
			$this -> error('参数错误');
		}
		$this->assign('coin_info', $coin_info);
		
		$market_info=M('market')->where(array('name'=>$coin_name."_becc"))->find();
		$this->assign('market_info',$market_info);
		*/
		$market = $market_info['name'];
		if ($market) {
			$topdata['info']['img'] = C('market')[$market]['xnbimg'];
			$topdata['info']['title'] = C('market')[$market]['title'];
			$topdata['info']['new_price'] = number_format(C('market')[$market]['new_price'],$market_info['round'],'.','');
			$topdata['info']['max_price'] = number_format(C('market')[$market]['max_price'],$market_info['round'],'.','');
			$topdata['info']['min_price'] = number_format(C('market')[$market]['min_price'],$market_info['round'],'.','');
			$topdata['info']['buy_price'] = number_format(C('market')[$market]['buy_price'],$market_info['round'],'.','');
			$topdata['info']['sell_price'] = number_format(C('market')[$market]['sell_price'],$market_info['round'],'.','');
			$topdata['info']['volume'] =number_format(C('market')[$market]['volume'],6,'.','');// C('market')[$market]['volume'];
			$topdata['info']['change'] = C('market')[$market]['change'];
		}
		$topdata['info']['cje'] = number_format($topdata['info']['volume']*$topdata['info']['new_price'],4,'.','');
		if ($topdata['info']['volume']) {
			if($topdata['info']['volume'] <= 1000){
				// $topdata['info']['volume'] = number_format($topdata['info']['volume'],8-$market_info['round'],'.','');
				$topdata['info']['volume'] = number_format($topdata['info']['volume'],6,'.','');
			}
			if ($topdata['info']['volume'] > 10000) {
				$topdata['info']['volume'] = (intval($topdata['info']['volume'] / 10000*100)/100) . "万";
			}
			if ($topdata['info']['volume'] > 100000000) {
				$topdata['info']['volume'] = (intval($topdata['info']['volume'] / 100000000*100)/100) . "亿";
			}
		}
		$this->assign('data', $topdata['info']);
		
		$this->display();
	}



	public function index($market = NULL)

	{

		if (!userid()) {

		}



		check_server();



		if (!$market) {

			$market = C('market_mr');

		}

		// TODO: SEPARATE

		// TODO: SEPARATE
		

		$this->assign('market', $market);

		$this->assign('xnb', explode('_', $market)[0]);

		$this->assign('rmb', explode('_', $market)[1]);

		$this->display();

	}


	public function info($market = NULL)

	{

		if (!userid()) {

		}



		check_server();



		if (!$market) {

			$market = C('market_mr');

		}

		// TODO: SEPARATE

		// TODO: SEPARATE



		$this->assign('market', $market);

		$this->assign('xnb', explode('_', $market)[0]);

		$this->assign('rmb', explode('_', $market)[1]);

		$this->display();

	}



	public function comment($market = NULL)

	{

		if (!userid()) {

		}



		check_server();



		if (!$market) {

			$market = C('market_mr');

		}



		if (!$market) {

			$market = C('market_mr');

		}

		// TODO: SEPARATE

		// TODO: SEPARATE



		$this->assign('market', $market);

		$this->assign('xnb', explode('_', $market)[0]);

		$this->assign('rmb', explode('_', $market)[1]);

		$where['coinname'] = explode('_', $market)[0];

		$Mobile = M('CoinComment');

		$count = $Mobile->where($where)->count();

		$Page = new \Think\Page($count, 15);

		$show = $Page->show();

		$list = $Mobile->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();



		foreach ($list as $k => $v) {

			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');

		}



		$this->assign('list', $list);

		$this->assign('page', $show);

		$this->display();

	}


	public function upTrade($paypassword = NULL, $market = NULL, $price, $num, $type, $mtoken)

	{
		$extra='';
		
		// 过滤非法字符----------------S

		if (checkstr($market) || checkstr($price) || checkstr($num) || checkstr($type) || checkstr($mtoken)) {
			$this->error(L('info_error'),$extra);
		}

		// 过滤非法字符----------------E

		if (!userid()) {

			$this->error(L('login_first'),$extra);

		}
		
		if($type==1){
			if(!session('mbuycointoken')) {
				set_token('mbuycoin');
			}
			if(!empty($mtoken)){
				$res = valid_token('mbuycoin',$mtoken);
				if(!$res){
					$this->error(L('cant_often'),session('mbuycointoken'));
				}
			}
			$extra=session('mbuycointoken');
		}
		
		if($type==2){
			if(!session('msellcointoken')) {
				set_token('msellcoin');
			}
			if(!empty($mtoken)){
				$res = valid_token('msellcoin',$mtoken);
				if(!$res){
					$this->error(L('cant_often'),session('msellcointoken'));
				}
			}
			$extra=session('msellcointoken');
		}

		// 处理开盘闭盘交易时间===开始
		$times = date('G',time());
		$minute = date('i',time());
		$minute = intval($minute);
		if(( $times <= C('market')[$market]['start_time'] && $minute< intval(C('market')[$market]['start_minute']))|| ( $times > C('market')[$market]['stop_time'] && $minute>= intval(C('market')[$market]['stop_minute'] ))){
			$this->error(L('sCommon_gsjbp'),$extra);
		}
		if(( $times <C('market')[$market]['start_time'] )|| $times > C('market')[$market]['stop_time']){
			$this->error(L('sCommon_gsjbp'),$extra);
		}else{
			if($times == C('market')[$market]['start_time']){
				if( $minute< intval(C('market')[$market]['start_minute'])){
					$this->error(L('sCommon_gsjbp'),$extra);
				}
			}elseif($times == C('market')[$market]['stop_time']){
				if(( $minute > C('market')[$market]['stop_minute'])){
					$this->error(L('sCommon_gsjbp'),$extra);
				}
			}
		}
		// 处理周六周日是否可交易===开始
		$weeks = date('N',time());
		if(!C('market')[$market]['agree6']){
			if($weeks == 6){
				$this->error(L('sCommon_gsjbp'),$extra);
			}
		}
		if(!C('market')[$market]['agree7']){
			if($weeks == 7){
				$this->error(L('sCommon_gsjbp'),$extra);
			}
		}
		//处理周六周日是否可交易===结束

		if (!check($price, 'double')) {

			$this->error(L('sTrade_index_jyjggscw'),$extra);

		}



		if (!check($num, 'double')) {

			$this->error(L('sTrade_index_jyslgscw'),$extra);

		}



		if (($type != 1) && ($type != 2)) {

			$this->error(L('sTrade_index_jylxgscw'),$extra);

		}



		$user = M('User')->where(array('id' => userid()))->find();
		if(shiming($user['id'])<1){
			// $this->error(L('sTrade_index_qxsmrz'),$extra);
		}


		// if ($user['tpwdsetting'] == 3) {

		// }



		// if ($user['tpwdsetting'] == 2) {

		// 	if (md5($paypassword) != $user['paypassword']) {

		// 		$this->error('交易密码错误！');

		// 	}

		// }



		// if ($user['tpwdsetting'] == 3) {

		// 	if (!session(userid() . 'tpwdsetting')) {

		// 		if (md5($paypassword) != $user['paypassword']) {

		// 			$this->error('交易密码错误！');

		// 		}

		// 		else {

		// 			session(userid() . 'tpwdsetting', 1);

		// 		}

		// 	}

		// }
		if (md5($paypassword) != $user['paypassword']) {

			$this->error(L('sCommon_jymmcw'),$extra);

		}



		if (!C('market')[$market]) {

			$this->error(L('sCommon_jysccw'),$extra);

		}

		else {

			$xnb = explode('_', $market)[0];

			$rmb = explode('_', $market)[1];

		}



		if (!C('market')[$market]['trade']) {

			$this->error(L('sCommon_dqscjzjy'),$extra);

		}

		// TODO: SEPARATE



		$price = round(floatval($price), C('market')[$market]['round']);



		if (!$price) {

			$this->error(L('sCommon_jyjgcw') . $price,$extra);

		}



		// $num = round($num, 8 - C('market')[$market]['round']);
		// 改成12
		$num = round($num, 12 - C('market')[$market]['round']);



		if (!check($num, 'double')) {

			$this->error(L('sCommon_jyslcw'),$extra);

		}



		if ($type == 1) {

			$min_price = (C('market')[$market]['buy_min'] ? C('market')[$market]['buy_min'] : 1.0E-8);

			$max_price = (C('market')[$market]['buy_max'] ? C('market')[$market]['buy_max'] : 10000000);

		}

		else if ($type == 2) {

			$min_price = (C('market')[$market]['sell_min'] ? C('market')[$market]['sell_min'] : 1.0E-8);

			$max_price = (C('market')[$market]['sell_max'] ? C('market')[$market]['sell_max'] : 10000000);

		}

		else {

			$this->error(L('sCommon_jylxcw'),$extra);

		}



		// if ($max_price < $price) {

		// 	$this->error('交易价格超过最大限制！');

		// }



		// if ($price < $min_price) {

		// 	$this->error('交易价格超过最小限制！');

		// }



		$hou_price = C('market')[$market]['hou_price'];



		if ($hou_price) {

			if (C('market')[$market]['zhang']) {

				// TODO: SEPARATE

				$zhang_price = round(($hou_price / 100) * (100 + C('market')[$market]['zhang']), C('market')[$market]['round']);



				if ($zhang_price < $price) {

					$this->error(L('sCommon_jyjgcgzfxz'),$extra);

				}

			}



			if (C('market')[$market]['die']) {

				// TODO: SEPARATE

				$die_price = round(($hou_price / 100) * (100 - C('market')[$market]['die']), C('market')[$market]['round']);



				if ($price < $die_price) {

					$this->error(L('sCommon_jyjgcgdfxz'),$extra);

				}

			}

		}



		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();



		if ($type == 1) {

			$trade_fee = C('market')[$market]['fee_buy'];



			if ($trade_fee) {

				$fee = round((($num * $price) / 100) * $trade_fee, 8);

				$mum = round((($num * $price) / 100) * (100 + $trade_fee), 8);

			}

			else {

				$fee = 0;

				$mum = round($num * $price, 8);

			}



			if ($user_coin[$rmb] < $mum) {

				$this->error(C('coin')[$rmb]['title'] . L('sCommon_yebz'),$extra);

			}

		}

		else if ($type == 2) {

			$trade_fee = C('market')[$market]['fee_sell'];



			if ($trade_fee) {

				$fee = round((($num * $price) / 100) * $trade_fee, 8);

				$mum = round((($num * $price) / 100) * (100 - $trade_fee), 8);

			}

			else {

				$fee = 0;

				$mum = round($num * $price, 8);

			}



			if ($user_coin[$xnb] < $num) {

				$this->error(C('coin')[$xnb]['title'] . L('sCommon_yebz'),$extra);

			}

		}

		else {

			$this->error(L('sCommon_jylxcw'),$extra);

		}



		if (C('coin')[$xnb]['fee_bili']) {

			if ($type == 2) {

				// TODO: SEPARATE

				$bili_user = round($user_coin[$xnb] + $user_coin[$xnb . 'd'], C('market')[$market]['round']);



				if ($bili_user) {

					// TODO: SEPARATE

					$bili_keyi = round(($bili_user / 100) * C('coin')[$xnb]['fee_bili'], C('market')[$market]['round']);



					if ($bili_keyi) {

						$bili_zheng = M()->query('select id,price,sum(num-deal)as nums from tw_trade where userid=' . userid() . ' and status=0 and type=2 and market =\'' . $market . '\' ;');



						if (!$bili_zheng[0]['nums']) {

							$bili_zheng[0]['nums'] = 0;

						}



						$bili_kegua = $bili_keyi - $bili_zheng[0]['nums'];



						if ($bili_kegua < 0) {

							$bili_kegua = 0;

						}



						if ($bili_kegua < $num) {

							$this->error(L('sTrade_uptrade_ndgdzlcgxtxz') . C('coin')[$xnb]['title'] . $bili_user . L('sTrade_uptrade_yjgd') . $bili_zheng[0]['nums'] . L('sTrade_uptrade_hkygd') . $bili_kegua . L('sTrade_uptrade_ge'), $extra, 5);

						}

					}

					else {

						$this->error(L('sTrade_uptrade_kjylcw'),$extra);

					}

				}

			}

		}



		if (C('coin')[$xnb]['fee_meitian']) {

			if ($type == 2) {

				$bili_user = round($user_coin[$xnb] + $user_coin[$xnb . 'd'], 8);



				if ($bili_user < 0) {

					$this->error(L('sTrade_uptrade_kjylcw'),$extra);

				}



				$kemai_bili = ($bili_user / 100) * C('coin')[$xnb]['fee_meitian'];



				if ($kemai_bili < 0) {

					$this->error(L('sTrade_uptrade_njrznzm') . C('coin')[$xnb]['title'] . 0 . L('sTrade_uptrade_ge'), $extra, 5);

				}



				$kaishi_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

				$jintian_sell = M('Trade')->where(array(

					'userid'  => userid(),

					'addtime' => array('egt', $kaishi_time),

					'type'    => 2,

					'status'  => array('neq', 2),

					'market'  => array('eq', $market)

					))->sum('num');



				if ($jintian_sell) {

					$kemai = $kemai_bili - $jintian_sell;

				}

				else {

					$kemai = $kemai_bili;

				}



				if ($kemai < $num) {

					if ($kemai < 0) {

						$kemai = 0;

					}



					$this->error(L('sTrade_uptrade_ngdcxnjrznzm') . C('coin')[$xnb]['title'] . $kemai . L('sTrade_uptrade_ge'), $extra, 5);

				}

			}

		}



		if (C('market')[$market]['trade_min']) {

			if ($mum < C('market')[$market]['trade_min']) {

				$this->error(L('sTrade_uptrade_jyzebnxy') . C('market')[$market]['trade_min'],$extra);

			}

		}



		if (C('market')[$market]['trade_max']) {

			if (C('market')[$market]['trade_max'] < $mum) {

				$this->error(L('sTrade_uptrade_jyzebndy') . C('market')[$market]['trade_max'],$extra);

			}

		}



		if (!$rmb) {

			$this->error(L('sCommon_sjcw'),$extra);

		}



		if (!$xnb) {

			$this->error(L('sCommon_sjcw'),$extra);

		}



		if (!$market) {

			$this->error(L('sCommon_sjcw'),$extra);

		}



		if (!$price) {

			$this->error(L('sCommon_sjcw'),$extra);

		}



		if (!$num) {

			$this->error(L('sCommon_sjcw'),$extra);

		}



		if (!$mum) {

			$this->error(L('sCommon_sjcw'),$extra);

		}



		if (!$type) {

			$this->error(L('sCommon_sjcw'),$extra);

		}
		$coin_info = M('Coin')->where(array('name' => C('coin')[$xnb]['name']))->find();

		try{
			$mo = M();

			$mo->startTrans();

			//$mo->execute('lock tables tw_trade write ,tw_user_coin write ,tw_finance write,tw_finance_log write,tw_user write');

			$rs = array();

			$user_coin = $mo->table('tw_user_coin')->where(array('userid' => userid()))->find();

			if ($type == 1) {

				if ($user_coin[$rmb] < $mum) {
					throw new \Think\Exception(C('coin')[$rmb]['title'] . L('sCommon_yebz'));
				}

				$finance = $mo->table('tw_finance')->where(array('userid' => userid()))->order('id desc')->find();

				$finance_num_user_coin = $mo->table('tw_user_coin')->where(array('userid' => userid()))->find();

				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => userid()))->setDec($rmb, $mum);

				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => userid()))->setInc($rmb . 'd', $mum);

				$rs[] = $finance_nameid = $mo->table('tw_trade')->add(array('userid' => userid(), 'market' => $market, 'price' => $price, 'num' => $num, 'mum' => $mum, 'fee' => $fee, 'type' => 1, 'addtime' => time(), 'status' => 0));

				$finance_mum_user_coin = $mo->table('tw_user_coin')->where(array('userid' => userid()))->find();

				$finance_hash = md5(userid() . $finance_num_user_coin[$rmb] . $finance_num_user_coin[$rmb.'d'] . $mum . $finance_mum_user_coin[$rmb] . $finance_mum_user_coin[$rmb.'d'] . MSCODE . 'tp3.net.cn');

				$finance_num = $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb.'d'];



				// 处理资金变更日志-----------------S

				$user_n_info = $mo->table('tw_user')->where(array('id' => userid()))->find();

				$mo->table('tw_finance_log')->add(array('username' => $user_n_info['username'], 'adminname' => $user_n_info['username'], 'addtime' => time(), 'plusminus' => 0, 'amount' => $mum, 'optype' => 18, 'cointype' => 1, 'old_amount' => $finance_num_user_coin[$rmb], 'new_amount' => $finance_mum_user_coin[$rmb], 'userid' => userid(), 'adminid' => userid(),'addip'=>get_client_ip(),'position'=>1));

				$mo->table('tw_finance_log')->add(array('username' => $user_n_info['username'], 'adminname' => $user_n_info['username'], 'addtime' => time(), 'plusminus' => 1, 'amount' => $mum, 'optype' => 20, 'cointype' => 1, 'old_amount' => $finance_num_user_coin[$rmb. 'd'], 'new_amount' => $finance_mum_user_coin[$rmb. 'd'], 'userid' => userid(), 'adminid' => userid(),'addip'=>get_client_ip(),'position'=>1));

				// 处理资金变更日志-----------------E

				if ($finance['mum'] < $finance_num) {

					$finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);

				}

				else {

					$finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);

				}

				$rs[] = $mo->table('tw_finance')->add(array('userid' => userid(), 'coinname' => $rmb, 'num_a' => $finance_num_user_coin[$rmb], 'num_b' => $finance_num_user_coin[$rmb.'d'], 'num' => $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb.'d'], 'fee' => $mum, 'type' => 2, 'name' => 'trade', 'nameid' => $finance_nameid, 'remark' => L('sTrade_uptrade_jyzxwtmrsc') . $market, 'mum_a' => $finance_mum_user_coin[$rmb], 'mum_b' => $finance_mum_user_coin[$rmb.'d'], 'mum' => $finance_mum_user_coin[$rmb] + $finance_mum_user_coin[$rmb.'d'], 'move' => $finance_hash, 'addtime' => time(), 'status' => $finance_status));

			}

			else if ($type == 2) {

				if ($user_coin[$xnb] < $num) {
					throw new \Think\Exception(C('coin')[$xnb]['title'] . L('sCommon_yebz'));
				}

				$fin_user_coin = $mo->table('tw_user_coin')->where(array('userid' => userid()))->find();//处理资金变更日志

				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => userid()))->setDec($xnb, $num);

				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => userid()))->setInc($xnb . 'd', $num);

				$rs[] = $mo->table('tw_trade')->add(array('userid' => userid(), 'market' => $market, 'price' => $price, 'num' => $num, 'mum' => $mum, 'fee' => $fee, 'type' => 2, 'addtime' => time(), 'status' => 0));

				$fin_user_coin_new = $mo->table('tw_user_coin')->where(array('userid' => userid()))->find();//处理资金变更日志

				$user_n_info = $mo->table('tw_user')->where(array('id' => userid()))->find();

				$mo->table('tw_finance_log')->add(array('username' => $user_n_info['username'], 'adminname' => $user_n_info['username'], 'addtime' => time(), 'plusminus' => 0, 'amount' => $num, 'optype' => 19, 'cointype' => $coin_info['id'], 'old_amount' => $fin_user_coin[$xnb], 'new_amount' => $fin_user_coin_new[$xnb], 'userid' => userid(), 'adminid' => userid(),'addip'=>get_client_ip(),'position'=>1));

				$mo->table('tw_finance_log')->add(array('username' => $user_n_info['username'], 'adminname' => $user_n_info['username'], 'addtime' => time(), 'plusminus' => 1, 'amount' => $num, 'optype' => 21, 'cointype' => $coin_info['id'], 'old_amount' => $fin_user_coin[$xnb. 'd'], 'new_amount' => $fin_user_coin_new[$xnb. 'd'], 'userid' => userid(), 'adminid' => userid(),'addip'=>get_client_ip(),'position'=>1));

				// 处理资金变更日志-----------------E

			}

			else {
				throw new \Think\Exception(L('sCommon_jylxcw'));
			}
		
			if (check_arr($rs)) {
				$mo->commit();
			}else {
				throw new \Think\Exception(L('sTrade_uptrade_jysb'));
			}
		}catch(\Think\Exception $e){
			$mo->rollback();
			$this->error(L('sTrade_uptrade_jysb'),$extra);
		}

		S('getDepth', null);
		
		$fp = fopen("./trading.txt", "w+");
		if(flock($fp,LOCK_EX | LOCK_NB))
		{
			$this->matchingTrade($market);
			flock($fp,LOCK_UN);
		}
		fclose($fp);
		
		$this->success(L('sTrade_uptrade_jycg'),$extra);
	}

		public function matchingTrade($market = NULL)

	{

		if (!$market) {

			return false;

		}

		else {

			$xnb = explode('_', $market)[0];

			$rmb = explode('_', $market)[1];

		}



		$fee_buy = C('market')[$market]['fee_buy'];

		$fee_sell = C('market')[$market]['fee_sell'];

		$invit_buy = C('market')[$market]['invit_buy'];

		$invit_sell = C('market')[$market]['invit_sell'];

		$invit_1 = C('market')[$market]['invit_1'];

		$invit_2 = C('market')[$market]['invit_2'];

		$invit_3 = C('market')[$market]['invit_3'];

		$mo = M();

		$new_trade_movesay = 0;



		for (; true; ) {

			$buy = $mo->table('tw_trade')->where(array('market' => $market, 'type' => 1, 'status' => 0))->order('price desc,id asc')->find();

			$sell = $mo->table('tw_trade')->where(array('market' => $market, 'type' => 2, 'status' => 0))->order('price asc,id asc')->find();



			if ($sell['id'] < $buy['id']) {

				$type = 1;

			}

			else {

				$type = 2;

			}



			if ($buy && $sell && (0 <= floatval($buy['price']) - floatval($sell['price']))) {

				$rs = array();



				if ($buy['num'] <= $buy['deal']) {

				}



				if ($sell['num'] <= $sell['deal']) {

				}



				$amount = min(round($buy['num'] - $buy['deal'], 8 - C('market')[$market]['round']), round($sell['num'] - $sell['deal'], 8 - C('market')[$market]['round']));

				$amount = round($amount, 8 - C('market')[$market]['round']);



				if ($amount <= 0) {

					$log = '错误1交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . "\n";

					$log .= 'ERR: 成交数量出错，数量是' . $amount;

					M('Trade')->where(array('id' => $buy['id']))->setField('status', 1);

					M('Trade')->where(array('id' => $sell['id']))->setField('status', 1);

					break;

				}



				if ($type == 1) {

					$price = $sell['price'];

				}

				else if ($type == 2) {

					$price = $buy['price'];

				}

				else {

					break;

				}



				if (!$price) {

					$log = '错误2交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . "\n";

					$log .= 'ERR: 成交价格出错，价格是' . $price;

					break;

				}

				else {

					// TODO: SEPARATE

					$price = round($price, C('market')[$market]['round']);

				}



				$mum = round($price * $amount, 8);



				if (!$mum) {

					$log = '错误3交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . "\n";

					$log .= 'ERR: 成交总额出错，总额是' . $mum;

					mlog($log);

					break;

				}

				else {

					$mum = round($mum, 8);

				}



				if ($fee_buy) {

					$buy_fee = round(($mum / 100) * $fee_buy, 8);

					$buy_save = round(($mum / 100) * (100 + $fee_buy), 8);

				}

				else {

					$buy_fee = 0;

					$buy_save = $mum;

				}



				if (!$buy_save) {

					$log = '错误4交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";

					$log .= 'ERR: 买家更新数量出错，更新数量是' . $buy_save;

					mlog($log);

					break;

				}



				if ($fee_sell) {

					$sell_fee = round(($mum / 100) * $fee_sell, 8);

					$sell_save = round(($mum / 100) * (100 - $fee_sell), 8);

				}

				else {

					$sell_fee = 0;

					$sell_save = $mum;

				}



				if (!$sell_save) {

					$log = '错误5交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";

					$log .= 'ERR: 卖家更新数量出错，更新数量是' . $sell_save;

					mlog($log);

					break;

				}



				$user_buy = M('UserCoin')->where(array('userid' => $buy['userid']))->find();



				if (!$user_buy[$rmb . 'd']) {

					$log = '错误6交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";

					$log .= 'ERR: 买家财产错误，冻结财产是' . $user_buy[$rmb . 'd'];

					mlog($log);

					break;

				}



				$user_sell = M('UserCoin')->where(array('userid' => $sell['userid']))->find();



				if (!$user_sell[$xnb . 'd']) {

					$log = '错误7交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";

					$log .= 'ERR: 卖家财产错误，冻结财产是' . $user_sell[$xnb . 'd'];

					mlog($log);

					break;

				}



				if ($user_buy[$rmb . 'd'] < 1.0E-8) {

					$log = '错误88交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";

					$log .= 'ERR: 买家更新冻结人民币出现错误,应该更新' . $buy_save . '账号余额' . $user_buy[$rmb . 'd'] . '进行错误处理';

					mlog($log);

					M('Trade')->where(array('id' => $buy['id']))->setField('status', 1);

					break;

				}



				if ($buy_save <= round($user_buy[$rmb . 'd'], 8)) {

					$save_buy_rmb = $buy_save;

				}

				else if ($buy_save <= round($user_buy[$rmb . 'd'], 8) + 1) {

					$save_buy_rmb = $user_buy[$rmb . 'd'];

					$log = '错误8交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";

					$log .= 'ERR: 买家更新冻结人民币出现误差,应该更新' . $buy_save . '账号余额' . $user_buy[$rmb . 'd'] . '实际更新' . $save_buy_rmb;

					mlog($log);

				}

				else {

					$log = '错误9交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";

					$log .= 'ERR: 买家更新冻结人民币出现错误,应该更新' . $buy_save . '账号余额' . $user_buy[$rmb . 'd'] . '进行错误处理';

					mlog($log);

					M('Trade')->where(array('id' => $buy['id']))->setField('status', 1);

					break;

				}

				// TODO: SEPARATE



				if ($amount <= round($user_sell[$xnb . 'd'], C('market')[$market]['round'])) {

					$save_sell_xnb = $amount;

				}

				else {

					// TODO: SEPARATE



					if ($amount <= round($user_sell[$xnb . 'd'], C('market')[$market]['round']) + 1) {

						$save_sell_xnb = $user_sell[$xnb . 'd'];

						$log = '错误10交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";

						$log .= 'ERR: 卖家更新冻结虚拟币出现误差,应该更新' . $amount . '账号余额' . $user_sell[$xnb . 'd'] . '实际更新' . $save_sell_xnb;

						mlog($log);

					}

					else {

						$log = '错误11交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";

						$log .= 'ERR: 卖家更新冻结虚拟币出现错误,应该更新' . $amount . '账号余额' . $user_sell[$xnb . 'd'] . '进行错误处理';

						mlog($log);

						M('Trade')->where(array('id' => $sell['id']))->setField('status', 1);

						break;

					}

				}



				if (!$save_buy_rmb) {

					$log = '错误12交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";

					$log .= 'ERR: 买家更新数量出错错误,更新数量是' . $save_buy_rmb;

					mlog($log);

					M('Trade')->where(array('id' => $buy['id']))->setField('status', 1);

					break;

				}



				if (!$save_sell_xnb) {

					$log = '错误13交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";

					$log .= 'ERR: 卖家更新数量出错错误,更新数量是' . $save_sell_xnb;

					mlog($log);

					M('Trade')->where(array('id' => $sell['id']))->setField('status', 1);

					break;

				}

				$coin_info = $mo->table('tw_coin')->where(array('name'=>$xnb))->field('id')->find();
				$cointype = $coin_info['id'];
				
				$mo->startTrans();

				//$mo->execute('lock tables tw_trade write ,tw_trade_log write ,tw_user write,tw_user_coin write ,tw_finance write,tw_finance_log write');

				$rs[] = $mo->table('tw_trade')->where(array('id' => $buy['id']))->setInc('deal', $amount);

				$rs[] = $mo->table('tw_trade')->where(array('id' => $sell['id']))->setInc('deal', $amount);

				$rs[] = $finance_nameid = $mo->table('tw_trade_log')->add(array('userid' => $buy['userid'], 'peerid' => $sell['userid'], 'market' => $market, 'price' => $price, 'num' => $amount, 'mum' => $mum, 'type' => $type, 'fee_buy' => $buy_fee, 'fee_sell' => $sell_fee, 'addtime' => time(), 'status' => 1));

				$fin_2 = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->find();//处理资金变更日志

				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->setInc($xnb, $amount);

				$finance = $mo->table('tw_finance')->where(array('userid' => $buy['userid']))->order('id desc')->find();

				$finance_num_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->find();


				// 处理资金变更日志--------买入类型---------S				

				// 获取用户信息
				$user_info = $mo->table('tw_user')->where(array('id' => $sell['userid']))->find();
				$user_2_info = $mo->table('tw_user')->where(array('id' => $buy['userid']))->find();

				$mo->table('tw_finance_log')->add(array('username' => $user_2_info['username'], 'adminname' => $user_info['username'], 'addtime' => time(), 'plusminus' => 1, 'amount' => $amount, 'optype' => 10, 'cointype' => $cointype, 'old_amount' => $fin_2[$xnb], 'new_amount' => $finance_num_user_coin[$xnb], 'userid' => $user_2_info['id'], 'adminid' => $user_info['id'], 'addip'=>get_client_ip(),'position'=>1));

				// 处理资金变更日志---------买入类型--------E

				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->setDec($rmb . 'd', $save_buy_rmb);

				$finance_mum_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->find();


				// 处理资金变更日志-------买入类型----------S

				$mo->table('tw_finance_log')->add(array('username' => $user_2_info['username'], 'adminname' => $user_info['username'], 'addtime' => time(), 'plusminus' => 0, 'amount' => $save_buy_rmb, 'optype' => 13, 'cointype' => 1, 'old_amount' => $fin_2[$rmb . 'd'], 'new_amount' => $finance_mum_user_coin[$rmb . 'd'], 'userid' => $user_2_info['id'], 'adminid' => $user_info['id'],'addip'=>get_client_ip(),'position'=>1));
				
				//增加买家累计购买金额
				$rs[] = $mo->table('tw_user')->where(array('id'=>$buy['userid']))->setInc('buy_sum',($save_buy_rmb-$buy_fee));
				$rs[] = $mo->table('tw_user')->where(array('id'=>$buy['userid']))->setInc('trade_sum',($save_buy_rmb-$buy_fee));

				// 处理资金变更日志-------买入类型----------E

				$finance_hash = md5($buy['userid'] . $finance_num_user_coin[$rmb] . $finance_num_user_coin[$rmb.'d'] . $mum . $finance_mum_user_coin[$rmb] . $finance_mum_user_coin[$rmb.'d'] . MSCODE . 'tp3.net.cn');

				$finance_num = $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb.'d'];



				if ($finance['mum'] < $finance_num) {

					$finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);

				}

				else {

					$finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);

				}



				$rs[] = $mo->table('tw_finance')->add(array('userid' => $buy['userid'], 'coinname' => $rmb, 'num_a' => $finance_num_user_coin[$rmb], 'num_b' => $finance_num_user_coin[$rmb.'d'], 'num' => $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb.'d'], 'fee' => $save_buy_rmb, 'type' => 2, 'name' => 'tradelog', 'nameid' => $finance_nameid, 'remark' => L('sTrade_uptrade_jyzxcgmrsc') . $market, 'mum_a' => $finance_mum_user_coin[$rmb], 'mum_b' => $finance_mum_user_coin[$rmb.'d'], 'mum' => $finance_mum_user_coin[$rmb] + $finance_mum_user_coin[$rmb.'d'], 'move' => $finance_hash, 'addtime' => time(), 'status' => $finance_status));

				$finance = $mo->table('tw_finance')->where(array('userid' => $buy['userid']))->order('id desc')->find();

				$finance_num_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $sell['userid']))->find();

				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $sell['userid']))->setInc($rmb, $sell_save);

				$finance_mum_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $sell['userid']))->find();

				// 处理资金变更日志-----------------S

				// 获取用户信息
				$user_s2_info = $mo->table('tw_user')->where(array('id' => $sell['userid']))->find();

				$mo->table('tw_finance_log')->add(array('username' => $user_s2_info['username'], 'adminname' => $user_info['username'], 'addtime' => time(), 'plusminus' => 1, 'amount' => $sell_save, 'optype' => 11, 'cointype' => 1, 'old_amount' => $finance_num_user_coin[$rmb], 'new_amount' => $finance_mum_user_coin[$rmb], 'userid' => $user_s2_info['id'], 'adminid' => $user_info['id'],'addip'=>get_client_ip(),'position'=>1));

				// 处理资金变更日志-----------------E

				//增加卖家累计卖出金额
				$rs[] = $mo->table('tw_user')->where(array('id'=>$sell['userid']))->setInc('sell_sum',($sell_save+$sell_fee));
				$rs[] = $mo->table('tw_user')->where(array('id'=>$sell['userid']))->setInc('trade_sum',($sell_save+$sell_fee));
				
				$finance_hash = md5($sell['userid'] . $finance_num_user_coin[$rmb] . $finance_num_user_coin[$rmb.'d'] . $mum . $finance_mum_user_coin[$rmb] . $finance_mum_user_coin[$rmb.'d'] . MSCODE . 'tp3.net.cn');

				$finance_num = $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb.'d'];



				if ($finance['mum'] < $finance_num) {

					$finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);

				}

				else {

					$finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);

				}

				$rs[] = $mo->table('tw_finance')->add(array('userid' => $sell['userid'], 'coinname' => $rmb, 'num_a' => $finance_num_user_coin[$rmb], 'num_b' => $finance_num_user_coin[$rmb.'d'], 'num' => $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb.'d'], 'fee' => $save_buy_rmb, 'type' => 1, 'name' => 'tradelog', 'nameid' => $finance_nameid, 'remark' => L('sTrade_uptrade_jyzxcgmcsc') . $market, 'mum_a' => $finance_mum_user_coin[$rmb], 'mum_b' => $finance_mum_user_coin[$rmb.'d'], 'mum' => $finance_mum_user_coin[$rmb] + $finance_mum_user_coin[$rmb.'d'], 'move' => $finance_hash, 'addtime' => time(), 'status' => $finance_status));

				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $sell['userid']))->setDec($xnb . 'd', $save_sell_xnb);


				$fin_s_coin = $mo->table('tw_user_coin')->where(array('userid' => $sell['userid']))->find();



				// 处理资金变更日志-----------------S

				$mo->table('tw_finance_log')->add(array('username' => $user_2_info['username'], 'adminname' => $user_info['username'], 'addtime' => time(), 'plusminus' => 0, 'amount' => $save_sell_xnb, 'optype' => 14, 'cointype' => $cointype, 'old_amount' => $fin_2[$xnb . 'd'], 'new_amount' => $fin_s_coin[$xnb . 'd'], 'userid' => $user_2_info['id'], 'adminid' => $user_info['id'],'addip'=>get_client_ip(),'position'=>1));

				// 处理资金变更日志-----------------E

				$buy_list = $mo->table('tw_trade')->where(array('id' => $buy['id'], 'status' => 0))->find();

				if ($buy_list) {

					if ($buy_list['num'] <= $buy_list['deal']) {

						$rs[] = $mo->table('tw_trade')->where(array('id' => $buy['id']))->setField('status', 1);

					}

				}

				$sell_list = $mo->table('tw_trade')->where(array('id' => $sell['id'], 'status' => 0))->find();

				if ($sell_list) {

					if ($sell_list['num'] <= $sell_list['deal']) {

						$rs[] = $mo->table('tw_trade')->where(array('id' => $sell['id']))->setField('status', 1);

					}

				}



				if ($price < $buy['price']) {

					$chajia_dong = round((($amount * $buy['price']) / 100) * (100 + $fee_buy), 8);

					$chajia_shiji = round((($amount * $price) / 100) * (100 + $fee_buy), 8);

					$chajia = round($chajia_dong - $chajia_shiji, 8);



					if ($chajia) {

						$chajia_user_buy = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->find();



						if ($chajia <= round($chajia_user_buy[$rmb . 'd'], 8)) {

							$chajia_save_buy_rmb = $chajia;

						}

						else if ($chajia <= round($chajia_user_buy[$rmb . 'd'], 8) + 1) {

							$chajia_save_buy_rmb = $chajia_user_buy[$rmb . 'd'];

							mlog('错误91交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount, '成交价格' . $price . '成交总额' . $mum . "\n");

							mlog('交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '成交数量' . $amount . '交易方式：' . $type . '卖家更新冻结虚拟币出现误差,应该更新' . $chajia . '账号余额' . $chajia_user_buy[$rmb . 'd'] . '实际更新' . $chajia_save_buy_rmb);

						}

						else {

							mlog('错误92交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount, '成交价格' . $price . '成交总额' . $mum . "\n");

							mlog('交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '成交数量' . $amount . '交易方式：' . $type . '卖家更新冻结虚拟币出现错误,应该更新' . $chajia . '账号余额' . $chajia_user_buy[$rmb . 'd'] . '进行错误处理');

							$mo->rollback();

							M('Trade')->where(array('id' => $buy['id']))->setField('status', 1);

							break;

						}



						if ($chajia_save_buy_rmb) {

							$fin_b2_coin = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->find();// 处理资金变更日志

							$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->setDec($rmb . 'd', $chajia_save_buy_rmb);

							$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->setInc($rmb, $chajia_save_buy_rmb);

							$fin_b1_coin = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->find();// 处理资金变更日志

							// 处理资金变更日志-----------------S

							// 人民币-买入差价可用
							$mo->table('tw_finance_log')->add(array('username' => $user_2_info['username'], 'adminname' => $user_info['username'], 'addtime' => time(), 'plusminus' => 1, 'amount' => $chajia_save_buy_rmb, 'optype' => 12, 'cointype' => 1, 'old_amount' => $fin_b2_coin[$rmb], 'new_amount' => $fin_b1_coin[$rmb], 'userid' => $user_2_info['id'], 'adminid' => $user_info['id'],'addip'=>get_client_ip(),'position'=>1));

							// 人民币-买入差价冻结
							$mo->table('tw_finance_log')->add(array('username' => $user_2_info['username'], 'adminname' => $user_info['username'], 'addtime' => time(), 'plusminus' => 0, 'amount' => $chajia_save_buy_rmb, 'optype' => 22, 'cointype' => 1, 'old_amount' => $fin_b2_coin[$rmb . 'd'], 'new_amount' => $fin_b1_coin[$rmb . 'd'], 'userid' => $user_2_info['id'], 'adminid' => $user_info['id'],'addip'=>get_client_ip(),'position'=>1));

							// 处理资金变更日志-----------------E

						}

					}

				}



				$you_buy = $mo->table('tw_trade')->where(array(

					'status' => 0,

					'userid' => $buy['userid']

					))->find();

				$you_sell = $mo->table('tw_trade')->where(array(

					'market' => array('eq', $market),

					'status' => 0,

					'userid' => $sell['userid']

					))->find();



				if (!$you_buy) {

					$you_user_buy = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->find();



					if (0 < $you_user_buy[$rmb . 'd']) {

						$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->setField($rmb . 'd', 0);

						$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->setInc($rmb, $you_user_buy[$rmb . 'd']);


						$fin_b3_coin = $mo->table('tw_user_coin')->where(array('userid' => $buy['userid']))->find();// 处理资金变更日志

						// 处理资金变更日志-----------------S

						$mo->table('tw_finance_log')->add(array('username' => $user_2_info['username'], 'adminname' => L('sTrade_uptrade_xt'), 'addtime' => time(), 'plusminus' => 0, 'amount' => $you_user_buy[$rmb . 'd'], 'optype' => 15, 'cointype' => 1, 'old_amount' => $you_user_buy[$rmb. 'd'], 'new_amount' => '0', 'userid' => $user_2_info['id'],'addip'=>get_client_ip(),'position'=>1));

						$mo->table('tw_finance_log')->add(array('username' => $user_2_info['username'], 'adminname' => L('sTrade_uptrade_xt'), 'addtime' => time(), 'plusminus' => 1, 'amount' => $you_user_buy[$rmb . 'd'], 'optype' => 23, 'cointype' => 1, 'old_amount' => $you_user_buy[$rmb], 'new_amount' => $fin_b3_coin[$rmb], 'userid' => $user_2_info['id'],'addip'=>get_client_ip(),'position'=>1));

						// 处理资金变更日志-----------------E

					}

				}



				if (!$you_sell) {

					$you_user_sell = $mo->table('tw_user_coin')->where(array('userid' => $sell['userid']))->find();

					if (0 < $you_user_sell[$xnb . 'd']) {

						$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $sell['userid']))->setField($xnb . 'd', 0);

						// $rs[] = $mo->table('tw_user_coin')->where(array('userid' => $sell['userid']))->setInc($rmb, $you_user_sell[$xnb . 'd']);
						$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $sell['userid']))->setInc($xnb, $you_user_sell[$xnb . 'd']);

						$fin_b4_coin = $mo->table('tw_user_coin')->where(array('userid' => $sell['userid']))->find();// 处理资金变更日志

						// 处理资金变更日志-----------------S

						// optype 动作类型 'cointype' => 1人民币类型 'plusminus' => 0减少类型

						$mo->table('tw_finance_log')->add(array('username' => $user_s2_info['username'], 'adminname' => L('sTrade_uptrade_xt'), 'addtime' => time(), 'plusminus' => 0, 'amount' => $you_user_sell[$xnb . 'd'], 'optype' => 15, 'cointype' => $cointype, 'old_amount' => $you_user_sell[$xnb. 'd'], 'new_amount' => '0', 'userid' => $user_s2_info['id'],'addip'=>get_client_ip(),'position'=>1));

						$mo->table('tw_finance_log')->add(array('username' => $user_s2_info['username'], 'adminname' => L('sTrade_uptrade_xt'), 'addtime' => time(), 'plusminus' => 1, 'amount' => $you_user_sell[$xnb . 'd'], 'optype' => 23, 'cointype' => $cointype, 'old_amount' => $you_user_sell[$xnb], 'new_amount' => $fin_b4_coin[$xnb], 'userid' => $user_s2_info['id'],'addip'=>get_client_ip(),'position'=>1));

						// 处理资金变更日志-----------------E

					}

				}



				$invit_buy_user = $mo->table('tw_user')->where(array('id' => $buy['userid']))->find();

				$invit_sell_user = $mo->table('tw_user')->where(array('id' => $sell['userid']))->find();

				if ($invit_buy) {

					if ($invit_1) {

						if ($buy_fee) {

							if ($invit_buy_user['invit_1']) {

								$invit_buy_save_1 = round(($buy_fee / 100) * $invit_1, 6);

								if ($invit_buy_save_1) {

									$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $invit_buy_user['invit_1']))->setInc($rmb, $invit_buy_save_1);

									$rs[] = $mo->table('tw_invit')->add(array('userid' => $invit_buy_user['invit_1'], 'invit' => $buy['userid'], 'name' => 1, 'type' => $coin_info['id'], 'num' => $amount, 'mum' => $mum, 'fee' => $invit_buy_save_1, 'addtime' => time(), 'status' => 1, 'buysell' => 1, 'rmb'=>$rmb));

								}
								
								//直系下属交易额奖励开始
								/*$invit_buy_save_1s = round(($buy_fee / 100) * floatval(C('tui_jy_jl')), 6);
								
								if ($invit_buy_save_1s) {
									
									$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $invit_buy_user['invit_1']))->setInc($rmb, $invit_buy_save_1s);

									$rs[] = $mo->table('tw_invit')->add(array('userid' => $invit_buy_user['invit_1'], 'invit' => $buy['userid'], 'name' => '直系下属奖励', 'type' => $coin_info['title'] . '买入交易奖励', 'num' => $amount, 'mum' => $mum, 'fee' => $invit_buy_save_1s, 'addtime' => time(), 'status' => 1, 'buysell' => 1));
					
								}*/
								//直系下属交易额奖励结束

							}

							if ($invit_buy_user['invit_2']) {

								$invit_buy_save_2 = round(($buy_fee / 100) * $invit_2, 6);

								if ($invit_buy_save_2) {

									$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $invit_buy_user['invit_2']))->setInc($rmb, $invit_buy_save_2);

									$rs[] = $mo->table('tw_invit')->add(array('userid' => $invit_buy_user['invit_2'], 'invit' => $buy['userid'], 'name' => 2, 'type' => $coin_info['id'], 'num' => $amount, 'mum' => $mum, 'fee' => $invit_buy_save_2, 'addtime' => time(), 'status' => 1, 'buysell' => 1, 'rmb'=>$rmb));

								}

							}

							if ($invit_buy_user['invit_3']) {

								$invit_buy_save_3 = round(($buy_fee / 100) * $invit_3, 6);

								if ($invit_buy_save_3) {

									$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $invit_buy_user['invit_3']))->setInc($rmb, $invit_buy_save_3);

									$rs[] = $mo->table('tw_invit')->add(array('userid' => $invit_buy_user['invit_3'], 'invit' => $buy['userid'], 'name' => 3, 'type' => $coin_info['id'], 'num' => $amount, 'mum' => $mum, 'fee' => $invit_buy_save_3, 'addtime' => time(), 'status' => 1, 'buysell' => 1, 'rmb'=>$rmb));

								}

							}

						}

					}

					if ($invit_sell) {

						if ($sell_fee) {

							if ($invit_sell_user['invit_1']) {

								$invit_sell_save_1 = round(($sell_fee / 100) * $invit_1, 6);

								if ($invit_sell_save_1) {

									$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $invit_sell_user['invit_1']))->setInc($rmb, $invit_sell_save_1);

									$rs[] = $mo->table('tw_invit')->add(array('userid' => $invit_sell_user['invit_1'], 'invit' => $sell['userid'], 'name' => 1, 'type' => $coin_info['id'], 'num' => $amount, 'mum' => $mum, 'fee' => $invit_sell_save_1, 'addtime' => time(), 'status' => 1, 'buysell' => 2, 'rmb'=>$rmb));

								}
								
								//直系下属交易额奖励开始
								/*$invit_sell_save_1s = round(($sell_fee / 100) * floatval(C('tui_jy_jl')), 6);

								if ($invit_sell_save_1s) {

									$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $invit_sell_user['invit_1']))->setInc($rmb, $invit_sell_save_1s);

									$rs[] = $mo->table('tw_invit')->add(array('userid' => $invit_sell_user['invit_1'], 'invit' => $sell['userid'], 'name' => 4, 'type' => $market . '卖出交易奖励', 'num' => $amount, 'mum' => $mum, 'fee' => $invit_sell_save_1s, 'addtime' => time(), 'status' => 1, 'buysell' => 2));

								}*/
								//直系下属交易额奖励结束

							}

							if ($invit_sell_user['invit_2']) {

								$invit_sell_save_2 = round(($sell_fee / 100) * $invit_2, 6);

								if ($invit_sell_save_2) {

									$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $invit_sell_user['invit_2']))->setInc($rmb, $invit_sell_save_2);

									$rs[] = $mo->table('tw_invit')->add(array('userid' => $invit_sell_user['invit_2'], 'invit' => $sell['userid'], 'name' => 2, 'type' => $coin_info['id'], 'num' => $amount, 'mum' => $mum, 'fee' => $invit_sell_save_2, 'addtime' => time(), 'status' => 1, 'buysell' => 2, 'rmb'=>$rmb));

								}

							}



							if ($invit_sell_user['invit_3']) {

								$invit_sell_save_3 = round(($sell_fee / 100) * $invit_3, 6);

								if ($invit_sell_save_3) {

									$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $invit_sell_user['invit_3']))->setInc($rmb, $invit_sell_save_3);

									$rs[] = $mo->table('tw_invit')->add(array('userid' => $invit_sell_user['invit_3'], 'invit' => $sell['userid'], 'name' => 3, 'type' => $coin_info['id'], 'num' => $amount, 'mum' => $mum, 'fee' => $invit_sell_save_3, 'addtime' => time(), 'status' => 1, 'buysell' => 2, 'rmb'=>$rmb));

								}

							}

						}

					}

				}

				if (check_arr($rs)) {

					$mo->commit();

					$new_trade_movesay = 1;

					$coin = $xnb;

					S('allsum', null);

					S('getJsonTop' . $market, null);

					S('getTradelog' . $market, null);

					S('getDepth' . $market . '1', null);

					S('getDepth' . $market . '3', null);

					S('getDepth' . $market . '4', null);

					S('ChartgetJsonData' . $market, null);

					S('allcoin', null);

					S('trends', null);

				}

				else {

					$mo->rollback();
					
					break;

				}

			}

			else {

				break;

			}

			unset($rs);
		}

	}

	public function chexiao($id, $mtoken)

	{
		$extra = '';
		
		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error(L('sCommon_nsrdxxyw'),$extra);
		}

		// 过滤非法字符----------------E

		if (!userid()) {

			$this->error(L('login_first'),$extra);

		}

		if(!session('mcanceltoken')) {
			set_token('mcancel');
		}
		if(!empty($mtoken)){
			$res = valid_token('mcancel',$mtoken);
			if(!$res){
				$this->error(L('sCommon_qbypftj'),session('mcanceltoken'));
			}
			$extra=session('mcanceltoken');
		}

		if (!check($id, 'd')) {

			$this->error(L('sTrade_uptrade_qxzycxdwt'),$extra);

		}

		$trade = M('Trade')->where(array('id' => $id))->find();

		if (!$trade) {

			$this->error(L('weituo_para_err'),$extra);

		}

		if ($trade['userid'] != userid()) {

			$this->error(L('para_illega'),$extra);

		}
		
		if ($trade['status'] != 0) {
			$this->error(L('order_cant_che'),$extra);
		}

		$result = D('Trade')->chexiao($id);
		if(!empty($result[0])){
			$this->success($result[1],$extra);
		}else{
			$this->error($result[1],$extra);
		}
	}

	public function kline($coin_name = null){

		// 过滤非法字符----------------S

		if (checkstr($coin_name)) {
			$this->error(L('sCommon_nsrdxxyw'));
		}

		// 过滤非法字符----------------E

		if(!empty($coin_name)){
			//行情图选择
			$this->assign('kmap',$coin_name);
			$this->display();
		}
	}
	public function specialty($market = NULL)
	{
		// 过滤非法字符----------------S

		if (checkstr($market)) {
			$this->error(L('sCommon_nsrdxxyw'));
		}

		// 过滤非法字符----------------E
		
		if (!$market) {
			$market = C('market_mr');
		}
		$this->assign('market', $market);
		$arr = explode("_",$market);
		$xnb = $arr[0];
		$this->assign('xnb',$xnb);
		$rmb = $arr[1];
		$this->assign('rmb',$rmb);
		
		// if(LANG_SET == 'zh-cn'){
		// 	$this->assign('language',1);
		// }
		
		$this->display("/Trade/specialty");
	}
}
?>