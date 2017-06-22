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
//模块信息配置
return array(
    //模块信息
    'info'       => array(
        'name'        => 'Forum',
        'title'       => '论坛',
        'icon'        => 'fa fa-commenting',
        'icon_color'  => '#FF1493',
        'description' => '论坛模块',
        'developer'   => '南京科斯克网络科技有限公司',
        'website'     => 'http://www.lingyun.net',
        'version'     => '1.6.2',
        'dependences' => array(
            'Admin' => '1.6.2',
        ),
    ),

    // 用户中心导航
    'user_nav'   => array(
        'center' => array(
            '0' => array(
                'title' => '我的帖子',
                'icon'  => 'fa fa-edit',
                'url'   => 'Forum/Index/my',
                'color' => '#80C243',
            ),
        ),
        'main'   => array(
            '0' => array(
                'title' => '发布新帖',
                'icon'  => 'fa fa-plus',
                'url'   => 'Forum/Index/add',
                'color' => '#80C243',
            ),
        ),
        'search' => array(
            'placeholder' => '搜索帖子',
            'url'         => 'Forum/Index/index',
        ),
    ),

    // 模块配置
    'config'     => array(
        'need_check' => array(
            'title'   => '发帖开启审核',
            'type'    => 'toggle',
            'options' => array(
                '1' => '开启',
                '0' => '关闭',
            ),
            'value'   => '0',
        ),
        'need_cert'  => array(
            'title'   => '发帖开启实名认证',
            'type'    => 'toggle',
            'options' => array(
                '1' => '开启',
                '0' => '关闭',
            ),
            'value'   => '0',
        ),
    ),

    //个人主页导航
    'home_nav'   => array(
        '0' => array(
            'title' => '帖子',
            'icon'  => 'fa fa-commenting',
            'url'   => 'Forum/Index/home',
        ),
    ),

    //后台菜单及权限节点配置
    'admin_menu' => array(
        '1'  => array(
            'id'    => '1',
            'pid'   => '0',
            'title' => '论坛',
            'icon'  => 'fa fa-commenting',
        ),
        '2'  => array(
            'pid'   => '1',
            'title' => '论坛管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3'  => array(
            'pid'   => '2',
            'title' => '论坛配置',
            'icon'  => 'fa fa-wrench',
            'url'   => 'Forum/Index/module_config',
        ),
        '4'  => array(
            'pid'   => '2',
            'title' => '导航管理',
            'icon'  => 'fa fa-map-signs',
            'url'   => 'Forum/Nav/index',
        ),
        '5'  => array(
            'pid'   => '4',
            'title' => '新增',
            'url'   => 'Forum/Nav/add',
        ),
        '6'  => array(
            'pid'   => '4',
            'title' => '编辑',
            'url'   => 'Forum/Nav/edit',
        ),
        '7'  => array(
            'pid'   => '4',
            'title' => '设置状态',
            'url'   => 'Forum/Nav/setStatus',
        ),
        '8'  => array(
            'pid'   => '4',
            'title' => '文章管理',
            'icon'  => 'fa fa-edit',
            'url'   => 'Forum/Post/index',
        ),
        '9'  => array(
            'pid'   => '8',
            'title' => '新增',
            'url'   => 'Forum/Post/add',
        ),
        '10' => array(
            'pid'   => '8',
            'title' => '编辑',
            'url'   => 'Forum/Post/edit',
        ),
        '11' => array(
            'pid'   => '8',
            'title' => '设置状态',
            'url'   => 'Forum/Post/setStatus',
        ),
        '12' => array(
            'pid'   => '2',
            'title' => '板块列表',
            'icon'  => 'fa fa-th-large',
            'url'   => 'Forum/Plate/index',
        ),
        '13' => array(
            'pid'   => '12',
            'title' => '新增',
            'url'   => 'Forum/Plate/add',
        ),
        '14' => array(
            'pid'   => '12',
            'title' => '编辑',
            'url'   => 'Forum/Plate/edit',
        ),
        '15' => array(
            'pid'   => '12',
            'title' => '设置状态',
            'url'   => 'Forum/Plate/setStatus',
        ),
        '16' => array(
            'pid'   => '13',
            'title' => '帖子列表',
            'url'   => 'Forum/Index/index',
        ),
        '17' => array(
            'pid'   => '16',
            'title' => '新增',
            'url'   => 'Forum/Index/add',
        ),
        '18' => array(
            'pid'   => '16',
            'title' => '编辑',
            'url'   => 'Forum/Index/edit',
        ),
        '19' => array(
            'pid'   => '8',
            'title' => '设置状态',
            'url'   => 'Forum/Index/setStatus',
        ),
    ),

    // 路由规则
    'router'     => array(
        '0' => array(
            'type'     => '1',
            'pathinfo' => '/index/detail',
            'params'   => '',
            'rule'     => 'forum/:id\d',
        ),
    ),
);
