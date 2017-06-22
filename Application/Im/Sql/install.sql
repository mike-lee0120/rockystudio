CREATE TABLE `ly_im_index` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '会话类型',
  `im_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会话ID',
  `data_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '消息ID已读',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户会话列表';

CREATE TABLE `ly_im_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `from_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发信人',
  `to_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `message` text NOT NULL COMMENT '消息正文',
  `is_read` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已读',
  `is_pushed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推送过',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户消息表';

CREATE TABLE `ly_im_qun` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '群主',
  `title` varchar(127) NOT NULL DEFAULT '' COMMENT '群名称',
  `abstract` varchar(255) NOT NULL DEFAULT '' COMMENT '群简介',
  `cover` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '群logo',
  `is_public` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否公开',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='IM群聊';

CREATE TABLE `ly_im_qun_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `data_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '群ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '加入时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='群成员表';

CREATE TABLE `ly_im_qun_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `qun_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '群ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发信人',
  `message` text NOT NULL COMMENT '消息正文',
  `is_pushed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推送过',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户消息表';

CREATE TABLE `ly_im_mp` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `name` varchar(63) NOT NULL DEFAULT '' COMMENT '公众号名称',
  `title` varchar(127) NOT NULL DEFAULT '' COMMENT '公众号标题',
  `abstract` varchar(255) NOT NULL DEFAULT '' COMMENT '公众号简介',
  `is_auth` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否认证',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_timeCopy` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='公众号列表';

CREATE TABLE `ly_im_mp_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `data_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '加入时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='公众号关注表';

CREATE TABLE `ly_im_mp_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `mp_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '消息类型',
  `data_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '消息ID',
  `is_pushed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推送过',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户消息表';
