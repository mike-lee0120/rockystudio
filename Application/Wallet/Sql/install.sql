CREATE TABLE `ly_wallet_index` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `out_trade_no` varchar(128) NOT NULL DEFAULT '' COMMENT '订单号',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `type` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '余额变动种类 1增加 -1减少',
  `title` varchar(1024) NOT NULL COMMENT '余额变动说明',
  `original_money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '原价',
  `callback` varchar(255) NOT NULL DEFAULT '0' COMMENT '回调方法',
  `return_url` varchar(255) NOT NULL DEFAULT '' COMMENT '跳转地址',
  `allow_redbag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '允许红包支付',
  `allow_coupon` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '允许折扣券支付',
  `allow_third` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '允许第三方支付',
  `alllow_money` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '允许余额支付',
  `money` decimal(11,2) unsigned NOT NULL COMMENT '余额变动数量',
  `pay_type` varchar(50) NOT NULL DEFAULT '0' COMMENT '支付方式',
  `redbag_id` int(11) NOT NULL DEFAULT '0' COMMENT '红包ID',
  `coupon_id` int(11) NOT NULL DEFAULT '0' COMMENT '折扣券ID',
  `third_money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '原价',
  `ready` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单准备完成',
  `after_money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '变动后余额',
  `before_money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '变动前🈷️',
  `third_is_pay` tinyint(3) NOT NULL DEFAULT '0' COMMENT '第三方支付',
  `is_pay` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否支付完成',
  `is_callback` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否回调',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `add_uid` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '人工订单创建者UID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户余额变动记录表';

CREATE TABLE `ly_wallet_recharge` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `out_trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT '订单号',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `money` decimal(10,2) NOT NULL COMMENT '支付金额',
  `pay_type` varchar(11) NOT NULL DEFAULT '0' COMMENT '付款方式',
  `is_pay` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否支付成功',
  `is_callback` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否回调成功',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用充值记录表';

CREATE TABLE `ly_wallet_recharge_bonuses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '首次充值',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `bonuses` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '赠送金额',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用充值优惠表';

CREATE TABLE `ly_wallet_redbag_share_pool` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '红包金额',
  `expire` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '有效期/天',
  `limit` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '满多少可用',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='红包表';

INSERT INTO `ly_wallet_redbag_share_pool` (`id`, `money`, `expire`, `limit`, `create_time`, `update_time`, `status`)
VALUES
	(1, 1.00, 30, 3.00, 0, 0, 1),
	(2, 2.00, 30, 5.00, 0, 0, 1),
	(3, 3.00, 30, 5.00, 0, 0, 1),
	(4, 4.00, 30, 5.00, 0, 0, 1),
	(5, 5.00, 30, 6.00, 0, 0, 1),
	(6, 6.00, 30, 7.00, 0, 0, 1),
	(7, 7.00, 30, 10.00, 0, 0, 1),
	(8, 8.00, 30, 10.00, 0, 0, 1),
	(9, 9.00, 30, 15.00, 0, 0, 1),
	(10, 10.00, 30, 15.00, 0, 0, 1);

CREATE TABLE `ly_wallet_redbag_share` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `expire` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `rule` text NOT NULL COMMENT '活动规则',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='红包分享列表';

CREATE TABLE `ly_wallet_redbag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `account` varchar(63) NOT NULL DEFAULT '' COMMENT '领取输入的账号',
  `title` varchar(127) NOT NULL DEFAULT '' COMMENT '标题',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '红包金额',
  `expire` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `limit` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '满多少可用',
  `is_used` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否使用了',
  `share_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '领取来源',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='红包表';

CREATE TABLE `ly_wallet_withdraw` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `type` varchar(11) NOT NULL DEFAULT '' COMMENT '提现方式',
  `realname` varchar(31) NOT NULL COMMENT '真实姓名',
  `account` varchar(255) NOT NULL DEFAULT '' COMMENT '提现账号',
  `bankname` varchar(127) NOT NULL DEFAULT '' COMMENT '开户行',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户提现信息表';

CREATE TABLE `ly_wallet_coupon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `account` varchar(63) NOT NULL DEFAULT '' COMMENT '领取输入的账号',
  `title` varchar(127) NOT NULL DEFAULT '' COMMENT '标题',
  `coupon` decimal(11,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '折扣',
  `expire` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `limit` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '满多少可用',
  `is_used` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否使用了',
  `share_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '领取来源',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='折扣券表';

CREATE TABLE `ly_wallet_coupon_share` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `expire` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `rule` text NOT NULL COMMENT '活动规则',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='折扣券分享列表';

CREATE TABLE `ly_wallet_coupon_share_pool` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `coupon` decimal(11,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '折扣',
  `expire` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '有效期/天',
  `limit` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '满多少可用',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='折扣分享随机列表';

INSERT INTO `ly_wallet_coupon_share_pool` (`id`, `coupon`, `expire`, `limit`, `create_time`, `update_time`, `status`)
VALUES
	(1, 8.0, 15, 5000.00, 0, 0, 1),
	(2, 8.1, 15, 5000.00, 0, 0, 1),
	(3, 8.2, 15, 5000.00, 0, 0, 1),
	(4, 8.3, 15, 5000.00, 0, 0, 1),
	(5, 8.4, 15, 5000.00, 0, 0, 1),
	(6, 8.5, 15, 5000.00, 0, 0, 1),
	(7, 8.6, 15, 5000.00, 0, 0, 1),
	(8, 8.7, 15, 5000.00, 0, 0, 1),
	(9, 8.8, 15, 5000.00, 0, 0, 1),
	(10, 8.9, 15, 5000.00, 0, 0, 1),
	(11, 9.0, 15, 3000.00, 0, 0, 1),
	(12, 9.1, 15, 3000.00, 0, 0, 1),
	(13, 9.2, 15, 3000.00, 0, 0, 1),
	(14, 9.3, 15, 3000.00, 0, 0, 1),
	(15, 9.4, 15, 3000.00, 0, 0, 1),
	(16, 9.5, 15, 3000.00, 0, 0, 1),
	(17, 9.6, 15, 3000.00, 0, 0, 1),
	(18, 9.7, 15, 3000.00, 0, 0, 1),
	(19, 9.8, 15, 3000.00, 0, 0, 1),
	(20, 9.9, 15, 3000.00, 0, 0, 1);
