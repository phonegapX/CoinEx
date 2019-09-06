<?php
namespace Common\Model;

class TradeModel extends \Think\Model
{
	protected $keyS = 'Trade';

	public function hangqing($market = NULL)
	{
		if (empty($market)) {
			return null;
		}

		$timearr = array(1, 3, 5, 10, 15, 30, 60, 120, 240, 360, 720, 1440, 10080);

		foreach ($timearr as $k => $v) {
			$tradeJson = M('TradeJson')->where(array('market' => $market, 'type' => $v))->order('id desc')->find();

			if ($tradeJson) {
				$addtime = $tradeJson['addtime'];
			}
			else {
				$addtime = M('TradeLog')->where(array('market' => $market))->order('id asc')->getField('addtime');
			}

			if ($addtime) {
				$youtradelog = M('TradeLog')->where('addtime >=' . $addtime . '  and market =\'' . $market . '\'')->sum('num');
			}

			if ($youtradelog) {
				if ($v == 1) {
					$start_time = $addtime;
				}
				else {
					$start_time = mktime(date('H', $addtime), floor(date('i', $addtime) / $v) * $v, 0, date('m', $addtime), date('d', $addtime), date('Y', $addtime));
				}

				$x = 0;

				for (; $x <= 20; $x++) {
					$na = $start_time + (60 * $v * $x);
					$nb = $start_time + (60 * $v * ($x + 1));

					if (time() < $na) {
						break;
					}

					$sum = M('TradeLog')->where('addtime >=' . $na . ' and addtime <' . $nb . ' and market =\'' . $market . '\'')->sum('num');

					if ($sum) {
						$sta = M('TradeLog')->where('addtime >=' . $na . ' and addtime <' . $nb . ' and market =\'' . $market . '\'')->order('id asc')->getField('price');
						$max = M('TradeLog')->where('addtime >=' . $na . ' and addtime <' . $nb . ' and market =\'' . $market . '\'')->max('price');
						$min = M('TradeLog')->where('addtime >=' . $na . ' and addtime <' . $nb . ' and market =\'' . $market . '\'')->min('price');
						$end = M('TradeLog')->where('addtime >=' . $na . ' and addtime <' . $nb . ' and market =\'' . $market . '\'')->order('id desc')->getField('price');
						$d = array($na, $sum, $sta, $max, $min, $end);

						if (M('TradeJson')->where(array('market' => $market, 'addtime' => $na, 'type' => $v))->find()) {
							M('TradeJson')->where(array('market' => $market, 'addtime' => $na, 'type' => $v))->save(array('data' => json_encode($d)));
							M('TradeJson')->execute('commit');
						}
						else {
							M('TradeJson')->add(array('market' => $market, 'data' => json_encode($d), 'addtime' => $na, 'type' => $v));
							M('TradeJson')->execute('commit');
							M('TradeJson')->where(array('market' => $market, 'data' => '', 'type' => $v))->delete();
							M('TradeJson')->execute('commit');
						}
					}
					else {
						M('TradeJson')->add(array('market' => $market, 'data' => '', 'addtime' => $na, 'type' => $v));
						M('TradeJson')->execute('commit');
					}
				}
			}
		}
	}

	public function chexiao($id = NULL)
	{
		try{
			if (!check($id, 'd')) {
				throw new \Think\Exception(L('sCommon_cscw'));
			}

			$trade = M('Trade')->where(array('id' => $id))->find();

			if (!$trade) {
				throw new \Think\Exception(L('sCommon_ddbcz'));
			}

			if ($trade['status'] != 0) {
				throw new \Think\Exception(L('sCommon_cddbncx'));
			}

			$xnb = explode('_', $trade['market'])[0];
			$rmb = explode('_', $trade['market'])[1];

			if (!$xnb) {
				throw new \Think\Exception(L('sTrade_uptrade_mcsccw'));
			}
			
			if (!$rmb) {
				throw new \Think\Exception(L('sTrade_uptrade_mrsccw'));
			}
			
			$fee_buy = C('market')[$trade['market']]['fee_buy'];
			$fee_sell = C('market')[$trade['market']]['fee_sell'];

			if ($fee_buy < 0) {
				throw new \Think\Exception(L('sTrade_uptrade_mrsxfcw'));
			}
			if ($fee_sell < 0) {
				throw new \Think\Exception(L('sTrade_uptrade_mcsxfcw'));
			}
			$coin_info = M('Coin')->where(array('name' => $xnb))->find();
			$mo = M();
			$mo->execute('set autocommit=0');
			$mo->execute('lock tables tw_user_coin write  , tw_trade write ,tw_finance write,tw_finance_log write,tw_user write');//处理资金变更日志
			$rs = array();
			if ($trade['type'] == 1) {
				$user_buy = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->find();
				$save_buy_rmb = (($trade['num']-$trade['deal']) * $trade['price'] / 100) * (100 + $fee_buy);
				if($save_buy_rmb<=0){
					throw new \Think\Exception(L('sTrade_uptrade_cxsb'));
				}
				$finance = $mo->table('tw_finance')->where(array('userid' => $trade['userid']))->order('id desc')->find();
				$finance_num_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->find();
				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->setInc($rmb, $save_buy_rmb);
				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->setDec($rmb . 'd', $save_buy_rmb);
				$finance_nameid = $trade['id'];
				$finance_mum_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->find();
				// 处理资金变更日志--------买入类型---------S
				$user_2_info = $mo->table('tw_user')->where(array('id' => $trade['userid']))->find();
				if(session('userId') > 0){
					$position = 1;
					// 获取用户信息
					$user_info = $mo->table('tw_user')->where(array('id' => session('userId')))->find();
					$uu_name = $user_2_info['username'];
					$aa_name = $user_info['username'];
					$uu_id = $trade['userid'];
					$aa_id = session('userId');
				}else{
					$position = 0;
					$uu_name = $user_2_info['username'];
					$aa_name = session('admin_username');
					$uu_id = $trade['userid'];
					$aa_id = session('admin_id');
				}
				// optype 10 买入-动作类型 'cointype' => 资金类型 'plusminus' => 1增加类型
				$rs[] = $mo->table('tw_finance_log')->add(array('username' => $uu_name, 'adminname' => $aa_name, 'addtime' => time(), 'plusminus' => 1, 'amount' => $save_buy_rmb, 'optype' => 16, 'cointype' => 1, 'old_amount' => $finance_num_user_coin[$rmb], 'new_amount' => $finance_mum_user_coin[$rmb], 'userid' => $uu_id, 'adminid' => $aa_id,'addip'=>get_client_ip(),'position'=>$position));
				// 处理资金变更日志---------买入类型--------E
				// optype动作类型 'cointype' => 资金类型 'plusminus' => 1增加类型
				$rs[] = $mo->table('tw_finance_log')->add(array('username' => $uu_name, 'adminname' => L('sTrade_uptrade_xt'), 'addtime' => time(), 'plusminus' => 0, 'amount' => $save_buy_rmb, 'optype' => 25, 'cointype' => 1, 'old_amount' => $user_buy[$rmb . 'd'], 'new_amount' => $user_buy[$rmb . 'd']-$save_buy_rmb, 'userid' => $uu_id,'addip'=>get_client_ip(),'position'=>$position));
				// 处理资金变更日志-----------------E
				$finance_hash = md5($trade['userid'] . $finance_num_user_coin[$rmb] . $finance_num_user_coin[$rmb.'d'] . $save_buy_rmb . $finance_mum_user_coin[$rmb] . $finance_mum_user_coin[$rmb.'d'] . MSCODE . 'tp3.net.cn');
				$finance_num = $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb.'d'];

				if ($finance['mum'] < $finance_num) {
					$finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);
				}
				else {
					$finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);
				}
				$rs[] = $mo->table('tw_finance')->add(array('userid' => $trade['userid'], 'coinname' => $rmb, 'num_a' => $finance_num_user_coin[$rmb], 'num_b' => $finance_num_user_coin[$rmb.'d'], 'num' => $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb.'d'], 'fee' => $save_buy_rmb, 'type' => 1, 'name' => 'trade', 'nameid' => $finance_nameid, 'remark' => L('sTrade_uptrade_jyzxjycx') . $trade['market'], 'mum_a' => $finance_mum_user_coin[$rmb], 'mum_b' => $finance_mum_user_coin[$rmb.'d'], 'mum' => $finance_mum_user_coin[$rmb] + $finance_mum_user_coin[$rmb.'d'], 'move' => $finance_hash, 'addtime' => time(), 'status' => $finance_status));
				$rs[] = $mo->table('tw_trade')->where(array('id' => $trade['id']))->setField('status', 2);
			}
			else if ($trade['type'] == 2) {
				$user_sell = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->find();
				$save_sell_xnb = $trade['num'] - $trade['deal'];
				if (0 < $save_sell_xnb) {
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->setInc($xnb, $save_sell_xnb);
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->setDec($xnb . 'd', $save_sell_xnb);
					$user_sell_f = $mo->table('tw_user_coin')->where(array('userid' => $trade['userid']))->find();
					// 处理资金变更日志-----------------S
					$user_2_info = $mo->table('tw_user')->where(array('id' => $trade['userid']))->find();
					if(session('userId') > 0){
						$position = 1;
						// 获取用户信息
						$user_info = $mo->table('tw_user')->where(array('id' => session('userId')))->find();
						$uu_name = $user_2_info['username'];
						$aa_name = $user_info['username'];
						$uu_id = $trade['userid'];
						$aa_id = session('userId');
					}else{
						$position = 0;
						$uu_name = $user_2_info['username'];
						$aa_name = session('admin_username');
						$uu_id = $trade['userid'];
						$aa_id = session('admin_id');
					}
					// optype动作类型 'cointype' => 资金类型 'plusminus' => 1增加类型
					$rs[] = $mo->table('tw_finance_log')->add(array('username' => $uu_name, 'adminname' => $aa_name, 'addtime' => time(), 'plusminus' => 1, 'amount' => $save_sell_xnb, 'optype' => 17, 'cointype' => $coin_info['id'], 'old_amount' => $user_sell[$xnb], 'new_amount' => $user_sell_f[$xnb], 'userid' => $uu_id, 'adminid' => $aa_id,'addip'=>get_client_ip(),'position'=>$position));
					// 处理资金变更日志-----------------E
					// optype动作类型 'cointype' => 资金类型 'plusminus' => 1增加类型
					$rs[] = $mo->table('tw_finance_log')->add(array('username' => $uu_name, 'adminname' => L('sTrade_uptrade_xt'), 'addtime' => time(), 'plusminus' => 0, 'amount' => $user_sell[$xnb . 'd'], 'optype' => 26, 'cointype' => $coin_info['id'], 'old_amount' => $user_sell[$xnb . 'd'], 'new_amount' => $user_sell[$xnb . 'd']-$save_sell_xnb, 'userid' => $uu_id,'addip'=>get_client_ip(),'position'=>$position));
					// 处理资金变更日志-----------------E
				}else{
					throw new \Think\Exception(L('sTrade_uptrade_cxsb'));
				}
				$rs[] = $mo->table('tw_trade')->where(array('id' => $trade['id']))->setField('status', 2);
			}
			else {
				throw new \Think\Exception(L('sTrade_uptrade_cxsb'));
			}
		}catch(\Think\Exception $e){
			$reason = $e->getMessage();
			if($reason==L('sCommon_cscw')||$reason==L('sCommon_ddbcz')||$reason==L('sCommon_cddbncx')||$reason==L('sTrade_uptrade_mcsccw')||$reason==L('sTrade_uptrade_mrsccw')||$reason==L('sTrade_uptrade_mrsxfcw')||$reason==L('sTrade_uptrade_mcsxfcw')){
				return array('0', $reason);
			}elseif($reason==L('sTrade_uptrade_cxsb')){
				$mo->execute('rollback');
				$mo->execute('unlock tables');
				return array('0', L('sTrade_uptrade_cxsb'));
			}elseif($reason==L('sTrade_uptrade_cxsb')){
				$mo->execute('rollback');
				$mo->execute('unlock tables');
				return array('0', L('sTrade_uptrade_cxsb'));
			}else{
				$mo->execute('rollback');
				$mo->execute('unlock tables');
				return array('0', $reason);
			}
		}
		if (check_arr($rs)) {
			$mo->execute('commit');
			$mo->execute('unlock tables');
			S('getDepth', null);
			return array('1', L('sTrade_uptrade_cxcg'));
		}
		else {
			$mo->execute('rollback');
			$mo->execute('unlock tables');
			return array('0', L('sTrade_uptrade_cxsb') . implode('|', $rs));
		}
	}


	/*
		获取交易列表
		@param int $userid 用户id
		@param int $start_time 最早的委托时间戳
		@param int $end_time 最晚的委托时间戳
		@param int $type 类型 0 全部订单  1 买入订单  2 卖出订单
	*/
	public function getTrade($userid,$start_time = NULL,$end_time = NULL,$type = 0){

		if(!empty($start_time)){
			$time_start['addtime'] = array('egt',$start_time);
		}

		if(!empty($end_time)){
			$time_end['addtime']   = array('elt',$end_time);
		}

		if($type != 0){
			$types['type'] = $type;
		}else{
			$types = array();
		}

		return M('trade')->where(array('userid'=>$userid))->where(array($time_start))->where($time_end)->where($types)->select();

	}
}
?>