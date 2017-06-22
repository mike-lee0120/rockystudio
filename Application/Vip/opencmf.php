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
        'name'        => 'Vip',
        'title'       => 'Vip',
        'icon'        => 'fa fa-heart',
        'icon_color'  => '#F9B440',
        'description' => 'Vip模块',
        'developer'   => '南京科斯克网络科技有限公司',
        'website'     => 'http://www.lingyun.net',
        'version'     => '1.6.2',
        'dependences' => array(
            'Admin' => '1.6.2',
            'User'  => '1.6.2',
        ),
    ),

    // 用户中心导航
    'user_nav'   => array(
        'center' => array(
            '0' => array(
                'title' => '我的会员',
                'icon'  => 'fa fa-heart',
                'url'   => '/Vip',
                'color' => '#E83A2C',
            ),
        ),
    ),

    // 后台菜单及权限节点配置
    'admin_menu' => array(
        '1'  => array(
            'pid'   => '0',
            'title' => 'Vip',
            'icon'  => 'fa fa-heart',
            'top'   => 'User',
        ),
        '2'  => array(
            'pid'   => '1',
            'title' => 'Vip管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3'  => array(
            'pid'   => '2',
            'title' => 'Vip设置',
            'icon'  => 'fa fa-wrench',
            'url'   => 'Vip/Index/module_config',
        ),
        '4'  => array(
            'pid'   => '2',
            'title' => 'Vip列表',
            'icon'  => 'fa fa-list',
            'url'   => 'Vip/Index/index',
        ),
        '5'  => array(
            'pid'   => '4',
            'title' => '新增',
            'url'   => 'Vip/Index/add',
        ),
        '6'  => array(
            'pid'   => '4',
            'title' => '编辑',
            'url'   => 'Vip/Index/edit',
        ),
        '7'  => array(
            'pid'   => '5',
            'title' => '设置状态',
            'url'   => 'Vip/Index/setStatus',
        ),
        '8'  => array(
            'pid'   => '2',
            'title' => 'VIP订单',
            'icon'  => 'fa fa-check-square-o',
            'url'   => 'Vip/Order/index',
        ),
        '9'  => array(
            'pid'   => '8',
            'title' => '新增',
            'url'   => 'Vip/Order/add',
        ),
        '10' => array(
            'pid'   => '8',
            'title' => '编辑',
            'url'   => 'Vip/Order/edit',
        ),
        '11' => array(
            'pid'   => '8',
            'title' => '设置状态',
            'url'   => 'Vip/Order/setStatus',
        ),
        '12' => array(
            'pid'   => '2',
            'title' => 'VIP类型',
            'icon'  => 'fa fa-th-large',
            'url'   => 'Vip/Type/index',
        ),
        '13' => array(
            'pid'   => '12',
            'title' => '新增',
            'url'   => 'Vip/Type/add',
        ),
        '14' => array(
            'pid'   => '12',
            'title' => '编辑',
            'url'   => 'Vip/Type/edit',
        ),
        '15' => array(
            'pid'   => '12',
            'title' => '设置状态',
            'url'   => 'Vip/Type/setStatus',
        ),
    ),
);
