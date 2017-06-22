CREATE TABLE `ly_weixin_custom_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(10) DEFAULT '0' COMMENT '一级菜单',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `type` varchar(30) NOT NULL DEFAULT 'click' COMMENT '菜单事件类型',
  `key` varchar(255) DEFAULT NULL COMMENT '事件关键词',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序号',
  `status` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信插件：自定义菜单表';

CREATE TABLE `ly_weixin_custom_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `keyword` varchar(255) NOT NULL COMMENT '关键词',
  `content` text NOT NULL COMMENT '回复内容',
  `reply_type` varchar(32) NOT NULL DEFAULT 'text' COMMENT '回复类型',
  `reply_key` varchar(31) NOT NULL DEFAULT '' COMMENT '回复其他表数据主键',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信模块：自动回复：文字类型';

CREATE TABLE `ly_weixin_material` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `author` varchar(255) NOT NULL DEFAULT '' COMMENT '作者',
  `cover` int(10) unsigned DEFAULT NULL COMMENT '封面图片',
  `abstract` text COMMENT '简介',
  `content` text NOT NULL COMMENT '内容',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '外链',
  `view_count` int(11) NOT NULL DEFAULT '0' COMMENT '阅读数',
  `good_count` int(11) NOT NULL DEFAULT '0' COMMENT '赞数',
  `comment_count` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ly_weixin_user_bind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `openid` varchar(64) NOT NULL COMMENT '用户openid',
  `latitude` varchar(11) NOT NULL DEFAULT '' COMMENT '纬度',
  `longitude` varchar(11) NOT NULL DEFAULT '' COMMENT '经度',
  `precision` varchar(11) NOT NULL DEFAULT '' COMMENT '精度',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信公众号用户与CT用户绑定';
