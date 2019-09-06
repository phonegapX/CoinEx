/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50634
Source Host           : localhost:3306
Source Database       : yingbt

Target Server Type    : MYSQL
Target Server Version : 50634
File Encoding         : 65001

Date: 2018-09-05 15:21:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tw_admin
-- ----------------------------
DROP TABLE IF EXISTS `tw_admin`;
CREATE TABLE `tw_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL DEFAULT '',
  `username` char(16) NOT NULL,
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `moble` varchar(50) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL,
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0',
  `last_login_ip` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员表';

-- ----------------------------
-- Records of tw_admin
-- ----------------------------
INSERT INTO `tw_admin` VALUES ('1', '', 'admin', '', '', 'e10adc3949ba59abbe56e057f20f883e', '0', '0', '0', '0', '0', '1');

-- ----------------------------
-- Table structure for tw_adver
-- ----------------------------
DROP TABLE IF EXISTS `tw_adver`;
CREATE TABLE `tw_adver` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `img` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `look` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 电脑端 1手机端',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='广告图片表';

-- ----------------------------
-- Records of tw_adver
-- ----------------------------

-- ----------------------------
-- Table structure for tw_article
-- ----------------------------
DROP TABLE IF EXISTS `tw_article`;
CREATE TABLE `tw_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8,
  `adminid` int(10) unsigned NOT NULL DEFAULT '1',
  `type` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `footer` int(11) unsigned NOT NULL DEFAULT '0',
  `index` int(11) unsigned NOT NULL DEFAULT '0',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `img` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title_en` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `content_en` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `adminid` (`adminid`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tw_article
-- ----------------------------
INSERT INTO `tw_article` VALUES ('32', '喜迎十九大召开', '<p>喜迎十九大召开</p>', '1', 'news', '0', '1', '1', '0', '1503825749', '1503763200', '0', '', 'Welcomes the convening of nineteen', '<p>Welcomes the convening of nineteen</p>');
INSERT INTO `tw_article` VALUES ('33', '法律声明', '', '1', 'aboutus', '0', '1', '1', '2', '1503825988', '1503763200', '0', '', '', null);
INSERT INTO `tw_article` VALUES ('34', '免责声明', '', '1', 'aboutus', '0', '1', '1', '3', '1503826010', '1503763200', '0', '', '', null);
INSERT INTO `tw_article` VALUES ('35', '注册指南', '', '1', 'firstclass', '0', '1', '1', '0', '1503826557', '1503763200', '0', '', '', null);
INSERT INTO `tw_article` VALUES ('36', '交易指南', '', '1', 'firstclass', '0', '1', '1', '0', '1503826599', '1503763200', '0', '', '', null);
INSERT INTO `tw_article` VALUES ('37', '充值提现', '<p>本平台是币币交易平台，暂不支持法币直接参与充值提现，只支持莱特币(LTC)、比特币(BTC)、超级现金(BCC)、建设链(BECC)、币付币(CPC)的充值提现业务。敬请谅解！</p>', '1', 'notice', '0', '1', '1', '1', '1506159428', '1506159428', '0', '', 'Deposit and Withdraw', '<p>Deposit and Withdraw</p>');
INSERT INTO `tw_article` VALUES ('39', '转币指南', '', '1', 'firstclass', '0', '1', '1', '0', '1503826766', '1503763200', '0', '', '', null);
INSERT INTO `tw_article` VALUES ('40', '交易手续费', '', '1', 'firstclass', '0', '1', '1', '0', '1503826855', '1503763200', '0', '', '', null);
INSERT INTO `tw_article` VALUES ('74', '收不到邮件怎么办？', '', '1', 'faq', '0', '0', '0', '100', '1501743208', '1501689600', '0', '', '', null);
INSERT INTO `tw_article` VALUES ('75', '用户注册', '', '1', 'firstclass', '0', '1', '1', '1', '1503818514', '1503799200', '0', '', '', null);
INSERT INTO `tw_article` VALUES ('77', '费用说明', '', '1', 'aboutus', '0', '1', '1', '4', '1503546836', '1503504000', '0', '', 'Cost statement', '');
INSERT INTO `tw_article` VALUES ('84', '比特币疯涨带动市场火热 加密币成美国创业公司融资新手段', '', '1', 'hyzx', '0', '1', '1', '1', '1503726536', '1503676800', '0', '', 'Bitcoin skyrocketing driven market fiery encryption currency  the new means of American venture financing', '<p><a href=\\\"\\\\&quot;http://fanyi.baidu.com/?aldtype=85&keyfrom=alading###\\\\&quot;\\\" class=\\\"\\\\&quot;operate-btn\\\" style=\\\"\\\\&quot;display:\\\"></a></p><p>Bitcoin skyrocketing driven market fiery encryption currency into the new means of American venture financing<a class=\\\"\\\\&quot;operate-btn\\\" title=\\\"\\\\&quot;添加到收藏夹\\\\&quot;\\\" style=\\\"\\\\&quot;display:\\\"></a></p>');
INSERT INTO `tw_article` VALUES ('91', '用户注册协议', '<p>用户注册协议<br/></p>', '1', 'aboutus', '0', '1', '1', '1', '1503847449', '1503763200', '0', '', 'User agreement', '<p>User agreement</p>');
INSERT INTO `tw_article` VALUES ('104', '关于落实《中国人民银行 中央网信办 工业和信息化部 工商总局 银监会 证监会 保监会关于防范代币发行融资风险的公告》文件精神的公告', '', '1', 'notice', '0', '1', '1', '2', '1504520384', '1504454400', '0', '', 'Announcement on the implementation of the spirit of the announcement of the Ministry of industry and', '<p>Announcement on the implementation of the spirit of the announcement of the Ministry of industry and commerce, the China Banking Regulatory Commission, the China Banking Regulatory Commission, the CSRC and the CIRC on the prevention of the financing risks of the issue of tokens issued by the Ministry of industry and information technology of the people&#39;s Bank of China</p>');
INSERT INTO `tw_article` VALUES ('106', '关于我们', '<table><tbody><tr class=\\\"firstRow\\\"><td width=\\\"763\\\" valign=\\\"top\\\" style=\\\"word-break: break-all;\\\"><p style=\\\"white-space: normal;\\\">币付在线是一家专业的数字资产交易平台，安全、稳定、快捷、可信赖！邀请好友注册交易可免费得到CPC和每天CPC发放的鼓励金！<br/></p><p style=\\\"white-space: normal;\\\"><br/></p><p style=\\\"white-space: normal;\\\"><br/></p><p style=\\\"white-space: normal;\\\"><br/></p><p style=\\\"white-space: normal; text-align: justify;\\\"><span style=\\\"color: rgb(51, 51, 51); font-family: &#39;Microsoft Yahei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Helvetica Neue&#39;, Helvetica, tahoma, arial, Verdana, sans-serif, &#39;WenQuanYi Micro Hei&#39;, 宋体; font-size: 12px; orphans: 2; widows: 2; background-color: rgb(255, 255, 255);\\\">　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　联系方式:　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　ＱＱ：428203483</span></p><p style=\\\"white-space: normal; text-align: justify;\\\"><span style=\\\"color: rgb(51, 51, 51); font-family: &#39;Microsoft Yahei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Helvetica Neue&#39;, Helvetica, tahoma, arial, Verdana, sans-serif, &#39;WenQuanYi Micro Hei&#39;, 宋体; font-size: 12px; orphans: 2; widows: 2; background-color: rgb(255, 255, 255);\\\">　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　邮箱&nbsp;</span><a class=\\\"link\\\" href=\\\"mailto:contact@coincola.com\\\" style=\\\"word-wrap: break-word; outline: 0px; cursor: pointer; color: rgb(23, 152, 242); font-family: &#39;Microsoft Yahei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Helvetica Neue&#39;, Helvetica, tahoma, arial, Verdana, sans-serif, &#39;WenQuanYi Micro Hei&#39;, 宋体; font-size: 12px; orphans: 2; widows: 2; background-color: rgb(255, 255, 255); text-decoration: none !important;\\\">admin@coinonline.club</a></p><p style=\\\"white-space: normal;\\\"><br/></p></td></tr></tbody></table><p><br/></p><p><br/></p><p><br/></p><p><br/></p><p><br/></p><p><br/></p><p style=\\\"text-align: justify;\\\">　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　<br/></p>', '1', 'aboutus', '0', '1', '1', '0', '1506257934', '1506182400', '0', '', 'About us', '<p>about us</p>');

-- ----------------------------
-- Table structure for tw_article_type
-- ----------------------------
DROP TABLE IF EXISTS `tw_article_type`;
CREATE TABLE `tw_article_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `remark` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `index` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `footer` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `shang` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `content` text COLLATE utf8_unicode_ci,
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  `title_en` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tw_article_type
-- ----------------------------
INSERT INTO `tw_article_type` VALUES ('25', 'firstclass', '交易入门', '', '0', '0', '', '', '0', '1492617600', '1492617600', '0', '');
INSERT INTO `tw_article_type` VALUES ('26', 'faq', '常见问题', '', '1', '0', '', '<p>常见问题<br/></p>', '0', '1492012800', '1492617600', '0', '');
INSERT INTO `tw_article_type` VALUES ('27', 'aboutus', '关于我们', '', '1', '1', '', '<p>关于我们。</p>', '1', '1492790400', '1492790400', '1', 'About us');
INSERT INTO `tw_article_type` VALUES ('30', 'notice', '官方公告', '', '1', '0', '', '<p>系统内测公告</p>', '1', '1494950400', '1494950400', '1', 'Announcement');
INSERT INTO `tw_article_type` VALUES ('31', 'hyzx', '行业资讯', '', '1', '0', '', '', '1', '1494950400', '1494950400', '1', 'Industry information');
INSERT INTO `tw_article_type` VALUES ('32', 'news', '新闻资讯', '', '1', '0', '', '', '1', '1492617600', '1492617600', '1', 'News information');

-- ----------------------------
-- Table structure for tw_auth_extend
-- ----------------------------
DROP TABLE IF EXISTS `tw_auth_extend`;
CREATE TABLE `tw_auth_extend` (
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) unsigned NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限',
  UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`),
  KEY `uid` (`group_id`),
  KEY `group_id` (`extend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tw_auth_extend
-- ----------------------------
INSERT INTO `tw_auth_extend` VALUES ('1', '1', '1');
INSERT INTO `tw_auth_extend` VALUES ('1', '1', '2');
INSERT INTO `tw_auth_extend` VALUES ('1', '2', '1');
INSERT INTO `tw_auth_extend` VALUES ('1', '2', '2');
INSERT INTO `tw_auth_extend` VALUES ('1', '3', '1');
INSERT INTO `tw_auth_extend` VALUES ('1', '3', '2');
INSERT INTO `tw_auth_extend` VALUES ('1', '4', '1');
INSERT INTO `tw_auth_extend` VALUES ('1', '37', '1');

-- ----------------------------
-- Table structure for tw_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `tw_auth_group`;
CREATE TABLE `tw_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tw_auth_group
-- ----------------------------
INSERT INTO `tw_auth_group` VALUES ('1', 'admin', '1', '资讯管理员', '拥有网站文章资讯相关权限', '-1', '424,426,431,441,446,449,453,461,475,501,502,503,508,509,510,515,516');
INSERT INTO `tw_auth_group` VALUES ('2', 'admin', '1', '财务管理组', '拥有网站资金相关的权限', '-1', '431');
INSERT INTO `tw_auth_group` VALUES ('4', 'admin', '1', '资讯管理员', '拥有网站文章资讯相关权限11', '-1', '');
INSERT INTO `tw_auth_group` VALUES ('11', 'admin', '1', 'superadmin', '超级管理员', '0', '');
INSERT INTO `tw_auth_group` VALUES ('12', 'admin', '1', 'caiwu', '财务管理', '0', '1721,1722,1724,1731,1732,1733,1734,1735,1736,1739,1740,1764,1765,1767,1779,1780,1783,1786,1787,1788,1789,1800,1803,1806,1808,1810,1819,1821,1822,1845,1846,1864,1865,1866,1869,1876,1877,1878,1879,1880,1881,1882');
INSERT INTO `tw_auth_group` VALUES ('14', 'admin', '1', 'neirong', '内容管理', '0', '');
INSERT INTO `tw_auth_group` VALUES ('15', 'admin', '1', 'neirong', '内容管理', '0', '');
INSERT INTO `tw_auth_group` VALUES ('16', 'admin', '1', 'neirong', '内容管理', '0', '');
INSERT INTO `tw_auth_group` VALUES ('17', 'admin', '1', 'neirong', '内容管理', '1', '');

-- ----------------------------
-- Table structure for tw_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `tw_auth_group_access`;
CREATE TABLE `tw_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tw_auth_group_access
-- ----------------------------
INSERT INTO `tw_auth_group_access` VALUES ('3', '2');
INSERT INTO `tw_auth_group_access` VALUES ('1', '11');
INSERT INTO `tw_auth_group_access` VALUES ('2', '12');
INSERT INTO `tw_auth_group_access` VALUES ('4', '12');
INSERT INTO `tw_auth_group_access` VALUES ('5', '14');

-- ----------------------------
-- Table structure for tw_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `tw_auth_rule`;
CREATE TABLE `tw_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=1897 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tw_auth_rule
-- ----------------------------
INSERT INTO `tw_auth_rule` VALUES ('425', 'admin', '1', 'Admin/article/add', '新增', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('427', 'admin', '1', 'Admin/article/setStatus', '改变状态', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('428', 'admin', '1', 'Admin/article/update', '保存', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('429', 'admin', '1', 'Admin/article/autoSave', '保存草稿', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('430', 'admin', '1', 'Admin/article/move', '移动', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('432', 'admin', '2', 'Admin/Article/mydocument', '内容', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('437', 'admin', '1', 'Admin/Trade/config', '交易配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('449', 'admin', '1', 'Admin/Index/operate', '市场统计', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('455', 'admin', '1', 'Admin/Issue/config', '认购配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('457', 'admin', '1', 'Admin/Index/database/type/export', '数据备份', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('461', 'admin', '1', 'Admin/Article/chat', '聊天列表', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('464', 'admin', '1', 'Admin/Index/database/type/import', '数据还原', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('471', 'admin', '1', 'Admin/Mytx/config', '提现配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('472', 'admin', '2', 'Admin/Mytx/index', '提现', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('473', 'admin', '1', 'Admin/Config/market', '市场配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('477', 'admin', '1', 'Admin/User/myzr', '转入虚拟币', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('479', 'admin', '1', 'Admin/User/myzc', '转出虚拟币', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('482', 'admin', '2', 'Admin/ExtA/index', '扩展', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('488', 'admin', '1', 'Admin/Auth_manager/createGroup', '新增用户组', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('499', 'admin', '1', 'Admin/ExtA/index', '扩展管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('509', 'admin', '1', 'Admin/Article/adver_edit', '编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('510', 'admin', '1', 'Admin/Article/adver_status', '修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('513', 'admin', '1', 'Admin/Issue/index_edit', '认购编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('514', 'admin', '1', 'Admin/Issue/index_status', '认购修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('515', 'admin', '1', 'Admin/Article/chat_edit', '编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('516', 'admin', '1', 'Admin/Article/chat_status', '修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('517', 'admin', '1', 'Admin/User/coin_edit', 'coin修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('519', 'admin', '1', 'Admin/Mycz/type_status', '状态修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('520', 'admin', '1', 'Admin/Issue/log_status', '认购状态', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('521', 'admin', '1', 'Admin/Issue/log_jiedong', '认购解冻', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('522', 'admin', '1', 'Admin/Tools/database/type/export', '数据备份', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('525', 'admin', '1', 'Admin/Config/coin_edit', '编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('526', 'admin', '1', 'Admin/Config/coin_add', '编辑币种', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('527', 'admin', '1', 'Admin/Config/coin_status', '状态修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('528', 'admin', '1', 'Admin/Config/market_edit', '编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('530', 'admin', '1', 'Admin/Tools/database/type/import', '数据还原', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('541', 'admin', '2', 'Admin/Trade/config', '交易', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('569', 'admin', '1', 'Admin/ADVERstatus', '修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('570', 'admin', '1', 'Admin/Tradelog/index', '交易记录', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('585', 'admin', '1', 'Admin/Config/mycz', '充值配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('590', 'admin', '1', 'Admin/Mycztype/index', '充值类型', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('600', 'admin', '1', 'Admin/Usergoods/index', '用户联系地址', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1379', 'admin', '1', 'Admin/Bazaar/index', '集市管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1405', 'admin', '1', 'Admin/Bazaar/config', '集市配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1425', 'admin', '1', 'Admin/Bazaar/log', '集市记录', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1451', 'admin', '1', 'Admin/Bazaar/invit', '集市推广', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1519', 'admin', '2', 'Admin/Finance/index', '财务', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1575', 'admin', '1', 'Admin/Shop/index', '商品管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1576', 'admin', '1', 'Admin/Issue/index', '认购管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1577', 'admin', '1', 'Admin/Issue/log', '认购记录', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1578', 'admin', '1', 'Admin/Huafei/index', '充值记录', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1579', 'admin', '1', 'Admin/Huafei/config', '充值配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1580', 'admin', '1', 'Admin/Vote/index', '投票记录', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1581', 'admin', '1', 'Admin/Vote/type', '投票类型', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1582', 'admin', '1', 'Admin/Money/index', '理财管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1599', 'admin', '1', 'Admin/Config/moble', '短信配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1606', 'admin', '1', 'Admin/Shop/config', '商城配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1607', 'admin', '1', 'Admin/Money/log', '理财日志', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1623', 'admin', '1', 'Admin/Shop/type', '商品类型', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1624', 'admin', '1', 'Admin/Fenhong/index', '分红管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1625', 'admin', '1', 'Admin/Huafei/type', '充值金额', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1626', 'admin', '1', 'Admin/Money/fee', '理财明细', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1651', 'admin', '1', 'Admin/Shop/coin', '付款方式', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1652', 'admin', '1', 'Admin/Huafei/coin', '付款方式', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1673', 'admin', '1', 'Admin/Shop/log', '购物记录', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1674', 'admin', '1', 'Admin/Fenhong/log', '分红记录', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1681', 'admin', '1', 'Admin/Shop/goods', '收货地址', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1701', 'admin', '1', 'Admin/AuthManager/createGroup', '新增用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1702', 'admin', '1', 'Admin/AuthManager/editgroup', '编辑用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1703', 'admin', '1', 'Admin/AuthManager/writeGroup', '更新用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1704', 'admin', '1', 'Admin/AuthManager/changeStatus', '改变状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1705', 'admin', '1', 'Admin/AuthManager/access', '访问授权', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1706', 'admin', '1', 'Admin/AuthManager/category', '分类授权', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1707', 'admin', '1', 'Admin/AuthManager/user', '成员授权', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1708', 'admin', '1', 'Admin/AuthManager/tree', '成员列表授权', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1709', 'admin', '1', 'Admin/AuthManager/group', '用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1710', 'admin', '1', 'Admin/AuthManager/addToGroup', '添加到用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1711', 'admin', '1', 'Admin/AuthManager/removeFromGroup', '用户组移除', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1712', 'admin', '1', 'Admin/AuthManager/addToCategory', '分类添加到用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1713', 'admin', '1', 'Admin/AuthManager/addToModel', '模型添加到用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1714', 'admin', '1', 'Admin/Trade/status', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1715', 'admin', '1', 'Admin/Trade/chexiao', '撤销挂单', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1716', 'admin', '1', 'Admin/Shop/images', '图片', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1717', 'admin', '1', 'Admin/Login/index', '用户登录', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1718', 'admin', '1', 'Admin/Login/loginout', '用户退出', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1719', 'admin', '1', 'Admin/User/setpwd', '修改管理员密码', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1720', 'admin', '1', 'Admin/Analog/console', '行情调整', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1721', 'admin', '2', 'Admin/Index/index', '系统', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1722', 'admin', '2', 'Admin/Article/index', '内容', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1723', 'admin', '2', 'Admin/User/index', '用户', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1724', 'admin', '2', 'Admin/Finance/mycz', '财务', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1725', 'admin', '2', 'Admin/Trade/index', '交易', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1726', 'admin', '2', 'Admin/Game/index', '应用', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1727', 'admin', '2', 'Admin/Config/index', '设置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1728', 'admin', '2', 'Admin/Operate/index', '运营', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1729', 'admin', '2', 'Admin/Tools/index', '工具', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1730', 'admin', '2', 'Admin/Cloud/index', '扩展', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1731', 'admin', '1', 'Admin/Index/index', '系统概览', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1732', 'admin', '1', 'Admin/Article/index', '文章管理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1733', 'admin', '1', 'Admin/Article/edit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1734', 'admin', '1', 'Admin/Text/index', '提示文字', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1735', 'admin', '1', 'Admin/Text/edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1736', 'admin', '1', 'Admin/Text/status', '修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1737', 'admin', '1', 'Admin/User/index', '用户管理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1738', 'admin', '1', 'Admin/User/config', '用户配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1739', 'admin', '1', 'Admin/Finance/index', '财务明细', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1740', 'admin', '1', 'Admin/Finance/myczTypeEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1741', 'admin', '1', 'Admin/Cloud/index', '云市场', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1742', 'admin', '1', 'Admin/Finance/config', '配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1743', 'admin', '1', 'Admin/Tools/index', '清理缓存', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1744', 'admin', '1', 'Admin/Finance/type', '类型', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1745', 'admin', '1', 'Admin/Finance/type_status', '状态修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1746', 'admin', '1', 'Admin/User/edit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1747', 'admin', '1', 'Admin/User/status', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1748', 'admin', '1', 'Admin/User/adminEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1749', 'admin', '1', 'Admin/User/adminStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1750', 'admin', '1', 'Admin/User/authEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1751', 'admin', '1', 'Admin/User/authStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1752', 'admin', '1', 'Admin/User/authStart', '重新初始化权限', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1753', 'admin', '1', 'Admin/User/logEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1754', 'admin', '1', 'Admin/User/logStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1755', 'admin', '1', 'Admin/User/qianbaoEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1756', 'admin', '1', 'Admin/Trade/index', '委托管理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1757', 'admin', '1', 'Admin/User/qianbaoStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1758', 'admin', '1', 'Admin/User/bankEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1759', 'admin', '1', 'Admin/User/bankStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1760', 'admin', '1', 'Admin/User/coinEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1761', 'admin', '1', 'Admin/User/coinLog', '财产统计', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1762', 'admin', '1', 'Admin/User/goodsEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1763', 'admin', '1', 'Admin/User/goodsStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1764', 'admin', '1', 'Admin/Article/typeEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1765', 'admin', '1', 'Admin/Article/youqingEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1766', 'admin', '1', 'Admin/Config/index', '基本配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1767', 'admin', '1', 'Admin/Article/adverEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1768', 'admin', '1', 'Admin/User/authAccess', '访问授权', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1769', 'admin', '1', 'Admin/User/authAccessUp', '访问授权修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1770', 'admin', '1', 'Admin/User/authUser', '成员授权', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1771', 'admin', '1', 'Admin/User/authUserAdd', '成员授权增加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1772', 'admin', '1', 'Admin/User/authUserRemove', '成员授权解除', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1773', 'admin', '1', 'Admin/Operate/index', '推广奖励', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1774', 'admin', '1', 'Admin/App/config', 'APP配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1775', 'admin', '1', 'AdminUser/detail', '后台用户详情', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1776', 'admin', '1', 'AdminUser/status', '后台用户状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1777', 'admin', '1', 'AdminUser/add', '后台用户新增', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1778', 'admin', '1', 'AdminUser/edit', '后台用户编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1779', 'admin', '1', 'Admin/Articletype/edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1780', 'admin', '1', 'Admin/Article/images', '上传图片', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1781', 'admin', '1', 'Admin/Adver/edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1782', 'admin', '1', 'Admin/Adver/status', '修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1783', 'admin', '1', 'Admin/Article/type', '文章类型', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1784', 'admin', '1', 'Admin/User/index_edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1785', 'admin', '1', 'Admin/User/index_status', '修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1786', 'admin', '1', 'Admin/Finance/mycz', '人民币充值', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1787', 'admin', '1', 'Admin/Finance/myczTypeStatus', '状态修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1788', 'admin', '1', 'Admin/Finance/myczTypeImage', '上传图片', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1789', 'admin', '1', 'Admin/Finance/mytxStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1790', 'admin', '1', 'Admin/Tools/dataExport', '备份数据库', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1791', 'admin', '1', 'Admin/Tools/dataImport', '还原数据库', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1792', 'admin', '1', 'Admin/User/admin', '管理员管理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1793', 'admin', '1', 'Admin/Trade/log', '成交记录', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1794', 'admin', '1', 'Admin/Issue/edit', '认购编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1795', 'admin', '1', 'Admin/Issue/status', '认购修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1796', 'admin', '1', 'Admin/Invit/config', '推广配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1797', 'admin', '1', 'Admin/App/vip_config_list', 'APP等级', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1798', 'admin', '1', 'Admin/Link/edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1799', 'admin', '1', 'Admin/Link/status', '修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1800', 'admin', '1', 'Admin/Index/coin', '币种统计', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1801', 'admin', '1', 'Admin/Cloud/update', '自动升级', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1802', 'admin', '1', 'Admin/Config/mobile', '短信配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1803', 'admin', '1', 'Admin/Index/market', '市场统计', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1804', 'admin', '1', 'Admin/Chat/edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1805', 'admin', '1', 'Admin/Chat/status', '修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1806', 'admin', '1', 'Admin/Article/adver', '广告管理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1807', 'admin', '1', 'Admin/Trade/chat', '交易聊天', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1808', 'admin', '1', 'Admin/Finance/myczType', '人民币充值方式', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1809', 'admin', '1', 'Admin/Usercoin/edit', '财产修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1810', 'admin', '1', 'Admin/Finance/mytxExcel', '导出选中', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1811', 'admin', '1', 'Admin/User/auth', '权限列表', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1812', 'admin', '1', 'Admin/Mycz/status', '修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1813', 'admin', '1', 'Admin/Mycztype/status', '状态修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1814', 'admin', '1', 'Admin/Config/contact', '客服配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1815', 'admin', '1', 'Admin/App/adsblock_list', 'APP广告板块', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1816', 'admin', '1', 'Admin/Tools/queue', '服务器队列', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1817', 'admin', '1', 'Admin/Tools/qianbao', '钱包检查', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1818', 'admin', '1', 'Admin/Cloud/game', '应用管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1819', 'admin', '1', 'Admin/Article/youqing', '友情链接', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1820', 'admin', '1', 'Admin/User/log', '登陆日志', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1821', 'admin', '1', 'Admin/Finance/mytx', '人民币提现', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1822', 'admin', '1', 'Admin/Finance/mytxChuli', '正在处理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1823', 'admin', '1', 'Admin/Config/bank', '银行配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1824', 'admin', '1', 'Admin/Config/bank_edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1825', 'admin', '1', 'Admin/Coin/edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1826', 'admin', '1', 'Admin/Coin/status', '状态修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1827', 'admin', '1', 'Admin/Market/edit', '编辑市场', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1828', 'admin', '1', 'Admin/Config/market_add', '状态修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1829', 'admin', '1', 'Admin/Tools/invoke', '其他模块调用', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1830', 'admin', '1', 'Admin/Tools/optimize', '优化表', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1831', 'admin', '1', 'Admin/Tools/repair', '修复表', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1832', 'admin', '1', 'Admin/Tools/del', '删除备份文件', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1833', 'admin', '1', 'Admin/Tools/export', '备份数据库', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1834', 'admin', '1', 'Admin/Tools/import', '还原数据库', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1835', 'admin', '1', 'Admin/Tools/excel', '导出数据库', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1836', 'admin', '1', 'Admin/Tools/exportExcel', '导出Excel', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1837', 'admin', '1', 'Admin/Tools/importExecl', '导入Excel', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1838', 'admin', '1', 'Admin/Config/coin', '币种配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1839', 'admin', '1', 'Admin/User/detail', '用户详情', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1840', 'admin', '1', 'Admin/App/ads_user', 'APP广告用户', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1841', 'admin', '1', 'Admin/Cloud/theme', '主题模板', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1842', 'admin', '1', 'Admin/Trade/comment', '币种评论', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1843', 'admin', '1', 'Admin/User/qianbao', '用户钱包', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1844', 'admin', '1', 'Admin/Trade/market', '交易市场', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1845', 'admin', '1', 'Admin/Finance/mytxConfig', '人民币提现配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1846', 'admin', '1', 'Admin/Finance/mytxChexiao', '撤销提现', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1847', 'admin', '1', 'Admin/Mytx/status', '状态修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1848', 'admin', '1', 'Admin/Mytx/excel', '取消', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1849', 'admin', '1', 'Admin/Mytx/exportExcel', '导入excel', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1850', 'admin', '1', 'Admin/Menu/index', '菜单管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1851', 'admin', '1', 'Admin/Menu/sort', '排序', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1852', 'admin', '1', 'Admin/Menu/add', '添加', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1853', 'admin', '1', 'Admin/Menu/edit', '编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1854', 'admin', '1', 'Admin/Cloud/kefu', '客服代码', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1855', 'admin', '1', 'Admin/Menu/del', '删除', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1856', 'admin', '1', 'Admin/Cloud/kefuUp', '使用', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1857', 'admin', '1', 'Admin/Menu/toogleHide', '是否隐藏', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1858', 'admin', '1', 'Admin/Menu/toogleDev', '是否开发', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1859', 'admin', '1', 'Admin/Menu/importFile', '导入文件', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1860', 'admin', '1', 'Admin/Menu/import', '导入', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1861', 'admin', '1', 'Admin/Config/text', '提示文字', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1862', 'admin', '1', 'Admin/User/bank', '提现地址', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1863', 'admin', '1', 'Admin/Trade/invit', '交易推荐', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1864', 'admin', '1', 'Admin/Finance/myzr', '虚拟币转入', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1865', 'admin', '1', 'Admin/Finance/mytxQueren', '确认提现', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1866', 'admin', '1', 'Admin/Finance/myzcQueren', '确认转出', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1867', 'admin', '1', 'Admin/Config/qita', '其他配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1868', 'admin', '1', 'Admin/User/coin', '用户财产', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1869', 'admin', '1', 'Admin/Finance/myzc', '虚拟币转出', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1870', 'admin', '1', 'Admin/Verify/code', '图形验证码', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1871', 'admin', '1', 'Admin/Verify/mobile', '手机验证码', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1872', 'admin', '1', 'Admin/Verify/email', '邮件验证码', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1873', 'admin', '1', 'Admin/Config/daohang', '导航配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1874', 'admin', '1', 'Admin/User/myzc_qr', '确认转出', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1875', 'admin', '1', 'Admin/User/amountlog', '资金变更日志', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1876', 'admin', '1', 'Admin/Article/status', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1877', 'admin', '1', 'Admin/Finance/myczStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1878', 'admin', '1', 'Admin/Finance/myczQueren', '确认到账', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1879', 'admin', '1', 'Admin/Article/typeStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1880', 'admin', '1', 'Admin/Article/youqingStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1881', 'admin', '1', 'Admin/Article/adverStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1882', 'admin', '1', 'Admin/Article/adverImage', '上传图片', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1883', 'admin', '1', 'Admin/User/feedback', '用户反馈', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1884', 'admin', '1', 'Admin/Finance/myczExcel', '导出excel', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1885', 'admin', '1', 'Admin/Tools/recoverzc', '恢复自动转出队列', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1886', 'admin', '1', 'Admin/Tools/chkzdzc', '查看自动转出队列状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1887', 'admin', '1', 'Admin/Finance/myzcBatch', '批量转出', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1888', 'admin', '1', 'Admin/Finance/myzcBatchLog', '批量转出错误日志', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1889', 'admin', '1', 'Admin/Trade/tradeExcel', '导出excel', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1890', 'admin', '1', 'Admin/Trade/tradelogExcel', '导出excel', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1891', 'admin', '1', 'Admin/Finance/ethtransfer', '以太坊转账', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1892', 'admin', '1', 'Admin//Admin/User/nameauth', '实名审核', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1893', 'admin', '1', 'Admin/User/invittree', '推荐关系', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1894', 'admin', '1', 'Admin/Finance/tradePrize', '交易奖励', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1895', 'admin', '1', 'Admin/Finance/incentive', '鼓励金', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1896', 'admin', '2', 'Admin/Finance/myzr', '财务', '1', '');

-- ----------------------------
-- Table structure for tw_coin
-- ----------------------------
DROP TABLE IF EXISTS `tw_coin`;
CREATE TABLE `tw_coin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `img` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `fee_bili` varchar(50) NOT NULL DEFAULT '',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) unsigned NOT NULL DEFAULT '0',
  `fee_meitian` varchar(200) NOT NULL DEFAULT '' COMMENT '每天限制',
  `dj_zj` varchar(200) NOT NULL DEFAULT '',
  `dj_dk` varchar(200) NOT NULL DEFAULT '',
  `dj_yh` varchar(200) NOT NULL DEFAULT '',
  `dj_mm` varchar(200) NOT NULL DEFAULT '',
  `zr_zs` varchar(50) NOT NULL DEFAULT '',
  `zr_jz` varchar(50) NOT NULL DEFAULT '',
  `zr_dz` varchar(50) NOT NULL DEFAULT '',
  `zr_sm` varchar(50) NOT NULL DEFAULT '',
  `zc_sm` varchar(50) NOT NULL DEFAULT '',
  `zc_fee` decimal(12,5) NOT NULL DEFAULT '0.00000',
  `zc_user` varchar(50) NOT NULL DEFAULT '',
  `zc_min` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `zc_max` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `zc_jz` varchar(50) NOT NULL DEFAULT '',
  `zc_zd` varchar(50) NOT NULL DEFAULT '',
  `js_yw` varchar(50) NOT NULL DEFAULT '',
  `js_sm` text,
  `js_qb` varchar(50) NOT NULL DEFAULT '',
  `js_ym` varchar(50) NOT NULL DEFAULT '',
  `js_gw` varchar(50) NOT NULL DEFAULT '',
  `js_lt` varchar(50) NOT NULL DEFAULT '',
  `js_wk` varchar(50) NOT NULL DEFAULT '',
  `cs_yf` varchar(50) NOT NULL DEFAULT '',
  `cs_sf` varchar(50) NOT NULL DEFAULT '',
  `cs_fb` varchar(50) NOT NULL DEFAULT '',
  `cs_qk` varchar(50) NOT NULL DEFAULT '',
  `cs_zl` varchar(50) NOT NULL DEFAULT '',
  `cs_cl` varchar(50) NOT NULL DEFAULT '',
  `cs_zm` varchar(50) NOT NULL DEFAULT '',
  `cs_nd` varchar(50) NOT NULL DEFAULT '',
  `cs_jl` varchar(50) NOT NULL DEFAULT '',
  `cs_ts` varchar(50) NOT NULL DEFAULT '',
  `cs_bz` varchar(50) NOT NULL DEFAULT '',
  `tp_zs` varchar(50) NOT NULL DEFAULT '',
  `tp_js` varchar(50) NOT NULL DEFAULT '',
  `tp_yy` varchar(50) NOT NULL DEFAULT '',
  `tp_qj` varchar(50) NOT NULL DEFAULT '',
  `sh_zd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='币种配置表';

-- ----------------------------
-- Records of tw_coin
-- ----------------------------
INSERT INTO `tw_coin` VALUES ('3', 'btc', 'qbb', '比特币', 'http://tebtc.oss-ap-southeast-1.aliyuncs.com/upload/bitcoin.png', '0', '0', '0', '0', '1', '0', '47.74.133.61', '8332', 'abc', '123', '0', '1', '6', '', '', '0.20000', '1Pkagvrp88Z8XrvCfFT4xfwR34s8gMRnfv', '0.01000', '10000.00000', '1', '0.01', 'BitCoin', '<p>比特币介绍2</p>', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0');
INSERT INTO `tw_coin` VALUES ('4', 'ltc', 'qbb', '莱特币', 'http://tebtc.oss-ap-southeast-1.aliyuncs.com/upload/ltc.png', '0', '0', '0', '0', '1', '0', '47.74.133.61', '9332', 'abc', '123', '0', '1', '6', '', '', '0.20000', 'LaUNJ8GY7XXT3CcapCUF2ZcVjm1QbBJgQS', '0.10000', '10000.00000', '1', '0.01', 'LiteCoin', '<p>莱特币介绍</p>', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0');
INSERT INTO `tw_coin` VALUES ('6', 'bcc', 'qbb', '比特币现金', 'http://tebtc.oss-ap-southeast-1.aliyuncs.com/upload/bcclogo.png', '0', '0', '0', '0', '1', '0', '47.74.133.61', '3467', 'abc', '123', '0', '1', '6', '', '', '0.20000', 'CGBDjtbhvUuBNZ5HeLKzJszTEwKCWCF4RE', '0.02000', '10000.00000', '1', '0.001', 'Bitcoin Cash', '<p>比特币现金详细介绍</p>', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0');
INSERT INTO `tw_coin` VALUES ('8', 'eth', 'qbb', '以太坊', 'http://tebtc.oss-ap-southeast-1.aliyuncs.com/upload/eth.png', '0', '0', '0', '0', '0', '0', '47.74.133.61', '3467', 'abc', '123', '0', '1', '2', '', '', '0.00000', '', '0.00100', '10000.00000', '1', '0.001', 'Ethereum', '<p>以太坊介绍<br/></p>', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0');

-- ----------------------------
-- Table structure for tw_coin_json
-- ----------------------------
DROP TABLE IF EXISTS `tw_coin_json`;
CREATE TABLE `tw_coin_json` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `data` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tw_coin_json
-- ----------------------------

-- ----------------------------
-- Table structure for tw_config
-- ----------------------------
DROP TABLE IF EXISTS `tw_config`;
CREATE TABLE `tw_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `footer_logo` varchar(200) NOT NULL DEFAULT '' COMMENT ' ',
  `kefu` varchar(200) NOT NULL DEFAULT '',
  `index_lejimum` varchar(200) NOT NULL DEFAULT '' COMMENT '设置',
  `login_verify` varchar(200) NOT NULL DEFAULT '' COMMENT '设置',
  `fee_meitian` varchar(200) NOT NULL DEFAULT '' COMMENT '设置',
  `top_name` varchar(200) NOT NULL DEFAULT '' COMMENT '设置',
  `web_name` varchar(200) NOT NULL DEFAULT '',
  `web_title` varchar(200) NOT NULL DEFAULT '',
  `web_logo` varchar(200) NOT NULL DEFAULT '',
  `web_llogo_small` varchar(200) NOT NULL DEFAULT '',
  `web_keywords` varchar(200) DEFAULT NULL,
  `web_description` varchar(200) DEFAULT NULL,
  `web_close` varchar(255) DEFAULT NULL,
  `web_close_cause` varchar(255) DEFAULT NULL,
  `web_icp` varchar(255) DEFAULT NULL,
  `web_cnzz` varchar(255) DEFAULT NULL,
  `web_ren` varchar(255) DEFAULT NULL,
  `web_reg` text,
  `market_mr` varchar(255) DEFAULT NULL,
  `xnb_mr` varchar(255) DEFAULT NULL,
  `rmb_mr` varchar(255) DEFAULT NULL,
  `web_waring` varchar(255) DEFAULT NULL,
  `moble_type` varchar(255) DEFAULT NULL,
  `moble_url` varchar(255) DEFAULT NULL,
  `moble_user` varchar(255) DEFAULT NULL,
  `moble_pwd` varchar(255) DEFAULT NULL,
  `contact_moble` varchar(255) DEFAULT NULL,
  `contact_weibo` text,
  `contact_tqq` text,
  `contact_qq` text,
  `contact_qqun` text,
  `contact_weixin` text,
  `contact_weixin_img` text,
  `contact_email` text,
  `contact_alipay` text,
  `contact_alipay_img` text,
  `contact_bank` text,
  `user_truename` text,
  `user_moble` text,
  `user_alipay` text,
  `user_bank` text,
  `user_text_truename` text,
  `user_text_moble` text,
  `user_text_alipay` text,
  `user_text_bank` text,
  `user_text_log` text,
  `user_text_password` text,
  `user_text_paypassword` text,
  `mytx_min` text,
  `mytx_max` text,
  `mytx_bei` text,
  `mytx_coin` text,
  `mytx_fee_min` float(12,2) DEFAULT '0.00' COMMENT '提现手续费最低',
  `mytx_fee` text,
  `trade_min` text,
  `trade_max` text,
  `trade_limit` text,
  `trade_text_log` text,
  `invit_type` text,
  `invit_fee1` text,
  `invit_fee2` text,
  `invit_fee3` text,
  `invit_text_txt` text,
  `invit_text_log` text,
  `index_notice_1` text,
  `index_notice_11` text,
  `index_notice_2` text,
  `index_notice_22` text,
  `index_notice_3` text,
  `index_notice_33` text,
  `index_notice_4` text,
  `index_notice_44` text,
  `text_footer` text,
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  `index_html` varchar(50) NOT NULL DEFAULT '',
  `trade_hangqing` varchar(50) NOT NULL DEFAULT '',
  `trade_moshi` varchar(50) NOT NULL DEFAULT '',
  `mytx_day_max` decimal(13,0) NOT NULL DEFAULT '0',
  `en_web_reg` text,
  `tui_jy_jl` decimal(13,4) DEFAULT NULL,
  `usd_rmb` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `btc_rmb` decimal(12,4) DEFAULT NULL,
  `eth_rmb` decimal(12,4) DEFAULT NULL,
  `en_web_name` varchar(100) DEFAULT NULL,
  `en_web_title` varchar(100) DEFAULT NULL,
  `en_web_keywords` varchar(100) DEFAULT NULL,
  `en_web_description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统配置表';

-- ----------------------------
-- Records of tw_config
-- ----------------------------
INSERT INTO `tw_config` VALUES ('1', '58f881562ea7b.png', 'c', '0', '1', '', '', '合约交易', '合约交易', 'http://tebtc.oss-ap-southeast-1.aliyuncs.com/upload/logo2.png', '58f881536618f.png', '数字资产交易平台,虚拟币交易平台,虚拟币,数字货币', '合约交易是最专业的综合虚拟货币和数字货币交易平台，支持多种数字货币交易投资买卖，提供数字货币行情、排行交易查询等资讯信息。', '1', '', '', '', '100', '', 'ltc_btc', 'btc', 'becc', '本平台仅为数字货币的爱好者提供一个自由的网上交换平台，对币的投资价值不承担任何审查、担保、赔偿的责任。数字货币可能存在矿工预挖、庄家操控、团队解散、技术缺陷等问题，其价格波动较大，因此我们强烈建议您在自身能承受的风险范围内参与数字货币交易。', '1', '', '', '', '18253251582', '', '', '', '', '', '56f98e6d70135.jpg', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '56f98e6d7245d.jpg', '中国银行|中国农业银行', '2', '2', '2', '2', '&lt;span&gt;&lt;span&gt;会员您好,务必正确填写好自己的真实姓名和真实身份证号码.&lt;/span&gt;&lt;/span&gt;', '&lt;span&gt;会员您好,务必用自己的手机号码进行手机认证,认证以后可以用来接收验证码.&lt;/span&gt;', '&lt;span&gt;会员您好,务必正确填写支付宝 &amp;nbsp;真实姓名（与实名认证姓名相同）和支付宝账号,后期提现唯一依据.&lt;/span&gt;', '&lt;span&gt;会员您好,&lt;/span&gt;&lt;span&gt;&lt;span&gt;务必正确填写银行卡信息 提现唯一依据.&lt;/span&gt;&lt;span&gt;&lt;/span&gt;&lt;/span&gt;', '&lt;span&gt;自己以往操作和登录及登录地点的相关记录.&lt;/span&gt;', '&lt;span&gt;会员您好,修改登录密码以后请不要忘记.若不记得旧登录密码,请点击--&lt;/span&gt;&lt;span style=&quot;color:#EE33EE;&quot;&gt;忘记密码&lt;/span&gt;', '&lt;span&gt;会员您好,修改交易密码以后请不要忘记.若不记得旧交易密码,请点击--&lt;/span&gt;&lt;span style=&quot;color:#EE33EE;&quot;&gt;忘记密码&lt;/span&gt;', '100', '100000', '1', 'becc', '2.00', '0.5', '1', '10000000', '10', '&lt;span&gt;&lt;span&gt;你委托买入或者卖出成功交易后的记录.&lt;/span&gt;&lt;/span&gt;', '1', '5', '3', '2', '安全便捷', '&lt;span&gt;&lt;span&gt;查看自己推广的好友,请点击&lt;/span&gt;&lt;span style=&quot;color:#EE33EE;&quot;&gt;“+”&lt;/span&gt;&lt;span&gt;,同时正确引导好友实名认证以及买卖,赚取推广收益和交易手续费.&lt;/span&gt;&lt;/span&gt;', '系统可靠', '银行级用户数据加密、动态身份验证多级风险识别控制，保障交易安全', '系统可靠', '账户多层加密，分布式服务器离线存储，即时隔离备份数据，确保安全', '快捷方便', '充值即时、提现迅速，每秒万单的高性能交易引擎，保证一切快捷方便', '服务专业', '热忱的客服工作人员和24小时的技术团队随时为您的账户安全保驾护航', '&lt;p&gt;\r\n	&lt;a href=&quot;/Article/index/type/aboutus.html&quot; target=&quot;_blank&quot;&gt;/Article/index/type/aboutus.html&lt;/a&gt;\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;br /&gt;\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n	&amp;lt;a href=&quot;&lt;a href=&quot;/Article/index/type/aboutus.html&quot; target=&quot;_blank&quot;&gt;/Article/index/type/aboutus.html&lt;/a&gt;&quot;&amp;gt;关于我们&amp;lt;/a&amp;gt;\r\n&lt;/p&gt;\r\n|&lt;br /&gt;\r\n&amp;lt;a href=&quot;/Article/index/type/aboutus.html&quot;&amp;gt;联系我们&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n|&lt;br /&gt;\r\n&amp;lt;a href=&quot;/Article/index/type/aboutus.html&quot;&amp;gt;资质证明&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n|&lt;br /&gt;\r\n&amp;lt;a href=&quot;/Article/index/type/aboutus.html&quot;&amp;gt;用户协议&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n|&lt;br /&gt;\r\n&amp;lt;a href=&quot;/Article/index/type/aboutus.html&quot;&amp;gt;法律声明&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n&amp;lt;p style=&quot;margin-top: 5px;text-align: center;&quot;&amp;gt;&lt;br /&gt;\r\nCopyright &amp;copy; 2016&lt;br /&gt;\r\n&amp;lt;a href=&quot;/&quot;&amp;gt;{$C[\'web_name\']}交易平台 &amp;lt;/a&amp;gt;&lt;br /&gt;\r\nAll Rights Reserved.&lt;br /&gt;\r\n&amp;lt;a href=&quot;http://www.miibeian.gov.cn/&quot;&amp;gt;{$C[\'web_icp\']}&amp;lt;/a&amp;gt;{$C[\'web_cnzz\']|htmlspecialchars_decode}&lt;br /&gt;\r\n&lt;br /&gt;\r\n&amp;lt;/p&amp;gt;&lt;br /&gt;\r\n&amp;lt;p class=&quot;clear1&quot; id=&quot;ut646&quot; style=&quot;margin-top: 10px;text-align: center;&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;a href=&quot;http://webscan.360.cn/index/checkwebsite/url/www.movesay.com&quot; target=&quot;_blank&quot;&amp;gt;&amp;lt;img border=&quot;0&quot; width=&quot;83&quot; height=&quot;31&quot; src=&quot;http://img.webscan.360.cn/status/pai/hash/a272bae5f02b1df25be2c1d9d0b251f7&quot;/&amp;gt;&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n&amp;lt;a href=&quot;http://www.szfw.org/&quot; target=&quot;_blank&quot; id=&quot;ut118&quot; class=&quot;margin10&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;img src=&quot;__UPLOAD__/footer/footer_2.png&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n&amp;lt;a href=&quot;http://www.miibeian.gov.cn/&quot; target=&quot;_blank&quot; id=&quot;ut119&quot; class=&quot;margin10&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;img src=&quot;__UPLOAD__/footer/footer_3.png&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n&amp;lt;a href=&quot;http://www.cyberpolice.cn/&quot; target=&quot;_blank&quot; id=&quot;ut120&quot; class=&quot;margin10&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;img src=&quot;__UPLOAD__/footer/footer_4.png&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n&amp;lt;/p&amp;gt;&lt;br /&gt;', '1467383018', '0', 'a', '1', '0', '100000', '', '0.0000', '6.6177', '40303.0000', '1937.0000', 'EBTC', 'EBTC', '', '');

-- ----------------------------
-- Table structure for tw_daohang
-- ----------------------------
DROP TABLE IF EXISTS `tw_daohang`;
CREATE TABLE `tw_daohang` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `title_en` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT 'url',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '编辑时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=gbk ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tw_daohang
-- ----------------------------
INSERT INTO `tw_daohang` VALUES ('1', 'finance', '财务中心', 'Finance', 'Finance/index', '1', '0', '0', '0');
INSERT INTO `tw_daohang` VALUES ('2', 'user', '账户', 'Account', 'User/index', '4', '0', '0', '1');
INSERT INTO `tw_daohang` VALUES ('4', 'article', '帮助中心', 'Help', 'Article/index', '4', '0', '0', '0');

-- ----------------------------
-- Table structure for tw_eth_hash
-- ----------------------------
DROP TABLE IF EXISTS `tw_eth_hash`;
CREATE TABLE `tw_eth_hash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ethhash` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `addtime` int(10) NOT NULL,
  `isdeal` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ethhash` (`ethhash`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_eth_hash
-- ----------------------------

-- ----------------------------
-- Table structure for tw_eth_transfer
-- ----------------------------
DROP TABLE IF EXISTS `tw_eth_transfer`;
CREATE TABLE `tw_eth_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zc_addr` varchar(100) NOT NULL,
  `zr_addr` varchar(100) NOT NULL,
  `zc_amount` decimal(20,8) NOT NULL,
  `addtime` int(10) NOT NULL,
  `zchash` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_eth_transfer
-- ----------------------------

-- ----------------------------
-- Table structure for tw_feedback
-- ----------------------------
DROP TABLE IF EXISTS `tw_feedback`;
CREATE TABLE `tw_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` varchar(255) NOT NULL,
  `addtime` int(10) NOT NULL,
  `endtime` int(10) DEFAULT NULL,
  `subject` varchar(50) NOT NULL,
  `attachone` varchar(200) DEFAULT NULL,
  `attachtwo` varchar(200) DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `isread` tinyint(1) NOT NULL DEFAULT '0',
  `txid` varchar(100) DEFAULT '',
  `freshtime` int(10) DEFAULT '0',
  `userstatus` tinyint(1) DEFAULT '0',
  `adminstatus` tinyint(1) DEFAULT '0',
  `recordno` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for tw_feedback_reply
-- ----------------------------
DROP TABLE IF EXISTS `tw_feedback_reply`;
CREATE TABLE `tw_feedback_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `addtime` int(10) NOT NULL,
  `content` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_feedback_reply
-- ----------------------------

-- ----------------------------
-- Table structure for tw_fenhong
-- ----------------------------
DROP TABLE IF EXISTS `tw_fenhong`;
CREATE TABLE `tw_fenhong` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `coinname` varchar(50) NOT NULL,
  `coinjian` varchar(50) NOT NULL,
  `num` decimal(20,8) unsigned NOT NULL,
  `sort` int(11) unsigned NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `endtime` int(11) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_fenhong
-- ----------------------------

-- ----------------------------
-- Table structure for tw_fenhong_log
-- ----------------------------
DROP TABLE IF EXISTS `tw_fenhong_log`;
CREATE TABLE `tw_fenhong_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `coinname` varchar(50) NOT NULL,
  `coinjian` varchar(50) NOT NULL,
  `fenzong` varchar(50) NOT NULL,
  `fenchi` varchar(50) NOT NULL,
  `price` decimal(20,8) unsigned NOT NULL,
  `num` decimal(20,8) unsigned NOT NULL,
  `mum` decimal(20,8) unsigned NOT NULL,
  `sort` int(11) unsigned NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `endtime` int(11) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL,
  `userid` int(11) unsigned NOT NULL COMMENT '用户id',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_fenhong_log
-- ----------------------------

-- ----------------------------
-- Table structure for tw_finance
-- ----------------------------
DROP TABLE IF EXISTS `tw_finance`;
CREATE TABLE `tw_finance` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `userid` int(11) unsigned NOT NULL COMMENT '用户id',
  `coinname` varchar(50) NOT NULL COMMENT '币种',
  `num_a` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '之前正常',
  `num_b` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '之前冻结',
  `num` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '之前总计',
  `fee` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '操作数量',
  `type` varchar(50) NOT NULL COMMENT '操作类型',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '操作名称',
  `nameid` int(11) NOT NULL DEFAULT '0' COMMENT '操作详细',
  `remark` varchar(50) NOT NULL DEFAULT '' COMMENT '操作备注',
  `mum_a` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '剩余正常',
  `mum_b` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '剩余冻结',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '剩余总计',
  `move` varchar(50) NOT NULL DEFAULT '' COMMENT '附加',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `coinname` (`coinname`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='财务记录表'
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (1500000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (2000000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (2500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (3000000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (3500000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (4000000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (4500000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (5000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (5500000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (6000000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (6500000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (7000000) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (7500000) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (8000000) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (8500000) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (9000000) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (9500000) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (10000000) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN (10500000) ENGINE = InnoDB,
 PARTITION p22 VALUES LESS THAN (11000000) ENGINE = InnoDB,
 PARTITION p23 VALUES LESS THAN (11500000) ENGINE = InnoDB,
 PARTITION p24 VALUES LESS THAN (12000000) ENGINE = InnoDB,
 PARTITION p25 VALUES LESS THAN (12500000) ENGINE = InnoDB,
 PARTITION p26 VALUES LESS THAN (13000000) ENGINE = InnoDB,
 PARTITION p27 VALUES LESS THAN (13500000) ENGINE = InnoDB,
 PARTITION p28 VALUES LESS THAN (14000000) ENGINE = InnoDB,
 PARTITION p29 VALUES LESS THAN (14500000) ENGINE = InnoDB,
 PARTITION p30 VALUES LESS THAN (15000000) ENGINE = InnoDB,
 PARTITION p31 VALUES LESS THAN (15500000) ENGINE = InnoDB,
 PARTITION p32 VALUES LESS THAN (16000000) ENGINE = InnoDB,
 PARTITION p33 VALUES LESS THAN (16500000) ENGINE = InnoDB,
 PARTITION p34 VALUES LESS THAN (17000000) ENGINE = InnoDB,
 PARTITION p35 VALUES LESS THAN (17500000) ENGINE = InnoDB,
 PARTITION p36 VALUES LESS THAN (18000000) ENGINE = InnoDB,
 PARTITION p37 VALUES LESS THAN (18500000) ENGINE = InnoDB,
 PARTITION p38 VALUES LESS THAN (19000000) ENGINE = InnoDB,
 PARTITION p39 VALUES LESS THAN (19500000) ENGINE = InnoDB,
 PARTITION p40 VALUES LESS THAN (20000000) ENGINE = InnoDB,
 PARTITION p41 VALUES LESS THAN (20500000) ENGINE = InnoDB,
 PARTITION p42 VALUES LESS THAN (21000000) ENGINE = InnoDB,
 PARTITION p43 VALUES LESS THAN (21500000) ENGINE = InnoDB,
 PARTITION p44 VALUES LESS THAN (22000000) ENGINE = InnoDB,
 PARTITION p45 VALUES LESS THAN (22500000) ENGINE = InnoDB,
 PARTITION p46 VALUES LESS THAN (23000000) ENGINE = InnoDB,
 PARTITION p47 VALUES LESS THAN (23500000) ENGINE = InnoDB,
 PARTITION p48 VALUES LESS THAN (24000000) ENGINE = InnoDB,
 PARTITION p49 VALUES LESS THAN (24500000) ENGINE = InnoDB,
 PARTITION p50 VALUES LESS THAN (25000000) ENGINE = InnoDB,
 PARTITION p51 VALUES LESS THAN (25500000) ENGINE = InnoDB,
 PARTITION p52 VALUES LESS THAN (26000000) ENGINE = InnoDB,
 PARTITION p53 VALUES LESS THAN (26500000) ENGINE = InnoDB,
 PARTITION p54 VALUES LESS THAN (27000000) ENGINE = InnoDB,
 PARTITION p55 VALUES LESS THAN (27500000) ENGINE = InnoDB,
 PARTITION p56 VALUES LESS THAN (28000000) ENGINE = InnoDB,
 PARTITION p57 VALUES LESS THAN (28500000) ENGINE = InnoDB,
 PARTITION p58 VALUES LESS THAN (29000000) ENGINE = InnoDB,
 PARTITION p59 VALUES LESS THAN (30000000) ENGINE = InnoDB,
 PARTITION p60 VALUES LESS THAN (30500000) ENGINE = InnoDB,
 PARTITION p61 VALUES LESS THAN (31000000) ENGINE = InnoDB,
 PARTITION p62 VALUES LESS THAN (31500000) ENGINE = InnoDB,
 PARTITION p63 VALUES LESS THAN (32000000) ENGINE = InnoDB,
 PARTITION p64 VALUES LESS THAN (32500000) ENGINE = InnoDB,
 PARTITION p65 VALUES LESS THAN (33000000) ENGINE = InnoDB,
 PARTITION p66 VALUES LESS THAN (33500000) ENGINE = InnoDB,
 PARTITION p67 VALUES LESS THAN (34000000) ENGINE = InnoDB,
 PARTITION p68 VALUES LESS THAN (34500000) ENGINE = InnoDB,
 PARTITION p69 VALUES LESS THAN (35000000) ENGINE = InnoDB,
 PARTITION p70 VALUES LESS THAN (35500000) ENGINE = InnoDB,
 PARTITION p71 VALUES LESS THAN (36000000) ENGINE = InnoDB,
 PARTITION p72 VALUES LESS THAN (36500000) ENGINE = InnoDB,
 PARTITION p73 VALUES LESS THAN (37000000) ENGINE = InnoDB,
 PARTITION p74 VALUES LESS THAN (37500000) ENGINE = InnoDB,
 PARTITION p75 VALUES LESS THAN (38000000) ENGINE = InnoDB,
 PARTITION p76 VALUES LESS THAN (38500000) ENGINE = InnoDB,
 PARTITION p77 VALUES LESS THAN (39000000) ENGINE = InnoDB,
 PARTITION p78 VALUES LESS THAN (39500000) ENGINE = InnoDB,
 PARTITION p79 VALUES LESS THAN (40000000) ENGINE = InnoDB,
 PARTITION p80 VALUES LESS THAN (40500000) ENGINE = InnoDB,
 PARTITION p81 VALUES LESS THAN (41000000) ENGINE = InnoDB,
 PARTITION p82 VALUES LESS THAN (41500000) ENGINE = InnoDB,
 PARTITION p83 VALUES LESS THAN (42000000) ENGINE = InnoDB,
 PARTITION p84 VALUES LESS THAN (42500000) ENGINE = InnoDB,
 PARTITION p85 VALUES LESS THAN (43000000) ENGINE = InnoDB,
 PARTITION p86 VALUES LESS THAN (43500000) ENGINE = InnoDB,
 PARTITION p87 VALUES LESS THAN (44000000) ENGINE = InnoDB,
 PARTITION p88 VALUES LESS THAN (44500000) ENGINE = InnoDB,
 PARTITION p89 VALUES LESS THAN (45000000) ENGINE = InnoDB,
 PARTITION p90 VALUES LESS THAN (45500000) ENGINE = InnoDB,
 PARTITION p91 VALUES LESS THAN (46000000) ENGINE = InnoDB,
 PARTITION p92 VALUES LESS THAN (46500000) ENGINE = InnoDB,
 PARTITION p93 VALUES LESS THAN (47000000) ENGINE = InnoDB,
 PARTITION p94 VALUES LESS THAN (47500000) ENGINE = InnoDB,
 PARTITION p95 VALUES LESS THAN (48000000) ENGINE = InnoDB,
 PARTITION p96 VALUES LESS THAN (48500000) ENGINE = InnoDB,
 PARTITION p97 VALUES LESS THAN (49000000) ENGINE = InnoDB,
 PARTITION p98 VALUES LESS THAN (49500000) ENGINE = InnoDB,
 PARTITION p99 VALUES LESS THAN (50000000) ENGINE = InnoDB,
 PARTITION p100 VALUES LESS THAN (50500000) ENGINE = InnoDB,
 PARTITION p101 VALUES LESS THAN (51000000) ENGINE = InnoDB,
 PARTITION p102 VALUES LESS THAN (51500000) ENGINE = InnoDB,
 PARTITION p103 VALUES LESS THAN (52000000) ENGINE = InnoDB,
 PARTITION p104 VALUES LESS THAN (52500000) ENGINE = InnoDB,
 PARTITION p105 VALUES LESS THAN (53000000) ENGINE = InnoDB,
 PARTITION p106 VALUES LESS THAN (53500000) ENGINE = InnoDB,
 PARTITION p107 VALUES LESS THAN (54000000) ENGINE = InnoDB,
 PARTITION p108 VALUES LESS THAN (54500000) ENGINE = InnoDB,
 PARTITION p109 VALUES LESS THAN (55000000) ENGINE = InnoDB,
 PARTITION p110 VALUES LESS THAN (55500000) ENGINE = InnoDB,
 PARTITION p111 VALUES LESS THAN (56000000) ENGINE = InnoDB,
 PARTITION p112 VALUES LESS THAN (56500000) ENGINE = InnoDB,
 PARTITION p113 VALUES LESS THAN (57000000) ENGINE = InnoDB,
 PARTITION p114 VALUES LESS THAN (57500000) ENGINE = InnoDB,
 PARTITION p115 VALUES LESS THAN (58000000) ENGINE = InnoDB,
 PARTITION p116 VALUES LESS THAN (58500000) ENGINE = InnoDB,
 PARTITION p117 VALUES LESS THAN (59000000) ENGINE = InnoDB,
 PARTITION p118 VALUES LESS THAN (59500000) ENGINE = InnoDB,
 PARTITION p119 VALUES LESS THAN (60000000) ENGINE = InnoDB,
 PARTITION p120 VALUES LESS THAN (60500000) ENGINE = InnoDB,
 PARTITION p121 VALUES LESS THAN (61000000) ENGINE = InnoDB,
 PARTITION p122 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_finance
-- ----------------------------
INSERT INTO `tw_finance` VALUES ('3', '6272', 'cny', '0.00000000', '0.00000000', '0.00000000', '100.49000000', '1', 'mycz', '2', '人民币充值-人工到账', '100.49000000', '0.00000000', '100.49000000', '539aaa4a712b74908558d8031baf7519', '1510817205', '1');
INSERT INTO `tw_finance` VALUES ('4', '6272', 'cny', '100.49000000', '0.00000000', '100.49000000', '100.00000000', '2', 'mytx', '1', '人民币提现-申请提现', '0.49000000', '0.00000000', '0.49000000', 'b2cc547b4667d12548771240edd9c111', '1510817522', '1');
INSERT INTO `tw_finance` VALUES ('5', '6272', 'cny', '0.49000000', '0.00000000', '0.49000000', '100.00000000', '1', 'mytx', '1', '人民币提现-撤销提现', '100.49000000', '0.00000000', '100.49000000', '00bab7495c0e0bbb8fb09e812eb004d3', '1510817526', '1');
INSERT INTO `tw_finance` VALUES ('6', '6272', 'btc', '10.00000000', '0.00000000', '10.00000000', '0.01111110', '2', 'trade', '1', '交易中心-委托买入-市场ltc_btc', '9.98888890', '0.01111110', '10.00000000', '4a9b95fd9a96345e00a07068e007a856', '1510817655', '0');
INSERT INTO `tw_finance` VALUES ('7', '6272', 'btc', '9.98888890', '0.01111110', '10.00000000', '0.01111110', '2', 'tradelog', '1', '交易中心-成功买入-市场ltc_btc', '9.98888890', '0.00000000', '9.98888890', '28dc80d8a255620ad33fbfceebf9096b', '1510817663', '1');
INSERT INTO `tw_finance` VALUES ('8', '6272', 'btc', '9.98888890', '0.00000000', '9.98888890', '0.01111110', '1', 'tradelog', '1', '交易中心-成功卖出-市场ltc_btc', '9.99997780', '0.00000000', '9.99997780', '1c9e13f390fa2a246597d827475ac23d', '1510817663', '1');
INSERT INTO `tw_finance` VALUES ('9', '6272', 'btc', '9.99997780', '0.00000000', '9.99997780', '0.05605600', '2', 'trade', '3', '交易中心-委托买入-市场bcc_btc', '9.94392180', '0.05605600', '9.99997780', '26432549fe470096150da82f798ae19a', '1510817673', '1');
INSERT INTO `tw_finance` VALUES ('10', '6272', 'btc', '9.94392180', '0.05605600', '9.99997780', '0.05605600', '2', 'tradelog', '2', '交易中心-成功买入-市场bcc_btc', '9.94392180', '0.00000000', '9.94392180', 'b3b4cf700b2e540502dbaef6a4d6e6e3', '1510817684', '1');
INSERT INTO `tw_finance` VALUES ('11', '6272', 'btc', '9.94392180', '0.00000000', '9.94392180', '0.05605600', '1', 'tradelog', '2', '交易中心-成功卖出-市场bcc_btc', '9.99986580', '0.00000000', '9.99986580', 'e6f546c3c889579020194184d8e7af0f', '1510817684', '1');

-- ----------------------------
-- Table structure for tw_finance_log
-- ----------------------------
DROP TABLE IF EXISTS `tw_finance_log`;
CREATE TABLE `tw_finance_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `adminname` varchar(50) DEFAULT '' COMMENT '管理员用户名',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '记录时间',
  `plusminus` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0减少1增加',
  `amount` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '金额，8位小数',
  `description` varchar(100) DEFAULT '' COMMENT '备注',
  `optype` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '动作类型',
  `cointype` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '资金类型',
  `old_amount` decimal(20,8) NOT NULL DEFAULT '0.00000000' COMMENT '原始数据',
  `new_amount` decimal(20,8) NOT NULL DEFAULT '0.00000000' COMMENT '修改后的数据',
  `userid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `adminid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作者id',
  `addip` varchar(100) NOT NULL DEFAULT '0.0.0.0',
  `position` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0后台1前台pc端2前台手机端',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (2000000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (3000000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (4000000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (5000000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (6000000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (7000000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (8000000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (9000000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (10000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (11000000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (12000000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (13000000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (14000000) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (15000000) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (16000000) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (17000000) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (18000000) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (19000000) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (20000000) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN (21000000) ENGINE = InnoDB,
 PARTITION p22 VALUES LESS THAN (22000000) ENGINE = InnoDB,
 PARTITION p23 VALUES LESS THAN (23000000) ENGINE = InnoDB,
 PARTITION p24 VALUES LESS THAN (24000000) ENGINE = InnoDB,
 PARTITION p25 VALUES LESS THAN (25000000) ENGINE = InnoDB,
 PARTITION p26 VALUES LESS THAN (26000000) ENGINE = InnoDB,
 PARTITION p27 VALUES LESS THAN (27000000) ENGINE = InnoDB,
 PARTITION p28 VALUES LESS THAN (28000000) ENGINE = InnoDB,
 PARTITION p29 VALUES LESS THAN (29000000) ENGINE = InnoDB,
 PARTITION p30 VALUES LESS THAN (30000000) ENGINE = InnoDB,
 PARTITION p31 VALUES LESS THAN (31000000) ENGINE = InnoDB,
 PARTITION p32 VALUES LESS THAN (32000000) ENGINE = InnoDB,
 PARTITION p33 VALUES LESS THAN (33000000) ENGINE = InnoDB,
 PARTITION p34 VALUES LESS THAN (34000000) ENGINE = InnoDB,
 PARTITION p35 VALUES LESS THAN (35000000) ENGINE = InnoDB,
 PARTITION p36 VALUES LESS THAN (36000000) ENGINE = InnoDB,
 PARTITION p37 VALUES LESS THAN (37000000) ENGINE = InnoDB,
 PARTITION p38 VALUES LESS THAN (38000000) ENGINE = InnoDB,
 PARTITION p39 VALUES LESS THAN (39000000) ENGINE = InnoDB,
 PARTITION p40 VALUES LESS THAN (40000000) ENGINE = InnoDB,
 PARTITION p41 VALUES LESS THAN (41000000) ENGINE = InnoDB,
 PARTITION p42 VALUES LESS THAN (42000000) ENGINE = InnoDB,
 PARTITION p43 VALUES LESS THAN (43000000) ENGINE = InnoDB,
 PARTITION p44 VALUES LESS THAN (44000000) ENGINE = InnoDB,
 PARTITION p45 VALUES LESS THAN (45000000) ENGINE = InnoDB,
 PARTITION p46 VALUES LESS THAN (46000000) ENGINE = InnoDB,
 PARTITION p47 VALUES LESS THAN (47000000) ENGINE = InnoDB,
 PARTITION p48 VALUES LESS THAN (48000000) ENGINE = InnoDB,
 PARTITION p49 VALUES LESS THAN (49000000) ENGINE = InnoDB,
 PARTITION p50 VALUES LESS THAN (50000000) ENGINE = InnoDB,
 PARTITION p51 VALUES LESS THAN (51000000) ENGINE = InnoDB,
 PARTITION p52 VALUES LESS THAN (52000000) ENGINE = InnoDB,
 PARTITION p53 VALUES LESS THAN (53000000) ENGINE = InnoDB,
 PARTITION p54 VALUES LESS THAN (54000000) ENGINE = InnoDB,
 PARTITION p55 VALUES LESS THAN (55000000) ENGINE = InnoDB,
 PARTITION p56 VALUES LESS THAN (56000000) ENGINE = InnoDB,
 PARTITION p57 VALUES LESS THAN (57000000) ENGINE = InnoDB,
 PARTITION p58 VALUES LESS THAN (58000000) ENGINE = InnoDB,
 PARTITION p59 VALUES LESS THAN (59000000) ENGINE = InnoDB,
 PARTITION p60 VALUES LESS THAN (60000000) ENGINE = InnoDB,
 PARTITION p61 VALUES LESS THAN (61000000) ENGINE = InnoDB,
 PARTITION p62 VALUES LESS THAN (62000000) ENGINE = InnoDB,
 PARTITION p63 VALUES LESS THAN (63000000) ENGINE = InnoDB,
 PARTITION p64 VALUES LESS THAN (64000000) ENGINE = InnoDB,
 PARTITION p65 VALUES LESS THAN (65000000) ENGINE = InnoDB,
 PARTITION p66 VALUES LESS THAN (66000000) ENGINE = InnoDB,
 PARTITION p67 VALUES LESS THAN (67000000) ENGINE = InnoDB,
 PARTITION p68 VALUES LESS THAN (68000000) ENGINE = InnoDB,
 PARTITION p69 VALUES LESS THAN (69000000) ENGINE = InnoDB,
 PARTITION p70 VALUES LESS THAN (70000000) ENGINE = InnoDB,
 PARTITION p71 VALUES LESS THAN (71000000) ENGINE = InnoDB,
 PARTITION p72 VALUES LESS THAN (72000000) ENGINE = InnoDB,
 PARTITION p73 VALUES LESS THAN (73000000) ENGINE = InnoDB,
 PARTITION p74 VALUES LESS THAN (74000000) ENGINE = InnoDB,
 PARTITION p75 VALUES LESS THAN (75000000) ENGINE = InnoDB,
 PARTITION p76 VALUES LESS THAN (76000000) ENGINE = InnoDB,
 PARTITION p77 VALUES LESS THAN (77000000) ENGINE = InnoDB,
 PARTITION p78 VALUES LESS THAN (78000000) ENGINE = InnoDB,
 PARTITION p79 VALUES LESS THAN (79000000) ENGINE = InnoDB,
 PARTITION p80 VALUES LESS THAN (80000000) ENGINE = InnoDB,
 PARTITION p81 VALUES LESS THAN (81000000) ENGINE = InnoDB,
 PARTITION p82 VALUES LESS THAN (82000000) ENGINE = InnoDB,
 PARTITION p83 VALUES LESS THAN (83000000) ENGINE = InnoDB,
 PARTITION p84 VALUES LESS THAN (84000000) ENGINE = InnoDB,
 PARTITION p85 VALUES LESS THAN (85000000) ENGINE = InnoDB,
 PARTITION p86 VALUES LESS THAN (86000000) ENGINE = InnoDB,
 PARTITION p87 VALUES LESS THAN (87000000) ENGINE = InnoDB,
 PARTITION p88 VALUES LESS THAN (88000000) ENGINE = InnoDB,
 PARTITION p89 VALUES LESS THAN (89000000) ENGINE = InnoDB,
 PARTITION p90 VALUES LESS THAN (90000000) ENGINE = InnoDB,
 PARTITION p91 VALUES LESS THAN (91000000) ENGINE = InnoDB,
 PARTITION p92 VALUES LESS THAN (92000000) ENGINE = InnoDB,
 PARTITION p93 VALUES LESS THAN (93000000) ENGINE = InnoDB,
 PARTITION p94 VALUES LESS THAN (94000000) ENGINE = InnoDB,
 PARTITION p95 VALUES LESS THAN (95000000) ENGINE = InnoDB,
 PARTITION p96 VALUES LESS THAN (96000000) ENGINE = InnoDB,
 PARTITION p97 VALUES LESS THAN (97000000) ENGINE = InnoDB,
 PARTITION p98 VALUES LESS THAN (98000000) ENGINE = InnoDB,
 PARTITION p99 VALUES LESS THAN (99000000) ENGINE = InnoDB,
 PARTITION p100 VALUES LESS THAN (100000000) ENGINE = InnoDB,
 PARTITION p101 VALUES LESS THAN (101000000) ENGINE = InnoDB,
 PARTITION p102 VALUES LESS THAN (102000000) ENGINE = InnoDB,
 PARTITION p103 VALUES LESS THAN (103000000) ENGINE = InnoDB,
 PARTITION p104 VALUES LESS THAN (104000000) ENGINE = InnoDB,
 PARTITION p105 VALUES LESS THAN (105000000) ENGINE = InnoDB,
 PARTITION p106 VALUES LESS THAN (106000000) ENGINE = InnoDB,
 PARTITION p107 VALUES LESS THAN (107000000) ENGINE = InnoDB,
 PARTITION p108 VALUES LESS THAN (108000000) ENGINE = InnoDB,
 PARTITION p109 VALUES LESS THAN (109000000) ENGINE = InnoDB,
 PARTITION p110 VALUES LESS THAN (110000000) ENGINE = InnoDB,
 PARTITION p111 VALUES LESS THAN (111000000) ENGINE = InnoDB,
 PARTITION p112 VALUES LESS THAN (112000000) ENGINE = InnoDB,
 PARTITION p113 VALUES LESS THAN (113000000) ENGINE = InnoDB,
 PARTITION p114 VALUES LESS THAN (114000000) ENGINE = InnoDB,
 PARTITION p115 VALUES LESS THAN (115000000) ENGINE = InnoDB,
 PARTITION p116 VALUES LESS THAN (116000000) ENGINE = InnoDB,
 PARTITION p117 VALUES LESS THAN (117000000) ENGINE = InnoDB,
 PARTITION p118 VALUES LESS THAN (118000000) ENGINE = InnoDB,
 PARTITION p119 VALUES LESS THAN (119000000) ENGINE = InnoDB,
 PARTITION p120 VALUES LESS THAN (120000000) ENGINE = InnoDB,
 PARTITION p121 VALUES LESS THAN (121000000) ENGINE = InnoDB,
 PARTITION p122 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_finance_log
-- ----------------------------
INSERT INTO `tw_finance_log` VALUES ('3', 'xxxxxxx@qq.com', 'admin', '1510817205', '1', '100.49000000', '', '1', '1', '0.00000000', '100.49000000', '6272', '1', '127.0.0.1', '0');
INSERT INTO `tw_finance_log` VALUES ('4', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817522', '0', '100.00000000', '', '5', '1', '100.49000000', '0.49000000', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('5', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817526', '1', '100.00000000', '', '24', '1', '0.49000000', '100.49000000', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('6', 'xxxxxxx@qq.com', 'admin', '1510817593', '1', '10.00000000', '', '3', '3', '0.00000000', '10.00000000', '6272', '1', '127.0.0.1', '0');
INSERT INTO `tw_finance_log` VALUES ('7', 'xxxxxxx@qq.com', 'admin', '1510817593', '1', '100.00000000', '', '3', '4', '0.00000000', '100.00000000', '6272', '1', '127.0.0.1', '0');
INSERT INTO `tw_finance_log` VALUES ('8', 'xxxxxxx@qq.com', 'admin', '1510817593', '1', '1000.00000000', '', '3', '6', '0.00000000', '1000.00000000', '6272', '1', '127.0.0.1', '0');
INSERT INTO `tw_finance_log` VALUES ('9', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817655', '0', '0.01111110', '', '18', '1', '10.00000000', '9.98888890', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('10', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817655', '1', '0.01111110', '', '20', '1', '0.00000000', '0.01111110', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('11', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817663', '0', '1.00000000', '', '19', '4', '100.00000000', '99.00000000', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('12', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817663', '1', '1.00000000', '', '21', '4', '0.00000000', '1.00000000', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('13', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817663', '1', '1.00000000', '', '10', '4', '99.00000000', '100.00000000', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('14', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817663', '0', '0.01111110', '', '13', '1', '0.01111110', '0.00000000', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('15', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817663', '1', '0.01108890', '', '11', '1', '9.98888890', '9.99997780', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('16', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817663', '0', '1.00000000', '', '14', '4', '1.00000000', '0.00000000', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('17', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817673', '0', '0.05605600', '', '18', '1', '9.99997780', '9.94392180', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('18', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817673', '1', '0.05605600', '', '20', '1', '0.00000000', '0.05605600', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('19', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817684', '0', '1.00000000', '', '19', '6', '1000.00000000', '999.00000000', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('20', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817684', '1', '1.00000000', '', '21', '6', '0.00000000', '1.00000000', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('21', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817684', '1', '1.00000000', '', '10', '6', '999.00000000', '1000.00000000', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('22', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817684', '0', '0.05605600', '', '13', '1', '0.05605600', '0.00000000', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('23', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817684', '1', '0.05594400', '', '11', '1', '9.94392180', '9.99986580', '6272', '6272', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('24', 'xxxxxxx@qq.com', 'xxxxxxx@qq.com', '1510817684', '0', '1.00000000', '', '14', '6', '1.00000000', '0.00000000', '6272', '6272', '127.0.0.1', '1');

-- ----------------------------
-- Table structure for tw_footer
-- ----------------------------
DROP TABLE IF EXISTS `tw_footer`;
CREATE TABLE `tw_footer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `img` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_footer
-- ----------------------------
INSERT INTO `tw_footer` VALUES ('1', '1', '关于我们', '/Article/index/type/aboutus.html', '', '1', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('2', '1', '联系我们', '/Article/index/type/aboutus.html', '', '1', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('3', '1', '资质证明', '/Article/index/type/aboutus.html', '', '1', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('4', '1', '用户协议', '/Article/index/type/aboutus.html', '', '1', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('5', '1', '法律声明', '/Article/index/type/aboutus.html', '', '1', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('6', '1', '1', '/', 'footer_1.png', '2', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('7', '1', '1', 'http://www.szfw.org/', 'footer_2.png', '2', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('8', '1', '1', 'http://www.miibeian.gov.cn/', 'footer_3.png', '2', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('9', '1', '1', 'http://www.cyberpolice.cn/', 'footer_4.png', '2', '', '1', '111', '0', '1');

-- ----------------------------
-- Table structure for tw_invit
-- ----------------------------
DROP TABLE IF EXISTS `tw_invit`;
CREATE TABLE `tw_invit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `invit` int(11) unsigned NOT NULL,
  `name` tinyint(4) NOT NULL,
  `type` int(11) NOT NULL,
  `num` decimal(20,8) unsigned NOT NULL,
  `mum` decimal(20,8) unsigned NOT NULL,
  `fee` decimal(20,8) unsigned NOT NULL,
  `sort` int(11) unsigned NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `endtime` int(11) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL,
  `buysell` tinyint(1) NOT NULL DEFAULT '2',
  `rmb` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `invit` (`invit`),
  KEY `name` (`name`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `buysell` (`buysell`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推广奖励表';

-- ----------------------------
-- Records of tw_invit
-- ----------------------------

-- ----------------------------
-- Table structure for tw_link
-- ----------------------------
DROP TABLE IF EXISTS `tw_link`;
CREATE TABLE `tw_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `img` varchar(100) NOT NULL DEFAULT '',
  `mytx` varchar(100) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  `look_type` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COMMENT='常用银行地址';

-- ----------------------------
-- Records of tw_link
-- ----------------------------
INSERT INTO `tw_link` VALUES ('27', '巴比特', '巴比特', 'http://www.8btc.com/', '', '', '', '0', '1492790400', '1492790400', '1', '1');
INSERT INTO `tw_link` VALUES ('43', 'wan', 'wan', '', 'http://eryuemei.oss-cn-shenzhen.aliyuncs.com/upload/4.jpg', '', '', '0', '1492652293', '1492652293', '1', '0');
INSERT INTO `tw_link` VALUES ('44', '网录科技', '网录科技', '', 'http://eryuemei.oss-cn-shenzhen.aliyuncs.com/upload/3.jpg', '', '', '0', '1492652293', '1492652293', '1', '0');
INSERT INTO `tw_link` VALUES ('45', '新链加速器', '新链加速器', '', 'http://eryuemei.oss-cn-shenzhen.aliyuncs.com/upload/2.jpg', '', '', '0', '1492652293', '1492652293', '1', '0');
INSERT INTO `tw_link` VALUES ('46', '云财经', '云财经', 'http://www.yuncaijing.com/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('47', '金投网', '金投网', 'http://finance.cngold.org/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('48', '互动百科', '互动百科', 'http://www.baike.com/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('49', '比特币挖矿', '比特币挖矿', 'http://www.cybtc.com/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('50', '中国纸黄金', '中国纸黄金', 'http://www.zhijinwang.com/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('51', '炎黄财经视频', '炎黄财经视频', 'http://www.mytv365.com/', '', '', '', '0', '1503417600', '1503417600', '1', '1');
INSERT INTO `tw_link` VALUES ('53', 'BTCBOX', 'BTCBOX', 'https://btcbox.com/', '', '', '', '0', '1503417600', '1503417600', '1', '1');
INSERT INTO `tw_link` VALUES ('54', '比特范', '比特范', 'http://news.btcfans.com/', '', '', '', '0', '1503331200', '1503331200', '1', '1');
INSERT INTO `tw_link` VALUES ('55', '链行', '链行', 'https://www.lhang.com/#/', '', '', '', '0', '1503417600', '1503417600', '1', '1');
INSERT INTO `tw_link` VALUES ('56', '玩币族', '玩币族', 'http://www.wanbizu.com/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('57', 'bitbank', 'bitbank', 'https://www.bitbank.com/', '', '', '', '0', '1503417600', '1503417600', '1', '1');
INSERT INTO `tw_link` VALUES ('58', '铅笔', '铅笔', 'http://chainb.com/', '', '', '', '0', '1503244800', '1503244800', '1', '1');
INSERT INTO `tw_link` VALUES ('59', '彩云比特', '彩云比特', 'http://www.cybtc.com/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('60', '小黑屋', '小黑屋', 'http://www.cybtc.com/forum.php?mod=misc&action=showdarkroom', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('61', '比西西商城', '比西西商城', 'http://shop.bitxixi.com/', '', '', '', '0', '1503331200', '1503331200', '1', '1');
INSERT INTO `tw_link` VALUES ('62', 'BITKAN', 'BITKAN', '', 'http://eryuemei.oss-cn-shenzhen.aliyuncs.com/upload/6.jpg', '', '', '0', '1503504000', '1503504000', '1', '0');
INSERT INTO `tw_link` VALUES ('63', '趣块链', '趣块链', '', 'http://eryuemei.oss-cn-shenzhen.aliyuncs.com/upload/1.jpg', '', '', '0', '1503504000', '1503504000', '1', '0');

-- ----------------------------
-- Table structure for tw_market
-- ----------------------------
DROP TABLE IF EXISTS `tw_market`;
CREATE TABLE `tw_market` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `round` varchar(255) NOT NULL DEFAULT '',
  `fee_buy` varchar(255) NOT NULL DEFAULT '',
  `fee_sell` varchar(255) NOT NULL DEFAULT '',
  `buy_min` varchar(255) NOT NULL DEFAULT '',
  `buy_max` varchar(255) NOT NULL DEFAULT '',
  `sell_min` varchar(255) NOT NULL DEFAULT '',
  `sell_max` varchar(255) NOT NULL DEFAULT '',
  `trade_min` varchar(255) NOT NULL DEFAULT '',
  `trade_max` varchar(255) NOT NULL DEFAULT '',
  `invit_buy` varchar(50) NOT NULL DEFAULT '',
  `invit_sell` varchar(50) NOT NULL DEFAULT '',
  `invit_1` varchar(50) NOT NULL DEFAULT '',
  `invit_2` varchar(50) NOT NULL DEFAULT '',
  `invit_3` varchar(50) NOT NULL DEFAULT '',
  `zhang` varchar(255) NOT NULL DEFAULT '',
  `die` varchar(255) NOT NULL DEFAULT '',
  `hou_price` varchar(255) NOT NULL DEFAULT '',
  `tendency` text,
  `trade` int(11) unsigned NOT NULL DEFAULT '0',
  `new_price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `buy_price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `sell_price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `min_price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `max_price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `volume` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `change` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  `api_min` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `api_max` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `start_time` int(11) DEFAULT NULL COMMENT '开盘时间（小时）',
  `stop_time` int(11) DEFAULT NULL COMMENT '闭盘时间（小时）',
  `start_minute` int(11) DEFAULT NULL COMMENT '开盘时间（分钟）',
  `stop_minute` int(11) DEFAULT NULL COMMENT '毕盘时间（分钟）',
  `agree6` int(11) DEFAULT NULL COMMENT '周六是否可以交易0是不可交易1是可交易',
  `agree7` int(11) DEFAULT NULL COMMENT '周天是否可以交易0是不可交易1是可交易',
  `trade_num_min` varchar(30) NOT NULL DEFAULT '',
  `cjamount` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='行情配置表';

-- ----------------------------
-- Records of tw_market
-- ----------------------------
INSERT INTO `tw_market` VALUES ('10', 'ltc_btc', '4', '0.1', '0.1', '0.0001', '10', '0.0001', '10', '0.001', '1000', '1', '1', '30', '0', '0', '99999999', '99999999', '0.01100000', '[[1510554781,0],[1510569181,0],[1510583581,0],[1510597981,0],[1510612381,0],[1510626781,0],[1510641181,0],[1510655581,0],[1510669981,0],[1510684381,0],[1510698781,0],[1510713181,0],[1510727581,0],[1510741981,0],[1510756381,0],[1510770781,0],[1510785181,0],[1510799581,0],[1510813981,0]]', '1', '0.01100000', '0.01100000', '0.01110000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0', '0', '0', '1', '0', '23', '0', '59', '1', '1', '0.001', '0.00000000');
INSERT INTO `tw_market` VALUES ('11', 'bcc_btc', '3', '0.1', '0.1', '0.001', '10', '0.001', '10', '0.001', '1000', '1', '1', '30', '0', '0', '99999999', '99999999', '0.05800000', '[[1510554781,0],[1510569181,0],[1510583581,0],[1510597981,0],[1510612381,0],[1510626781,0],[1510641181,0],[1510655581,0],[1510669981,0],[1510684381,0],[1510698781,0],[1510713181,0],[1510727581,0],[1510741981,0],[1510756381,0],[1510770781,0],[1510785181,0],[1510799581,0],[1510813981,0]]', '1', '0.05800000', '0.05700000', '0.05800000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0', '0', '0', '1', '0', '23', '0', '59', '1', '1', '0.0001', '0.00000000');
INSERT INTO `tw_market` VALUES ('12', 'ltc_eth', '3', '0.1', '0.1', '0.001', '100', '0.001', '100', '0.001', '10000', '1', '1', '30', '0', '0', '99999999', '99999999', '0.19000000', '[[1508211002,0],[1508225402,0],[1508239802,0],[1508254202,0],[1508268602,0],[1508283002,0],[1508297402,0],[1508311802,0],[1508326202,0],[1508340602,0],[1508355002,0],[1508369402,0],[1508383802,0],[1508398202,0],[1508412602,0],[1508427002,0],[1508441402,0],[1508455802,0],[1508470202,0]]', '1', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '-100.00000000', '0.00000000', '0.00000000', '0', '0', '0', '0', '0', '23', '0', '59', '1', '1', '0.0001', '0.00000000');
INSERT INTO `tw_market` VALUES ('13', 'bcc_eth', '3', '0.1', '0.1', '0.001', '100', '0.001', '100', '0.001', '10000', '1', '1', '30', '0', '0', '99999999', '99999999', '1.53600000', '[[1508211002,0],[1508225402,0],[1508239802,0],[1508254202,0],[1508268602,0],[1508283002,0],[1508297402,0],[1508311802,0],[1508326202,0],[1508340602,0],[1508355002,0],[1508369402,0],[1508383802,0],[1508398202,0],[1508412602,0],[1508427002,0],[1508441402,0],[1508455802,0],[1508470202,0]]', '1', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '-100.00000000', '0.00000000', '0.00000000', '0', '0', '0', '0', '0', '23', '0', '59', '1', '1', '0.0001', '0.00000000');
INSERT INTO `tw_market` VALUES ('14', 'eth_btc', '3', '0.1', '0.1', '0.001', '100', '0.001', '100', '0.001', '1000000', '1', '1', '30', '0', '0', '99999999', '99999999', '0.07500000', '[[1508211002,0],[1508225402,0],[1508239802,0],[1508254202,0],[1508268602,0],[1508283002,0],[1508297402,0],[1508311802,0],[1508326202,0],[1508340602,0],[1508355002,0],[1508369402,0],[1508383802,0],[1508398202,0],[1508412602,0],[1508427002,0],[1508441402,0],[1508455802,0],[1508470202,0]]', '1', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '-100.00000000', '0.00000000', '0.00000000', '0', '0', '0', '0', '0', '23', '0', '59', '1', '1', '0.001', '0.00000000');
INSERT INTO `tw_market` VALUES ('15', 'btc_eth', '3', '0.1', '0.1', '0.001', '100', '0.001', '100', '0.001', '1000000', '1', '1', '30', '0', '0', '99999999', '99999999', '13.41100000', '[[1508211002,0],[1508225402,0],[1508239802,0],[1508254202,0],[1508268602,0],[1508283002,0],[1508297402,0],[1508311802,0],[1508326202,0],[1508340602,0],[1508355002,0],[1508369402,0],[1508383802,0],[1508398202,0],[1508412602,0],[1508427002,0],[1508441402,0],[1508455802,0],[1508470202,0]]', '1', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '0.00000000', '-100.00000000', '0.00000000', '0.00000000', '0', '0', '0', '0', '0', '23', '0', '59', '1', '1', '0.001', '0.00000000');

-- ----------------------------
-- Table structure for tw_market_json
-- ----------------------------
DROP TABLE IF EXISTS `tw_market_json`;
CREATE TABLE `tw_market_json` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `data` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tw_market_json
-- ----------------------------

-- ----------------------------
-- Table structure for tw_menu
-- ----------------------------
DROP TABLE IF EXISTS `tw_menu`;
CREATE TABLE `tw_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  `ico_name` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=463 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tw_menu
-- ----------------------------
INSERT INTO `tw_menu` VALUES ('1', '系统', '0', '1', 'Index/index', '0', '', '', '0', 'home');
INSERT INTO `tw_menu` VALUES ('2', '内容', '0', '1', 'Article/index', '0', '', '', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('3', '用户', '0', '1', 'User/index', '0', '', '', '0', 'user');
INSERT INTO `tw_menu` VALUES ('4', '财务', '0', '1', 'Finance/myzr', '0', '', '', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('5', '交易', '0', '1', 'Trade/index', '0', '', '', '0', 'stats');
INSERT INTO `tw_menu` VALUES ('7', '设置', '0', '1', 'Config/index', '0', '', '', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('9', '工具', '0', '1', 'Tools/index', '0', '', '', '0', 'wrench');
INSERT INTO `tw_menu` VALUES ('11', '系统概览', '1', '1', 'Index/index', '0', '', '系统', '0', 'home');
INSERT INTO `tw_menu` VALUES ('12', '市场统计', '1', '3', 'Index/market', '0', '', '系统', '0', 'home');
INSERT INTO `tw_menu` VALUES ('13', '文章管理', '2', '1', 'Article/index', '0', '', '内容', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('14', '编辑添加', '13', '1', 'Article/edit', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('15', '修改状态', '13', '100', 'Article/status', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('16', '上传图片', '13', '2', 'Article/images', '1', '', '内容管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('18', '编辑', '17', '2', 'Adver/edit', '1', '', '内容管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('19', '修改', '17', '2', 'Adver/status', '1', '', '内容管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('21', '编辑', '20', '3', 'Chat/edit', '1', '', '聊天管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('22', '修改', '20', '3', 'Chat/status', '1', '', '聊天管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('23', '提示文字', '2', '1', 'Text/index', '1', '', '提示管理', '0', 'exclamation-sign');
INSERT INTO `tw_menu` VALUES ('24', '编辑', '23', '1', 'Text/edit', '1', '', '提示管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('25', '修改', '23', '1', 'Text/status', '1', '', '提示管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('26', '用户管理', '3', '1', 'User/index', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('32', '确认转出', '26', '8', 'User/myzc_qr', '1', '', '用户管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('33', '用户配置', '3', '1', 'User/config', '1', '', '前台用户管理', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('34', '编辑', '33', '2', 'User/index_edit', '1', '', '用户管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('35', '修改', '33', '2', 'User/index_status', '1', '', '用户管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('37', '财产修改', '26', '3', 'Usercoin/edit', '1', '', '用户管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('39', '新增用户组', '38', '0', 'AuthManager/createGroup', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('40', '编辑用户组', '38', '0', 'AuthManager/editgroup', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('41', '更新用户组', '38', '0', 'AuthManager/writeGroup', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('42', '改变状态', '38', '0', 'AuthManager/changeStatus', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('43', '访问授权', '38', '0', 'AuthManager/access', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('44', '分类授权', '38', '0', 'AuthManager/category', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('45', '成员授权', '38', '0', 'AuthManager/user', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('46', '成员列表授权', '38', '0', 'AuthManager/tree', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('47', '用户组', '38', '0', 'AuthManager/group', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('48', '添加到用户组', '38', '0', 'AuthManager/addToGroup', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('49', '用户组移除', '38', '0', 'AuthManager/removeFromGroup', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('50', '分类添加到用户组', '38', '0', 'AuthManager/addToCategory', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('51', '模型添加到用户组', '38', '0', 'AuthManager/addToModel', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('53', '配置', '52', '1', 'Finance/config', '1', '', '', '0', '0');
INSERT INTO `tw_menu` VALUES ('55', '类型', '52', '1', 'Finance/type', '1', '', '', '0', '0');
INSERT INTO `tw_menu` VALUES ('56', '状态修改', '52', '1', 'Finance/type_status', '1', '', '', '0', '0');
INSERT INTO `tw_menu` VALUES ('60', '修改', '57', '3', 'Mycz/status', '1', '', '充值管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('61', '状态修改', '57', '3', 'Mycztype/status', '1', '', '充值管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('64', '状态修改', '62', '5', 'Mytx/status', '1', '', '提现管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('65', '取消', '62', '5', 'Mytx/excel', '1', '', '提现管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('68', '委托管理', '5', '1', 'Trade/index', '0', '', '交易', '0', 'stats');
INSERT INTO `tw_menu` VALUES ('69', '成交记录', '5', '2', 'Trade/log', '0', '', '交易', '0', 'stats');
INSERT INTO `tw_menu` VALUES ('70', '修改状态', '68', '0', 'Trade/status', '1', '', '交易管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('71', '撤销挂单', '68', '0', 'Trade/chexiao', '1', '', '交易管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('74', '认购编辑', '72', '2', 'Issue/edit', '1', '', '认购管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('75', '认购修改', '72', '2', 'Issue/status', '1', '', '认购管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('79', '基本配置', '7', '1', 'Config/index', '0', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('81', '客服配置', '7', '3', 'Config/contact', '0', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('83', '编辑', '82', '4', 'Config/bank_edit', '1', '', '网站配置', '0', '0');
INSERT INTO `tw_menu` VALUES ('85', '编辑', '84', '4', 'Coin/edit', '0', '', '网站配置', '0', '0');
INSERT INTO `tw_menu` VALUES ('87', '状态修改', '84', '4', 'Coin/status', '1', '', '网站配置', '0', '0');
INSERT INTO `tw_menu` VALUES ('89', '编辑市场', '88', '4', 'Market/edit', '0', '', '', '0', '0');
INSERT INTO `tw_menu` VALUES ('91', '状态修改', '88', '4', 'Config/market_add', '1', '', '', '0', '0');
INSERT INTO `tw_menu` VALUES ('95', '其他配置', '7', '6', 'Config/qita', '0', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('115', '图片', '111', '0', 'Shop/images', '0', '', '云购商城', '0', '0');
INSERT INTO `tw_menu` VALUES ('127', '用户登录', '3', '0', 'Login/index', '1', '', '用户配置', '0', '0');
INSERT INTO `tw_menu` VALUES ('128', '用户退出', '3', '0', 'Login/loginout', '1', '', '用户配置', '0', '0');
INSERT INTO `tw_menu` VALUES ('129', '修改管理员密码', '3', '0', 'User/setpwd', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('131', '用户详情', '3', '4', 'User/detail', '1', '', '前台用户管理', '0', 'time');
INSERT INTO `tw_menu` VALUES ('132', '后台用户详情', '3', '1', 'AdminUser/detail', '1', '', '后台用户管理', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('133', '后台用户状态', '3', '1', 'AdminUser/status', '1', '', '后台用户管理', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('134', '后台用户新增', '3', '1', 'AdminUser/add', '1', '', '后台用户管理', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('135', '后台用户编辑', '3', '1', 'AdminUser/edit', '1', '', '后台用户管理', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('138', '编辑', '2', '1', 'Articletype/edit', '1', '', '内容管理', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('140', '编辑', '139', '2', 'Link/edit', '1', '', '内容管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('141', '修改', '139', '2', 'Link/status', '1', '', '内容管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('155', '服务器队列', '9', '3', 'Tools/queue', '0', '', '工具', '0', 'wrench');
INSERT INTO `tw_menu` VALUES ('156', '钱包检查', '9', '3', 'Tools/qianbao', '0', '', '工具', '0', 'wrench');
INSERT INTO `tw_menu` VALUES ('157', '币种统计', '1', '2', 'Index/coin', '0', '', '系统', '0', 'home');
INSERT INTO `tw_menu` VALUES ('163', '提示文字', '7', '5', 'Config/text', '0', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('278', '文章类型', '2', '2', 'Article/type', '0', '', '内容', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('279', '广告管理', '2', '3', 'Article/adver', '0', '', '内容', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('280', '友情链接', '2', '4', 'Article/youqing', '0', '', '内容', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('282', '登陆日志', '3', '4', 'User/log', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('283', '用户钱包', '3', '5', 'User/qianbao', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('284', '提现地址', '3', '6', 'User/bank', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('285', '用户财产', '3', '7', 'User/coin', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('288', '交易市场', '5', '5', 'Trade/market', '0', '', '交易', '0', 'stats');
INSERT INTO `tw_menu` VALUES ('289', '交易推荐', '5', '6', 'Trade/invit', '1', '', '交易', '0', 'stats');
INSERT INTO `tw_menu` VALUES ('291', '人民币充值', '4', '2', 'Finance/mycz', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('292', '人民币充值方式', '4', '3', 'Finance/myczType', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('293', '人民币提现', '4', '4', 'Finance/mytx', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('294', '人民币提现配置', '4', '5', 'Finance/mytxConfig', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('295', '虚拟币转入', '4', '6', 'Finance/myzr', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('296', '虚拟币转出', '4', '7', 'Finance/myzc', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('297', '修改状态', '291', '100', 'Finance/myczStatus', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('298', '确认到账', '291', '100', 'Finance/myczQueren', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('299', '编辑添加', '292', '1', 'Finance/myczTypeEdit', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('300', '状态修改', '292', '2', 'Finance/myczTypeStatus', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('301', '上传图片', '292', '2', 'Finance/myczTypeImage', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('302', '修改状态', '293', '2', 'Finance/mytxStatus', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('303', '导出选中', '293', '3', 'Finance/mytxExcel', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('304', '正在处理', '293', '4', 'Finance/mytxChuli', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('305', '撤销提现', '293', '5', 'Finance/mytxChexiao', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('306', '确认提现', '293', '6', 'Finance/mytxQueren', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('307', '确认转出', '296', '6', 'Finance/myzcQueren', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('309', '清理缓存', '9', '1', 'Tools/index', '0', '', '工具', '0', 'wrench');
INSERT INTO `tw_menu` VALUES ('312', '管理员管理', '3', '2', 'User/admin', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('313', '权限列表', '3', '3', 'User/auth', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('314', '编辑添加', '26', '1', 'User/edit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('315', '修改状态', '26', '1', 'User/status', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('316', '编辑添加', '312', '1', 'User/adminEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('317', '修改状态', '312', '1', 'User/adminStatus', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('318', '编辑添加', '313', '1', 'User/authEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('319', '修改状态', '313', '1', 'User/authStatus', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('320', '重新初始化权限', '313', '1', 'User/authStart', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('321', '编辑添加', '282', '1', 'User/logEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('322', '修改状态', '282', '1', 'User/logStatus', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('323', '编辑添加', '283', '1', 'User/qianbaoEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('324', '修改状态', '283', '1', 'User/qianbaoStatus', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('325', '编辑添加', '284', '1', 'User/bankEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('326', '修改状态', '284', '1', 'User/bankStatus', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('327', '编辑添加', '285', '1', 'User/coinEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('328', '财产统计', '285', '1', 'User/coinLog', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('329', '编辑添加', '286', '1', 'User/goodsEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('330', '修改状态', '286', '1', 'User/goodsStatus', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('331', '编辑添加', '278', '1', 'Article/typeEdit', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('332', '修改状态', '278', '100', 'Article/typeStatus', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('333', '编辑添加', '280', '1', 'Article/youqingEdit', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('334', '修改状态', '280', '100', 'Article/youqingStatus', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('335', '编辑添加', '279', '1', 'Article/adverEdit', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('336', '修改状态', '279', '100', 'Article/adverStatus', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('337', '上传图片', '279', '100', 'Article/adverImage', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('377', '访问授权', '313', '1', 'User/authAccess', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('378', '访问授权修改', '313', '1', 'User/authAccessUp', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('379', '成员授权', '313', '1', 'User/authUser', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('380', '成员授权增加', '313', '1', 'User/authUserAdd', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('381', '成员授权解除', '313', '1', 'User/authUserRemove', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('382', '币种配置', '7', '4', 'Config/coin', '0', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('388', '导航配置', '7', '7', 'Config/daohang', '0', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('444', '短信配置', '7', '2', 'Config/mobile', '0', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('446', '资金变更日志', '3', '9', 'User/amountlog', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('447', '用户反馈', '3', '10', 'User/feedback', '0', '', '用户', '0', '0');
INSERT INTO `tw_menu` VALUES ('448', '实名审核', '3', '102', '/Admin/User/nameauth', '0', '审核用户实名认证信息', '用户', '0', '0');
INSERT INTO `tw_menu` VALUES ('449', '以太坊转账', '4', '100', 'Finance/ethtransfer', '1', '以太坊转账', '财务', '0', '0');
INSERT INTO `tw_menu` VALUES ('450', '导出excel', '291', '0', 'Finance/myczExcel', '1', '', '财务', '1', '0');
INSERT INTO `tw_menu` VALUES ('453', '恢复自动转出队列', '296', '0', 'Tools/recoverzc', '1', '', '财务', '1', '0');
INSERT INTO `tw_menu` VALUES ('454', '查看自动转出队列状态', '296', '0', 'Tools/chkzdzc', '1', '', '财务', '1', '0');
INSERT INTO `tw_menu` VALUES ('455', '批量转出', '296', '0', 'Finance/myzcBatch', '1', '', '财务', '1', '0');
INSERT INTO `tw_menu` VALUES ('456', '批量转出错误日志', '296', '0', 'Finance/myzcBatchLog', '1', '', '财务', '1', '0');
INSERT INTO `tw_menu` VALUES ('457', '导出excel', '68', '0', 'Trade/tradeExcel', '1', '', '交易', '1', '0');
INSERT INTO `tw_menu` VALUES ('458', '导出excel', '69', '0', 'Trade/tradelogExcel', '1', '', '财务', '1', '0');
INSERT INTO `tw_menu` VALUES ('460', '交易奖励', '4', '8', 'Finance/tradePrize', '0', '', '财务', '0', '0');
INSERT INTO `tw_menu` VALUES ('461', '鼓励金', '4', '9', 'Finance/incentive', '0', '', '财务', '0', '0');
INSERT INTO `tw_menu` VALUES ('462', '推荐关系', '3', '2', 'User/invittree', '0', '', '用户', '0', '0');

-- ----------------------------
-- Table structure for tw_mycz
-- ----------------------------
DROP TABLE IF EXISTS `tw_mycz`;
CREATE TABLE `tw_mycz` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `num` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  `mum` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  `type` varchar(50) NOT NULL,
  `tradeno` varchar(50) NOT NULL DEFAULT '',
  `remark` varchar(250) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  `alipay_truename` varchar(20) DEFAULT NULL,
  `alipay_account` varchar(35) DEFAULT NULL,
  `ewmname` varchar(50) NOT NULL DEFAULT '',
  `fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `bank` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='充值记录表';

-- ----------------------------
-- Records of tw_mycz
-- ----------------------------
INSERT INTO `tw_mycz` VALUES ('1', '6272', '100.76', '100.76', 'bank', 'JV549517974', '', '0', '1510816671', '0', '0', '赵薇', '1231231231231231232', '', '0.00', '农业银行');
INSERT INTO `tw_mycz` VALUES ('2', '6272', '100.49', '100.49', 'bank', 'KJ991287433', '', '0', '1510816717', '1510817205', '2', '赵薇', '1123123123123121231231', '', '0.00', '中国银行');

-- ----------------------------
-- Table structure for tw_mycz_invit
-- ----------------------------
DROP TABLE IF EXISTS `tw_mycz_invit`;
CREATE TABLE `tw_mycz_invit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `userid` int(11) unsigned NOT NULL COMMENT '用户id',
  `invitid` int(11) unsigned NOT NULL COMMENT '推荐人id',
  `num` decimal(20,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '操作金额',
  `fee` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '赠送金额',
  `coinname` varchar(50) NOT NULL DEFAULT '' COMMENT '赠送币种',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '到账金额',
  `remark` varchar(250) NOT NULL DEFAULT '' COMMENT '备注',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '编辑时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值赠送';

-- ----------------------------
-- Records of tw_mycz_invit
-- ----------------------------

-- ----------------------------
-- Table structure for tw_mycz_type
-- ----------------------------
DROP TABLE IF EXISTS `tw_mycz_type`;
CREATE TABLE `tw_mycz_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `max` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
  `min` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
  `kaihu` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
  `truename` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
  `name` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(50) NOT NULL DEFAULT '',
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `img` varchar(50) NOT NULL DEFAULT '',
  `extra` varchar(50) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  `fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='充值类型';

-- ----------------------------
-- Records of tw_mycz_type
-- ----------------------------
INSERT INTO `tw_mycz_type` VALUES ('1', '10000000', '100', '', '', 'alipay', '支付宝转账', '', '15099566764', '', '', '', '需要在联系方式里面设置支付宝账号', '0', '0', '0', '0', '0.00');
INSERT INTO `tw_mycz_type` VALUES ('2', '10000000', '100', '中国银行', '', 'bank', '银行卡转帐', '', '6216608300003225303', '', '', '', '需要在联系方式里面设置银行卡号', '0', '0', '0', '1', '0.00');
INSERT INTO `tw_mycz_type` VALUES ('4', '1000', '100', '', '', 'weixin', '微信转账支付', '', '', '', '', '', '需要在联系方式里面设置微信账号', '0', '0', '0', '0', '5.00');

-- ----------------------------
-- Table structure for tw_mytx
-- ----------------------------
DROP TABLE IF EXISTS `tw_mytx`;
CREATE TABLE `tw_mytx` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  `fee` decimal(20,2) unsigned NOT NULL DEFAULT '0.00',
  `mum` decimal(20,2) unsigned NOT NULL DEFAULT '0.00',
  `truename` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `bank` varchar(250) NOT NULL DEFAULT '',
  `bankprov` varchar(50) NOT NULL DEFAULT '',
  `bankcity` varchar(50) NOT NULL DEFAULT '',
  `bankaddr` varchar(50) NOT NULL DEFAULT '',
  `bankcard` varchar(200) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='提现记录表';

-- ----------------------------
-- Records of tw_mytx
-- ----------------------------
INSERT INTO `tw_mytx` VALUES ('1', '6272', '100', '2.00', '98.00', '赵薇', '银行卡', '浦发银行', '北京', '东城区', '12312312', '1231231231212', '0', '1510817522', '0', '2');

-- ----------------------------
-- Table structure for tw_myzc
-- ----------------------------
DROP TABLE IF EXISTS `tw_myzc`;
CREATE TABLE `tw_myzc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `username` varchar(200) NOT NULL,
  `coinname` varchar(200) NOT NULL,
  `txid` varchar(200) NOT NULL DEFAULT '',
  `num` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `fee` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  `to_user` int(2) NOT NULL DEFAULT '0' COMMENT '会员转币',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `status` (`status`),
  KEY `coinname` (`coinname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (1500000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (2000000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (2500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (3000000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (3500000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (4000000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (4500000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (5000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (5500000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (6000000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (6500000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (7000000) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (7500000) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (8000000) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (8500000) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (9000000) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (9500000) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (10000000) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_myzc
-- ----------------------------

-- ----------------------------
-- Table structure for tw_myzc_fee
-- ----------------------------
DROP TABLE IF EXISTS `tw_myzc_fee`;
CREATE TABLE `tw_myzc_fee` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `username` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `coinname` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `txid` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fee` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  `num` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tw_myzc_fee
-- ----------------------------

-- ----------------------------
-- Table structure for tw_myzr
-- ----------------------------
DROP TABLE IF EXISTS `tw_myzr`;
CREATE TABLE `tw_myzr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `username` varchar(200) NOT NULL,
  `coinname` varchar(200) NOT NULL,
  `txid` varchar(200) NOT NULL DEFAULT '',
  `num` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `fee` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  `from_user` int(2) NOT NULL DEFAULT '0' COMMENT '会员转币',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `userid` (`userid`),
  KEY `coinname` (`coinname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (1500000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (2000000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (2500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (3000000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (3500000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (4000000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (4500000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (5000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (5500000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (6000000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (6500000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (7000000) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (7500000) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (8000000) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (8500000) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (9000000) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (9500000) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (10000000) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_myzr
-- ----------------------------

-- ----------------------------
-- Table structure for tw_myzr_json
-- ----------------------------
DROP TABLE IF EXISTS `tw_myzr_json`;
CREATE TABLE `tw_myzr_json` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text,
  `coinname` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_myzr_json
-- ----------------------------

-- ----------------------------
-- Table structure for tw_text
-- ----------------------------
DROP TABLE IF EXISTS `tw_text`;
CREATE TABLE `tw_text` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_text
-- ----------------------------
INSERT INTO `tw_text` VALUES ('1', 'user_index', '安全中心首页', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('2', 'user_truename', '实名认证', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('3', 'user_bank', '银行卡管理', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('4', 'user_group', '用户级别', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('5', 'user_log', '账户日志', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160600', '0', '1');
INSERT INTO `tw_text` VALUES ('6', 'user_message', '我的消息', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('7', 'user_moble', '手机绑定', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('8', 'user_email', '邮箱绑定', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('9', 'user_google', '谷歌验证绑定', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('10', 'user_password', '修改登录密码', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('11', 'user_paypassword', '修改交易密码', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('12', 'user_question', '提交问题', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('13', 'user_qianbao', '钱包管理', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('14', 'pool_index', '矿机首页', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('15', 'pool_log', '矿机管理', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('16', 'issue_index', '认购首页', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('17', 'issue_index', '认购记录', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('18', 'finance_mycz', '认购记录', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1457160962', '0', '1');
INSERT INTO `tw_text` VALUES ('19', 'user_nameauth', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464677557', '0', '1');
INSERT INTO `tw_text` VALUES ('20', 'user_ga', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464677561', '0', '1');
INSERT INTO `tw_text` VALUES ('21', 'user_alipay', '绑定支付宝账号', '<span><span style=\"background-color:#FFFFFF;\">您填写的支付宝账号的实名认证信息需要和注册本网站时填写的实名信息一致，否则该支付宝账号不能用来提现。</span></span>', '0', '1464677631', '0', '1');
INSERT INTO `tw_text` VALUES ('22', 'user_tpwdset', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464677866', '0', '1');
INSERT INTO `tw_text` VALUES ('23', 'user_goods', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464677872', '0', '1');
INSERT INTO `tw_text` VALUES ('24', 'finance_index', '213', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息121321321321321</span></span>', '0', '1464678956', '0', '0');
INSERT INTO `tw_text` VALUES ('25', 'finance_mytx', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464678959', '0', '1');
INSERT INTO `tw_text` VALUES ('26', 'finance_myzr', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464678960', '0', '1');
INSERT INTO `tw_text` VALUES ('27', 'finance_myzc', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464678961', '0', '1');
INSERT INTO `tw_text` VALUES ('28', 'finance_mywt', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464678962', '0', '1');
INSERT INTO `tw_text` VALUES ('29', 'finance_mycj', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464678963', '0', '1');
INSERT INTO `tw_text` VALUES ('30', 'finance_mytj', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464678964', '0', '1');
INSERT INTO `tw_text` VALUES ('31', 'finance_mywd', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464678966', '0', '1');
INSERT INTO `tw_text` VALUES ('32', 'finance_myjp', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464678967', '0', '1');
INSERT INTO `tw_text` VALUES ('33', 'game_shop_goods', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464695180', '0', '1');
INSERT INTO `tw_text` VALUES ('34', 'game_issue', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464695216', '0', '1');
INSERT INTO `tw_text` VALUES ('35', 'game_issue_log', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464695219', '0', '1');
INSERT INTO `tw_text` VALUES ('36', 'game_fenhong', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464695289', '0', '1');
INSERT INTO `tw_text` VALUES ('37', 'game_fenhong_log', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464695290', '0', '1');
INSERT INTO `tw_text` VALUES ('38', 'game_shop', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464695294', '0', '1');
INSERT INTO `tw_text` VALUES ('39', 'game_shop_log', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464695669', '0', '1');
INSERT INTO `tw_text` VALUES ('40', 'game_shop_view', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464710521', '0', '1');
INSERT INTO `tw_text` VALUES ('41', 'game_issue_buy', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464762288', '0', '1');
INSERT INTO `tw_text` VALUES ('42', 'game_vote', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1464856754', '0', '1');
INSERT INTO `tw_text` VALUES ('43', 'game_huafei', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1466398472', '0', '1');
INSERT INTO `tw_text` VALUES ('44', 'game_money', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1467371753', '0', '1');
INSERT INTO `tw_text` VALUES ('45', 'game_money_log', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1467371757', '0', '1');
INSERT INTO `tw_text` VALUES ('46', 'user_mobile', '', '<span style=\"color:#0096E0;line-height:21px;background-color:#FFFFFF;\"><span>请在后台修改此处内容</span></span><span style=\"color:#0096E0;line-height:21px;font-family:\'Microsoft Yahei\', \'Sim sun\', tahoma, \'Helvetica,Neue\', Helvetica, STHeiTi, Arial, sans-serif;background-color:#FFFFFF;\">,<span style=\"color:#EE33EE;\">详细信息</span></span>', '0', '1490837035', '0', '1');

-- ----------------------------
-- Table structure for tw_trade
-- ----------------------------
DROP TABLE IF EXISTS `tw_trade`;
CREATE TABLE `tw_trade` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `market` varchar(50) NOT NULL,
  `price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `num` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `deal` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `fee` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `type` tinyint(2) unsigned NOT NULL,
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `market` (`market`,`type`,`status`),
  KEY `num` (`num`,`deal`),
  KEY `status` (`status`),
  KEY `market_2` (`market`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='交易下单表'
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (1500000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (2000000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (2500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (3000000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (3500000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (4000000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (4500000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (5000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (5500000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (6000000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (6500000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (7000000) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (7500000) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (8000000) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (8500000) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (9000000) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (9500000) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (10000000) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN (10500000) ENGINE = InnoDB,
 PARTITION p22 VALUES LESS THAN (11000000) ENGINE = InnoDB,
 PARTITION p23 VALUES LESS THAN (11500000) ENGINE = InnoDB,
 PARTITION p24 VALUES LESS THAN (12000000) ENGINE = InnoDB,
 PARTITION p25 VALUES LESS THAN (12500000) ENGINE = InnoDB,
 PARTITION p26 VALUES LESS THAN (13000000) ENGINE = InnoDB,
 PARTITION p27 VALUES LESS THAN (13500000) ENGINE = InnoDB,
 PARTITION p28 VALUES LESS THAN (14000000) ENGINE = InnoDB,
 PARTITION p29 VALUES LESS THAN (14500000) ENGINE = InnoDB,
 PARTITION p30 VALUES LESS THAN (15000000) ENGINE = InnoDB,
 PARTITION p31 VALUES LESS THAN (15500000) ENGINE = InnoDB,
 PARTITION p32 VALUES LESS THAN (16000000) ENGINE = InnoDB,
 PARTITION p33 VALUES LESS THAN (16500000) ENGINE = InnoDB,
 PARTITION p34 VALUES LESS THAN (17000000) ENGINE = InnoDB,
 PARTITION p35 VALUES LESS THAN (17500000) ENGINE = InnoDB,
 PARTITION p36 VALUES LESS THAN (18000000) ENGINE = InnoDB,
 PARTITION p37 VALUES LESS THAN (18500000) ENGINE = InnoDB,
 PARTITION p38 VALUES LESS THAN (19000000) ENGINE = InnoDB,
 PARTITION p39 VALUES LESS THAN (19500000) ENGINE = InnoDB,
 PARTITION p40 VALUES LESS THAN (20000000) ENGINE = InnoDB,
 PARTITION p41 VALUES LESS THAN (20500000) ENGINE = InnoDB,
 PARTITION p42 VALUES LESS THAN (21000000) ENGINE = InnoDB,
 PARTITION p43 VALUES LESS THAN (21500000) ENGINE = InnoDB,
 PARTITION p44 VALUES LESS THAN (22000000) ENGINE = InnoDB,
 PARTITION p45 VALUES LESS THAN (22500000) ENGINE = InnoDB,
 PARTITION p46 VALUES LESS THAN (23000000) ENGINE = InnoDB,
 PARTITION p47 VALUES LESS THAN (23500000) ENGINE = InnoDB,
 PARTITION p48 VALUES LESS THAN (24000000) ENGINE = InnoDB,
 PARTITION p49 VALUES LESS THAN (24500000) ENGINE = InnoDB,
 PARTITION p50 VALUES LESS THAN (25000000) ENGINE = InnoDB,
 PARTITION p51 VALUES LESS THAN (25500000) ENGINE = InnoDB,
 PARTITION p52 VALUES LESS THAN (26000000) ENGINE = InnoDB,
 PARTITION p53 VALUES LESS THAN (26500000) ENGINE = InnoDB,
 PARTITION p54 VALUES LESS THAN (27000000) ENGINE = InnoDB,
 PARTITION p55 VALUES LESS THAN (27500000) ENGINE = InnoDB,
 PARTITION p56 VALUES LESS THAN (28000000) ENGINE = InnoDB,
 PARTITION p57 VALUES LESS THAN (28500000) ENGINE = InnoDB,
 PARTITION p58 VALUES LESS THAN (29000000) ENGINE = InnoDB,
 PARTITION p59 VALUES LESS THAN (30000000) ENGINE = InnoDB,
 PARTITION p60 VALUES LESS THAN (30500000) ENGINE = InnoDB,
 PARTITION p61 VALUES LESS THAN (31000000) ENGINE = InnoDB,
 PARTITION p62 VALUES LESS THAN (31500000) ENGINE = InnoDB,
 PARTITION p63 VALUES LESS THAN (32000000) ENGINE = InnoDB,
 PARTITION p64 VALUES LESS THAN (32500000) ENGINE = InnoDB,
 PARTITION p65 VALUES LESS THAN (33000000) ENGINE = InnoDB,
 PARTITION p66 VALUES LESS THAN (33500000) ENGINE = InnoDB,
 PARTITION p67 VALUES LESS THAN (34000000) ENGINE = InnoDB,
 PARTITION p68 VALUES LESS THAN (34500000) ENGINE = InnoDB,
 PARTITION p69 VALUES LESS THAN (35000000) ENGINE = InnoDB,
 PARTITION p70 VALUES LESS THAN (35500000) ENGINE = InnoDB,
 PARTITION p71 VALUES LESS THAN (36000000) ENGINE = InnoDB,
 PARTITION p72 VALUES LESS THAN (36500000) ENGINE = InnoDB,
 PARTITION p73 VALUES LESS THAN (37000000) ENGINE = InnoDB,
 PARTITION p74 VALUES LESS THAN (37500000) ENGINE = InnoDB,
 PARTITION p75 VALUES LESS THAN (38000000) ENGINE = InnoDB,
 PARTITION p76 VALUES LESS THAN (38500000) ENGINE = InnoDB,
 PARTITION p77 VALUES LESS THAN (39000000) ENGINE = InnoDB,
 PARTITION p78 VALUES LESS THAN (39500000) ENGINE = InnoDB,
 PARTITION p79 VALUES LESS THAN (40000000) ENGINE = InnoDB,
 PARTITION p80 VALUES LESS THAN (40500000) ENGINE = InnoDB,
 PARTITION p81 VALUES LESS THAN (41000000) ENGINE = InnoDB,
 PARTITION p82 VALUES LESS THAN (41500000) ENGINE = InnoDB,
 PARTITION p83 VALUES LESS THAN (42000000) ENGINE = InnoDB,
 PARTITION p84 VALUES LESS THAN (42500000) ENGINE = InnoDB,
 PARTITION p85 VALUES LESS THAN (43000000) ENGINE = InnoDB,
 PARTITION p86 VALUES LESS THAN (43500000) ENGINE = InnoDB,
 PARTITION p87 VALUES LESS THAN (44000000) ENGINE = InnoDB,
 PARTITION p88 VALUES LESS THAN (44500000) ENGINE = InnoDB,
 PARTITION p89 VALUES LESS THAN (45000000) ENGINE = InnoDB,
 PARTITION p90 VALUES LESS THAN (45500000) ENGINE = InnoDB,
 PARTITION p91 VALUES LESS THAN (46000000) ENGINE = InnoDB,
 PARTITION p92 VALUES LESS THAN (46500000) ENGINE = InnoDB,
 PARTITION p93 VALUES LESS THAN (47000000) ENGINE = InnoDB,
 PARTITION p94 VALUES LESS THAN (47500000) ENGINE = InnoDB,
 PARTITION p95 VALUES LESS THAN (48000000) ENGINE = InnoDB,
 PARTITION p96 VALUES LESS THAN (48500000) ENGINE = InnoDB,
 PARTITION p97 VALUES LESS THAN (49000000) ENGINE = InnoDB,
 PARTITION p98 VALUES LESS THAN (49500000) ENGINE = InnoDB,
 PARTITION p99 VALUES LESS THAN (50000000) ENGINE = InnoDB,
 PARTITION p100 VALUES LESS THAN (50500000) ENGINE = InnoDB,
 PARTITION p101 VALUES LESS THAN (51000000) ENGINE = InnoDB,
 PARTITION p102 VALUES LESS THAN (51500000) ENGINE = InnoDB,
 PARTITION p103 VALUES LESS THAN (52000000) ENGINE = InnoDB,
 PARTITION p104 VALUES LESS THAN (52500000) ENGINE = InnoDB,
 PARTITION p105 VALUES LESS THAN (53000000) ENGINE = InnoDB,
 PARTITION p106 VALUES LESS THAN (53500000) ENGINE = InnoDB,
 PARTITION p107 VALUES LESS THAN (54000000) ENGINE = InnoDB,
 PARTITION p108 VALUES LESS THAN (54500000) ENGINE = InnoDB,
 PARTITION p109 VALUES LESS THAN (55000000) ENGINE = InnoDB,
 PARTITION p110 VALUES LESS THAN (55500000) ENGINE = InnoDB,
 PARTITION p111 VALUES LESS THAN (56000000) ENGINE = InnoDB,
 PARTITION p112 VALUES LESS THAN (56500000) ENGINE = InnoDB,
 PARTITION p113 VALUES LESS THAN (57000000) ENGINE = InnoDB,
 PARTITION p114 VALUES LESS THAN (57500000) ENGINE = InnoDB,
 PARTITION p115 VALUES LESS THAN (58000000) ENGINE = InnoDB,
 PARTITION p116 VALUES LESS THAN (58500000) ENGINE = InnoDB,
 PARTITION p117 VALUES LESS THAN (59000000) ENGINE = InnoDB,
 PARTITION p118 VALUES LESS THAN (59500000) ENGINE = InnoDB,
 PARTITION p119 VALUES LESS THAN (60000000) ENGINE = InnoDB,
 PARTITION p120 VALUES LESS THAN (60500000) ENGINE = InnoDB,
 PARTITION p121 VALUES LESS THAN (61000000) ENGINE = InnoDB,
 PARTITION p122 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_trade
-- ----------------------------
INSERT INTO `tw_trade` VALUES ('1', '6272', 'ltc_btc', '0.01110000', '1.00000000', '1.00000000', '0.01111110', '0.00001110', '1', '0', '1510817655', '0', '1');
INSERT INTO `tw_trade` VALUES ('2', '6272', 'ltc_btc', '0.01100000', '1.00000000', '1.00000000', '0.01098900', '0.00001100', '2', '0', '1510817663', '0', '1');
INSERT INTO `tw_trade` VALUES ('3', '6272', 'bcc_btc', '0.05600000', '1.00000000', '1.00000000', '0.05605600', '0.00005600', '1', '0', '1510817673', '0', '1');
INSERT INTO `tw_trade` VALUES ('4', '6272', 'bcc_btc', '0.05600000', '1.00000000', '1.00000000', '0.05594400', '0.00005600', '2', '0', '1510817684', '0', '1');

-- ----------------------------
-- Table structure for tw_trade_json
-- ----------------------------
DROP TABLE IF EXISTS `tw_trade_json`;
CREATE TABLE `tw_trade_json` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `market` varchar(100) NOT NULL,
  `data` varchar(500) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `market` (`market`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易图表表';

-- ----------------------------
-- Records of tw_trade_json
-- ----------------------------

-- ----------------------------
-- Table structure for tw_trade_json_copy
-- ----------------------------
DROP TABLE IF EXISTS `tw_trade_json_copy`;
CREATE TABLE `tw_trade_json_copy` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `market` varchar(100) NOT NULL,
  `data` varchar(500) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `market` (`market`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易图表表';

-- ----------------------------
-- Records of tw_trade_json_copy
-- ----------------------------

-- ----------------------------
-- Table structure for tw_trade_log
-- ----------------------------
DROP TABLE IF EXISTS `tw_trade_log`;
CREATE TABLE `tw_trade_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `peerid` int(11) unsigned NOT NULL,
  `market` varchar(50) NOT NULL,
  `price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `num` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `fee_buy` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `fee_sell` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `userid` (`userid`),
  KEY `peerid` (`peerid`),
  KEY `main` (`market`,`status`,`addtime`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (1500000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (2000000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (2500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (3000000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (3500000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (4000000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (4500000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (5000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (5500000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (6000000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (6500000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (7000000) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (7500000) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (8000000) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (8500000) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (9000000) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (9500000) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (10000000) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN (10500000) ENGINE = InnoDB,
 PARTITION p22 VALUES LESS THAN (11000000) ENGINE = InnoDB,
 PARTITION p23 VALUES LESS THAN (11500000) ENGINE = InnoDB,
 PARTITION p24 VALUES LESS THAN (12000000) ENGINE = InnoDB,
 PARTITION p25 VALUES LESS THAN (12500000) ENGINE = InnoDB,
 PARTITION p26 VALUES LESS THAN (13000000) ENGINE = InnoDB,
 PARTITION p27 VALUES LESS THAN (13500000) ENGINE = InnoDB,
 PARTITION p28 VALUES LESS THAN (14000000) ENGINE = InnoDB,
 PARTITION p29 VALUES LESS THAN (14500000) ENGINE = InnoDB,
 PARTITION p30 VALUES LESS THAN (15000000) ENGINE = InnoDB,
 PARTITION p31 VALUES LESS THAN (15500000) ENGINE = InnoDB,
 PARTITION p32 VALUES LESS THAN (16000000) ENGINE = InnoDB,
 PARTITION p33 VALUES LESS THAN (16500000) ENGINE = InnoDB,
 PARTITION p34 VALUES LESS THAN (17000000) ENGINE = InnoDB,
 PARTITION p35 VALUES LESS THAN (17500000) ENGINE = InnoDB,
 PARTITION p36 VALUES LESS THAN (18000000) ENGINE = InnoDB,
 PARTITION p37 VALUES LESS THAN (18500000) ENGINE = InnoDB,
 PARTITION p38 VALUES LESS THAN (19000000) ENGINE = InnoDB,
 PARTITION p39 VALUES LESS THAN (19500000) ENGINE = InnoDB,
 PARTITION p40 VALUES LESS THAN (20000000) ENGINE = InnoDB,
 PARTITION p41 VALUES LESS THAN (20500000) ENGINE = InnoDB,
 PARTITION p42 VALUES LESS THAN (21000000) ENGINE = InnoDB,
 PARTITION p43 VALUES LESS THAN (21500000) ENGINE = InnoDB,
 PARTITION p44 VALUES LESS THAN (22000000) ENGINE = InnoDB,
 PARTITION p45 VALUES LESS THAN (22500000) ENGINE = InnoDB,
 PARTITION p46 VALUES LESS THAN (23000000) ENGINE = InnoDB,
 PARTITION p47 VALUES LESS THAN (23500000) ENGINE = InnoDB,
 PARTITION p48 VALUES LESS THAN (24000000) ENGINE = InnoDB,
 PARTITION p49 VALUES LESS THAN (24500000) ENGINE = InnoDB,
 PARTITION p50 VALUES LESS THAN (25000000) ENGINE = InnoDB,
 PARTITION p51 VALUES LESS THAN (25500000) ENGINE = InnoDB,
 PARTITION p52 VALUES LESS THAN (26000000) ENGINE = InnoDB,
 PARTITION p53 VALUES LESS THAN (26500000) ENGINE = InnoDB,
 PARTITION p54 VALUES LESS THAN (27000000) ENGINE = InnoDB,
 PARTITION p55 VALUES LESS THAN (27500000) ENGINE = InnoDB,
 PARTITION p56 VALUES LESS THAN (28000000) ENGINE = InnoDB,
 PARTITION p57 VALUES LESS THAN (28500000) ENGINE = InnoDB,
 PARTITION p58 VALUES LESS THAN (29000000) ENGINE = InnoDB,
 PARTITION p59 VALUES LESS THAN (30000000) ENGINE = InnoDB,
 PARTITION p60 VALUES LESS THAN (30500000) ENGINE = InnoDB,
 PARTITION p61 VALUES LESS THAN (31000000) ENGINE = InnoDB,
 PARTITION p62 VALUES LESS THAN (31500000) ENGINE = InnoDB,
 PARTITION p63 VALUES LESS THAN (32000000) ENGINE = InnoDB,
 PARTITION p64 VALUES LESS THAN (32500000) ENGINE = InnoDB,
 PARTITION p65 VALUES LESS THAN (33000000) ENGINE = InnoDB,
 PARTITION p66 VALUES LESS THAN (33500000) ENGINE = InnoDB,
 PARTITION p67 VALUES LESS THAN (34000000) ENGINE = InnoDB,
 PARTITION p68 VALUES LESS THAN (34500000) ENGINE = InnoDB,
 PARTITION p69 VALUES LESS THAN (35000000) ENGINE = InnoDB,
 PARTITION p70 VALUES LESS THAN (35500000) ENGINE = InnoDB,
 PARTITION p71 VALUES LESS THAN (36000000) ENGINE = InnoDB,
 PARTITION p72 VALUES LESS THAN (36500000) ENGINE = InnoDB,
 PARTITION p73 VALUES LESS THAN (37000000) ENGINE = InnoDB,
 PARTITION p74 VALUES LESS THAN (37500000) ENGINE = InnoDB,
 PARTITION p75 VALUES LESS THAN (38000000) ENGINE = InnoDB,
 PARTITION p76 VALUES LESS THAN (38500000) ENGINE = InnoDB,
 PARTITION p77 VALUES LESS THAN (39000000) ENGINE = InnoDB,
 PARTITION p78 VALUES LESS THAN (39500000) ENGINE = InnoDB,
 PARTITION p79 VALUES LESS THAN (40000000) ENGINE = InnoDB,
 PARTITION p80 VALUES LESS THAN (40500000) ENGINE = InnoDB,
 PARTITION p81 VALUES LESS THAN (41000000) ENGINE = InnoDB,
 PARTITION p82 VALUES LESS THAN (41500000) ENGINE = InnoDB,
 PARTITION p83 VALUES LESS THAN (42000000) ENGINE = InnoDB,
 PARTITION p84 VALUES LESS THAN (42500000) ENGINE = InnoDB,
 PARTITION p85 VALUES LESS THAN (43000000) ENGINE = InnoDB,
 PARTITION p86 VALUES LESS THAN (43500000) ENGINE = InnoDB,
 PARTITION p87 VALUES LESS THAN (44000000) ENGINE = InnoDB,
 PARTITION p88 VALUES LESS THAN (44500000) ENGINE = InnoDB,
 PARTITION p89 VALUES LESS THAN (45000000) ENGINE = InnoDB,
 PARTITION p90 VALUES LESS THAN (45500000) ENGINE = InnoDB,
 PARTITION p91 VALUES LESS THAN (46000000) ENGINE = InnoDB,
 PARTITION p92 VALUES LESS THAN (46500000) ENGINE = InnoDB,
 PARTITION p93 VALUES LESS THAN (47000000) ENGINE = InnoDB,
 PARTITION p94 VALUES LESS THAN (47500000) ENGINE = InnoDB,
 PARTITION p95 VALUES LESS THAN (48000000) ENGINE = InnoDB,
 PARTITION p96 VALUES LESS THAN (48500000) ENGINE = InnoDB,
 PARTITION p97 VALUES LESS THAN (49000000) ENGINE = InnoDB,
 PARTITION p98 VALUES LESS THAN (49500000) ENGINE = InnoDB,
 PARTITION p99 VALUES LESS THAN (50000000) ENGINE = InnoDB,
 PARTITION p100 VALUES LESS THAN (50500000) ENGINE = InnoDB,
 PARTITION p101 VALUES LESS THAN (51000000) ENGINE = InnoDB,
 PARTITION p102 VALUES LESS THAN (51500000) ENGINE = InnoDB,
 PARTITION p103 VALUES LESS THAN (52000000) ENGINE = InnoDB,
 PARTITION p104 VALUES LESS THAN (52500000) ENGINE = InnoDB,
 PARTITION p105 VALUES LESS THAN (53000000) ENGINE = InnoDB,
 PARTITION p106 VALUES LESS THAN (53500000) ENGINE = InnoDB,
 PARTITION p107 VALUES LESS THAN (54000000) ENGINE = InnoDB,
 PARTITION p108 VALUES LESS THAN (54500000) ENGINE = InnoDB,
 PARTITION p109 VALUES LESS THAN (55000000) ENGINE = InnoDB,
 PARTITION p110 VALUES LESS THAN (55500000) ENGINE = InnoDB,
 PARTITION p111 VALUES LESS THAN (56000000) ENGINE = InnoDB,
 PARTITION p112 VALUES LESS THAN (56500000) ENGINE = InnoDB,
 PARTITION p113 VALUES LESS THAN (57000000) ENGINE = InnoDB,
 PARTITION p114 VALUES LESS THAN (57500000) ENGINE = InnoDB,
 PARTITION p115 VALUES LESS THAN (58000000) ENGINE = InnoDB,
 PARTITION p116 VALUES LESS THAN (58500000) ENGINE = InnoDB,
 PARTITION p117 VALUES LESS THAN (59000000) ENGINE = InnoDB,
 PARTITION p118 VALUES LESS THAN (59500000) ENGINE = InnoDB,
 PARTITION p119 VALUES LESS THAN (60000000) ENGINE = InnoDB,
 PARTITION p120 VALUES LESS THAN (60500000) ENGINE = InnoDB,
 PARTITION p121 VALUES LESS THAN (61000000) ENGINE = InnoDB,
 PARTITION p122 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_trade_log
-- ----------------------------
INSERT INTO `tw_trade_log` VALUES ('1', '6272', '6272', 'ltc_btc', '0.01110000', '1.00000000', '0.01110000', '0.00001110', '0.00001110', '2', '0', '1510817663', '0', '1');
INSERT INTO `tw_trade_log` VALUES ('2', '6272', '6272', 'bcc_btc', '0.05600000', '1.00000000', '0.05600000', '0.00005600', '0.00005600', '2', '0', '1510817684', '0', '1');

-- ----------------------------
-- Table structure for tw_user
-- ----------------------------
DROP TABLE IF EXISTS `tw_user`;
CREATE TABLE `tw_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `mobiletime` int(11) unsigned NOT NULL DEFAULT '0',
  `password` varchar(32) NOT NULL,
  `tpwdsetting` varchar(32) NOT NULL DEFAULT '',
  `paypassword` varchar(50) DEFAULT '',
  `invit_1` varchar(50) NOT NULL DEFAULT '',
  `invit_2` varchar(50) NOT NULL DEFAULT '',
  `invit_3` varchar(50) NOT NULL DEFAULT '',
  `truename` varchar(32) DEFAULT '',
  `idcard` varchar(32) DEFAULT '',
  `logins` int(11) unsigned NOT NULL DEFAULT '0',
  `ga` varchar(50) DEFAULT NULL,
  `addip` varchar(50) NOT NULL DEFAULT '',
  `addr` varchar(50) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `alipay` varchar(50) DEFAULT NULL COMMENT '支付宝',
  `invit` varchar(50) DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `mibao_question` varchar(200) DEFAULT NULL COMMENT '密保',
  `mibao_answer` varchar(200) DEFAULT NULL COMMENT '密保答案',
  `zhengjian` varchar(20) DEFAULT NULL,
  `idcard_zheng` varchar(200) DEFAULT NULL,
  `idcard_fan` varchar(200) DEFAULT NULL,
  `findpwd_mibao` tinyint(1) DEFAULT '0',
  `findpaypwd_mibao` tinyint(1) DEFAULT '0',
  `is_agree` tinyint(1) DEFAULT '0' COMMENT '0是未进行同意操作（默认） 1是已经同意',
  `idcard_shouchi` varchar(200) DEFAULT NULL,
  `ethpassword` varchar(50) DEFAULT NULL,
  `etcpassword` varchar(50) DEFAULT NULL,
  `pwd_err` tinyint(1) NOT NULL DEFAULT '0' COMMENT '登陆密码错误次数',
  `buy_sum` decimal(20,5) DEFAULT '0.00000',
  `sell_sum` decimal(20,5) DEFAULT '0.00000',
  `trade_sum` decimal(20,5) DEFAULT '0.00000',
  `cpcprize_sum` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `mobile` (`mobile`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE,
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=6273 DEFAULT CHARSET=utf8 COMMENT='用户信息表';

-- ----------------------------
-- Records of tw_user
-- ----------------------------
INSERT INTO `tw_user` VALUES ('6271', 'admin', '18888888888', '1464527801', '8a6f2805b4515ac12058e79e66539be9', '1', 'c293048f9e4415de9d3c28705d5c4646', '0', '0', '0', '官方测试', '888888111111115555', '427', '', '119.96.222.46', '未分配或者内网IP', '0', '1464527738', '0', '1', null, '13116614698', 'XZHRAP', '', null, '11', 'sfz', null, null, '0', '0', '1', null, '', null, '0', '0.00000', '0.00000', '0.00000', '0');
INSERT INTO `tw_user` VALUES ('6272', 'xxxxxxx@qq.com', null, '1510815223', '8a6f2805b4515ac12058e79e66539be9', '1', 'c293048f9e4415de9d3c28705d5c4646', '0', '0', '0', '赵薇', '341234196510011110', '1', null, '127.0.0.1', '未分配或者内网IP', '0', '1510815223', '0', '1', 'xxxxxxx@qq.com', null, 'HJLVBFTDN', null, null, null, 'sfz', null, null, '0', '0', '0', null, null, null, '0', '0.06710', '0.06710', '0.13420', '0');

-- ----------------------------
-- Table structure for tw_user_bank
-- ----------------------------
DROP TABLE IF EXISTS `tw_user_bank`;
CREATE TABLE `tw_user_bank` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `bank` varchar(200) NOT NULL DEFAULT '',
  `bankprov` varchar(200) NOT NULL DEFAULT '',
  `bankcity` varchar(200) NOT NULL DEFAULT '',
  `bankaddr` varchar(200) NOT NULL DEFAULT '',
  `bankcard` varchar(200) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_user_bank
-- ----------------------------
INSERT INTO `tw_user_bank` VALUES ('1', '6272', '银行卡', '浦发银行', '北京', '东城区', '12312312', '1231231231212', '0', '1510817422', '0', '1');

-- ----------------------------
-- Table structure for tw_user_bank_type
-- ----------------------------
DROP TABLE IF EXISTS `tw_user_bank_type`;
CREATE TABLE `tw_user_bank_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `img` varchar(200) NOT NULL DEFAULT '',
  `mytx` varchar(200) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='常用银行地址';

-- ----------------------------
-- Records of tw_user_bank_type
-- ----------------------------
INSERT INTO `tw_user_bank_type` VALUES ('4', 'boc', '中国银行', 'http://www.boc.cn/', 'img_56937003683ce.jpg', '', '', '0', '1452503043', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('5', 'abc', '农业银行', 'http://www.abchina.com/cn/', 'img_569370458b18d.jpg', '', '', '0', '1452503109', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('6', 'bccb', '北京银行', 'http://www.bankofbeijing.com.cn/', 'img_569370588dcdc.jpg', '', '', '0', '1452503128', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('8', 'ccb', '建设银行', 'http://www.ccb.com/', 'img_5693709bbd20f.jpg', '', '', '0', '1452503195', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('9', 'ceb', '光大银行', 'http://www.bankofbeijing.com.cn/', 'img_569370b207cc8.jpg', '', '', '0', '1452503218', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('10', 'cib', '兴业银行', 'http://www.cib.com.cn/cn/index.html', 'img_569370d29bf59.jpg', '', '', '0', '1452503250', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('11', 'citic', '中信银行', 'http://www.ecitic.com/', 'img_569370fb7a1b3.jpg', '', '', '0', '1452503291', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('12', 'cmb', '招商银行', 'http://www.cmbchina.com/', 'img_5693710a9ac9c.jpg', '', '', '0', '1452503306', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('13', 'cmbc', '民生银行', 'http://www.cmbchina.com/', 'img_5693711f97a9d.jpg', '', '', '0', '1452503327', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('14', 'comm', '交通银行', 'http://www.bankcomm.com/BankCommSite/default.shtml', 'img_5693713076351.jpg', '', '', '0', '1452503344', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('16', 'gdb', '广发银行', 'http://www.cgbchina.com.cn/', 'img_56937154bebc5.jpg', '', '', '0', '1452503380', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('17', 'icbc', '工商银行', 'http://www.icbc.com.cn/icbc/', 'img_56937162db7f5.jpg', '', '', '0', '1452503394', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('19', 'psbc', '邮政银行', 'http://www.psbc.com/portal/zh_CN/index.html', 'img_5693717eefaa3.jpg', '', '', '0', '1452503422', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('20', 'spdb', '浦发银行', 'http://www.spdb.com.cn/chpage/c1/', 'img_5693718f1d70e.jpg', '', '', '0', '1452503439', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('21', 'szpab', '平安银行', 'http://bank.pingan.com/', '56c2e4c9aff85.jpg', '', '', '0', '1455613129', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('22', 'alipay', '支付宝', 'https://www.alipay.com/', '56c2e4c9aff85.jpg', '', '', '0', '1455613129', '0', '0');
INSERT INTO `tw_user_bank_type` VALUES ('23', 'tenpay', '财付通', 'https://www.tenpay.com/v3/', '56c2e4c9aff85.jpg', '', '', '0', '1455613129', '0', '0');

-- ----------------------------
-- Table structure for tw_user_coin
-- ----------------------------
DROP TABLE IF EXISTS `tw_user_coin`;
CREATE TABLE `tw_user_coin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `btc` decimal(20,8) unsigned NOT NULL,
  `btcd` decimal(20,8) unsigned NOT NULL,
  `btcb` varchar(200) NOT NULL,
  `ltc` decimal(20,8) unsigned NOT NULL,
  `ltcd` decimal(20,8) unsigned NOT NULL,
  `ltcb` varchar(200) NOT NULL,
  `bcc` decimal(20,8) unsigned NOT NULL,
  `bccd` decimal(20,8) unsigned NOT NULL,
  `bccb` varchar(200) NOT NULL,
  `eth` decimal(20,8) unsigned NOT NULL,
  `ethd` decimal(20,8) unsigned NOT NULL,
  `ethb` varchar(200) NOT NULL,
  `cny` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  `cnyd` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户币种表';

-- ----------------------------
-- Records of tw_user_coin
-- ----------------------------
INSERT INTO `tw_user_coin` VALUES ('1', '6271', '0.00000000', '0.00000000', '1Pkagvrp88Z8XrvCfFT4xfwR34s8gMRnfv', '0.00000000', '0.00000000', 'LaUNJ8GY7XXT3CcapCUF2ZcVjm1QbBJgQS', '0.00000000', '0.00000000', 'CGBDjtbhvUuBNZ5HeLKzJszTEwKCWCF4RE', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000');
INSERT INTO `tw_user_coin` VALUES ('2', '6272', '9.99986580', '0.00000000', '', '100.00000000', '0.00000000', '', '1000.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '100.49000000', '0.00000000');

-- ----------------------------
-- Table structure for tw_user_log
-- ----------------------------
DROP TABLE IF EXISTS `tw_user_log`;
CREATE TABLE `tw_user_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `addip` varchar(20) NOT NULL DEFAULT '',
  `addr` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `session_key` varchar(100) DEFAULT NULL,
  `state` tinyint(4) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `state` (`state`) USING BTREE,
  KEY `session_key` (`session_key`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='用户记录表'
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (100000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (200000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (300000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (400000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (600000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (700000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (800000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (900000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_user_log
-- ----------------------------
INSERT INTO `tw_user_log` VALUES ('1', '6272', 'login', '注册完成后自动登录', '127.0.0.1', '未分配或者内网IP', '0', '1510815223', '1535361042', '1', 'cq5flqm4oier4oktmbuvag9q04', '0');
INSERT INTO `tw_user_log` VALUES ('2', '6271', 'login', 'PC端Windows NT 6.1，Firefox/61.0登录', '127.0.0.1', '未分配或者内网IP', '0', '1535359762', '1535360885', '1', '9unpmphjjjtfn4qq53vvbt7pj7', '0');
INSERT INTO `tw_user_log` VALUES ('3', '6271', 'login', 'PC端Windows NT 6.1，Firefox/61.0登录', '127.0.0.1', '未分配或者内网IP', '0', '1535360884', '1535360885', '1', '9go8depjn00dlii7a3flouaid1', '0');
INSERT INTO `tw_user_log` VALUES ('4', '6271', 'login', 'PC端Windows NT 6.1，Firefox/61.0登录', '127.0.0.1', '未分配或者内网IP', '0', '1535360884', '1535360885', '1', '9go8depjn00dlii7a3flouaid1', '0');
INSERT INTO `tw_user_log` VALUES ('5', '6271', 'login', 'PC端Windows NT 6.1，Firefox/61.0登录', '127.0.0.1', '未分配或者内网IP', '0', '1535360904', '1535360937', '1', '9go8depjn00dlii7a3flouaid1', '0');
INSERT INTO `tw_user_log` VALUES ('6', '6271', 'login', 'PC端Windows NT 6.1，Firefox/61.0登录', '127.0.0.1', '未分配或者内网IP', '0', '1535360936', '1535360958', '1', '9unpmphjjjtfn4qq53vvbt7pj7', '0');
INSERT INTO `tw_user_log` VALUES ('7', '6271', 'login', 'PC端Windows NT 6.1，Firefox/61.0登录', '127.0.0.1', '未分配或者内网IP', '0', '1535360956', '1535360972', '1', '9go8depjn00dlii7a3flouaid1', '0');
INSERT INTO `tw_user_log` VALUES ('8', '6271', 'login', 'PC端Windows NT 6.1，Firefox/61.0登录', '127.0.0.1', '未分配或者内网IP', '0', '1535361008', '1535361388', '1', '9unpmphjjjtfn4qq53vvbt7pj7', '0');
INSERT INTO `tw_user_log` VALUES ('9', '6272', 'login', 'PC端Windows NT 6.1，Firefox/61.0登录', '127.0.0.1', '未分配或者内网IP', '0', '1535361042', '1535361042', '1', '9go8depjn00dlii7a3flouaid1', '1');
INSERT INTO `tw_user_log` VALUES ('10', '6271', 'login', 'PC端Windows NT 6.1，Chrome/68.0.3440.106登录', '127.0.0.1', '未分配或者内网IP', '0', '1535361388', '1535361392', '1', 'vknn1ls8lpefcls1ei2jirh0a0', '0');
INSERT INTO `tw_user_log` VALUES ('11', '6271', 'login', 'PC端Windows NT 6.1，Firefox/61.0登录', '127.0.0.1', '未分配或者内网IP', '0', '1535361454', '1535361454', '1', '9unpmphjjjtfn4qq53vvbt7pj7', '1');

-- ----------------------------
-- Table structure for tw_user_log_copy
-- ----------------------------
DROP TABLE IF EXISTS `tw_user_log_copy`;
CREATE TABLE `tw_user_log_copy` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `addip` varchar(20) NOT NULL DEFAULT '',
  `addr` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `session_key` varchar(100) DEFAULT NULL,
  `state` tinyint(4) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `state` (`state`) USING BTREE,
  KEY `session_key` (`session_key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户记录表'
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (100000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (200000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (300000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (400000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (600000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (700000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (800000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (900000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_user_log_copy
-- ----------------------------

-- ----------------------------
-- Table structure for tw_user_qianbao
-- ----------------------------
DROP TABLE IF EXISTS `tw_user_qianbao`;
CREATE TABLE `tw_user_qianbao` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `coinname` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `addr` varchar(200) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `userid` (`userid`),
  KEY `coinname` (`coinname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户钱包表';

-- ----------------------------
-- Records of tw_user_qianbao
-- ----------------------------

-- ----------------------------
-- Table structure for tw_zcbatch_error
-- ----------------------------
DROP TABLE IF EXISTS `tw_zcbatch_error`;
CREATE TABLE `tw_zcbatch_error` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zcid` int(11) NOT NULL,
  `addtime` int(10) NOT NULL,
  `beizhu` varchar(255) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_zcbatch_error
-- ----------------------------

-- ----------------------------
-- Procedure structure for e_del_user_log
-- ----------------------------
DROP PROCEDURE IF EXISTS `e_del_user_log`;
DELIMITER ;;
CREATE DEFINER=`sssjz1718`@`%` PROCEDURE `e_del_user_log`()
BEGIN
	insert into tw_user_log_copy select * from tw_user_log where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=2;
	DELETE FROM tw_user_log WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=2;
  insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=2 and type = 1;
	DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=2 and type = 1;
  insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=3 and type = 3;
	DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=3 and type = 3;
  insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=5 and type = 5;
	DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=5 and type = 5;
  insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=15 and type = 15;
	DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=15 and type = 15;
	insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=30 and type = 30;
	DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=30 and type = 30;
	insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=60 and type = 60;
	DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=60 and type = 60;
END
;;
DELIMITER ;

-- ----------------------------
-- Event structure for e_del_user_log
-- ----------------------------
DROP EVENT IF EXISTS `e_del_user_log`;
DELIMITER ;;
CREATE DEFINER=`sssjz1718`@`%` EVENT `e_del_user_log` ON SCHEDULE EVERY 1 DAY STARTS '2017-09-28 05:00:00' ON COMPLETION PRESERVE ENABLE DO CALL e_del_user_log()
;;
DELIMITER ;
