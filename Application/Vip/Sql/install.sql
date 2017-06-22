CREATE TABLE `ly_vip_index` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `vip_type` int(3) unsigned NOT NULL DEFAULT '0' COMMENT 'VIP等级',
  `expire` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='VIP信息表';

CREATE TABLE `ly_vip_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `out_trade_no` varchar(128) NOT NULL COMMENT '订单号',
  `order_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '订单类型',
  `vip_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员类型',
  `buy_period` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购买时长',
  `original_price` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '原价',
  `total_price` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '需支付',
  `is_pay` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否支付',
  `data_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员记录ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员订单';

CREATE TABLE `ly_vip_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `level` int(3) unsigned NOT NULL DEFAULT '0' COMMENT 'VIP等级',
  `title` varchar(31) NOT NULL DEFAULT '' COMMENT 'VIP标题',
  `price` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `content` text NOT NULL COMMENT 'VIP特权',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='VIP类型信息表';

INSERT INTO `ly_vip_type` (`id`, `level`, `title`, `price`, `create_time`, `update_time`, `sort`, `status`)
VALUES
	(1, 1, '初级会员', 10.00, 1438651748, 1438651748, 0, 1),
	(2, 2, '中级会员', 20.00, 1438651748, 1438651748, 0, 1),
	(3, 3, '高级会员', 50.00, 1438651748, 1438651748, 0, 1);
