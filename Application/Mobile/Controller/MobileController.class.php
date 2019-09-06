<?php
namespace Mobile\Controller;

class MobileController extends \Think\Controller
{
	protected function _initialize()
	{
		$allow_controller=array("Ajax","Api","Article","Finance","Index","Login","Pay","Queue","Trade","User","Chart");
		if(!in_array(CONTROLLER_NAME,$allow_controller)){
			$this->error("非法操作");
		}

		if (!session('userId')) {
			session('userId', 0);
		}else if (CONTROLLER_NAME != 'Login') {
			$member_session_id=session('sessionId');
			if(!empty($member_session_id)){
				$prev_login=M('user_log')->where(array('userid'=>session('userId'),'type' => 'login','state'=>1,'id'=>array('lt',$member_session_id)))->order('id desc')->find();
				if(!empty($prev_login)){
					M('user_log')->where(array('userid'=>session('userId'),'type' => 'login','state'=>1,'id'=>array('lt',$member_session_id)))->save(array('state'=>0,'endtime'=>time()));
				}
				$next_login=M('user_log')->where(array('userid'=>session('userId'),'type' => 'login','state'=>1,'id'=>array('gt',$member_session_id)))->order('id desc')->find();
				if(!empty($next_login)){
					redirect('/Login/loginout');
				}
			}else{
				redirect('/Login/loginout');
			}

			
			$user = M('user')->where('id = ' . session('userId'))->find();

			if(!session('loginTime')){
				redirect('/Login/loginout');
			}else{
				$login_records=M('user_log')->where(array('userid'=>$user['id'],'type' => 'login','session_key'=>session_id(),'state'=>1))->order('id desc')->find();
				if(empty($login_records) || (!empty($login_records)&&$login_records['addtime']!=session('loginTime'))){
					redirect('/Login/loginout');
				}
			}
			$cha=time()-session('saveTime');
			if(floor($cha/60)>30){
				redirect('/Login/loginout');
			}
			session('saveTime', time());
			if(session('userName')&&cookie('userName')){
				if(session('userName')!=cookie('userName')){
					session(null);
					cookie(null);
					redirect("/Mobile/Login/index.html");
				}
			}
		}
	}
	
	public function __construct() {
		parent::__construct();
		defined('APP_DEMO') || define('APP_DEMO', 0);
		
		if (isset($_GET['invit'])) {
			session('invit', $_GET['invit']);
		}

		$config = (APP_DEBUG ? null : S('home_config'));

		if (!$config) {
			$config = M('Config')->where(array('id' => 1))->find();
			S('home_config', $config);
		}

		if (!session('web_close')) {
			if (!$config['web_close']) {
				exit($config['web_close_cause']);
			}
		}

		C($config);
		C('contact_qq', explode('|', C('contact_qq')));
		C('contact_qqun', explode('|', C('contact_qqun')));
		C('contact_bank', explode('|', C('contact_bank')));
		$coin = (APP_DEBUG ? null : S('home_coin'));

		if (!$coin) {
			$coin = M('Coin')->where(array('status' => 1))->select();
			S('home_coin', $coin);
		}
		
		$coinList = array();

		foreach ($coin as $k => $v) {
			$coinList['coin'][$v['name']] = $v;

			if ($v['name'] != 'becc') {
				$coinList['coin_list'][$v['name']] = $v;
			}

			if ($v['type'] == 'rmb') {
				$coinList['rmb_list'][$v['name']] = $v;
			}
			else {
				$coinList['xnb_list'][$v['name']] = $v;
			}

			if ($v['type'] == 'rgb') {
				$coinList['rgb_list'][$v['name']] = $v;
			}

			if ($v['type'] == 'qbb') {
				$coinList['qbb_list'][$v['name']] = $v;
			}
		}

		C($coinList);
		
		
		// $market = (APP_DEBUG ? null : S('home_market'));

		// if (!$market) {
		// 	$market = M('Market')->where(array('status' => 1))->select();
		// 	S('home_market', $market);
		// }

		// foreach ($market as $k => $v) {
		// 	$v['new_price'] = round($v['new_price'], $v['round']);
		// 	$v['buy_price'] = round($v['buy_price'], $v['round']);
		// 	$v['sell_price'] = round($v['sell_price'], $v['round']);
		// 	$v['min_price'] = round($v['min_price'], $v['round']);
		// 	$v['max_price'] = round($v['max_price'], $v['round']);
		// 	$v['xnb'] = explode('_', $v['name'])[0];
		// 	$v['rmb'] = explode('_', $v['name'])[1];
		// 	$v['xnbimg'] = C('coin')[$v['xnb']]['img'];
		// 	$v['rmbimg'] = C('coin')[$v['rmb']]['img'];
		// 	$v['volume'] = $v['volume'] * 1;
		// 	$v['change'] = $v['change'] * 1;
		// 	$v['title'] = C('coin')[$v['xnb']]['title'] . '(' . strtoupper($v['xnb']) . '/' . strtoupper($v['rmb']) . ')';

		// 	$v['title_n'] = C('coin')[$v['xnb']]['title'];
		// 	$v['title_ns'] = '(' . strtoupper($v['xnb']) . '/' . strtoupper($v['rmb']) . ')';
		// 	$v['title_nsm'] = strtoupper($v['xnb']);

		// 	$marketList['market'][$v['name']] = $v;
		// }

		// C($marketList);
		//新加市场列表
		$market = (APP_DEBUG ? null : S('home_market'));

			if (!$market) {
				$market = M('Market')->where(array('status' => 1))->select();
				foreach ($market as $k => $v) {
					$market[$k]['xnb'] = explode('_', $v['name'])[0];
					$market[$k]['rmb'] = explode('_', $v['name'])[1];
				// $market_list[$v['name']] = $v;
					}
				S('home_market', $market);
			}
		
			
			foreach ($market as $k => $v) {
				$v['new_price'] =number_format($v['new_price'],$v['round'],'.','');//round($v['new_price'], $v['round']);
				$v['buy_price'] =number_format($v['buy_price'],$v['round'],'.','');// round($v['buy_price'], $v['round']);
				$v['sell_price'] =number_format($v['sell_price'],$v['round'],'.','');// round($v['sell_price'], $v['round']);
				$v['min_price'] =number_format($v['min_price'],$v['round'],'.',''); //round($v['min_price'], $v['round']);
				$v['max_price'] =number_format($v['max_price'],$v['round'],'.','');// round($v['max_price'], $v['round']);
				$v['xnb'] = explode('_', $v['name'])[0];
				$v['rmb'] = explode('_', $v['name'])[1];
				$v['xnbimg'] = C('coin')[$v['xnb']]['img'];
				$v['rmbimg'] = C('coin')[$v['rmb']]['img'];
				$v['volume'] =number_format($v['volume'],6,'.','');//;// $v['volume'] * 1;
				$v['change'] = $v['change'] * 1;
				$tmp_rmb = explode("_",$market)[1];
				$v['cjamount_btc'] = number_format(getoperator($tmp_rmb,'btc'),6,'.','');//;
				$v['title'] = C('coin')[$v['xnb']]['title'] . '(' . strtoupper($v['xnb']) . '/' . strtoupper($v['rmb']) . ')';
				$v['title_n'] = C('coin')[$v['xnb']]['title'];
				$v['title_ns'] = '(' . strtoupper($v['xnb']) . '/' . strtoupper($v['rmb']) . ')';
				$v['title_nsm'] = strtoupper($v['xnb']);
				$marketList['market'][$v['name']] = $v;
			}
			$this->assign('market_list',$marketList['market']);
			C($marketList);
		$C = C();

		foreach ($C as $k => $v) {
			$C[strtolower($k)] = $v;
		}

		$this->assign('C', $C);
		$this->kefu = './Application/Home/View/Kefu/' . $C['kefu'] . '/index.html';
	}
	
	public function common_content(){
		if (userid()) {
			$userCoin_top = M('UserCoin')->where(array('userid' => userid()))->find();
			$userCoin_top['becc'] = sprintf("%.2f", $userCoin_top['becc']);
			$userCoin_top['beccd'] = sprintf("%.2f", $userCoin_top['beccd']);
			$this->assign('userCoin_top', $userCoin_top);
		}
		if (!S('daohang')) {
			$this->daohang = M('Daohang')->where(array('status' => 1))->order('sort asc')->select();
			S('daohang', $this->daohang);
		}
		else {
			$this->daohang = S('daohang');
		}

		$footerArticleType = (APP_DEBUG ? null : S('footer_indexArticleType'));

		if (!$footerArticleType) {
			$footerArticleType = M('ArticleType')->where(array('status' => 1, 'footer' => 1, 'shang' => ''))->order('sort asc ,id desc')->limit(3)->select();
			S('footer_indexArticleType', $footerArticleType);
		}

		$this->assign('footerArticleType', $footerArticleType);
		$footerArticle = (APP_DEBUG ? null : S('footer_indexArticle'));

		if (!$footerArticle) {
			foreach ($footerArticleType as $k => $v) {
				 $second_class= M('ArticleType')->where(array('shang' => $v['name'], 'footer' => 1, 'status' => 1))->order('id asc')->select();
				 if(!empty($second_class)){
					 foreach($second_class as $val){
						 $article_list = M('Article')->where(array('footer'=>1,'index'=>1,'status'=>1,'type'=>$val['name']))->select();
						 if(!empty($article_list)){
							 foreach($article_list as $kk=>$vv){
								 $footerArticle[$v['name']][]=$vv;
							 }
						 }
					 }
				 }else{
					 $article_list = M('Article')->where(array('footer'=>1,'index'=>1,'status'=>1,'type'=>$v['name']))->select();
					 if(!empty($article_list)){
						 foreach($article_list as $kk=>$vv){
							 $footerArticle[$v['name']][]=$vv;
						 }
					 }
				 }
			}
			S('footer_indexArticle', $footerArticle);
		}
		
		// 底部友情链接--------------------S
		$footerindexLink = (APP_DEBUG ? null : S('index_indexLink'));
		if (!$footerindexLink) {
			$footerindexLink = M('Link')->where(array('status' => 1,'look_type'=>1))->order('sort asc ,id desc')->select();
		}
		$this->assign('footerindexLink', $footerindexLink);
		// 底部友情链接--------------------E

		// qq--------------------S
		$qqs = C('contact_qqun');
		foreach ($qqs as $k => $v) {
			$ss = $k + 1;
			$qqs[$k] = '会员'.$ss.'群：'.$v.'　';
		}
		$this->assign('qqs', $qqs);
		// qq--------------------E
		
		// 交易币种列表--------------------S
		$data = array();
		foreach (C('market') as $k => $v) {
			$v['xnb'] = explode('_', $v['name'])[0];
			$v['rmb'] = explode('_', $v['name'])[1];
			$data[$k]['name'] = $v['name'];
			$data[$k]['img'] = $v['xnbimg'];
			$data[$k]['title'] = $v['title'];
		}
		$this->assign('market_ss', $data);
		// 交易币种列表--------------------E
		
		$notice_info = M('Article')->where(array('type' => 'notice', 'status' => 1, 'index' => 1))->order('id desc')->find();
		if(!$notice_info){
			$notice_info['id'] = 0;
			$notice_info['title'] = '暂无公告';
			$notice_info['content'] = '暂无信息';
		}
		// 踢出内容中的标签
		$notice_info['content'] = strip_tags($notice_info['content']);
		$notice_type = M('ArticleType')->where(array('name' => 'notice'))->find();
		$this->assign('notice_info', $notice_info);
		$this->assign('footerArticle', $footerArticle);
	}
}
?>