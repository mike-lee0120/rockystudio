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
        'name'        => 'Im',
        'title'       => 'IM',
        'icon'        => 'fa fa-commenting-o',
        'icon_color'  => '#F9B440',
        'description' => 'IM模块',
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
        'hide_wap' => '1',
        'center'   => array(
            '0' => array(
                'title'       => '消息列表',
                'icon'        => 'fa fa-commenting-o',
                'url'         => '/im',
                'badge'       => array('Im/Message', 'newTalkCount'),
                'badge_class' => 'badge-danger',
                'color'       => '#398CD2',
            ),
        ),
        'main'     => array(
            '0' => array(
                'title'       => '消息列表',
                'icon'        => 'fa fa-commenting-o',
                'url'         => '/im',
                'badge'       => array('Im/Message', 'newTalkCount'),
                'badge_class' => 'badge-danger',
                'color'       => '#398CD2',
            ),
        ),
    ),

    // 模块配置
    'config'     => array(
        'status' => array(
            'title'   => '开启模块',
            'type'    => 'toggle',
            'options' => array(
                '1' => '开启',
                '0' => '关闭',
            ),
            'value'   => '1',
        ),
    ),

    // 后台菜单及权限节点配置
    'admin_menu' => array(
        '1' => array(
            'pid'   => '0',
            'title' => 'IM',
            'icon'  => 'fa fa-commenting-o',
            'top'   => 'User',
        ),
        '2' => array(
            'pid'   => '1',
            'title' => 'IM管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3' => array(
            'pid'   => '2',
            'title' => 'IM设置',
            'icon'  => 'fa fa-wrench',
            'url'   => 'Im/Index/module_config',
        ),
    ),

    // 路由规则
    'router'     => array(
        '0' => array(
            'type'     => '1',
            'pathinfo' => '/index/detail',
            'params'   => '',
            'rule'     => 'im/:id\d',
        ),
    ),
);
