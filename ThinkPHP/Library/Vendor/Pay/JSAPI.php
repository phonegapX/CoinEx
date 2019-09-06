<?php 

//error_reporting(E_ERROR);
require_once dirname(__FILE__)."/lib/WxPay.Api.php";
require_once dirname(__FILE__)."/example/WxPay.JsApiPay.php";
require_once dirname(__FILE__)."/example/WxPay.MicroPay.php";
require_once dirname(__FILE__)."/example/WxPay.NativePay.php";

// 复制pay文件夹到vendor文件夹下
// 退款例子
// vendor('Pay.JSAPI');
// $out_trade_no = 160129052221588;
// $total_fee = 1200;
// $refund_fee = $total_fee;
// $input = new \WxPayRefund();
// $input->SetOut_trade_no($out_trade_no);
// $input->SetTotal_fee($total_fee);
// $input->SetRefund_fee($refund_fee);
// $input->SetOut_refund_no(\WxPayConfig::MCHID.date("YmdHis"));
// $input->SetOp_user_id(\WxPayConfig::MCHID);
// print_r(\WxPayApi::refund($input));