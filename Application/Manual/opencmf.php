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
        'name'        => 'Manual',
        'title'       => '手册',
        'icon'        => 'fa fa-book',
        'icon_color'  => '#8ECD5D',
        'description' => '多用户云手册模块',
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
                'title' => '我的手册',
                'icon'  => 'fa fa-book',
                'url'   => 'Manual/Index/my',
                'color' => '#398CD2',
            ),
            '1' => array(
                'title' => '我参与的',
                'icon'  => 'fa fa-users',
                'url'   => 'Manual/Member/my',
                'color' => '#DC6AC6',
            ),
        ),
        'main'   => array(
            '0' => array(
                'title' => '新建手册',
                'icon'  => 'fa fa-plus',
                'url'   => 'Manual/Index/add',
            ),
        ),
    ),

    //后台菜单及权限节点配置
    'admin_menu' => array(
        '1'  => array(
            'id'    => '1',
            'pid'   => '0',
            'title' => '手册',
            'icon'  => 'fa fa-book',
        ),
        '2'  => array(
            'pid'   => '1',
            'title' => '手册管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3'  => array(
            'pid'   => '2',
            'title' => '手册设置',
            'icon'  => 'fa fa-wrench',
            'url'   => 'Manual/Index/module_config',
        ),
        '4'  => array(
            'pid'   => '2',
            'title' => '手册列表',
            'icon'  => 'fa fa-book',
            'url'   => 'Manual/Index/index',
        ),
        '5'  => array(
            'pid'   => '4',
            'title' => '新增',
            'url'   => 'Manual/Index/add',
        ),
        '6'  => array(
            'pid'   => '4',
            'title' => '编辑',
            'url'   => 'Manual/Index/edit',
        ),
        '7'  => array(
            'pid'   => '4',
            'title' => '设置状态',
            'url'   => 'Manual/Index/setStatus',
        ),
        '8'  => array(
            'pid'   => '4',
            'title' => '章节列表',
            'url'   => 'Manual/ManualPage/index',
        ),
        '9'  => array(
            'pid'   => '8',
            'title' => '新增',
            'url'   => 'Manual/ManualPage/add',
        ),
        '10' => array(
            'pid'   => '8',
            'title' => '编辑',
            'url'   => 'Manual/ManualPage/edit',
        ),
        '11' => array(
            'pid'   => '8',
            'title' => '设置状态',
            'url'   => 'Manual/ManualPage/setStatus',
        ),
        '12' => array(
            'pid'   => '1',
            'title' => '广告管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '13' => array(
            'pid'   => '12',
            'title' => '广告位列表',
            'icon'  => 'fa fa-adn',
            'url'   => 'Manual/Ad/index',
        ),
        '14' => array(
            'pid'   => '13',
            'title' => '新增',
            'url'   => 'Manual/Ad/add',
        ),
        '15' => array(
            'pid'   => '13',
            'title' => '编辑',
            'url'   => 'Manual/Ad/edit',
        ),
        '16' => array(
            'pid'   => '13',
            'title' => '设置状态',
            'url'   => 'Manual/Ad/setStatus',
        ),
    ),

    // 路由规则
    'router'     => array(
        '0' => array(
            'type'     => '1',
            'pathinfo' => '/index/read',
            'params'   => '',
            'rule'     => 'manual/:name$',
        ),
    ),
);
