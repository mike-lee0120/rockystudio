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
        'name'        => 'Worksheet',
        'title'       => '工单',
        'icon'        => 'fa fa-home',
        'icon_color'  => '#8ECD5D',
        'description' => '通用工单模块',
        'developer'   => '南京科斯克网络科技有限公司',
        'website'     => 'http://www.lingyun.net',
        'version'     => '1.6.2',
        'dependences' => array(
            'Admin' => '1.6.2',
        ),
    ),

    //后台菜单及权限节点配置
    'admin_menu' => array(
        '1' => array(
            'id'    => '1',
            'pid'   => '0',
            'title' => '工单',
            'icon'  => 'fa fa-book',
        ),
        '2' => array(
            'pid'   => '1',
            'title' => '工单管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3' => array(
            'pid'   => '2',
            'title' => '工单列表',
            'icon'  => 'fa fa-book',
            'url'   => 'Worksheet/Index/index',
        ),
        '4' => array(
            'pid'   => '3',
            'title' => '设置状态',
            'url'   => 'Worksheet/Index/setStatus',
        ),
    ),
);
