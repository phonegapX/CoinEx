<?php
namespace Home\Controller;

class IndexController extends HomeController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","charts","graph");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("非法操作！");
		}
	}
	
	public function __construct() {
		parent::__construct();
		$this->common_content();
	}
	
	public function charts($market=NULL){
		if(empty($market)){
			$market = C('market_mr');
		}
		$market_list = M('Market')->where(array('status'=>1))->order('id desc')->select();
		foreach($market_list as $key=>$val){
			$market_name = $val['name'];
			$coin_info = M('Coin')->where(array('name'=>explode("_",$market_name)[0]))->find();
			$market_list[$key]['img'] = $coin_info['img'];
		}
		$this->assign('market',$market);
		$this->assign('market_list',$market_list);
		if(LANG_SET == 'zh-cn'){
			$this->assign('language',1);
		}
		$this->display();
	}
	
	public function graph($market=NULL){
		if(empty($market)){
			$market = C('market_mr');
		}
		$rmb = explode("_",$market)[1];
		$generator = getoperator($rmb,'rmb');
		$trade_list = M('trade_json')->where(array('market'=>$market,'type'=>'5'))->order('addtime desc')->limit(10)->select();
		$datax = array();
		$datay = array();
		foreach($trade_list as $val){
			$data = json_decode($val['data'],true);
			array_unshift($datax,date("H:i",$data[0]));
			array_unshift($datay,$data[2]);
		}
		if(!empty($datax) && !empty($datay)){
			vendor("Jpgraph.jpgraph");
			vendor("Jpgraph.jpgraph_line");
			$graph = new \Graph(750,400);
			$graph->img->SetMargin(30,30,30,30);
			$graph->SetMargin(80,30,40,40);
			$graph->img->SetAntiAliasing();
			$graph->SetScale("textlin");
			//$graph->title->Set(iconv("UTF-8","GB2312//IGNORE",strtoupper($market)));
			//$graph->xaxis->title->Set(iconv("UTF-8","GB2312//IGNORE",strtoupper($market)));
			//$graph->yaxis->title->Set(iconv("UTF-8","GB2312//IGNORE",strtoupper($market)));
			if(!empty($datax)){
				$graph->xaxis->SetTickLabels($datax);
				$graph->xaxis->SetColor('#ADB8CC');
			}
			$graph->yaxis->SetColor('#ADB8CC');
			$graph->SetBackgroundGradient('#21202d', '#21202d', GRAD_HOR, BGRAD_FRAME);
			$graph->ygrid->SetFill(true,'#059ff5@0.8','#21202d@0.8');
			
			
			$p1 = new \LinePlot($datay);
			
			$p1->mark->SetType(MARK_FILLEDCIRCLE);
			$p1->mark->SetFillColor("#35be15");
			$p1->mark->SetWidth(4);
			$p1->mark->SetSize(4);
			$p1->SetColor("#35be15");
			$p1->SetCenter();
			$graph->Add($p1);

			$graph->Stroke();
		}
	}
	
	public function index()
	{

		// 首页轮播图 ----------------------S

		$indexAdver = (APP_DEBUG ? null : S('index_indexAdver'));

		if (!$indexAdver) {
			if(LANG_SET == 'zh-cn'){
				$indexAdver = M('Adver')->where(array('status' => 1,'look' => 0,'fenlei'=>0))->order('id asc')->select();
			}else{
				$indexAdver = M('Adver')->where(array('status' => 1,'look' => 0,'fenlei'=>1))->order('id asc')->select();
			}
			
			foreach($indexAdver as $key=>$val){
				$indexAdver[$key]['img']=stripslashes($indexAdver[$key]['img']);
			}
			S('index_indexAdver', $indexAdver);
		}

		$this->assign('indexAdver', $indexAdver);

		// 首页轮播图 ----------------------E
		
		if (!$market) {
			$market = C('market_mr');
		}
		$xnb = explode('_', $market)[0];
		$rmb = explode('_', $market)[1];
		
		$this->assign('market', $market);

		$this->assign('xnb', $xnb);

		$this->assign('rmb', $rmb);
		
		$config_shishi = M('Config')->where(array('id'=>1))->find();
		$this->assign('btc_price',$config_shishi['btc_rmb']);
		$this->assign('eth_price',$config_shishi['eth_rmb']);

		if(userid()){
			$CoinList = M('Coin')->where(array('status' => 1))->select();
			$UserCoin = M('UserCoin')->where(array('userid' => userid()))->find();
			$Market = M('Market')->where(array('status' => 1))->select();

			foreach ($Market as $k => $v) {
				$Market[$v['name']] = $v;
			}
		}

		if (C('index_html')) {
			$this->display('Index/' . C('index_html') . '/index');
		}
		else {
			$this->display();
		}
	}
}

?>