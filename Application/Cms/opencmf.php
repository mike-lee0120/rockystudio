<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
// | 版权申明：零云不是一个自由软件，是零云官方推出的商业源码，严禁在未经许可的情况下
// | 拷贝、复制、传播、使用零云的任意代码，如有违反，请立即删除，否则您将面临承担相应
// | 法律责任的风险。如果需要取得官方授权，请联系官方http://www.lingyun.net
// +----------------------------------------------------------------------
// 模块信息配置
return array(
    // 模块信息
    'info'       => array(
        'name'        => 'Cms',
        'title'       => '门户',
        'icon'        => 'fa fa-newspaper-o',
        'icon_color'  => '#9933FF',
        'description' => '门户(CMS)模块',
        'developer'   => '南京科斯克网络科技有限公司',
        'website'     => 'http://www.lingyun.net',
        'version'     => '1.6.2',
        'dependences' => array(
            'Admin' => '1.6.2',
        ),
    ),

    // 用户中心导航
    'user_nav'   => array(
        'title'  => array(
            'center' => '门户',
        ),
        'center' => array(
            '0' => array(
                'title' => '我的文档',
                'icon'  => 'fa fa-list',
                'url'   => 'Cms/Index/my',
                'color' => '#F68A3A',
            ),
            '1' => array(
                'title' => '收藏的文档',
                'icon'  => 'fa fa-heart',
                'url'   => 'Cms/Mark/my',
                'color' => '#398CD2',
            ),
        ),
    ),

    // 模块配置
    'config'     => array(
        'need_check'     => array(
            'title'   => '投稿需要审核',
            'type'    => 'toggle',
            'options' => array(
                '1' => '开启',
                '0' => '关闭',
            ),
            'value'   => '0',
        ),
        'toggle_comment' => array(
            'title'   => '开启评论文档',
            'type'    => 'toggle',
            'options' => array(
                '1' => '开启',
                '0' => '关闭',
            ),
            'value'   => '1',
        ),
        'group_list'     => array(
            'title' => '栏目分组',
            'type'  => 'array',
            'value' => '1:默认',
        ),
        'cate'           => array(
            'title' => '首页栏目自定义',
            'type'  => 'array',
            'value' => 'a:1',
        ),
    ),

    // 后台菜单及权限节点配置
    'admin_menu' => array(
        '1'  => array(
            'id'    => '1',
            'pid'   => '0',
            'title' => '门户',
            'icon'  => 'fa fa-newspaper-o',
        ),
        '2'  => array(
            'pid'   => '1',
            'title' => '内容管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3'  => array(
            'pid'   => '2',
            'title' => '门户配置',
            'icon'  => 'fa fa-wrench',
            'url'   => 'Cms/Index/module_config',
        ),
        '4'  => array(
            'pid'   => '2',
            'title' => '文档模型',
            'icon'  => 'fa fa-th-large',
            'url'   => 'Cms/Type/index',
        ),
        '5'  => array(
            'pid'   => '4',
            'title' => '新增',
            'url'   => 'Cms/Type/add',
        ),
        '6'  => array(
            'pid'   => '4',
            'title' => '编辑',
            'url'   => 'Cms/Type/edit',
        ),
        '7'  => array(
            'pid'   => '4',
            'title' => '设置状态',
            'url'   => 'Cms/Type/setStatus',
        ),
        '8'  => array(
            'pid'   => '4',
            'title' => '字段管理',
            'icon'  => 'fa fa-database',
            'url'   => 'Cms/Attribute/index',
        ),
        '9'  => array(
            'pid'   => '8',
            'title' => '新增',
            'url'   => 'Cms/Attribute/add',
        ),
        '10' => array(
            'pid'   => '8',
            'title' => '编辑',
            'url'   => 'Cms/Attribute/edit',
        ),
        '11' => array(
            'pid'   => '8',
            'title' => '设置状态',
            'url'   => 'Cms/Attribute/setStatus',
        ),
        '12' => array(
            'pid'   => '2',
            'title' => '幻灯管理',
            'icon'  => 'fa fa-image',
            'url'   => 'Cms/Slider/index',
        ),
        '13' => array(
            'pid'   => '12',
            'title' => '新增',
            'url'   => 'Cms/Slider/add',
        ),
        '14' => array(
            'pid'   => '12',
            'title' => '编辑',
            'url'   => 'Cms/Slider/edit',
        ),
        '15' => array(
            'pid'   => '12',
            'title' => '设置状态',
            'url'   => 'Cms/Slider/setStatus',
        ),
        '16' => array(
            'pid'   => '2',
            'title' => '栏目分类',
            'icon'  => 'fa fa-navicon',
            'url'   => 'Cms/Category/index',
        ),
        '17' => array(
            'pid'   => '16',
            'title' => '新增',
            'url'   => 'Cms/Category/add',
        ),
        '18' => array(
            'pid'   => '16',
            'title' => '编辑',
            'url'   => 'Cms/Category/edit',
        ),
        '19' => array(
            'pid'   => '16',
            'title' => '设置状态',
            'url'   => 'Cms/Category/setStatus',
        ),
        '20' => array(
            'pid'   => '2',
            'title' => '文档管理',
            'icon'  => 'fa fa-edit',
            'url'   => 'Cms/Index/index',
        ),
        '21' => array(
            'pid'   => '20',
            'title' => '新增',
            'url'   => 'Cms/Index/add',
        ),
        '22' => array(
            'pid'   => '20',
            'title' => '编辑',
            'url'   => 'Cms/Index/edit',
        ),
        '23' => array(
            'pid'   => '20',
            'title' => '设置状态',
            'url'   => 'Cms/Index/setStatus',
        ),
        '24' => array(
            'pid'   => '2',
            'title' => '评论管理',
            'icon'  => 'fa fa-commenting',
            'url'   => 'Cms/Comment/index',
        ),
        '25' => array(
            'pid'   => '24',
            'title' => '编辑',
            'url'   => 'Cms/Comment/edit',
        ),
        '26' => array(
            'pid'   => '24',
            'title' => '设置状态',
            'url'   => 'Cms/Comment/setStatus',
        ),
        '27' => array(
            'pid'   => '2',
            'title' => '回收站',
            'icon'  => 'fa fa-recycle',
            'url'   => 'Cms/Index/recycle',
        ),
        '28' => array(
            'pid'   => '2',
            'title' => '举报列表',
            'icon'  => 'fa fa-info-circle',
            'url'   => 'Cms/Report/index',
        ),
        '29' => array(
            'pid'   => '28',
            'title' => '编辑',
            'url'   => 'Cms/Report/edit',
        ),
        '30' => array(
            'pid'   => '28',
            'title' => '设置状态',
            'url'   => 'Cms/Report/setStatus',
        ),
        '31' => array(
            'pid'   => '2',
            'title' => '投稿审核',
            'icon'  => 'fa fa-edit',
            'url'   => 'Cms/Review/index',
        ),
        '32' => array(
            'pid'   => '31',
            'title' => '审核',
            'url'   => 'Cms/Review/review',
        ),
        '33' => array(
            'pid'   => '31',
            'title' => '拒绝',
            'url'   => 'Cms/Review/reject',
        ),
        '35' => array(
            'pid'   => '1',
            'title' => '广告管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '36' => array(
            'pid'   => '35',
            'title' => '广告位列表',
            'icon'  => 'fa fa-adn',
            'url'   => 'Cms/Ad/index',
        ),
        '37' => array(
            'pid'   => '36',
            'title' => '新增',
            'url'   => 'Cms/Ad/add',
        ),
        '38' => array(
            'pid'   => '36',
            'title' => '编辑',
            'url'   => 'Cms/Ad/edit',
        ),
        '39' => array(
            'pid'   => '36',
            'title' => '设置状态',
            'url'   => 'Cms/Ad/setStatus',
        ),
    ),

    // 路由规则
    'router'     => array(
        '0' => array(
            'type'     => '1',
            'pathinfo' => '/index/detail',
            'params'   => '',
            'rule'     => 'cms/:id\d',
        ),
        '1' => array(
            'type'     => '1',
            'pathinfo' => '/index/lists',
            'params'   => '',
            'rule'     => 'cms/list/:cid\d',
        ),
        '2' => array(
            'type'     => '1',
            'pathinfo' => '/category/detail',
            'params'   => '',
            'rule'     => 'cms/cate/:id\d',
        ),
    ),
);
