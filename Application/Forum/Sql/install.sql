CREATE TABLE `ly_forum_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group` varchar(11) NOT NULL DEFAULT '' COMMENT '分组',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `title` varchar(31) NOT NULL DEFAULT '' COMMENT '导航标题',
  `type` varchar(15) NOT NULL DEFAULT '' COMMENT '导航类型',
  `value` text COMMENT '导航值',
  `target` varchar(11) NOT NULL DEFAULT '' COMMENT '打开方式',
  `icon` varchar(32) NOT NULL DEFAULT '' COMMENT '图标',
  `lists_template` varchar(63) NOT NULL DEFAULT '' COMMENT '列表页模板',
  `detail_template` varchar(63) NOT NULL DEFAULT '' COMMENT '详情页模板',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='前台导航表';

INSERT INTO `ly_forum_nav` VALUES (1, 'main', 0, '发现', 'link', 'Forum/Index/index', '', 'fa-list', '', '', 1489137610, 1489137610, 0, 1);
INSERT INTO `ly_forum_nav` VALUES (2, 'main', 0, '话题', 'link', 'Forum/Index/plate', '', 'fa-comments', '', '', 1489137689, 1489137689, 0, 1);

CREATE TABLE `ly_forum_post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(127) NOT NULL DEFAULT '' COMMENT '标题',
  `cover` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '封面',
  `abstract` varchar(255) DEFAULT '' COMMENT '摘要',
  `content` text COMMENT '内容',
  `view_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '阅读',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章列表';

CREATE TABLE `ly_forum_plate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '板块ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父板块ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '板块名称',
  `abstract` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `icon` varchar(63) NOT NULL DEFAULT '' COMMENT '图标',
  `cover` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '封面',
  `post_auth` tinyint(4) NOT NULL DEFAULT '0' COMMENT '投稿权限',
  `post_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '帖子数',
  `reply_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '回复数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='论坛板块表';

CREATE TABLE `ly_forum_index` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '帖子ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `plate_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '板块ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '帖子标题',
  `cover` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '封面',
  `content` mediumtext NOT NULL COMMENT '帖子内容',
  `view_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '浏览数',
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '回复数',
  `top` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `good_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `last_reply_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后回复时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='论坛帖子表';

CREATE TABLE `ly_forum_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '回帖ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'PID',
  `data_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `content` text NOT NULL COMMENT '回复内容',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `ip` varchar(25) NOT NULL DEFAULT '' COMMENT '来源IP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='论坛回帖表';

INSERT INTO `ly_forum_plate` (`id`, `pid`, `title`, `post_auth`, `post_num`, `reply_num`, `create_time`, `update_time`, `sort`, `status`)
VALUES
  (1, 0, '下载', 0, 0, 0, 1470814032, 1470814032, 0, 1),
  (2, 0, '开发', 0, 0, 0, 1471963614, 1471963614, 0, 1),
  (4, 0, '微信', 0, 0, 0, 1470814032, 1470814032, 0, 1),
  (5, 0, '模板', 0, 0, 0, 1470814032, 1470814032, 0, 1),
  (6, 0, '设计', 0, 0, 0, 1470814032, 1470814032, 0, 1),
  (7, 0, '经验', 0, 0, 0, 1470814032, 1470814032, 0, 1),
  (8, 1, 'lyask', 0, 0, 0, 1470814032, 1470814032, 0, 1),
  (9, 2, '插件', 0, 0, 0, 1470814032, 1470814032, 0, 1),
  (11, 3, '公众号', 0, 0, 0, 1470814032, 1470814032, 0, 1),
  (12, 3, '小程序', 0, 0, 0, 1470814032, 1470814032, 0, 1),
  (13, 4, '模板教程', 0, 0, 0, 1470814032, 1470814032, 0, 1),
  (14, 5, 'lyadmin', 0, 0, 0, 1470814032, 1470814032, 0, 1),
  (15, 6, 'lyicon', 0, 0, 0, 1470814032, 1470814032, 0, 1),
  (16, 7, '企业官网', 0, 0, 0, 1470814032, 1470814032, 0, 1);

  INSERT INTO `ly_forum_index` VALUES ('1', '1', '8', '欢迎大家踊跃发言！', '0', '', '3', '0', '0', '0', '0', '0', '1489491237', '1489491237', '0', '1');
