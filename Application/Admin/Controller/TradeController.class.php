<?php
namespace Admin\Controller;

class TradeController extends AdminController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","chexiao","log","chat","chatStatus","comment","commentStatus","market","marketEdit","marketStatus","invit","tradeExcel","tradelogExcel");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("页面不存在！");
		}
	}
	
	public function index($field = NULL, $name = NULL, $market = NULL, $status = NULL, $bs_type = NULL, $starttime = NULL, $endtime = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($market) {
			$where['market'] = $market;
		}

		if ($status) {
			$where['status'] = $status - 1;
		}


		// 交易类型
		if ($bs_type) {
			$where['type'] = $bs_type;
		}


		// 时间--条件

		$time_type = 'addtime';

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where[$time_type] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where[$time_type] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where[$time_type] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}else{

			// 无时间查询，显示申请时间类型十天以内数据
			$now_time = time() - 10*24*60*60;
			$where['addtime'] =  array('EGT',$now_time);
		}


		$list_new = M('Trade')->where($where)->order('id desc')->limit(5000)->select();

		$num_zong = 0;
		$num_cj_zong = 0;
		$money_zong = 0;
		$fee = 0;
		$tradeid = array();
		foreach ($list_new as $k => $v) {
			$num_zong += $v['num'];
			$num_cj_zong += $v['deal'];
			$money_zong += $v['mum'];
			$fee += $v['fee'];
			array_push($tradeid,$v['id']);
		}
		$this->assign('tradeid',implode(",",$tradeid));

		$count = M('Trade')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Trade')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}
		$datas = array();
		$datas['num_zong'] = $num_zong;
		$datas['num_cj_zong'] = $num_cj_zong;
		$datas['money_zong'] = $money_zong;
		$datas['fee'] = $fee;
		$this->assign('datas', $datas);
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function chexiao($id = NULL)
	{
		$rs = D('Trade')->chexiao($id);

		if ($rs[0]) {
			$this->success($rs[1]);
		}
		else {
			$this->error($rs[1]);
		}
	}

	public function log($field = NULL, $name = NULL, $market = NULL, $bs_type = NULL, $starttime = NULL, $endtime = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else if ($field == 'peername') {
				$where['peerid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($market) {
			$where['market'] = $market;
		}

		// 交易类型
		if ($bs_type) {
			$where['type'] = $bs_type;
		}

		// 时间--条件

		$time_type = 'addtime';

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where[$time_type] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where[$time_type] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where[$time_type] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}else{

			// 无时间查询，显示申请时间类型十天以内数据
			$now_time = time() - 10*24*60*60;
			$where['addtime'] =  array('EGT',$now_time);
		}


		$list_new = M('TradeLog')->where($where)->limit(5000)->select();

		$num_zong = 0;
		$money_zong = 0;
		$fee_buy_zong = 0;
		$fee_sell_zong = 0;
		$logid = array();
		foreach ($list_new as $k => $v) {
			$num_zong += $v['num'];
			$money_zong += $v['mum'];
			$fee_buy_zong += $v['fee_buy'];
			$fee_sell_zong += $v['fee_sell'];
			array_push($logid,$v['id']);
		}
		$this->assign('logid',implode(',',$logid));
		$count = M('TradeLog')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('TradeLog')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
			$list[$k]['peername'] = M('User')->where(array('id' => $v['peerid']))->getField('username');
		}
		$datas = array();
		$datas['num_zong'] = $num_zong;
		$datas['money_zong'] = $money_zong;
		$datas['fee_buy'] = $fee_buy_zong;
		$datas['fee_sell'] = $fee_sell_zong;
		$this->assign('datas', $datas);
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function chat($field = NULL, $name = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		}

		$count = M('Chat')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Chat')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function chatStatus($id = NULL, $type = NULL, $mobile = 'Chat')
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (empty($id)) {
			$this->error('参数错误！');
		}

		if (empty($type)) {
			$this->error('参数错误1！');
		}

		if (strpos(',', $id)) {
			$id = implode(',', $id);
		}

		$where['id'] = array('in', $id);

		switch (strtolower($type)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;

		case 'repeal':
			$data = array('status' => 2, 'endtime' => time());
			break;

		case 'delete':
			$data = array('status' => -1);
			break;

		case 'del':
			if (M($mobile)->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('操作失败！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function comment($field = NULL, $name = NULL, $coinname = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($coinname) {
			$where['coinname'] = $coinname;
		}

		$count = M('CoinComment')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('CoinComment')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function commentStatus($id = NULL, $type = NULL, $mobile = 'CoinComment')
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (empty($id)) {
			$this->error('参数错误！');
		}

		if (empty($type)) {
			$this->error('参数错误1！');
		}

		if (strpos(',', $id)) {
			$id = implode(',', $id);
		}

		$where['id'] = array('in', $id);

		switch (strtolower($type)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;

		case 'repeal':
			$data = array('status' => 2, 'endtime' => time());
			break;

		case 'delete':
			$data = array('status' => -1);
			break;

		case 'del':
			if (M($mobile)->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('操作失败！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function market($field = NULL, $name = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		}

		$count = M('Market')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Market')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function marketEdit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = array();
			}
			else {
				$this->data = M('Market')->where(array('id' => $id))->find();
			}
			$time_arr = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
			$time_minute = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59');
			$this->assign('time_arr', $time_arr);
			$this->assign('time_minute', $time_minute);

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}

			$round = array(0, 1, 2, 3, 4, 5, 6);

			if (!in_array($_POST['round'], $round)) {
				$this->error('小数位数格式错误！');
			}

			if ($_POST['id']) {
				$rs = M('Market')->save($_POST);
			}
			else {
				$_POST['name'] = $_POST['sellname'] . '_' . $_POST['buyname'];
				unset($_POST['buyname']);
				unset($_POST['sellname']);

				if (M('Market')->where(array('name' => $_POST['name']))->find()) {
					$this->error('市场存在！');
				}

				$rs = M('Market')->add($_POST);
			}

			if ($rs) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}
		}
	}

	public function marketStatus($id = NULL, $type = NULL, $mobile = 'Market')
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (empty($id)) {
			$this->error('参数错误！');
		}

		if (empty($type)) {
			$this->error('参数错误1！');
		}

		if (strpos(',', $id)) {
			$id = implode(',', $id);
		}

		$where['id'] = array('in', $id);

		switch (strtolower($type)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;

		case 'repeal':
			$data = array('status' => 2, 'endtime' => time());
			break;

		case 'delete':
			$data = array('status' => -1);
			break;

		case 'del':
			if (M($mobile)->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('操作失败！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function invit($field = NULL, $name = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		}

		$count = M('Invit')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Invit')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$coin_info = M('Coin')->field('id,title')->select();
		$coin_arr = array();
		foreach($coin_info as $arr){
			$coin_arr[$arr['id']] = $arr['title'];
		}
		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
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

	public function tradeExcel(){
		if (IS_POST) {
			if(is_array($_POST['id'])){
				$id = implode(',', $_POST['id']);
			}else{
				$id = $_POST['id'];
			}
		}
		else {
			$id = $_GET['id'];
		}

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$where['id'] = array('in', $id);
		// 处理搜索的数据=================================================

		$list = M('Trade')->where($where)->select();
		foreach ($list as $k => $v) {
			$list[$k]['addtime'] = addtime($v['addtime']);
			$list[$k]['userid'] = M('User')->where(array('id' => $v['userid']))->getField('username');

			if ($list[$k]['type'] == 1) {
				$list[$k]['type'] = '买入';
			}
			else {
				$list[$k]['type'] = '卖出';
			}
			if ($list[$k]['status'] == 0) {
				$list[$k]['status'] = '交易中';
			}
			elseif ($list[$k]['status'] == 1) {
				$list[$k]['status'] = '已成交';
			}
			elseif ($list[$k]['status'] == 2) {
				$list[$k]['status'] = '已撤销';
			}
		}

		$zd = M('Trade')->getDbFields();
		array_splice($zd, 9, 1);
		array_splice($zd, 10, 1);
		// array_splice($zd, 6, 1);
		// array_splice($zd, 7, 7);
		$xlsName = '委托记录';
		$xls = array();

		foreach ($zd as $k => $v) {
			$xls[$k][0] = $v;
			$xls[$k][1] = $v;
		}

		$xls[0][2] = 'ID';
		$xls[1][2] = '用户名';
		$xls[2][2] = '市场';
		$xls[3][2] = '单价';
		$xls[4][2] = '数量';
		$xls[5][2] = '已成交';
		$xls[6][2] = '总额';
		$xls[7][2] = '手续费';
		$xls[8][2] = '类型';
		$xls[9][2] = '时间';
		$xls[10][2] = '状态';

		$this->trade_exportExcel($xlsName, $xls, $list);
	}

	public function tradelogExcel(){
		if (IS_POST) {
			if(is_array($_POST['id'])){
				$id = implode(',', $_POST['id']);
			}else{
				$id = $_POST['id'];
			}
		}
		else {
			$id = $_GET['id'];
		}

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$where['id'] = array('in', $id);
		// 处理搜索的数据=================================================

		$list = M('TradeLog')->where($where)->select();
		foreach ($list as $k => $v) {
			$list[$k]['addtime'] = addtime($v['addtime']);
			$list[$k]['userid'] = M('User')->where(array('id' => $v['userid']))->getField('username');
			$list[$k]['peerid'] = M('User')->where(array('id' => $v['userid']))->getField('username');

			if ($list[$k]['type'] == 1) {
				$list[$k]['type'] = '买入';
			}
			else {
				$list[$k]['type'] = '卖出';
			}
		}

		$zd = M('TradeLog')->getDbFields();
		array_splice($zd, 10, 1);
		array_splice($zd, 11, 2);
		// array_splice($zd, 6, 1);
		// array_splice($zd, 7, 7);
		$xlsName = '成交记录';
		$xls = array();

		foreach ($zd as $k => $v) {
			$xls[$k][0] = $v;
			$xls[$k][1] = $v;
		}

		$xls[0][2] = 'ID';
		$xls[1][2] = '买家';
		$xls[2][2] = '卖家';
		$xls[3][2] = '市场';
		$xls[4][2] = '单价';
		$xls[5][2] = '数量';
		$xls[6][2] = '总额';
		$xls[7][2] = '买家手续费';
		$xls[8][2] = '卖家手续费';
		$xls[9][2] = '交易类型';
		$xls[10][2] = '时间';

		$this->trade_exportExcel($xlsName, $xls, $list);
	}
}

?>
        