<?php
namespace Home\Controller;

class ApiController extends HomeController
{

	protected function _initialize(){

		header('Cache-Control: no-cache');

		if(empty($_REQUEST['AccessKey'])){
			return_json(-1,"AccessKey error,access denied!");
		}else{
			//验证基础信息
			$data['AccessKey'] = $_REQUEST['AccessKey'];

			$result = M("apiKey")->field('user_id,ip,SecretKey')->where($data)->find();

			//如果查询到的授权为空  或者  查询到的授权ip和本次访问的ip 不一致
			if(empty($result) || (!empty($result['ip']) && getIp() != $result['ip']) ){
				return_json(-1,"AccessKey|IP error,access denied");
			}else{
				 $this->user_id   = $result['user_id'];
				 $this->SecretKey = $result['secretkey'];

				 session('userId', $this->user_id);
			}
		}
		
	}
	
	public function __construct() {
		parent::__construct();
	}

	public function __destruct(){
		session(null);
	}
	

	
	public function accounts(){

		$result = M("user")->field(array('id','username','status'))->where(array('id'=>$this->user_id))->select();

		return_json(1,'Success',$result);
	}

	/*

		获取系统时间戳(东八区)
	*/
	public function get_time(){

		return_json(1,'Success',time());
	}


	/*
		获取支持的币种列表
	*/

	public function currencys(){
		
		$info = D('Coin')->getSupport();

		return_json(1,'Success',$info);
	}


	/*
		获取账余额
	*/
	public function balance(){

		$result = D('Coin')->getSupport();

		foreach ($result as $key => $value) {
			$result[] = $value.'d';
		}

		$info = M('userCoin')->field($result)->where(array('userid'=>$this->user_id))->find();

		return_json(1,"Success",$info);
	}


	/*
		获取交易列表
		@param int $start_time 最早的委托时间戳
		@param int $start_time 最晚的委托时间戳
		@param int $type 类型 0 全部订单  1 买入订单  2 卖出订单
	*/

	public function orders($start_time = NULL,$end_time = NULL,$type = 0){

		$result = D('Trade')->getTrade($this->user_id,$start_time,$end_time,$type);

		return_json(1,"Success",$result);
	}


	/*
		下订单
		@param string $price 价格
		@param double num 买入数量
		@param string market 交易币种 (ltc_btc)
		@param int type 交易类型 1 买入  2 卖出
	
	*/
	public function place(){



		$sign = $_POST['sign'];
		unset($_POST['sign']);

		$post_encode = http_build_query($_POST);
		

		$hash 		 =  hash_hmac('sha256', $post_encode, $this->SecretKey, true);
		$md5		 =  md5($hash);

		//验证签名
		if($md5 != $sign){
			return_json(-1,'sign error');
		}

		$Trade = A('Trade');

		$Trade->upTrade(NULL, $_POST['market'], $_POST['price'], $_POST['num'], $_POST['type']);
	}



	/*
		取消订单
	*/
	public function cancelOrder($id = NULL,$time = NULL){

		$sign = $_POST['sign'];
		unset($_POST['sign']);

		$post_encode = http_build_query($_POST);

		$hash 		 =  hash_hmac('sha256', $post_encode, $this->SecretKey, true);
		$md5		 =  md5($hash);

		//验证签名
		if($md5 != $sign){
			return_json(-1,'sign error');
		}

		if(!empty($id) || empty($time) ){
			
			$Trade = A('Trade');

			$Trade->chexiao($id);

		}else{
			return_json(-1,'ID can not be empty');
		}

	}


	/*
		获取K线图
	*/
	public function kline(){

		
		$size = $_REQUEST['size'];
		$type = $_REQUEST['type'];
		$market = !empty($_REQUEST['market']) ? $_REQUEST['market'] : C('market_mr');
		$xnb = explode("_",$market)[0];
		$rmb = explode("_",$market)[1];
		if(empty($size)){
			$size = 1000;
		}
		if($size>0){

			$time = get_switch_time($type);

			$tjcount = M('TradeJson')->where(array('type' => $time, 'market' => $market))->count();
			if($tjcount>$size){
				$tradeJson = M('TradeJson')->where(array('type' => $time, 'market' => $market))->order('addtime asc')->limit($tjcount-$size,$tjcount)->select();
			}else{

				$tradeJson = M('TradeJson')->where(array('type' => $time, 'market' => $market))->order('addtime asc')->select();
			}

			$list=array();
			foreach ($tradeJson as $k => $v) {
				$json_data = json_decode($v['data'], true);
				$list[] = array(($json_data[0]+$v['type']*60)*1000,$json_data[2]*1,$json_data[3]*1,$json_data[4]*1,$json_data[5]*1,$json_data[1]*1);
			}

			return_json(1,'Success',$list);

		}
	}



	/*
		获取交易记录
	*/
	public function getTradelog($market = NULL, $jjcoin = NULL,$size = NULL)
	{
		
		// 过滤非法字符----------------S

		if (checkstr($market)) {
			$this->error('您输入的信息有误！');
		}
		
		if(empty($jjcoin)){
			$jjcoin = "btc";
		}
		$rmb = explode("_",$market)[1];
		$operator = getoperator($rmb,$jjcoin);

		if(empty($size) || $size <= 0  || $size > 2000){
			$size = 200;
		}

		// 过滤非法字符----------------E

		$tradeLog = M('TradeLog')->where(array('status' => 1, 'market' => $market))->order('id desc')->limit($size)->select();

		if ($tradeLog) {
			foreach ($tradeLog as $k => $v) {
				$data['tradelog'][$k]['addtime'] = date('m-d H:i:s', $v['addtime']);
				$data['tradelog'][$k]['type'] = $v['type'];
				$data['tradelog'][$k]['price'] = $v['price'] * $operator;
				$data['tradelog'][$k]['num'] = round($v['num'], 6);
				$data['tradelog'][$k]['mum'] = round($v['mum'], 6);
			}
		}
		
		return_json(1,"Success",$data);
	}



}

?>