<?php
namespace Mobile\Controller;

class AjaxtradeController extends MobileController
{
	
	// 处理昨日收盘价、今日最高价、今日最低价
	public function ajax_trade($coin_name = null){

		if(!$coin_name){
			exit(json_encode('no'));
		}

		$market = $coin_name.'_becc';

		$data = array();

		// 最新成交价格------------S

		$list_t = M('TradeLog')->order('addtime desc')->find();

		$data['price_new'] = $list_t['price']*1;

		// 最新成交价格------------E



		// 最新一次成交价格--买一价------------S

		$list_buy = M('TradeLog')->where(array('type'=>1))->order('addtime desc')->find();

		$data['price_buy_new'] = $list_buy['price']*1;

		// 最新一次成交价格--买一价------------E



		// 最新一次成交价格--卖一价------------S

		$list_sell = M('TradeLog')->where(array('type'=>2))->order('addtime desc')->find();

		$data['price_sell_new'] = $list_sell['price']*1;

		// 最新一次成交价格--卖一价------------E



		// 用户资产------------S

		$userCoin = M('UserCoin')->where(array('userid' => userid()))->find();

		if ($userCoin) {
			$xnb = explode('_', $market)[0];
			$rmb = explode('_', $market)[1];
			$data['usercoin']['xnb'] = sprintf("%.3f",floatval($userCoin[$xnb]));
			$data['usercoin']['xnbd'] =  sprintf("%.3f",floatval($userCoin[$xnb . 'd']));

			$data['usercoin']['becc'] = sprintf("%.3f", floatval($userCoin[$rmb]) - floatval($userCoin['shouyi_num']));
			$data['usercoin']['beccd'] = sprintf("%.3f",floatval($userCoin[$rmb . 'd']) - floatval($userCoin['shouyi_numd']));

			// 处理代理收益
			$data['usercoin']['shouyi_num'] = sprintf("%.3f", floatval($userCoin['shouyi_num']));
			$data['usercoin']['shouyi_numd'] = sprintf("%.3f", floatval($userCoin['shouyi_numd']));

			// 处理当日积分冻结
			$data['usercoin']['todayd'] =  sprintf("%.3f",floatval($userCoin['todayd']));
		}
		else {
			$data['usercoin'] = null;
		}

		// 用户资产------------E


		// 处理买入价格与卖出价格（最佳买卖价）------------S

		$s_where = array('status'=>0,'type'=>2);
		$s_list = M('Trade')->where($s_where)->order('price asc')->find();

		$data['buy_good_price'] = round($s_list['price'],3);

		$b_where = array('status'=>0,'type'=>1);
		$b_list = M('Trade')->where($b_where)->order('price desc')->find();

		$data['sell_good_price'] = round($b_list['price'],3);

		// 处理买入价格与卖出价格（最佳买卖价）------------E




		// 获取涨跌比例------------S

		$bili = M('Market')->where(array('name'=>$market))->find();
		
		$data['zhang'] = $bili['zhang']/100;
		$data['die'] = $bili['die']/100;

		// 获取涨跌比例------------E
		


		// 获取昨日收盘价------------S

		$time = date('Y-m-d',time());
		$time2 = strtotime($time);
		$where['addtime'] = array('lt',$time2);

		$list = M('TradeLog')->where($where)->order('addtime desc')->find();
		$data['zuo_price'] = $list['price'];

		// 获取昨日收盘价------------E
		

		exit(json_encode($data));


	}
}

?>