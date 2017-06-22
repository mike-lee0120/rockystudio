# Dump of table ly_admin_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ly_admin_access`;

CREATE TABLE `ly_admin_access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `group` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员用户组',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台管理员与用户组对应关系表';

LOCK TABLES `ly_admin_access` WRITE;
/*!40000 ALTER TABLE `ly_admin_access` DISABLE KEYS */;

INSERT INTO `ly_admin_access` (`id`, `uid`, `group`, `create_time`, `update_time`, `sort`, `status`)
VALUES
    (1,1,1,1438651748,1438651748,0,1);

/*!40000 ALTER TABLE `ly_admin_access` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ly_admin_addon
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ly_admin_addon`;

CREATE TABLE `ly_admin_addon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(32) DEFAULT NULL COMMENT '插件名或标识',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text NOT NULL COMMENT '插件描述',
  `logo` varchar(63) NOT NULL DEFAULT '' COMMENT '图片图标',
  `icon` varchar(31) NOT NULL DEFAULT '' COMMENT '图标',
  `icon_color` varchar(7) NOT NULL DEFAULT '' COMMENT '图标颜色',
  `config` text COMMENT '配置',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `version` varchar(8) NOT NULL DEFAULT '' COMMENT '版本号',
  `adminlist` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '插件类型',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='插件表';



# Dump of table ly_admin_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ly_admin_config`;

CREATE TABLE `ly_admin_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '配置标题',
  `name` varchar(32) DEFAULT NULL COMMENT '配置名称',
  `value` text NOT NULL COMMENT '配置值',
  `group` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `type` varchar(16) NOT NULL DEFAULT '' COMMENT '配置类型',
  `options` varchar(255) NOT NULL DEFAULT '' COMMENT '配置额外值',
  `tip` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统配置表';

LOCK TABLES `ly_admin_config` WRITE;
/*!40000 ALTER TABLE `ly_admin_config` DISABLE KEYS */;

INSERT INTO `ly_admin_config` (`title`, `name`, `value`, `group`, `type`, `options`, `tip`, `create_time`, `update_time`, `sort`, `status`)
VALUES
    ('站点开关', 'TOGGLE_WEB_SITE', '1', 1, 'toggle', '0:关闭\r\n1:开启', '站点关闭后将不能访问', 1378898976, 1406992386, 1, 1),
    ('网站标题', 'WEB_SITE_TITLE', '零云', 1, 'text', '', '网站标题前台显示标题', 1378898976, 1379235274, 2, 1),
    ('网站口号', 'WEB_SITE_SLOGAN', '领先的积木式云平台', 1, 'text', '', '网站口号、宣传标语、一句话介绍', 1434081649, 1434081649, 3, 1),
    ('网站LOGO', 'WEB_SITE_LOGO', '', 1, 'picture', '', '网站LOGO', 1407003397, 1407004692, 4, 1),
    ('网站反色LOGO', 'WEB_SITE_LOGO_INVERSE', '', 1, 'picture', '', '匹配深色背景上的LOGO', 1476700797, 1476700797, 5, 1),
    ('网站描述', 'WEB_SITE_DESCRIPTION', '零云是一套基于统一核心的通用互联网+信息化服务解决方案，追求简单、高效、卓越。可轻松实现支持多终端的WEB产品快速搭建、部署、上线。系统功能采用模块化、组件化、插件化等开放化低耦合设计，应用商城拥有丰富的功能模块、插件、主题，便于用户灵活扩展和二次开发。', 1, 'textarea', '', '网站搜索引擎描述', 1378898976, 1379235841, 6, 1),
    ('网站关键字', 'WEB_SITE_KEYWORD', '零云、新一代云平台、微信小程序开发、微信应用号开发、零云网络、零云科技、零云、零云CMF、零云OA、OpenCMF、CoreThink、南京科斯克网络科技、corethink官网、corethink手册、php框架、web框架、网站开发、php教程、php开发、开源php框架、零云科技-零云科技,专业提供 vps云主机,BGP五线主机,域名注册、零云短租、零云商城、零云Docker、零云金融、零云贷、零云搜、零云视频、零云教育、零云在线、零云钱包、零云支付、零云IM、零云CMF', 1, 'textarea', '', '网站搜索引擎关键字', 1378898976, 1381390100, 7, 1),
    ('版权信息', 'WEB_SITE_COPYRIGHT', 'Copyright © 南京科斯克网络科技有限公司 All rights reserved.', 1, 'text', '', '设置在网站底部显示的版权信息，如“版权所有 © 2014-2015 科斯克网络科技”', 1406991855, 1406992583, 8, 1),
    ('网站备案号', 'WEB_SITE_ICP', '苏ICP备1502009号', 1, 'text', '', '设置在网站底部显示的备案号，如“苏ICP备1502009号\"', 1378900335, 1415983236, 9, 1),
    ('站点统计', 'WEB_SITE_STATISTICS', '', 1, 'textarea', '', '支持百度、Google、cnzz等所有Javascript的统计代码', 1378900335, 1415983236, 10, 1),
    ('公司名称', 'COMPANY_TITLE', '南京科斯克网络科技有限公司', 3, 'text', '', '', 1481014715, 1481014715, 1, 1),
    ('公司地址', 'COMPANY_ADDRESS', '南京市鼓楼区广东路38号', 3, 'text', '', '', 1481014768, 1481014768, 2, 1),
    ('公司邮箱', 'COMPANY_EMAIL', 'service@lingyun.net', 3, 'text', '', '', 1481014914, 1481014914, 3, 1),
    ('公司电话', 'COMPANY_PHONE', '15005173785', 3, 'text', '', '', 1481014961, 1481014961, 4, 1),
    ('公司QQ', 'COMPANY_QQ', '209216005', 3, 'text', '', '', 1481015016, 1481015016, 5, 1),
    ('公司QQ群', 'COMPANY_QQQUN', '105108204', 3, 'text', '', '', 1481015198, 1481015198, 6, 1),
    ('网站二维码', 'QR_CODE', '', 3, 'picture', '', '', 1481009623, 1481009623, 7, 1),
    ('IOS二维码', 'QR_IOS', '', 3, 'picture', '', '', 1481009623, 1481009623, 8, 1),
    ('安卓二维码', 'QR_ANDROID', '', 3, 'picture', '', '', 1481009921, 1481009921, 9, 1),
    ('微信公众号二维码', 'QR_WEIXIN', '', 3, 'picture', '', '', 1481009959, 1481009959, 10, 1),
    ('微信小程序二维码', 'QR_WEIXIN_APP', '', 3, 'picture', '', '', 1481009959, 1481009959, 11, 1),
    ('文件上传大小', 'UPLOAD_FILE_SIZE', '2', 5, 'num', '', '文件上传大小单位：MB', 1428681031, 1428681031, 1, 1),
    ('图片上传大小', 'UPLOAD_IMAGE_SIZE', '0.5', 5, 'num', '', '图片上传大小单位：MB', 1428681071, 1428681071, 2, 1),
    ('后台多标签', 'ADMIN_TABS', '0', 5, 'toggle', '0:关闭\r\n1:开启', '', 1453445526, 1453445526, 3, 1),
    ('分页数量', 'ADMIN_PAGE_ROWS', '10', 5, 'num', '', '分页时每页的记录数', 1434019462, 1434019481, 4, 1),
    ('后台主题', 'ADMIN_THEME', 'admin', 5, 'select', 'admin:默认主题\r\naliyun:阿里云风格\r\nweixin:微信风格\r\nred:红色风格\r\npink:粉色风格', '后台界面主题', 1436678171, 1436690570, 5, 1),
    ('导航分组', 'NAV_GROUP_LIST', 'main:主导航\r\nmain_extend:主导航扩展\r\nbottom:底部导航\r\ntabbar:TabBar导航\r\nfind:发现导航', 5, 'array', '', '导航分组', 1458382037, 1458382061, 6, 1),
    ('配置分组', 'CONFIG_GROUP_LIST', '1:基本\r\n3:扩展\r\n5:系统\r\n7:部署\r\n99:授权', 5, 'array', '', '配置分组', 1379228036, 1426930700, 7, 1),
    ('开发模式', 'DEVELOP_MODE', '1', 7, 'toggle', '1:开启\r\n0:关闭', '开发模式下会显示菜单管理、配置管理、数据字典等开发者工具', 1432393583, 1432393583, 1, 1),
    ('页面Trace', 'APP_TRACE', '0', 7, 'toggle', '0:关闭\r\n1:开启', '是否显示页面Trace信息', 1387165685, 1387165685, 2, 1),
    ('URL模式', 'URL_MODEL', '3', 7, 'select', '1:PATHINFO模式\r\n2:REWRITE模式\r\n3:兼容模式', '', 1438423248, 1438423248, 3, 1),
    ('开启路由', 'URL_ROUTER_ON', '0', 7, 'toggle', '0:关闭\r\n1:开启', '路由总开关', 1378898976, 1406992386, 4, 1),
    ('根域名', 'ROOT_DOMAIN', '', 7, 'text', '', '一般不需要配置，网站改变域名时最好配置此项。', 1490673564, 1490673564, 5, 1),
    ('默认模块', 'DEFAULT_MODULE', '', 7, 'select', 'callback:D(\'Admin/Module\')->getNameList', '', '1471458914', '1471458914', 6, '1'),
    ('默认模块布局', 'DEFAULT_MODULE_LAYOUT', '0', 7, 'toggle', '1:开启\r\n0:关闭', '启用默认模块的布局layout全局生效', 1481267362, 1481267389, 7, 1),
    ('静态文件独立域名', 'STATIC_DOMAIN', '', 7, 'text', '', '静态文件独立域名一般用于在用户无感知的情况下平和的将网站图片自动存储到腾讯万象优图、又拍云等第三方服务。', 1438564784, 1438564784, 8, 1),
    ('开启子域名配置', 'APP_SUB_DOMAIN_DEPLOY', '0', 7, 'toggle', '1:是\r\n0:否', '是否开启子域名配置', 1467549933, 1467549933, 9, 1),
    ('子域名规则', 'APP_SUB_DOMAIN_RULES', '', 7, 'array', '', '子域名规则', 1467549993, 1467549993, 10, 1),
    ('域名后缀', 'APP_DOMAIN_SUFFIX', '', 7, 'text', '', 'com.cn 、net.cn之类的需要配置此项', 1467550066, 1467550066, 11, 1),
    ('强制Wap主题', 'WAP_MODE', '0', 7, 'toggle', '1:是\r\n0:否', '是否在电脑端强制显示Wap主题', 1467121607, 1467121607, 12, 1),
    ('官网账号', 'AUTH_USERNAME', 'trial', 99, 'text', '', '官网登陆账号（用户名）', 1438647815, 1438647815, 1, 1),
    ('官网密码', 'AUTH_PASSWORD', 'trial', 99, 'text', '', '官网密码', 1438647815, 1438647815, 2, 1),
    ('密钥', 'AUTH_SN', '', 99, 'textarea', '', '密钥请通过登陆官网至个人中心获取', 1438647815, 1438647815, 3, 1);

/*!40000 ALTER TABLE `ly_admin_config` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ly_admin_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ly_admin_group`;

CREATE TABLE `ly_admin_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '部门ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级部门ID',
  `title` varchar(31) NOT NULL DEFAULT '' COMMENT '部门名称',
  `icon` varchar(31) NOT NULL DEFAULT '' COMMENT '图标',
  `menu_auth` text NOT NULL COMMENT '权限列表',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='部门信息表';

LOCK TABLES `ly_admin_group` WRITE;
/*!40000 ALTER TABLE `ly_admin_group` DISABLE KEYS */;

INSERT INTO `ly_admin_group` (`id`, `pid`, `title`, `icon`, `menu_auth`, `create_time`, `update_time`, `sort`, `status`)
VALUES
    (1,0,'超级管理员','','',1426881003,1427552428,0,1);

/*!40000 ALTER TABLE `ly_admin_group` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ly_admin_hook
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ly_admin_hook`;

CREATE TABLE `ly_admin_hook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '钩子ID',
  `name` varchar(32) DEFAULT NULL COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='钩子表';

LOCK TABLES `ly_admin_hook` WRITE;
/*!40000 ALTER TABLE `ly_admin_hook` DISABLE KEYS */;

INSERT INTO `ly_admin_hook` (`id`, `name`, `description`, `addons`, `type`, `create_time`, `update_time`, `status`)
VALUES
    (1,'AdminIndex','后台首页小工具','后台首页小工具',1,1446522155,1446522155,1),
    (2,'FormBuilderExtend','FormBuilder类型扩展Builder','',1,1447831268,1447831268,1),
    (3,'UploadFile','上传文件钩子','',1,1407681961,1407681961,1),
    (4,'PageHeader','页面header钩子，一般用于加载插件CSS文件和代码','',1,1407681961,1407681961,1),
    (5,'PageFooter','页面footer钩子，一般用于加载插件CSS文件和代码','',1,1407681961,1407681961,1),
    (6,'CommonHook','通用钩子，自定义用途，一般用来定制特殊功能','',1,1456147822, 1456147822, 1);

/*!40000 ALTER TABLE `ly_admin_hook` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ly_admin_module
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ly_admin_module`;

CREATE TABLE `ly_admin_module` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(31) DEFAULT NULL COMMENT '名称',
  `title` varchar(63) NOT NULL DEFAULT '' COMMENT '标题',
  `logo` varchar(63) NOT NULL DEFAULT '' COMMENT '图片图标',
  `icon` varchar(31) NOT NULL DEFAULT '' COMMENT '字体图标',
  `icon_color` varchar(7) NOT NULL DEFAULT '' COMMENT '字体图标颜色',
  `description` varchar(127) NOT NULL DEFAULT '' COMMENT '描述',
  `developer` varchar(31) NOT NULL DEFAULT '' COMMENT '开发者',
  `version` varchar(7) NOT NULL DEFAULT '' COMMENT '版本',
  `user_nav` text NOT NULL COMMENT '个人中心导航',
  `home_nav` text NULL COMMENT '个人主页导航',
  `config` text NOT NULL COMMENT '配置',
  `admin_menu` text NOT NULL COMMENT '菜单节点',
  `router` text NULL COMMENT '路由规则',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许卸载',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='模块功能表';

LOCK TABLES `ly_admin_module` WRITE;
/*!40000 ALTER TABLE `ly_admin_module` DISABLE KEYS */;

INSERT INTO `ly_admin_module` (`id`, `name`, `title`, `logo`, `icon`, `icon_color`, `description`, `developer`, `version`, `user_nav`, `config`, `admin_menu`, `is_system`, `create_time`, `update_time`, `sort`, `status`)
VALUES
    (1, 'Admin', '系统', '', 'fa fa-cog', '#3CA6F1', '核心系统', '南京科斯克网络科技有限公司', '1.6.2', '', '', '{\"1\":{\"pid\":\"0\",\"title\":\"\\u7cfb\\u7edf\",\"icon\":\"fa fa-cog\",\"level\":\"system\",\"id\":\"1\"},\"2\":{\"pid\":\"1\",\"title\":\"\\u7cfb\\u7edf\\u529f\\u80fd\",\"icon\":\"fa fa-folder-open-o\",\"id\":\"2\"},\"3\":{\"pid\":\"2\",\"title\":\"\\u7cfb\\u7edf\\u8bbe\\u7f6e\",\"icon\":\"fa fa-wrench\",\"url\":\"Admin\\/Config\\/group\",\"id\":\"3\"},\"4\":{\"pid\":\"3\",\"title\":\"\\u4fee\\u6539\\u8bbe\\u7f6e\",\"url\":\"Admin\\/Config\\/groupSave\",\"id\":\"4\"},\"5\":{\"pid\":\"2\",\"title\":\"\\u5bfc\\u822a\\u7ba1\\u7406\",\"icon\":\"fa fa-map-signs\",\"url\":\"Admin\\/Nav\\/index\",\"id\":\"5\"},\"6\":{\"pid\":\"5\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Nav\\/add\",\"id\":\"6\"},\"7\":{\"pid\":\"5\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Nav\\/edit\",\"id\":\"7\"},\"8\":{\"pid\":\"5\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Nav\\/setStatus\",\"id\":\"8\"},\"9\":{\"pid\":\"2\",\"title\":\"\\u5e7b\\u706f\\u7ba1\\u7406\",\"icon\":\"fa fa-image\",\"url\":\"Admin\\/Slider\\/index\",\"id\":\"9\"},\"10\":{\"pid\":\"9\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Slider\\/add\",\"id\":\"10\"},\"11\":{\"pid\":\"9\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Slider\\/edit\",\"id\":\"11\"},\"12\":{\"pid\":\"9\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Slider\\/setStatus\",\"id\":\"12\"},\"13\":{\"pid\":\"2\",\"title\":\"\\u914d\\u7f6e\\u7ba1\\u7406\",\"icon\":\"fa fa-cogs\",\"url\":\"Admin\\/Config\\/index\",\"id\":\"13\"},\"14\":{\"pid\":\"13\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Config\\/add\",\"id\":\"14\"},\"15\":{\"pid\":\"13\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Config\\/edit\",\"id\":\"15\"},\"16\":{\"pid\":\"13\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Config\\/setStatus\",\"id\":\"16\"},\"17\":{\"pid\":\"2\",\"title\":\"\\u4e0a\\u4f20\\u7ba1\\u7406\",\"icon\":\"fa fa-upload\",\"url\":\"Admin\\/Upload\\/index\",\"id\":\"17\"},\"18\":{\"pid\":\"17\",\"title\":\"\\u4e0a\\u4f20\\u6587\\u4ef6\",\"url\":\"Admin\\/Upload\\/upload\",\"id\":\"18\"},\"19\":{\"pid\":\"17\",\"title\":\"\\u5220\\u9664\\u6587\\u4ef6\",\"url\":\"Admin\\/Upload\\/delete\",\"id\":\"19\"},\"20\":{\"pid\":\"17\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Upload\\/setStatus\",\"id\":\"20\"},\"21\":{\"pid\":\"17\",\"title\":\"\\u4e0b\\u8f7d\\u8fdc\\u7a0b\\u56fe\\u7247\",\"url\":\"Admin\\/Upload\\/downremoteimg\",\"id\":\"21\"},\"22\":{\"pid\":\"17\",\"title\":\"\\u6587\\u4ef6\\u6d4f\\u89c8\",\"url\":\"Admin\\/Upload\\/fileManager\",\"id\":\"22\"},\"23\":{\"pid\":\"1\",\"title\":\"\\u7cfb\\u7edf\\u6743\\u9650\",\"icon\":\"fa fa-folder-open-o\",\"id\":\"23\"},\"24\":{\"pid\":\"23\",\"title\":\"\\u7528\\u6237\\u7ba1\\u7406\",\"icon\":\"fa fa-user\",\"url\":\"Admin\\/User\\/index\",\"id\":\"24\"},\"25\":{\"pid\":\"24\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/User\\/add\",\"id\":\"25\"},\"26\":{\"pid\":\"24\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/User\\/edit\",\"id\":\"26\"},\"27\":{\"pid\":\"24\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/User\\/setStatus\",\"id\":\"27\"},\"28\":{\"pid\":\"23\",\"title\":\"\\u7ba1\\u7406\\u5458\\u7ba1\\u7406\",\"icon\":\"fa fa-lock\",\"url\":\"Admin\\/Access\\/index\",\"id\":\"28\"},\"29\":{\"pid\":\"28\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Access\\/add\",\"id\":\"29\"},\"30\":{\"pid\":\"28\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Access\\/edit\",\"id\":\"30\"},\"31\":{\"pid\":\"28\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Access\\/setStatus\",\"id\":\"31\"},\"32\":{\"pid\":\"23\",\"title\":\"\\u7528\\u6237\\u7ec4\\u7ba1\\u7406\",\"icon\":\"fa fa-sitemap\",\"url\":\"Admin\\/Group\\/index\",\"id\":\"32\"},\"33\":{\"pid\":\"32\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Group\\/add\",\"id\":\"33\"},\"34\":{\"pid\":\"32\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Group\\/edit\",\"id\":\"34\"},\"35\":{\"pid\":\"32\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Group\\/setStatus\",\"id\":\"35\"},\"36\":{\"pid\":\"1\",\"title\":\"\\u6269\\u5c55\\u4e2d\\u5fc3\",\"icon\":\"fa fa-folder-open-o\",\"id\":\"36\"},\"37\":{\"pid\":\"36\",\"title\":\"\\u524d\\u53f0\\u4e3b\\u9898\",\"icon\":\"fa fa-adjust\",\"url\":\"Admin\\/Theme\\/index\",\"id\":\"37\"},\"38\":{\"pid\":\"37\",\"title\":\"\\u5b89\\u88c5\",\"url\":\"Admin\\/Theme\\/install\",\"id\":\"38\"},\"39\":{\"pid\":\"37\",\"title\":\"\\u5378\\u8f7d\",\"url\":\"Admin\\/Theme\\/uninstall\",\"id\":\"39\"},\"40\":{\"pid\":\"37\",\"title\":\"\\u66f4\\u65b0\\u4fe1\\u606f\",\"url\":\"Admin\\/Theme\\/updateInfo\",\"id\":\"40\"},\"41\":{\"pid\":\"37\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Theme\\/setStatus\",\"id\":\"41\"},\"42\":{\"pid\":\"37\",\"title\":\"\\u5207\\u6362\\u4e3b\\u9898\",\"url\":\"Admin\\/Theme\\/setCurrent\",\"id\":\"42\"},\"43\":{\"pid\":\"37\",\"title\":\"\\u53d6\\u6d88\\u4e3b\\u9898\",\"url\":\"Admin\\/Theme\\/cancel\",\"id\":\"43\"},\"44\":{\"pid\":\"36\",\"title\":\"\\u529f\\u80fd\\u6a21\\u5757\",\"icon\":\"fa fa-th-large\",\"url\":\"Admin\\/Module\\/index\",\"id\":\"44\"},\"45\":{\"pid\":\"44\",\"title\":\"\\u5b89\\u88c5\",\"url\":\"Admin\\/Module\\/install\",\"id\":\"45\"},\"46\":{\"pid\":\"44\",\"title\":\"\\u5378\\u8f7d\",\"url\":\"Admin\\/Module\\/uninstall\",\"id\":\"46\"},\"47\":{\"pid\":\"44\",\"title\":\"\\u66f4\\u65b0\\u4fe1\\u606f\",\"url\":\"Admin\\/Module\\/updateInfo\",\"id\":\"47\"},\"48\":{\"pid\":\"44\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Module\\/setStatus\",\"id\":\"48\"},\"49\":{\"pid\":\"36\",\"title\":\"\\u63d2\\u4ef6\\u7ba1\\u7406\",\"icon\":\"fa fa-th\",\"url\":\"Admin\\/Addon\\/index\",\"id\":\"49\"},\"50\":{\"pid\":\"49\",\"title\":\"\\u5b89\\u88c5\",\"url\":\"Admin\\/Addon\\/install\",\"id\":\"50\"},\"51\":{\"pid\":\"49\",\"title\":\"\\u5378\\u8f7d\",\"url\":\"Admin\\/Addon\\/uninstall\",\"id\":\"51\"},\"52\":{\"pid\":\"49\",\"title\":\"\\u8fd0\\u884c\",\"url\":\"Admin\\/Addon\\/execute\",\"id\":\"52\"},\"53\":{\"pid\":\"49\",\"title\":\"\\u8bbe\\u7f6e\",\"url\":\"Admin\\/Addon\\/config\",\"id\":\"53\"},\"54\":{\"pid\":\"49\",\"title\":\"\\u540e\\u53f0\\u7ba1\\u7406\",\"url\":\"Admin\\/Addon\\/adminList\",\"id\":\"54\"},\"55\":{\"pid\":\"54\",\"title\":\"\\u65b0\\u589e\\u6570\\u636e\",\"url\":\"Admin\\/Addon\\/adminAdd\",\"id\":\"55\"},\"56\":{\"pid\":\"54\",\"title\":\"\\u7f16\\u8f91\\u6570\\u636e\",\"url\":\"Admin\\/Addon\\/adminEdit\",\"id\":\"56\"},\"57\":{\"pid\":\"54\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Addon\\/setStatus\",\"id\":\"57\"}}', 1, 1438651748, 1457086111, 0, 1);

/*!40000 ALTER TABLE `ly_admin_module` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ly_admin_nav
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ly_admin_nav`;

CREATE TABLE `ly_admin_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group` varchar(11) NOT NULL DEFAULT '' COMMENT '分组',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `title` varchar(31) NOT NULL DEFAULT '' COMMENT '导航标题',
  `type` varchar(15) NOT NULL DEFAULT '' COMMENT '导航类型',
  `value` text COMMENT '导航值',
  `api_route` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'API路由',
  `target` varchar(11) NOT NULL DEFAULT '' COMMENT '打开方式',
  `icon` varchar(32) NOT NULL DEFAULT '' COMMENT '图标',
  `cover` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '封面图标'
  `lists_template` varchar(63) NOT NULL DEFAULT '' COMMENT '列表页模板',
  `detail_template` varchar(63) NOT NULL DEFAULT '' COMMENT '详情页模板',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='前台导航表';

INSERT INTO `ly_admin_nav` (`id`, `group`, `pid`, `title`, `type`, `value`, `target`, `icon`, `lists_template`, `detail_template`, `create_time`, `update_time`, `sort`, `status`)
VALUES
  (1, 'bottom', 0, '关于', 'page', '', '', '', '', '', 1449742225, 1449742255, 0, 1),
  (2, 'bottom', 1, '关于我们', 'page', '', '', '', '', '', 1449742312, 1449742312, 0, 1),
  (3, 'bottom', 1, '服务产品', 'page', '', '', '', '', '', 1449742597, 1449742651, 0, 1),
  (4, 'bottom', 1, '商务合作', 'page', '', '', '', '', '', 1449742664, 1449742664, 0, 1),
  (5, 'bottom', 1, '加入我们', 'page', '', '', '', '', '', 1449742678, 1449742697, 0, 1),
  (6, 'bottom', 0, '帮助', 'page', '', '', '', '', '', 1449742688, 1449742688, 0, 1),
  (7, 'bottom', 6, '用户协议', 'page', '', '', '', '', '', 1449742706, 1449742706, 0, 1),
  (8, 'bottom', 6, '意见反馈', 'page', '', '', '', '', '', 1449742716, 1449742716, 0, 1),
  (9, 'bottom', 6, '常见问题', 'page', '', '', '', '', '', 1449742728, 1449742728, 0, 1),
  (10, 'bottom', 0, '联系方式', 'page', '', '', '', '', '', 1449742742, 1449742742, 0, 1),
  (11, 'bottom', 10, '联系我们', 'page', '', '', '', '', '', 1449742752, 1449742752, 0, 1),
  (12, 'bottom', 10, '新浪微博', 'page', '', '', '', '', '', 1449742802, 1449742802, 0, 1),
  (13, 'main', 0, '首页', 'link', '', '', '', '', '', 1457084559, 1472993801, 0, 1),
  (14, 'main', 0, '客户服务', 'page', '', '', '', '', '', 1457084572, 1457084572, 0, 0),
  (15, 'main', 0, '产品展示', 'page', '<p>查看前台演示，请点击右上角“网站导航”查看具体内容。</p><p>查看后台演示，请点击顶部<a target=\"_blank\" href=\"http://demo.lingyun.net/admin2.php?s=/admin/login/login.html&amp;account=admin&amp;password=admin\" style=\"color: rgb(0, 115, 229); text-decoration: underline; outline: 0px; text-align: center;\">点击这里体验强大的零云后台管理</a>。</p>                            ', '', 'fa-search', 'lists', 'detail', 1486516611, 1488506030, 0, 1),
  (16, 'main', 0, '新闻动态', 'page', '<p>查看前台演示，请点击右上角“网站导航”查看具体内容。</p><p>查看后台演示，请点击顶部<a target=\"_blank\" href=\"http://demo.lingyun.net/admin2.php?s=/admin/login/login.html&amp;account=admin&amp;password=admin\" style=\"color: rgb(0, 115, 229); text-decoration: underline; outline: 0px; text-align: center;\">点击这里体验强大的零云后台管理</a>。</p>                            ', '', '', '', '', 1457084714, 1488505991, 0, 1),
  (17, 'main', 0, '联系我们', 'page', '<p>查看前台演示，请点击右上角“网站导航”查看具体内容。</p><p>查看后台演示，请点击顶部<a target=\"_blank\" href=\"http://demo.lingyun.net/admin2.php?s=/admin/login/login.html&amp;account=admin&amp;password=admin\" style=\"color: rgb(0, 115, 229); text-decoration: underline; outline: 0px; text-align: center;\">点击这里体验强大的零云后台管理</a>。</p>                            ', '', '', '', '', 1457084725, 1488506009, 0, 1),
  (18, 'main', 0, '案例展示', 'page', '<p>查看前台演示，请点击右上角“网站导航”查看具体内容。</p><p>查看后台演示，请点击顶部<a target=\"_blank\" href=\"http://demo.lingyun.net/admin2.php?s=/admin/login/login.html&amp;account=admin&amp;password=admin\" style=\"color: rgb(0, 115, 229); text-decoration: underline; outline: 0px; text-align: center;\">点击这里体验强大的零云后台管理</a>。</p><p>            </p>', '', '', '', '', 1457084583, 1488505982, 0, 1),
  (19, 'tabbar', 0, '首页', 'link', '', '', 'fa-home', '', '', 1458382401, 1458382401, 0, 1),
  (20, 'tabbar', 0, '消息', 'module', 'Im', '', 'fa-commenting-o', '', '', 1458382603, 1461381689, 0, 1),
  (21, 'tabbar', 0, '发现', 'link', 'Home/Index/find', '', 'fa-compass', '', '', 1458382637, 1492428926, 0, 1),
  (22, 'tabbar', 0, '我的', 'module', 'User', '', 'fa-user', '', '', 1458382814, 1461207462, 0, 1),
  (23, 'find', 0, '动态', 'module', 'User', '', 'fa-star-o', '', '', 1492429751, 1492429751, 0, 0),
  (24, 'find', 23, '微博', 'module', 'User', '', 'fa-star', '', '', 1492429771, 1492429771, 0, 1),
  (25, 'find', 0, '开发', 'link', '', '', '', '', '', 1492429787, 1492429787, 0, 1),
  (26, 'find', 25, '开发文档', 'module', 'Manual', '', 'fa-book', '', '', 1492429808, 1492429808, 0, 1),
  (27, 'find', 25, '应用市场', 'module', 'Appstore', '', 'fa-cloud', '', '', 1492429831, 1492429851, 0, 1),
  (28, 'find', 25, '社区交流', 'module', 'Forum', '', 'fa-comments-o', '', '', 1492429892, 1492429892, 0, 1);



# Dump of table ly_admin_post
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ly_admin_post`;

CREATE TABLE `ly_admin_post` (
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


# Dump of table ly_admin_theme
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ly_admin_theme`;

CREATE TABLE `ly_admin_theme` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(32) DEFAULT NULL COMMENT '名称',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(127) NOT NULL DEFAULT '' COMMENT '描述',
  `logo` varchar(63) NOT NULL DEFAULT '' COMMENT '图片图标',
  `icon` varchar(31) NOT NULL DEFAULT '' COMMENT '图标',
  `icon_color` varchar(7) NOT NULL DEFAULT '' COMMENT '图标颜色',
  `developer` varchar(32) NOT NULL DEFAULT '' COMMENT '开发者',
  `version` varchar(8) NOT NULL DEFAULT '' COMMENT '版本',
  `config` text COMMENT '主题配置',
  `current` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否当前主题',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='前台主题表';



# Dump of table ly_admin_upload
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ly_admin_upload`;

CREATE TABLE `ly_admin_upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `url` varchar(255) DEFAULT '' COMMENT '文件链接',
  `ext` char(4) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) DEFAULT '' COMMENT '文件sha1编码',
  `location` varchar(15) NOT NULL DEFAULT '' COMMENT '文件存储位置',
  `download` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文件上传表';

# Dump of table ly_admin_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ly_admin_user`;

CREATE TABLE `ly_admin_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'UID',
  `user_type` int(11) NOT NULL DEFAULT '1' COMMENT '用户类型',
  `nickname` varchar(63) DEFAULT NULL COMMENT '昵称',
  `username` varchar(31) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(63) NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(63) NOT NULL DEFAULT '' COMMENT '邮箱',
  `email_bind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮箱验证',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `mobile_bind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮箱验证',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `score` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_type` varchar(15) NOT NULL DEFAULT '' COMMENT '注册方式',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8 COMMENT='用户账号表';

LOCK TABLES `ly_admin_user` WRITE;
/*!40000 ALTER TABLE `ly_admin_user` DISABLE KEYS */;

INSERT INTO `ly_admin_user` (`id`, `user_type`, `nickname`, `username`, `password`, `email`, `mobile`, `avatar`, `score`, `money`, `reg_ip`, `create_time`, `update_time`, `status`)
VALUES
    (1,1,'超级管理员','admin','79cc780bd21b161230268824080b8476','','',0,0,0.00,0,1438651748,1438651748,1);

/*!40000 ALTER TABLE `ly_admin_user` ENABLE KEYS */;
UNLOCK TABLES;


DROP TABLE IF EXISTS `ly_admin_slider`;

CREATE TABLE `ly_admin_slider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '幻灯ID',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '标题',
  `cover` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '封面ID',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '点击链接',
  `target` varchar(11) NOT NULL DEFAULT '' COMMENT '新窗口打开',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='幻灯切换表';


CREATE TABLE `ly_admin_router` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '动态路由',
  `module` varchar(31) NOT NULL DEFAULT '' COMMENT '模块名',
  `pathinfo` varchar(255) NOT NULL DEFAULT '' COMMENT '原地址',
  `params` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `rule` varchar(255) NOT NULL DEFAULT '' COMMENT '路由规则',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '修改时间',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='路由信息表';


# Dump of table ly_admin_session
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ly_admin_session`;

CREATE TABLE `ly_admin_session` (
  `session_id` varchar(255) NOT NULL,
  `session_expire` int(11) NOT NULL,
  `session_data` blob,
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `update_time` int(11) unsigned NOT NULL COMMENT '更新时间',
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='session存储表';
