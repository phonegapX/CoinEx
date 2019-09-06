# 数字货币交易所

该项目为交易所项目,为用户提供在线的虚拟货币交易,行情查看,自动买卖

数据库路径 /Database/yingbt.sql

## 目录结构
    api  WEB部署目录
    ├─index.php       总入口文件
    ├─README.md       README文件
    ├─Application     应用目录
    │  ├─Admin        后台模块 负责后台各项功能与操作
    │  ├─common       公共函数目录
    │  ├─Home         前台模块 负责用户各项操作与功能
    │  ├─Mobile       手机端模块 负责手机访问时的展示与处理
    ├─Public          资源文件目录
    ├─Upload          上传资源目录
    └─ThinkPHP        框架目录
    └─run.php         执行数据同步操作(虚拟货币提币充币与查询，真实转账与交易)         



# 其他备注

新建的字段需要写上注释


# 数据库表结构

tw_user_coin 用户钱包表  

tw_coin      系统币种根据的置表

tw_trade_json 交易图表数据(按时间分隔)


# 前端API接口


###  POST根据的 /Ajax/getkey  获取API的key 

请求参数
 
 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 remarks  | 是  | string | 备注 
 ip  | 否  | string | 限制ip 



 响应结果:

 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 AccessKey  | 是  | string | API请求所必须的key 
 SecretKey  | 是  | string | 私钥 
 user_id    | 是  | int | 用户id
 remarks	| 是  | string | 备注
 ip         | 否  | string | 绑定ip


 返回示例:

	{
		"remarks": "test",
		"ip": "114.25.12.2",
		"user_id": "6277",
		"AccessKey": "myiyiloxpfr51l7zxxa7jdrz5xy5m2zv",
		"SecretKey": "rcc4wzbnxlxxolm57lx90746mp5sf4ti"
	}


 --------------------------------------------------------

# 用户API接口

请务必在Header中设置 X-Requested-With: XMLHttpRequest


用户的API权限在网站的 账户->API管理 中获取。点击创建即可获得。


### 重要提示：这两个密钥与账号安全紧密相关，无论何时都请勿向其它人透露。


签名方法（SignatureMethod） 用户计算签名的基于哈希的协议，此处使用 HmacSHA256


签名示例:

例如：在撤销委托订单的API接口中,对于如下的参数进行签名,假设Secret Key是:gnlzv781mgzsvkywj6tkhn1ogfeycbuu
		
	AccessKey=wgpthr6mz7uvaf8y5tcwiququh54jbxd&id=3405&time=1538100485

使用HmacSHA256算法,用SecretKey作为秘钥.对字符串进行签名 并对签名后的内容进行md5数字签名 获得md5值为:618ec861b1c3ff9a2fb2a9aec1ab7522

最终组合成的请求参数如下:

	AccessKey=wgpthr6mz7uvaf8y5tcwiququh54jbxd&id=3405&time=1538100485&sign=618ec861b1c3ff9a2fb2a9aec1ab7522




###  GET /api/accounts  获取用户基本信息

请求参数
 
 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 AccessKey  | 是  | string | API请求所必须的key  




 响应结果:

 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 code  | 是  | int | 1 请求成功 -1 请求失败
 msg  | 是  | string | 返回的提示消息
 code  | 是  | string | 返回的详细数据
 id  | 是  | int | 用户id
 username  | 是  | string | 用户名
 status  | 是  | int | 1 正常  0 冻结




 返回示例:
 
	{
		"code": 1,
		"msg": "Success",
		"data": [{
			"id": "6277",
			"username": "abc@qq.com",
			"status": "1"
		}]
	}





###  GET /api/get_time  获取当前时间戳

请求参数
 
 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 AccessKey  | 是  | string | API请求所必须的key  




 响应结果:

 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 code  | 是  | int | 1 请求成功 -1 请求失败
 msg  | 是  | string | 返回的提示消息
 data  | 是  | int | 系统时间戳(东八区)




 返回示例:
 
	{
	"code": 1,
	"msg": "Success",
	"data": 1537344636
	}


###  GET /api/currencys 获取支持的币种

请求参数
 
 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 AccessKey  | 是  | string | API请求所必须的key  




 响应结果:

 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 code  | 是  | int | 1 请求成功 -1 请求失败
 msg  | 是  | string | 返回的提示消息
 data  | 是  | string | 支持的币种




 返回示例:


	{
	"code": 1,
	"msg": "Success",
	"data": ["btc", "ltc", "bcc", "eth"]
	}



###  GET /api/balance 获取用户账户交易余额与冻结余额

请求参数
 
 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 AccessKey  | 是  | string | API请求所必须的key  




 响应结果:

 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 code  | 是  | int    | 1 请求成功 -1 请求失败
 msg   | 是  | string | 返回的提示消息
 data  | 是  | string | 返回余额
 btc   | 否  | double | 比特币余额
 btcd  | 否  | double | 比特币冻结数量
 eth   | 否  | double | 以太币余额
 ethd  | 否  | double | 以太币冻结数量
 ltc   | 否  | double | 莱特币余额
 ltcd  | 否  | double | 莱特币冻结数量



 返回示例:

	{
	"code": 1,
	"msg": "Success",
	"data": {
		"btc": "999495.70961240",
		"ltc": "975.00000000",
		"eth": "0.00000000",
		"btcd": "53.30785460",
		"ltcd": "0.00000000",
		"ethd": "0.00000000"
		}
	}

###  POST /api/place 进行交易

请务必在Header中设置 X-Requested-With: XMLHttpRequest

请求参数
 
 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 AccessKey  | 是  | string | API请求所必须的key  
 price		| 是  | double | 交易价格
 num		| 是  | double | 交易数量
 market		| 是  | string | 交易币种(ltc_btc 或 bcc_btc 或 ltc_eth)
 type		| 是  | int    | 交易类型 1 买入  2 卖出
 time 		| 是  | int    | 时间戳(东八区时间)
 sign       | 是  | string | 签名

 响应结果:

 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 into  | 是  | string    | 交易信息提示
 status| 是  | 	int      | 交易状态 1 成功   0 失败 
 url   | 否  | string    | 其他提示



 返回示例:

	{
		"code": 1,
		"msg": "交易成功！",
		"data": ""
	}



###  POST /api/cancelOrder 撤销委托订单



请求参数
 
 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 AccessKey  | 是  | string | API请求所必须的key  
 id		    | 是  | int    | 订单id
 time  		| 是  | int    | 时间戳(东八区)
 sign       | 是  | string | 签名

 响应结果:

 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 into  | 是  | string    | 交易信息提示
 status| 是  | 	int      | 订单撤销 1 成功   0 失败 
 url   | 否  | string    | 其他提示




 请求示例:

	POST /api/cancelOrder HTTP/1.1
	Host: www.hostname.com
	Connection: keep-alive
	Content-Length: 105
	Pragma: no-cache
	Content-Type: application/x-www-form-urlencoded; charset=UTF-8

	AccessKey=wgpthr6mz7uvaf8y5tcwiququh54jbxd&id=11111&time=1538100485&sign=94556ac4d85ca50c0197ab6e043173a7


 返回示例:

	{
		"code": 1,
		"msg": "撤销成功",
		"data": ""
	}





###  GET /api/orders 获取委托订单

请求参数
 
 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 AccessKey  | 是  | string | API请求所必须的key  
 start_time | 否  | int | 最早的委托时间(时间戳)
 end_time   | 否  | int | 截止的委托时间(时间戳)
 type       | 否  | int | 交易类型 0 全部交易  1买入 2卖出



 响应结果:

 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 code  | 是  | int    | 1 请求成功 -1 请求失败
 msg   | 是  | string | 返回的提示消息
 data  | 是  | string | 返回委托订单主体
 id    | 是  | int | 委托订单id
 userid| 是  | int | 用户id
 market| 是  | string | 交易币种 
 price | 是  | double | 交易价格
 num   | 是  | double | 交易数量
 deal  | 是  | double | 已成交量
 mum   | 是  | double | 交易总额(含手续费)
 fee   | 是  | double | 手续费
 type  | 是  | int | 交易类型 1买入   2卖出
 sort  | 是  | int | 交易排序
 addtime|是  | int |交易发起时间
 endtime|否  | int |交易结束时间
 status |是  | int |交易状态 0交易中 1已完成 2已撤销


 返回示例:

	{
	"code": 1,
	"msg": "Success",
	"data": [{
		"id": "3189",
		"userid": "6277",
		"market": "ltc_btc",
		"price": "0.72200000",
		"num": "15.00000000",
		"deal": "9.00000000",
		"mum": "10.84083000",
		"fee": "0.01083000",
		"type": "1",
		"sort": "0",
		"addtime": "1537413054",
		"endtime": "0",
		"status": "0"
		}]
	}





###  GET /api/getTradelog 获取交易所交易数据

请求参数
 
 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 AccessKey  | 是  | string | API请求所必须的key  
 market     | 是  | string | 币种信息(ltc_btc 比特币兑莱特币 / bcc_btc 比特币兑比特现金 / ltc_eth 比特币兑以太坊)
 jjcoin     | 否  | string | 计价方式 (btc rmb usd)
 size       | 否  | int | 获取数据条数[1-2000]



 响应结果:

 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 code  | 是  | int    | 1 请求成功 -1 请求失败
 msg   | 是  | string | 返回的提示消息
 data  | 是  | string | 返回余额
 addtime| 是 | string | 成交时间
 type   | 是 | string | 交易类型(1 买入 2 卖出)
 price  | 是 | double | 交易价格
 num    | 是 | double | 交易数量
 


 	{	
	"code": 1,
	"msg": "Success",
	"data": {
		"tradelog": [{
			"addtime": "09-20 11:10:54",
			"type": "1",
			"price": 0.722,
			"num": 9,
			"mum": 6.498
		}, {
			"addtime": "09-20 10:45:48",
			"type": "1",
			"price": 0.722,
			"num": 1,
			"mum": 0.722
		}]
	}
	}







###  GET /Api/kline 获取市场交易行情K线

#### 注意:header头中请务必加上 Content-Type: application/x-www-form-urlencoded

请求参数
 
 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 AccessKey  | 是  | string | API请求所必须的key  
 market  	| 否  | string | 币种信息(ltc_btc 比特币兑莱特币 / bcc_btc 比特币兑比特现金 / ltc_eth 比特币兑以太坊)
 type	    | 否  | string | 周期(1min / 3min / 5min / 15min / 30min / 1hour / 2hour / 4hour / 6hour / 12hour / 1day /3day / 1week)
 size	 	| 否  | int    | 要获取的数据条数


 响应结果:

 参数名称  | 是否必须  | 数据类型 | 描述 | 
 ---- | ----- | ------ | ----
 code  | 是  | int    | 返回状态
 msg   | 是  | string | 返回信息
 data  | 是  | string | 行情信息(1.时间 2.开盘价 3.最高价 4.最低价 5.收盘价 6.交易量)



POST发包格式:

	POST /Home/Ajax/kline HTTP/1.1
	Host: www.hostname.com
	Connection: keep-alive
	Content-Length: 10
	Content-Type: application/x-www-form-urlencoded

	type=1week



 返回示例:

	{
	"code": 1,
	"msg": "Success",
	"data": [
		[1537156802000, 0.51, 0.51, 0.3, 0.3, 2554],
		[1537243201000, 0.365, 0.51, 0.365, 0.51, 537],
		[1537329601000, 0.51, 0.51, 0.51, 0.51, 0],
		[1537416001000, 0.54, 0.722, 0.54, 0.722, 1409],
		[1537502401000, 0.722, 0.722, 0.722, 0.722, 0],
		[1537848001000, 0.722, 0.722, 0.722, 0.722, 0],
		[1537934401000, 0.722, 0.722, 0.722, 0.722, 0]
	]
	}

