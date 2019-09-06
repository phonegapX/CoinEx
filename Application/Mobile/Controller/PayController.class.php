<?php
namespace Mobile\Controller;

class PayController extends MobileController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","movepay","mycz","myczQueren","ecpss");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("非法操作！");
		}
	}
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index()
	{
		if (IS_POST) {
			if (isset($_POST['alipay'])) {
				$arr = explode('--', $_POST['alipay']);

				if (md5('movesay') != $arr[2]) {
					echo -1;
					exit();
				}

				if (!strstr($arr[0], 'Pay')) {
				}

				$arr[0] = trim(str_replace(PHP_EOL, '', $arr[0]));
				$arr[1] = trim(str_replace(PHP_EOL, '', $arr[1]));

				if (strstr($arr[0], '付款-')) {
					$arr[0] = str_replace('付款-', '', $arr[0]);
				}

				$mycz = M('Mycz')->where(array('tradeno' => $arr[0]))->find();

				if (!$mycz) {
					echo -3;
					exit();
				}

				if (($mycz['status'] != 0) && ($mycz['status'] != 3)) {
					echo -4;
					exit();
				}

				if ($mycz['num'] != $arr[1]) {
					echo -5;
					exit();
				}

				$mo = M();
				$mo->startTrans();
				//$mo->execute('lock tables tw_user_coin write,tw_mycz write,tw_finance write');
				$rs = array();
				$finance = $mo->table('tw_finance')->where(array('userid' => $mycz['userid']))->order('id desc')->find();
				$finance_num_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $mycz['userid']))->find();
				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $mycz['userid']))->setInc('becc', $mycz['num']);
				$rs[] = $mo->table('tw_mycz')->where(array('id' => $mycz['id']))->save(array('status' => 1, 'mum' => $mycz['num'], 'endtime' => time()));
				$finance_mum_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $mycz['userid']))->find();
				$finance_hash = md5($mycz['userid'] . $finance_num_user_coin['becc'] . $finance_num_user_coin['beccd'] . $mycz['num'] . $finance_mum_user_coin['becc'] . $finance_mum_user_coin['beccd'] . MSCODE . 'tp3.net.cn');
				$finance_num = $finance_num_user_coin['becc'] + $finance_num_user_coin['beccd'];

				if ($finance['mum'] < $finance_num) {
					$finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);
				}
				else {
					$finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);
				}

				$rs[] = $mo->table('tw_finance')->add(array('userid' => $mycz['userid'], 'coinname' => 'becc', 'num_a' => $finance_num_user_coin['becc'], 'num_b' => $finance_num_user_coin['beccd'], 'num' => $finance_num_user_coin['becc'] + $finance_num_user_coin['beccd'], 'fee' => $mycz['num'], 'type' => 1, 'name' => 'mycz', 'nameid' => $mycz['id'], 'remark' => '人民币充值-人工到账', 'mum_a' => $finance_mum_user_coin['becc'], 'mum_b' => $finance_mum_user_coin['beccd'], 'mum' => $finance_mum_user_coin['becc'] + $finance_mum_user_coin['beccd'], 'move' => $finance_hash, 'addtime' => time(), 'status' => $finance_status));

				if (check_arr($rs)) {
					$mo->commit();
					echo 1;
					exit();
				}
				else {
					$mo->rollback();
					echo -6;
					exit();
				}
			}
		}
	}

	public function movepay()
	{
		if (IS_POST) {
			$movepay = $_POST['movepay'];
			$tradeno = $_POST['tradeno'];
			$num = $_POST['num'];
			$status = $_POST['status'];

			if (md5('movesay') != $movepay) {
				echo -1;
				exit();
			}

			$mycz = M('Mycz')->where(array('tradeno' => $tradeno))->find();

			if (!$mycz) {
				echo -2;
				exit();
			}

			if ($mycz['status']) {
				echo -3;
				exit();
			}

			if ($mycz['num'] != $num) {
				echo -4;
				exit();
			}

			$mo = M();
			$mo->startTrans();
			//$mo->execute('lock tables tw_user_coin write,tw_mycz write');
			$rs = array();
			$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $mycz['userid']))->setInc('becc', $mycz['num']);
			$rs[] = $mo->table('tw_mycz')->where(array('id' => $mycz['id']))->save(array('status' => 1, 'mum' => $mycz['num'], 'endtime' => time()));

			if (check_arr($rs)) {
				$mo->commit();
				$this->redirect('Mycz/log');
				exit();
			}
			else {
				$mo->rollback();
				echo -5;
				exit();
			}
		}
	}

	public function mycz($id = NULL)
	{
		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E
		if (check($id, 'd')) {
			$mycz = M('Mycz')->where(array('id' => $id))->find();

			if (!$mycz) {
				$this->redirect('Finance/mycz');
			}

			$myczType = M('MyczType')->where(array('name' => $mycz['type']))->find();

			if ($mycz['type'] == 'bank') {
				$UserBankType = M('UserBankType')->where(array('status' => 1))->order('id desc')->select();
				$this->assign('UserBankType', $UserBankType);
			}

			$this->assign('myczType', $myczType);
			$this->assign('mycz', $mycz);
			$this->display($mycz['type']);
		}
		else {
			Vendor("Pay.JSAPI","",".php");
			$wxpay_res=new \WxPayApi;
			$results = $wxpay_res->notify();
			$out_trade_no=$results['out_trade_no'];
			$total_fee=$results['total_fee']/100;

			$mycz = M('Mycz')->where(array('tradeno' => $out_trade_no,'num' => $total_fee))->find();
			if (!$mycz) {
				$this->error('充值订单不存在！');
			}
			if ($mycz['status'] != 0) {
				$this->error('订单已经处理过！');
			}
			$rs = M('Mycz')->where(array('id' => $mycz['id']))->save(array('status' => 3));
			if ($rs) {
				$res = $this->myczQueren($mycz['id']);
				if($res){
					M('Mycz')->where(array('id'=>$mycz['id']))->save(array('ewmname'=>''));
					echo "success";
					exit;
				}
			}else {
				$this->error('订单更新失败！');
			}
		}
	}
	
	private function myczQueren($id)
	{
		if (empty($id)) {
			$this->error('缺少参数!');
		}

		$mycz = M('Mycz')->where(array('id' => $id))->find();
		
		if (($mycz['status'] != 0) && ($mycz['status'] != 3)) {
			$this->error('已经处理，禁止再次操作！');
		}
		$fp = fopen("./lockcz.txt", "w+");
		if(flock($fp,LOCK_EX | LOCK_NB))
		{
			$mo = M();
			$mo->startTrans();
			//$mo->execute('lock tables tw_user_coin write,tw_mycz write,tw_finance write');
			$rs = array();
			$finance = $mo->table('tw_finance')->where(array('userid' => $mycz['userid']))->order('id desc')->find();
			$finance_num_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $mycz['userid']))->find();
			$ucoin = $mycz['num']*(1-$mycz['fee']/100);
			$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $mycz['userid']))->setInc('becc', $ucoin);
			$rs[] = $mo->table('tw_mycz')->where(array('id' => $mycz['id']))->save(array('status' => 1, 'mum' => $mycz['mum'], 'endtime' => time()));
			$finance_mum_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $mycz['userid']))->find();
			$finance_hash = md5($mycz['userid'] . $finance_num_user_coin['becc'] . $finance_num_user_coin['beccd'] . $mycz['mum'] . $finance_mum_user_coin['becc'] . $finance_mum_user_coin['beccd'] . MSCODE);
			$finance_num = $finance_num_user_coin['becc'] + $finance_num_user_coin['beccd'];

			if ($finance['mum'] < $finance_num) {
				$finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);
			}
			else {
				$finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);
			}

			$rs[] = $mo->table('tw_finance')->add(array('userid' => $mycz['userid'], 'coinname' => 'becc', 'num_a' => $finance_num_user_coin['becc'], 'num_b' => $finance_num_user_coin['beccd'], 'num' => $finance_num_user_coin['becc'] + $finance_num_user_coin['beccd'], 'fee' => $mycz['num'], 'type' => 1, 'name' => 'mycz', 'nameid' => $mycz['id'], 'remark' => '人民币充值-自动充值', 'mum_a' => $finance_mum_user_coin['becc'], 'mum_b' => $finance_mum_user_coin['beccd'], 'mum' => $finance_mum_user_coin['becc'] + $finance_mum_user_coin['beccd'], 'move' => $finance_hash, 'addtime' => time(), 'status' => $finance_status));

			if (check_arr($rs)) {
				$mo->commit();
				$message="操作成功";
				$res=1;
			}
			else {
				$mo->rollback();
				$message="操作失败";
				$res=0;
			}
			flock($fp,LOCK_UN);
		}else{
			$message="请稍后提交";
			$res=0;
		}
		fclose($fp);
		if($res==1){
			return true;
		}else{
			return false;
		}
	}

	public function ecpss($id = NULL)
	{
		if (!userid()) {
			$this->error('请先登录！');
		}

		if (empty($id)) {
			$this->error('参数错误！');
		}

		$mycz = M('Mycz')->where(array('id' => $id))->find();

		if (!$mycz) {
			$this->error('订单不存在！');
		}

		if ($mycz['userid'] != userid()) {
			$this->error('参数非法！');
		}

		$this->error('订单不存在！');
	}
}

?>