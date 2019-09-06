<?php
namespace Mobile\Controller;

class FinanceController extends MobileController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","myzr","myzr_coin_list","myzr_log","myzc","myzcadd","myzc_coin_list","myuser_coin_list","myuseradd","upmyzc","mywt","mywt_coin_list","mycj","mycj_coin_list","mytj","mywd","myjp","myczlog","mycz_type_ajax");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("非法操作！");
		}
	}
	
	public function __construct() {
		parent::__construct();
		$display_action=array("index","myzr","myzc","mywt","mycj","mytj","mytp","myjp","mywd");
		if(in_array(ACTION_NAME,$display_action)){
			$this->common_content();
		}
	}
	
	public function mytj(){
		if (!userid()) {
			redirect('/Login/index');
		}
		
		$yjsum = M('Invit')->where(array('userid'=>userid()))->sum('fee');
		$this->assign('yjsum',remove_ling($yjsum));
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
			$Page->config = array('header'=>'Total','prev'=>'Previous','next'=>'Next','first'=>'First','last'=>'Last','theme'=>' %totalRow% %header% %nowPage%/%totalPage% Page %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
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
		$where['userid'] = userid();
		$count = M('Invit')->where($where)->count();
		$Page = new \Think\Page($count, 10);
		if(LANG_SET == 'en-us'){
			$Page->config = array('header'=>'Total','prev'=>'Previous','next'=>'Next','first'=>'First','last'=>'Last','theme'=>' %totalRow% %header% %nowPage%/%totalPage% Page %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		}
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
				$list[$k]['name'] = L('g_onedai');
			}elseif($v['name'] == 2){
				$list[$k]['name'] = L('g_twodai');//"二代"
			}elseif($v['name'] == 3){
				$list[$k]['name'] = L('g_thredai');//"三代"
			}
			$list[$k]['type'] = $coin_arr[$v['type']];
			if($v['buysell'] == 1){
				$list[$k]['buysell'] = L('buy_in');//"买入"
			}elseif($v['buysell'] == 2){
				$list[$k]['buysell'] = L('sell_out');//"卖出"
			}
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	// 充值记录
	public function myczlog($status = NULL)
	{
		if (!userid()) {
			redirect("/Login/index");
		}

		$myczType = M('MyczType')->where(array('status' => 1))->order('id desc')->select();

		foreach ($myczType as $k => $v) {
			$myczTypeList[$v['name']] = $v['title'];
		}

		$this->assign('myczTypeList', $myczTypeList);
		
		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();
		$user_coin['beccd'] = floor(($user_coin['beccd'] - $user_coin['shouyi_numd'])*1000)/1000;
		$user_coin['becc'] = floor(($user_coin['becc'] - $user_coin['shouyi_num'])*1000)/1000;
		$this->assign('user_coin', $user_coin);

		if (($status == 1) || ($status == 2) || ($status == 3) || ($status == 4)) {
			$where['status'] = $status - 1;
		}

		$this->assign('status', $status);
		$where['userid'] = userid();
		$count = M('Mycz')->where($where)->count();
		$Page = new \Think\Page1($count, 10);
		$show = $Page->show();
		$list = M('Mycz')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['type'] = M('MyczType')->where(array('name' => $v['type']))->getField('title');
			$list[$k]['num'] = (Num($v['num']) ? Num($v['num']) : '');
			$list[$k]['mum'] = (Num($v['mum']) ? Num($v['mum']) : '');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}


	// 充值方式ajax处理
	public function mycz_type_ajax($pp)
	{
		if($pp){
			$my = M('MyczType')->select();
			if($my){
				foreach ($my as $k => $v) {
					if($v['name'] == $pp){
						if($v['min']){
							echo $v['min'];die();
						}else{
							echo 0;die();
						}
					}
				}
				echo 0;
			}else{
				echo 0;	
			}
		}else{
			echo 0;
		}
	}

	// 转入虚拟币记录
	public function myzr_log($coin = null){
		$coin_info = M('Coin')->where(array('name' => $coin))->find();
		$this->assign('coin_info', $coin_info);
		$market_info = getmarket_frombi($coin,$rmb='btc');
		$where['userid'] = userid();
		$where['coinname'] = $coin;
		$Mzr = M('Myzr');
		$count = $Mzr->where($where)->count();
		$Page = new \Think\Page1($count, 10);
		if(LANG_SET == 'en-us'){
			$Page->config = array('header'=>'Totle','prev'=>'Previous','next'=>'Next','first'=>'First','last'=>'Last','theme'=>' %totalRow% %header% %nowPage%/%totalPage% Page</br> %upPage% %downPage% %first%  %end%');
		}
		$show = $Page->show();
		$list = $Mzr->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $key=>$val){
			$list[$key]['num'] = number_format($val['num'],8-$market_info['round'],'.','');
			$list[$key]['mum'] = number_format($val['mum'],8-$market_info['round'],'.','');
			$list[$key]['fee'] = number_format($val['fee'],8-$market_info['round'],'.','');
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function mycj_coin_list(){

		// 获取币种列表信息------S

		$map = array();
		//$map['name'] = array('NEQ','becc');
		$map['status'] = 1;

		$coin_list = M('Market')->where($map)->order('id asc')->select();

		$this->assign('coin_list', $coin_list);

		// 获取币种列表信息------E


		$this->display();
	}

	/*
	****币种列表页
	*/
	public function myzr_coin_list(){

		// 获取币种列表信息------S

		$map = array();
		$map['type'] = 'qbb';
		$map['status'] = 1;

		$coin_list = M('Coin')->where($map)->order('id asc')->select();

		$this->assign('coin_list', $coin_list);

		// 获取币种列表信息------E


		$this->display();
	}

	public function myzc_coin_list(){

		// 获取币种列表信息------S

		$map = array();
		$map['status'] = 1;

		$coin_list = M('Coin')->where($map)->order('id asc')->select();

		$this->assign('coin_list', $coin_list);

		// 获取币种列表信息------E


		$this->display();
	}


	public function myuser_coin_list(){

		// 获取币种列表信息------S

		$map = array();
		$map['name'] = array('NEQ','becc');
		$map['status'] = 1;

		$coin_list = M('Coin')->where($map)->order('id asc')->select();

		$this->assign('coin_list', $coin_list);

		// 获取币种列表信息------E


		$this->display();
	}


	/*
		转出虚拟币操作
	*/
	public function myzcadd($coin = NULL)
	{
		if (!userid()) {
			redirect("/Login/index.html");
		}

		if (C('coin')[$coin]) {
			$coin = trim($coin);
		}
		else {
			$coin = C('xnb_mr');
		}

		$this->assign('xnb', $coin);
		$Coin = M('Coin')->where(array('status' => 1))->select();

		foreach ($Coin as $k => $v) {
			$coin_list[$v['name']] = $v;
		}

		$this->assign('coin_list', $coin_list);
		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();
		$market_info = getmarket_frombi($coin,$rmb='btc');
		$round = pow(10,8-$market_info['round']);
		$user_coin[$coin] = floor($user_coin[$coin]*$round)/$round;
		$user_coin[$coin] = number_format($user_coin[$coin], 12-$market_info['round'],'.','');
		$this->assign('user_coin', $user_coin);

		if (!$coin_list[$coin]['zc_jz']) {
			$this->assign('zc_jz', L('g_dqbzjzzc'));
		}
		else {
			$userQianbaoList = M('UserQianbao')->where(array('userid' => userid(), 'status' => 1, 'coinname' => $coin))->order('id desc')->select();
			$this->assign('userQianbaoList', $userQianbaoList);
			$user = M('User')->where(array('id' => userid()))->find();
			$this->assign('user', $user);
		}

		$where['userid'] = userid();
		$where['coinname'] = $coin;
		$Module = M('Myzc');
		$count = $Module->where($where)->count();
		$Page = new \Think\Page1($count, 10);
		$show = $Page->show();

		$list = $Module->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		// 处理总计================================
		$lis = $Module->where($where)->select();
		$fees = 0;
		$nums = 0;
		$mums = 0;
		foreach ($lis as $k => $v) {
			$fees += $v['fee'];
			$nums += $v['num'];
			$mums += $v['mum'];
		}
		$this->assign('fees', $fees);
		$this->assign('nums', $nums);
		$this->assign('mums', $mums);
		// 处理总计================================

		$this->assign('coin', $coin);
		$this->assign('list', $list);
		$this->assign('page', $show);
		
		$mmyzc_token = set_token('mmyzc');
		$this->assign('mmyzc_token',$mmyzc_token);
		
		$this->display();
	}

	/*
	****币种列表页
	*/
	public function mywt_coin_list(){

		// 获取币种列表信息------S

		$map = array();
		//$map['name'] = array('NEQ','becc');
		$map['status'] = 1;

		$coin_list = M('Market')->where($map)->order('id asc')->select();

		$this->assign('coin_list', $coin_list);

		// 获取币种列表信息------E


		$this->display();
	}
	
	public function index()
	{
		if (!userid()) {
			redirect('/Login/index.html');
		}

		$CoinList = M('Coin')->where(array('status' => 1))->select();
		$UserCoin = M('UserCoin')->where(array('userid' => userid()))->find();

		$becc['zj'] = 0;

		foreach ($CoinList as $k => $v) {
			if ($v['name'] == 'becc') {
				$becc['ky'] = round($UserCoin[$v['name']], 2) * 1;
				$becc['dj'] = round($UserCoin[$v['name'] . 'd'], 2) * 1;
				$becc['zj'] = $becc['zj'] + $becc['ky'] + $becc['dj'];
				$becc['img'] = $v['img'];
			}
			else {
				$market_info = M('market')->where(array('name'=>$v['name']."_becc"))->find();
				$number_round = 8-$market_info['round'];
				$jia = !empty($market_info['new_price']) ? $market_info['new_price'] : 1;
				$round = pow(10,$number_round);
				$user_coin_xnb = floor($UserCoin[$v['name']]*$round)/$round;
				$user_coin_xnbz = floor(($UserCoin[$v['name']]+$UserCoin[$v['name'] . 'd'])*$round)/$round;
				$coinList[$v['name']] = array('name' => $v['name'], 'img' => $v['img'], 'title' => $v['title'] . '(' . strtoupper($v['name']) . ')', 'js_yw' => $v['js_yw'] . '(' . strtoupper($v['name']) . ')', 'xnb' => number_format($user_coin_xnb, $number_round,'.',''), 'xnbd' => number_format($UserCoin[$v['name'] . 'd'], $number_round,'.',''), 'xnbz' => number_format($user_coin_xnbz, $number_round,'.',''), 'jia' => $jia * 1, 'zhehe' => number_format(($UserCoin[$v['name']] + $UserCoin[$v['name'] . 'd']) * $jia, $market_info['round'],'.',''));
				$becc['zj'] = round($becc['zj'] + (($UserCoin[$v['name']] + $UserCoin[$v['name'] . 'd']) * $jia), 2) * 1;
			}
		}

		$this->assign('becc', $becc);
		$this->assign('coinList', $coinList);
		$this->display();
	}

	public function mycz($status = NULL)
	{
		if (!userid()) {
			redirect("/Login/index.html");
		}

		$myczType = M('MyczType')->where(array('status' => 1))->select();

		foreach ($myczType as $k => $v) {
			$myczTypeList[$v['name']] = $v['title'];
		}

		$this->assign('myczTypeList', $myczTypeList);
		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();
		$user_coin['becc'] = round($user_coin['becc'], 2);
		$user_coin['beccd'] = round($user_coin['beccd'], 2);
		$this->assign('user_coin', $user_coin);

		if (($status == 1) || ($status == 2) || ($status == 3) || ($status == 4)) {
			$where['status'] = $status - 1;
		}

		$this->assign('status', $status);
		$where['userid'] = userid();
		$count = M('Mycz')->where($where)->count();
		$Page = new \Think\Page1($count, 10);
		$show = $Page->show();
		$list = M('Mycz')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['type'] = M('MyczType')->where(array('name' => $v['type']))->getField('title');
			$list[$k]['num'] = (Num($v['num']) ? Num($v['num']) : '');
			$list[$k]['mum'] = (Num($v['mum']) ? Num($v['mum']) : '');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);

		$user_info=M('user')->where(array('id'=>userid()))->find();
		$this->assign('user_info', $user_info);

		$UserBankType = M('UserBankType')->where(array('status' => 1))->order('id desc')->select();
		$this->assign('UserBankType', $UserBankType);
		
		$this->display();
	}

	public function myczHuikuan($id = NULL)
	{
		if (!userid()) {
			$this->error(L('login_first'));
		}

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error(L('sCommon_nsrdxxyw'));
		}

		// 过滤非法字符----------------E

		if (!check($id, 'd')) {
			$this->error(L('sCommon_cscw'));
		}

		$mycz = M('Mycz')->where(array('id' => $id))->find();

		if (!$mycz) {
			$this->error(L('chong_order_no'));
		}

		if ($mycz['userid'] != userid()) {
			$this->error(L('sCommon_ffcz'));
		}

		if ($mycz['status'] != 0) {
			$this->error(L('order_chuli'));
		}

		$rs = M('Mycz')->where(array('id' => $id))->save(array('status' => 3));

		if ($rs) {
			$this->success(L('opera_suc'));
		}
		else {
			$this->error(L('opera_fail'));
		}
	}
	//获取充值手续费费率
	public function myczFee($cztype){
		if (!userid()) {
			$this->error(L('sCommon_qxdl'));
		}
		$cztype_list=M('mycz_type')->where(array('status'=>1))->select();
		$cztype_arr=array();
		foreach($cztype_list as $val){
			$cztype_arr[]=$val['name'];
		}
		if (!in_array($cztype, $cztype_arr)) {
			$this->error(L('chong_type_err'));
		}
		$fee=M('mycz_type')->where(array('status'=>1,'name'=>$cztype))->find();
		echo json_encode(array('fee'=>$fee['fee']));
		exit;
	}

	public function myczRes($id){
		if (!userid()) {
			$this->error(L('sCommon_qxdl'));
		}

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error(L('info_error'));
		}

		// 过滤非法字符----------------E

		if (!check($id, 'd')) {
			$this->error(L('para_error'));
		}

		$mycz = M('Mycz')->where(array('id' => $id))->find();

		if (!$mycz) {
			$this->error(L('chong_order_no'));
		}

		if ($mycz['userid'] != userid()) {
			$this->error(L('sCommon_ffcz'));
		}

		echo json_encode(array('status'=>$mycz['status'],'tradeno'=>$mycz['tradeno']));
		exit;
	}

	public function myczChakan($id = NULL)
	{
		if (!userid()) {
			$this->error(L('sCommon_qxdl'));
		}

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error(L('info_error'));
		}

		// 过滤非法字符----------------E

		if (!check($id, 'd')) {
			$this->error(L('para_error'));
		}

		$mycz = M('Mycz')->where(array('id' => $id))->find();

		if (!$mycz) {
			$this->error(L('chong_order_no'));
		}

		if ($mycz['userid'] != userid()) {
			$this->error(L('sCommon_ffcz'));
		}

		if ($mycz['status'] != 0) {
			$this->error(L('order_chuli'));
		}

		$rs = M('Mycz')->where(array('id' => $id))->save(array('status' => 3));

		if ($rs) {
			$this->success('', array('id' => $id));
		}
		else {
			$this->error(L('opera_fail'));
		}
	}

	public function myczUp($bankt = '', $type, $num, $mum, $truename, $aliaccount)
	{


		// 过滤非法字符----------------S

		if (checkstr($bankt) || checkstr($type) || checkstr($num) || checkstr($mum) || checkstr($truename) || checkstr($aliaccount)) {
			$this->error(L('sCommon_nsrdxxyw'));
		}

		// 过滤非法字符----------------E

		if (!check($type, 'n')) {
			$this->error(L('chong_type_format_err'));
		}

		if (!check($num, 'becc') || !check($mum, 'becc')) {
			$this->error(L('chong_sum_format_err'));
		}
		$myczType = M('MyczType')->where(array('name' => $type))->find();

		if (!$myczType) {
			$this->error(L('chong_type_no'));
		}

		if ($myczType['status'] != 1) {
			$this->error(L('chong_type_unopen'));
		}

		$mycz_min = ($myczType['min'] ? $myczType['min'] : 1);
		$mycz_max = ($myczType['max'] ? $myczType['max'] : 100000);

		if ($num < $mycz_min || $mum < $mycz_min) {
			$this->error(L('chong_money_cant_lt'). $mycz_min .L('yuan') );
		}

		if ($mycz_max < $num || $mycz_max < $mum) {
			$this->error(L('chong_money_cant_gt'). $mycz_max . L('yuan'));
		}

		for (; true; ) {
			$tradeno = tradeno();

			if (!M('Mycz')->where(array('tradeno' => $tradeno))->find()) {
				break;
			}
		}
		if($type=='alipay'){
			if(empty($truename)){
				$this->error(L('input_zfb_name'));
			}

			// 过滤非法字符----------------S

			if (checkstr($truename)||checkstr($aliaccount)) {
				$this->error(L('sCommon_nsrdxxyw'));
			}

			// 过滤非法字符----------------E

			if(!check($truename, 'chinese')){
				// $this->error(L('opera_fail')'真实姓名必须是汉字！');
			}
			if(empty($aliaccount)){
				$this->error(L('input_zfb'));
			}
			if (!check($aliaccount, 'mobile')) {
				if (!check($aliaccount, 'email')) {
					$this->error(L('zfb_format_err'));
				}
			}
		}elseif($type=='bank'){

			if(empty($bankt)){
				$this->error(L('choose_bank'));
			}

			if(empty($truename)){
				$this->error(L('input_bank_name'));
			}

			if(!check($truename, 'chinese')){
				// $this->error(L('true_name_hanzi'));
			}
			if(empty($aliaccount)){
				$this->error(L('input_bank_num'));
			}
			if(!is_numeric($aliaccount) || strlen(trim($aliaccount))<13){
				$this->error(L('bank_num_no_lt'));
			}
		}

		$mycz = M('Mycz')->add(array('userid' => userid(), 'bank' => $bankt, 'num' => $mum, 'mum' => $mum, 'type' => $type, 'tradeno' => $tradeno, 'addtime' => time(), 'status' => 0, 'alipay_truename'=>$truename, 'alipay_account'=>$aliaccount, 'fee'=>$myczType['fee']));
		
		if ($mycz) {
			if($type!='weixin'){
				$this->success(L('chong_order_suc'), array('id' => $mycz));
			}elseif($type=='weixin'){
				Vendor("Pay.JSAPI","",".php");
				$wxpay_obj=new \WxPayApi;
				$wxpayorder=new \WxPayUnifiedOrder;
				$wxpayorder->SetOut_trade_no($tradeno);
				$wxpayorder->SetBody('账户充值');
				$wxpayorder->SetTotal_fee($mum*100);
				$wxpayorder->SetTrade_type("NATIVE");
				$wxpayorder->SetProduct_id($mycz);
				$wxpayorder->SetNotify_url("http://xnb.huiz.net.cn/Home/Pay/mycz.html");
				$wxpayorder->SetSpbill_create_ip("120.77.221.213");
				$wxpayorder->SetFee_type("CNY");
				$wxpay=$wxpay_obj->unifiedOrder($wxpayorder);
				if(!empty($wxpay['code_url'])){
					Vendor("RandEx.RandEx","",".php");
					$rand = new \RandEx;
					$imgname = $rand->random(30,'all',0).".png";
					Vendor("PHPQRcode.phpqrcode","",".php");
					$level = 'L';
					$size = 4;
					$url = "./Upload/ewm/wxpay/".$imgname;
					\QRcode::png($wxpay['code_url'], $url, $level, $size);
					M('Mycz')->where(array('id'=>$mycz))->save(array('ewmname'=>$imgname));
					$res=array();
					$res['cztype']="wxpay";
					$res['status']=1;
					$res['id']=$mycz;
					echo json_encode($res);
					exit;
				}
			}else{
				$this->success(L('chong_order_suc'), array('id' => $mycz));
			}
		}
		else {
			$this->error(L('tixian_order_fail'));
		}
	}

	public function mytx($status = NULL)
	{
		if (!userid()) {
			redirect("/Login/index.html");
		}

		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');
		$email = M('User')->where(array('id' => userid()))->getField('email');
		if(empty($mobile) && empty($email)){
			$this->error(L('g_bindyxphoe'));
		}

		if ($mobile) {
			$mobile = substr_replace($mobile, '****', 3, 4);
		}
		$user = M('User')->where(array('id' => userid()))->find();
		if(shiming($user['id']) < 3){
			$this->error(L('sCommon_qxwcsmrz'),'/User/nameauth.html');
		}
		$this->assign('user', $user);
		$this->assign('mobile', $mobile);
		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();
		$user_coin['becc'] = intval($user_coin['becc']*100)/100;
		$user_coin['beccd'] = round($user_coin['beccd'], 2);
		$this->assign('user_coin', $user_coin);
		$userBankList = M('UserBank')->where(array('userid' => userid(), 'status' => 1))->order('id desc')->select();
		$this->assign('userBankList', $userBankList);

		if (($status == 1) || ($status == 2) || ($status == 3) || ($status == 4)) {
			$where['status'] = $status - 1;
		}

		$this->assign('status', $status);
		$where['userid'] = userid();
		$count = M('Mytx')->where($where)->count();
		$Page = new \Think\Page1($count, 10);
		$show = $Page->show();
		$list = M('Mytx')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['num'] = (Num($v['num']) ? Num($v['num']) : '');
			$list[$k]['fee'] = (Num($v['fee']) ? Num($v['fee']) : '');
			$list[$k]['fees'] = $list[$k]['fee']/$list[$k]['num']*100;
			$list[$k]['mum'] = (Num($v['mum']) ? Num($v['mum']) : '');
			$list[$k]['names'] = $v['bank'].' '.$v['bankcard'].' '.$v['truename'];
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		
		//生成token
		$mmytx_token = set_token('mmytx');
		$this->assign('mmytx_token',$mmytx_token);
		
		$this->display();
	}

	// 提现记录
	public function mytxlog($status = NULL)
	{
		if (!userid()) {
			redirect("/Login/index.html");
		}

		$this->assign('prompt_text', D('Text')->get_content('finance_mytx'));
		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');

		if ($mobile) {
			$mobile = substr_replace($mobile, '****', 3, 4);
		}
		else {
			$this->error(L('phone_first'));
		}

		$this->assign('mobile', $mobile);
		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();
		$user_coin['becc'] = round($user_coin['becc'], 2);
		$user_coin['beccd'] = round($user_coin['beccd'], 2);
		$this->assign('user_coin', $user_coin);
		$userBankList = M('UserBank')->where(array('userid' => userid(), 'status' => 1))->order('id desc')->select();
		$this->assign('userBankList', $userBankList);

		if (($status == 1) || ($status == 2) || ($status == 3) || ($status == 4)) {
			$where['status'] = $status - 1;
		}

		$this->assign('status', $status);
		$where['userid'] = userid();
		$count = M('Mytx')->where($where)->count();
		$Page = new \Think\Page1($count, 10);
		$show = $Page->show();
		$list = M('Mytx')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['num'] = (Num($v['num']) ? Num($v['num']) : '');
			$list[$k]['fee'] = (Num($v['fee']) ? Num($v['fee']) : '');
			$list[$k]['fees'] = $list[$k]['fee']/$list[$k]['num']*100;
			$list[$k]['mum'] = (Num($v['mum']) ? Num($v['mum']) : '');
			$list[$k]['names'] = $v['bank'].'　'.$v['bankcard'].'　'.$v['truename'];
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		
		$mtxcancel_token = set_token('mtxcancel');
		$this->assign('mtxcancel_token',$mtxcancel_token);
		
		$this->display();
	}

	public function mytxUp($mobile_verify, $num, $paypassword, $type, $token, $chkstyle, $email_verify)
	{

		$extra='';
		
		// 过滤非法字符----------------S

		if (checkstr($mobile_verify) || checkstr($num) || checkstr($type)|| checkstr($chkstyle)|| checkstr($email_verify)) {
			$this->error(L('info_error'),$extra);
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			$this->error(L('sCommon_qxdl'),$extra);
		}
		
		if(!session('mytxtoken')) {
			set_token('mytx');
		}
		if(!empty($token)){
			$res = valid_token('mytx',$token);
			if(!$res){
				$this->error(L('cant_often'),session('mytxtoken'));
			}
		}
		$extra=session('mytxtoken');
		
		$user_info = M('user')->where(array('id'=>userid()))->find();
		if(shiming($user_info['id']) < 3){
			$this->error(L('sCommon_qxwcsmrz'),'/User/nameauth.html');
		}
		if($chkstyle=='mobile'){
			if (!check($mobile_verify, 'd')) {
			 	$this->error(L('sms_format_err'),$extra);
			}

			if ($user_info['mobile'] != session('chkmobile')) {
				$this->error(L('sms_code_err'),$extra);
			}

			if ($mobile_verify != session('mytx_verify')) {
			 	$this->error(L('sms_code_err'),$extra);
			}
		}elseif($chkstyle=='email'){
			if (!check($email_verify, 'd')) {
			 	$this->error(L('sCommon_yxyzmgscw'),$extra);
			}

			if ($user_info['email'] != session('chkemail')) {
				$this->error(L('g_yx_verfy_error'),$extra);
			}

			if ($email_verify != session('emailmytx_verify')) {
			 	$this->error(L('g_yx_verfy_error'),$extra);
			}
		}

		if (!check($num, 'd')) {
			$this->error(L('tixian_money_err'),$extra);
		}

		if (!check($paypassword, 'password')) {
			$this->error(L('Finance_mmjc'),$extra);
		}

		if (!check($type, 'd')) {
			$this->error(L('tixian_type_err'),$extra);
		}

		$userCoin = M('UserCoin')->where(array('userid' => userid()))->find();

		if ($userCoin['becc'] < $num) {
			$this->error(L('rmb_buzu'),$extra);
		}

		$user = M('User')->where(array('id' => userid()))->find();

		if (md5($paypassword) != $user['paypassword']) {
			$this->error(L('sCommon_jymmcw'),$extra);
		}

		$userBank = M('UserBank')->where(array('id' => $type))->find();

		if (!$userBank) {
			$this->error(L('tixian_add_err'),$extra);
		}

		$mytx_min = (C('mytx_min') ? C('mytx_min') : 2);
		$mytx_max = (C('mytx_max') ? C('mytx_max') : 50000);
		$mytx_day_max = (C('mytx_day_max') ? C('mytx_day_max') : 200000);
		$start_time = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$end_time = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
		$today_tx_sum=M('mytx')->where(array('addtime'=>array('between',"$start_time,$end_time"),'status'=>array('neq',3),'userid' => session('userId')))->field('sum(num) as ttamount')->find();
		$today_tx_amount=intval($today_tx_sum['ttamount']);
		if($today_tx_amount+$num>$mytx_day_max){
			$this->error(L('tixian_exceed_max').($mytx_day_max-$today_tx_amount),$extra);
		}
		$mytx_bei = C('mytx_bei');
		$mytx_fee = C('mytx_fee');
		$mytx_fee_min = (C('mytx_fee_min') ? C('mytx_fee_min') : 0);
		if($mytx_min<=$mytx_fee_min){
			$mytx_min=$mytx_fee_min;
		}
		if ($num < $mytx_min) {
			$this->error(L('tixian_cant_lt'). $mytx_min . L('Finance_yuan'),$extra);
		}

		if ($mytx_max < $num) {
			$this->error(L('tixian_cant_gt'). $mytx_max .L('Finance_yuan') ,$extra);
		}

		if ($mytx_bei) {
			if ($num % $mytx_bei != 0) {
				$this->error(L('tixian_must').$mytx_bei . L('beishu'),$extra);
			}
		}

		$fee = round(($num / 100) * $mytx_fee, 2);
		if($fee<$mytx_fee_min){
			$fee = $mytx_fee_min;
		}
		$mum = round(($num- $fee), 2);
		try{
			$mo = M();
			$mo->startTrans();
			//$mo->execute('lock tables tw_mytx write , tw_user_coin write ,tw_finance write,tw_finance_log write');
			$rs = array();
			$finance = $mo->table('tw_finance')->where(array('userid' => userid()))->order('id desc')->find();
			$finance_num_user_coin = $mo->table('tw_user_coin')->where(array('userid' => userid()))->find();
			$rs[] = $mo->table('tw_user_coin')->where(array('userid' => userid()))->setDec('becc', $num);
			$rs[] = $finance_nameid = $mo->table('tw_mytx')->add(array('userid' => userid(), 'num' => $num, 'fee' => $fee, 'mum' => $mum, 'name' => $userBank['name'], 'truename' => $user['truename'], 'bank' => $userBank['bank'], 'bankprov' => $userBank['bankprov'], 'bankcity' => $userBank['bankcity'], 'bankaddr' => $userBank['bankaddr'], 'bankcard' => $userBank['bankcard'], 'addtime' => time(), 'status' => 0));
			$finance_mum_user_coin = $mo->table('tw_user_coin')->where(array('userid' => userid()))->find();
			$finance_hash = md5(userid() . $finance_num_user_coin['becc'] . $finance_num_user_coin['beccd'] . $mum . $finance_mum_user_coin['becc'] . $finance_mum_user_coin['beccd'] . MSCODE . 'tp3.net.cn');
			$finance_num = $finance_num_user_coin['becc'] + $finance_num_user_coin['beccd'];

			if ($finance['mum'] < $finance_num) {
				$finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);
			}
			else {
				$finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);
			}

			$rs[] = $mo->table('tw_finance')->add(array('userid' => userid(), 'coinname' => 'becc', 'num_a' => $finance_num_user_coin['becc'], 'num_b' => $finance_num_user_coin['beccd'], 'num' => $finance_num_user_coin['becc'] + $finance_num_user_coin['beccd'], 'fee' => $num, 'type' => 2, 'name' => 'mytx', 'nameid' => $finance_nameid, 'remark' => '人民币提现-申请提现', 'mum_a' => $finance_mum_user_coin['becc'], 'mum_b' => $finance_mum_user_coin['beccd'], 'mum' => $finance_mum_user_coin['becc'] + $finance_mum_user_coin['beccd'], 'move' => $finance_hash, 'addtime' => time(), 'status' => $finance_status));

			// 处理资金变更日志-----------------S

			// 'position' => 1前台-操作位置 optype=5 提现申请-动作类型 'cointype' => 1人民币-资金类型 'plusminus' => 0减少类型

			$mo->table('tw_finance_log')->add(array('username' => session('userName'), 'adminname' => session('userName'), 'addtime' => time(), 'plusminus' => 0, 'amount' => $num, 'optype' => 5, 'position' => 1, 'cointype' => 1, 'old_amount' => $finance_num_user_coin['becc'], 'new_amount' => $finance_mum_user_coin['becc'], 'userid' => session('userId'), 'adminid' => session('userId'),'addip'=>get_client_ip()));

			// 处理资金变更日志-----------------E

			if (check_arr($rs)) {
				session('mytx_verify', null);
				session('chkmobile', null);
				$mo->commit();
				$this->success(L('tixian_order_suc'),$extra);
			}
			else {
				throw new \Think\Exception(L('tixian_order_fail'));
			}
		}catch(\Think\Exception $e){
			$mo->rollback();
			$this->error(L('tixian_order_fail'),$extra);
		}
	}

	public function mytxChexiao($id,$mtoken)
	{
		$extra='';
		
		if (!userid()) {
			$this->error(L('sCommon_qxdl'),$extra);
		}

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error(L('sCommon_nsrdxxyw'),$extra);
		}

		// 过滤非法字符----------------E

		if(!session('mtxcanceltoken')) {
			set_token('mtxcancel');
		}
		if(!empty($mtoken)){
			$res = valid_token('mtxcancel',$mtoken);
			if(!$res){
				$this->error(L('cant_often'),session('mtxcanceltoken'));
			}
		}
		$extra=session('mtxcanceltoken');
		
		if (!check($id, 'd')) {
			$this->error(L('sCommon_cscw'),$extra);
		}

		$mytx = M('Mytx')->where(array('id' => $id))->find();

		if (!$mytx) {
			$this->error(L('tixian_order_no'),$extra);
		}

		if ($mytx['userid'] != userid()) {
			$this->error(L('sCommon_ffcz'),$extra);
		}

		if ($mytx['status'] != 0) {
			$this->error(L('order_cant_che'),$extra);
		}

		$mo = M();
		$mo->startTrans();
		//$mo->execute('lock tables tw_user_coin write,tw_mytx write,tw_finance write,tw_finance_log write');
		$rs = array();
		$finance = $mo->table('tw_finance')->where(array('userid' => $mytx['userid']))->order('id desc')->find();
		$finance_num_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $mytx['userid']))->find();
		$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $mytx['userid']))->setInc('becc', $mytx['num']);
		$rs[] = $mo->table('tw_mytx')->where(array('id' => $mytx['id']))->setField('status', 2);
		$finance_mum_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $mytx['userid']))->find();
		$finance_hash = md5($mytx['userid'] . $finance_num_user_coin['becc'] . $finance_num_user_coin['beccd'] . $mytx['num'] . $finance_mum_user_coin['becc'] . $finance_mum_user_coin['beccd'] . MSCODE . 'tp3.net.cn');
		$finance_num = $finance_num_user_coin['becc'] + $finance_num_user_coin['beccd'];

		if ($finance['mum'] < $finance_num) {
			$finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);
		}
		else {
			$finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);
		}

		$rs[] = $mo->table('tw_finance')->add(array('userid' => $mytx['userid'], 'coinname' => 'becc', 'num_a' => $finance_num_user_coin['becc'], 'num_b' => $finance_num_user_coin['beccd'], 'num' => $finance_num_user_coin['becc'] + $finance_num_user_coin['beccd'], 'fee' => $mytx['num'], 'type' => 1, 'name' => 'mytx', 'nameid' => $mytx['id'], 'remark' => '人民币提现-撤销提现', 'mum_a' => $finance_mum_user_coin['becc'], 'mum_b' => $finance_mum_user_coin['beccd'], 'mum' => $finance_mum_user_coin['becc'] + $finance_mum_user_coin['beccd'], 'move' => $finance_hash, 'addtime' => time(), 'status' => $finance_status));

		// 处理资金变更日志-----------------S

		$mo->table('tw_finance_log')->add(array('username' => session('userName'), 'adminname' => session('userName'), 'addtime' => time(), 'plusminus' => 1, 'amount' => $mytx['num'], 'optype' => 24, 'position' => 1, 'cointype' => 1, 'old_amount' => $finance_num_user_coin['becc'], 'new_amount' => $finance_mum_user_coin['becc'], 'userid' => session('userId'), 'adminid' => session('userId'),'addip'=>get_client_ip()));

		// 处理资金变更日志-----------------E

		if (check_arr($rs)) {
			$mo->commit();
			$this->success(L('opera_suc'),$extra);
		}
		else {
			$mo->rollback();
			$this->error(L('opera_fail'),$extra);
		}
	}

	public function myzr($coin = NULL)
	{
		if (!userid()) {
			redirect('/Login/index.html');
		}

		// 获取币种信息
		$coin_info = M('Coin')->where(array('name' => $coin))->find();
		if(!$coin_info){
			$this->error(L('g_bzerror'));
		}
		$this->assign('coin_info', $coin_info);
		$market_info = getmarket_frombi($coin,$rmb='btc');
		
		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();
		$round = pow(10,8-$market_info['round']);
		$user_coin[$coin] = floor($user_coin[$coin]*$round)/$round;
		$user_coin[$coin] = number_format($user_coin[$coin],6,'.','');
		$this->assign('user_coin', $user_coin);


		if (!$coin_info['zr_jz']) {
			$qianbao = L('g_bzzrr');
		}
		else {
			$qbdz = $coin . 'b';

			if (!$user_coin[$qbdz]) {
				if ($coin_info['type'] == 'rgb') {
					$qianbao = md5(username() . $coin);
					$rs = M('UserCoin')->where(array('userid' => userid()))->save(array($qbdz => $qianbao));

					if (!$rs) {
						$this->error(L('wallet_add_err'));
					}
				}

				if ($coin_info['type'] == 'qbb') {
					$dj_username = $coin_info['dj_yh'];
					$dj_password = $coin_info['dj_mm'];
					$dj_address = $coin_info['dj_zj'];
					$dj_port = $coin_info['dj_dk'];
					$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);
					$json = $CoinClient->getinfo() ;

					if (!isset($json['version']) || !$json['version']) {
						$this->error(L('wallet_link_err'));
					}

					$qianbao_addr = $CoinClient->getaddressesbyaccount(username());

					if (!is_array($qianbao_addr)) {
						$qianbao_ad = $CoinClient->getnewaddress(username());

						if (!$qianbao_ad) {
							$this->error(L('wallet_add_err'));
						}
						else {
							$qianbao = $qianbao_ad;
						}
					}
					else {
						$qianbao = $qianbao_addr[0];
					}

					if (!$qianbao) {
						$this->error(L('wallet_add_err').'2');
					}

					$rs = M('UserCoin')->where(array('userid' => userid()))->save(array($qbdz => $qianbao));

					if (!$rs) {
						$this->error(L('wallet_add_err').'3');
					}
				}
			}
			else {
				$qianbao = $user_coin[$coin . 'b'];
			}
		}

		$this->assign('qianbao', $qianbao);
		$this->display();
	}

	public function myzc($coin = NULL)
	{
		if (!userid()) {
			redirect('/Login/index.html');
		}

		$coin_info = M('Coin')->where(array('name' => $coin))->find();

		if(!$coin_info){
			$this->error(L('g_bzerror'));
		}

		$this->assign('coin_info', $coin_info);

		$market = $coin."_becc";
		$market_info = M("market")->where(array('name'=>$market))->find();
		$where = array();
		$where['userid'] = userid();
		$where['coinname'] = $coin;
		$where['to_user'] = array('neq','1' );
		$Mzc = M('Myzc');
		$count = $Mzc->where($where)->count();
		$Page = new \Think\Page1($count, 10);
		if(LANG_SET == 'en-us'){
			$Page->config = array('header'=>'Totle','prev'=>'Previous','next'=>'Next','first'=>'First','last'=>'Last','theme'=>' %totalRow% %header% %nowPage%/%totalPage% Page</br> %upPage% %downPage% %first%  %end%');
		}
		$show = $Page->show();
		$list = $Mzc->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $key=>$val){
			$list[$key]['num'] = number_format($val['num'],6,'.','');
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function myuseradd($coin = NULL)
	{
		if (!userid()) {
			redirect('/Login/index.html');
		}
		$coin_info = M('Coin')->where(array('name' => $coin))->find();
		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();
		$user_info = M('User')->where(array('id' => userid()))->find();
		if ($user_info['mobile']) {
			$user_info['mobile'] = substr_replace($user_info['mobile'], '****', 3, 4);
		}

		$this->assign('user_info', $user_info);
		$this->assign('user_coin', $user_coin);
		$this->assign('coin_info', $coin_info);
		$this->assign('coin', $coin);
		
		//生成token
		$mmyzcu_token = set_token('mmyzcu');
		$this->assign('mmyzcu_token',$mmyzcu_token);

		$this->display();
	}

	public function upmyzc($coin, $num, $addr, $paypassword, $mobile_verify='', $token, $chkstyle, $email_verify)
	{

		$extra='';
		
		// 过滤非法字符----------------S

		if (checkstr($coin) || checkstr($num) || checkstr($mobile_verify)) {
			$this->error(L('sCommon_nsrdxxyw'),$extra);
		}

		// 过滤非法字符----------------E

		if (!userid()) {
			$this->error(L('login_first'),$extra);
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
		if($chkstyle=='mobile'){
			if (!check($mobile_verify, 'd')) {
				$this->error(L('sms_format_err'),$extra);
			}
			
			$user_info = M('user')->where(array('id'=>userid()))->find();
			
			if ($user_info['mobile'] != session('chkmobile') ) {
				$this->error(L('sms_code_err'),$extra);
			}

			if ($mobile_verify != session('myzc_verify')) {
				$this->error(L('sms_code_err'),$extra);
			}
		}elseif($chkstyle=='email'){
			if (!check($email_verify, 'd')) {
				$this->error(L('sCommon_yxyzmgscw'),$extra);
			}
			
			$user_info = M('user')->where(array('id'=>userid()))->find();
			
			if ($user_info['email'] != session('chkemail')) {
				$this->error(L('g_yx_verfy_error'),$extra);
			}

			if ($email_verify != session('emailmyzc_verify')) {
				$this->error(L('g_yx_verfy_error'),$extra);
			}
		}

		$num = abs($num);

		if (!check($num, 'currency')) {
			$this->error(L('num_format_err'),$extra);
		}

		if (!check($addr, 'dw')) {
			$this->error(L('sFinance_myzc_qbdzgscw'),$extra);
		}

		if (!check($paypassword, 'password')) {
			$this->error(L('Finance_mmjc'),$extra);
		}

		if (!check($coin, 'n')) {
			$this->error(L('jifen_format_err'),$extra);
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
			$this->error(L('out_exceed_max'),$extra);
		}

		$user = M('User')->where(array('id' => userid()))->find();
		if(shiming($user['id']) < 3){
			$this->error(L('sCommon_qxwcsmrz'),$extra);
		}
		if (md5($paypassword) != $user['paypassword']) {
			$this->error(L('deal_code_err'),$extra);
		}

		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();

		if ($user_coin[$coin] < $num) {
			$this->error(L('g_use_yueno'),$extra);
		}

		$qbdz = $coin . 'b';
		$fee_user = M('UserCoin')->where(array($qbdz => $Coins['zc_user']))->find();

		if ($fee_user) {
			debug(L('fee_add') . $Coins['zc_user'] .L('have_fee'));
			//$fee = round($Coins['zc_fee'], 8);
			$fee = round($num*$Coins['zc_fee']/100, 8);
			$mum = round($num - $fee, 8);

			if ($mum < 0) {
				$this->error(L('fee_err'),$extra);
			}

			if ($fee < 0) {
				$this->error(L('sFinance_myzc_zcsxfszcw'),$extra);
			}
		}
		else {
			debug(L('fee_add') . $Coins['zc_user'] . L('no_fee'));
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
			//$mo->execute('lock tables  tw_user_coin write  , tw_myzc write  , tw_myzr write , tw_myzc_fee write,tw_finance_log write,tw_user read');

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
					$this->error('转出地址不存在！',$extra);
				}
				try{
					$mo = M();
					$mo->startTrans();
					//$mo->execute('lock tables  tw_user_coin write  , tw_myzc write  , tw_myzr write , tw_myzc_fee write,tw_finance_log write,tw_user read');

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
						session('chkmobile', null);
						$this->success('转账成功！',$extra);
					}else {
						throw new \Think\Exception('转账失败!');
					}
				}catch(\Think\Exception $e){
					$mo->rollback();
					$this->error('转账失败!',$extra);
				}
			}
			else {
				if($coin == 'eth' || $coin == 'ETH'){
				}else{
					$dj_username = $Coins['dj_yh'];
					$dj_password = $Coins['dj_mm'];
					$dj_address = $Coins['dj_zj'];
					$dj_port = $Coins['dj_dk'];
					$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);
					$json = $CoinClient->getinfo() ;

					if (!isset($json['version']) || !$json['version']) {
						$this->error('钱包链接失败！',$extra);
					}

					$valid_res = $CoinClient->validateaddress($addr);

					if (!$valid_res['isvalid']) {
						$this->error($addr . '不是一个有效的钱包地址！',$extra);
					}

					$auto_status = ($Coins['zc_zd'] && ($num < $Coins['zc_zd']) ? 1 : 0);

					if ($json['balance'] < $num) {
						$this->error('钱包余额不足',$extra);
					}
					try{

						$mo = M();
						$mo->startTrans();
						//$mo->execute('lock tables  tw_user_coin write  , tw_myzc write  , tw_myzr write , tw_myzc_fee write,tw_finance_log write,tw_user read');

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
									throw new \Think\Exception('转出失败!');
								}
							}else {
								$mo->commit();
								session('myzc_verify', null);
								session('chkmobile', null);
								$this->success('转出申请成功,请等待审核！',$extra);
							}
						}else {
							throw new \Think\Exception('转出失败!');
						}
					}catch(\Think\Exception $e){
						$mo->rollback();
						$this->error('转出失败!',$extra);
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
						$this->error('钱包服务器转出积分失败,请手动转出',$extra);
					}
					else {
						$this->success('转出成功!',$extra);
					}
				}
			}
		}
		
	}

	public function mywt($market = NULL, $type = NULL, $status = NULL)
	{
		if (!userid()) {
			redirect('/Login/index.html');
		}


		// 获取币种信息
//		$coin_info = M('Coin')->where(array('name' => $market))->find();
//
//		if(!$coin_info){
//			$this->error('币种不存在');
//		}
//
		$this->assign('coin_info', $market);



		if (($type == 1) || ($type == 2)) {
			$where['type'] = $type;
		}

		if (($status == 1) || ($status == 2) || ($status == 3)) {
			$where['status'] = $status - 1;
		}

		$this->assign('market', $market);
		$this->assign('type', $type);
		$this->assign('status', $status);


		// 筛选条件
		$where['userid'] = userid();
		$where['market'] = $market;
		$market_info = M('market')->where(array('name'=>$market))->find();

		$Mobile = M('Trade');
		$count = $Mobile->where($where)->count();
		$Page = new \Think\Page1($count, 10);
		if(LANG_SET == 'en-us'){
			$Page->config = array('header'=>'Totle','prev'=>'Previous','next'=>'Next','first'=>'First','last'=>'Last','theme'=>' %totalRow% %header% %nowPage%/%totalPage% Page</br> %upPage% %downPage% %first%  %end%');
		}
		$show = $Page->show();

		$list = $Mobile->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['num'] = number_format($v['num'],6,'.','');
			$list[$k]['price'] = number_format($v['price'],$market_info['round'],'.','');
			$list[$k]['deal'] = number_format($v['deal'],6,'.','');
			if($v['deal'] <= 0){
				$list[$k]['demark'] = L('No_trade');
			}else if($v['deal'] < $v['num']){
				$list[$k]['demark'] = L('Bufen_trade');
			}else if($v['deal'] >= $v['num']){
				$list[$k]['demark'] = L('Bazaar_ywc');
			}
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		
		$mcancel_token = set_token('mcancel');
		$this->assign('mcancel_token',$mcancel_token);
		
		$this->display();
	}

	public function mycj($market = NULL, $type = NULL)
	{
		if (!userid()) {
			redirect('/#login');
		}

		// 获取币种信息
//		$coin_info = M('Coin')->where(array('name' => $market))->find();
//		if(!$coin_info){
//			$this->error('币种不存在');
//		}
		$this->assign('coin_info', $market);

		$market_info = M('Market')->where(array('name'=>$market))->find();
		if ($type == 1) {
			$where['userid'] = userid();
		}
		else if ($type == 2) {
			$where['peerid'] = userid();
		}
		else {
			$where['userid|peerid'] = userid();
		}
		$where['market'] = $market;
		// 按时间筛选条件================================================
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
		// 按时间筛选条件=====结束===========================================

		$this->assign('market', $market);
		$this->assign('type', $type);
		$this->assign('userid', userid());
		$Module = M('TradeLog');
		$count = $Module->where($where)->count();
		$Page = new \Think\Page1($count, 10);
		if(LANG_SET == 'en-us'){
			$Page->config = array('header'=>'Totle','prev'=>'Previous','next'=>'Next','first'=>'First','last'=>'Last','theme'=>' %totalRow% %header% %nowPage%/%totalPage% Page</br> %upPage% %downPage% %first%  %end%');
		}
		$show = $Page->show();

		$list = $Module->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['num'] = number_format($v['num'],8-$market_info['round'],'.','');
			$list[$k]['price'] = number_format($v['price'],$market_info['round'],'.','');
			$list[$k]['mum'] = number_format($v['mum'],8-$market_info['round'],'.','');
			$list[$k]['fee_buy'] = number_format($v['fee_buy'],8-$market_info['round'],'.','');
			$list[$k]['fee_sell'] = number_format($v['fee_sell'],8-$market_info['round'],'.','');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

}

?>