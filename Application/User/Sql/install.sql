CREATE TABLE `ly_user_attribute` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段标题',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `tip` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `options` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `type_id` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '文档模型',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户模块：用户属性字段表';

LOCK TABLES `ly_user_attribute` WRITE;
INSERT INTO `ly_user_attribute` (`id`, `name`, `title`, `type`, `value`, `tip`, `options`, `type_id`, `create_time`, `update_time`, `status`)
VALUES
  (1, 'gender', '性别', 'radio', '0', '性别', '1:男\n-1:女\r\n0:保密\r\n', 1, 1438651748, 1438651748, 1),
  (2, 'city', '所在城市', 'text', '', '常住城市', '', 1, 1442026468, 1442123810, 1),
  (3, 'summary', '签名', 'text', '', '签名', '', 1, 1438651748, 1438651748, 1);
UNLOCK TABLES;

CREATE TABLE `ly_user_follow` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `follow_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '粉丝ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关注时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户模块：粉丝信息表';

CREATE TABLE `ly_user_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '消息ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '消息父ID',
  `title` varchar(1024) NOT NULL DEFAULT '' COMMENT '消息标题',
  `content` text COMMENT '消息内容',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '0系统消息,1评论消息,2私信消息',
  `to_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '接收用户ID',
  `from_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '私信消息发信用户ID',
  `is_read` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否已读',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发送时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户消息表';

CREATE TABLE `ly_user_attr` (
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `attr_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `value` text NOT NULL COMMENT '字段值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户属性信息表';

CREATE TABLE `ly_user_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(31) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(31) NOT NULL DEFAULT '' COMMENT '标题',
  `icon` varchar(31) NOT NULL DEFAULT '' COMMENT '缩略图',
  `home_template` varchar(127) NOT NULL DEFAULT '' COMMENT '主页模版',
  `center_template` varchar(127) NOT NULL DEFAULT '' COMMENT '个人中心模板',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户模块：用户类型表';

LOCK TABLES `ly_user_type` WRITE;
INSERT INTO `ly_user_type` (`id`, `name`, `title`, `icon`, `home_template`, `center_template`, `create_time`, `update_time`, `sort`, `status`)
VALUES
  (1, 'person', '个人', '', '', '', 1438651748, 1481101582, 0, 1);
UNLOCK TABLES;

CREATE TABLE `ly_user_score_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `type` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '积分变动种类 1增加 -1减少',
  `score` int(11) unsigned NOT NULL COMMENT '积分变动数量',
  `message` varchar(1024) NOT NULL DEFAULT '' COMMENT '积分变动说明',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户积分变动记录表';

CREATE TABLE `ly_user_login_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL COMMENT 'UID',
  `ip` bigint(20) NOT NULL DEFAULT '0' COMMENT 'IP',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '登录方式',
  `device` varchar(127) NOT NULL DEFAULT '' COMMENT '设备信息',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户登录日志';

CREATE TABLE `ly_user_message_push` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `session_id` varchar(127) NOT NULL DEFAULT '' COMMENT 'SID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `os` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '操作系统',
  `token` varchar(127) NOT NULL DEFAULT '' COMMENT '设备ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户消息APP推送账号与设备绑定信息';

CREATE TABLE `ly_user_address` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `title` varchar(31) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `gender` tinyint(1) NOT NULL DEFAULT 0 COMMENT '收货人称呼',
  `mobile` varchar(31) NOT NULL DEFAULT '' COMMENT '联系电话',
  `city` varchar(127) NOT NULL DEFAULT '' COMMENT '地址',
  `address` varchar(127) NOT NULL DEFAULT '' COMMENT '详细地址',
  `post_code` varchar(10) NOT NULL DEFAULT '000000' COMMENT '邮编',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '默认地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户收货地址';

CREATE TABLE `ly_user_cert` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '认证类型个人/公司/组织',
  `cert_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '证件类型身份证/军官证/组织机构代码证等',
  `cert_no` varchar(127) NOT NULL DEFAULT '' COMMENT '证件号码',
  `cert_title` varchar(127) NOT NULL DEFAULT '' COMMENT '真实名称',
  `cert_photo` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '证件照片',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户实名认证信息表';
