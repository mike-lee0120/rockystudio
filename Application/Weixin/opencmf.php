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
        'name'        => 'Weixin',
        'title'       => '微信',
        'icon'        => 'fa fa-weixin',
        'icon_color'  => '#4AB549',
        'description' => '微信公众号管理模块，适用于单帐号定制',
        'developer'   => '南京科斯克网络科技有限公司',
        'website'     => 'http://www.lingyun.net',
        'version'     => '1.6.2',
        'dependences' => array(
            'Admin' => '1.6.2',
            'User'  => '1.6.2',
        ),
    ),

    // 模块配置
    'config'     => array(
        'appid'           => array(
            'title' => 'AppId',
            'type'  => 'text',
            'value' => '',
        ),
        'appsecret'       => array(
            'title' => 'AppSecret',
            'type'  => 'password',
            'value' => '',
        ),
        'token'           => array(
            'title' => '微信后台填写的TOKEN',
            'type'  => 'text',
            'value' => '',
        ),
        'crypt'           => array(
            'title' => '加密密钥',
            'type'  => 'password',
            'value' => '',
        ),
        'access_token'    => array(
            'title' => 'AccessToken',
            'type'  => 'text',
            'value' => '',
        ),
        'weixin_login'    => array(
            'title'   => '开启微信登录',
            'type'    => 'toggle',
            'options' => array(
                '1' => '开启',
                '0' => '关闭',
            ),
            'value'   => '0',
        ),
        'no_reg'          => array(
            'title'   => '自动注册模式',
            'type'    => 'toggle',
            'options' => array(
                '1' => '开启',
                '0' => '关闭',
            ),
            'value'   => '0',
        ),
        'tuling_apikey'   => array(
            'title' => '图灵机器人ApiKey',
            'type'  => 'text',
            'value' => '',
        ),
        'subscribe_msg'   => array(
            'title' => '关注回复信息',
            'type'  => 'textarea',
            'value' => '欢迎您关注！',
        ),
        'unsubscribe_msg' => array(
            'title' => '取消关注回复信息',
            'type'  => 'textarea',
            'value' => '欢迎您再次关注！',
        ),
        'subscribe_msg'   => array(
            'title' => '关注回复信息',
            'type'  => 'textarea',
            'value' => '欢迎您关注！',
        ),
        'template_id'     => array(
            'title' => '通用模板消息ID',
            'type'  => 'text',
            'value' => 'Dfiwm6uEPEgzyrTvd1OKmHytR1I6yKHtDdyveBoi9j0',
        ),
    ),

    //后台菜单及权限节点配置
    'admin_menu' => array(
        '1'  => array(
            'pid'   => '0',
            'title' => '微信',
            'icon'  => 'fa fa-weixin',
        ),
        '2'  => array(
            'pid'   => '1',
            'title' => '基本功能',
            'icon'  => 'fa fa-weixin',
        ),
        '3'  => array(
            'pid'   => '2',
            'title' => '公众号配置',
            'icon'  => 'fa fa-weixin',
            'url'   => 'Weixin/Index/module_config',
        ),
        '4'  => array(
            'pid'   => '2',
            'title' => '自定义菜单',
            'icon'  => 'fa fa-bars',
            'url'   => 'Weixin/CustomMenu/index',
        ),
        '5'  => array(
            'pid'   => '4',
            'title' => '新增',
            'url'   => 'Weixin/CustomMenu/add',
        ),
        '6'  => array(
            'pid'   => '4',
            'title' => '编辑',
            'url'   => 'Weixin/CustomMenu/edit',
        ),
        '7'  => array(
            'pid'   => '4',
            'title' => '设置状态',
            'url'   => 'Weixin/CustomMenu/setStatus',
        ),
        '8'  => array(
            'pid'   => '2',
            'title' => '素材管理',
            'icon'  => 'fa fa-list',
            'url'   => 'Weixin/Material/index',
        ),
        '9'  => array(
            'pid'   => '8',
            'title' => '新增',
            'url'   => 'Weixin/Material/add',
        ),
        '10' => array(
            'pid'   => '8',
            'title' => '编辑',
            'url'   => 'Weixin/Material/edit',
        ),
        '11' => array(
            'pid'   => '8',
            'title' => '设置状态',
            'url'   => 'Weixin/Material/setStatus',
        ),
        '12' => array(
            'pid'   => '2',
            'title' => '自定义回复',
            'sort'  => '2',
            'icon'  => 'fa fa-reply',
            'url'   => 'Weixin/CustomReply/index',
        ),
        '13' => array(
            'pid'   => '12',
            'title' => '新增',
            'url'   => 'Weixin/CustomReply/add',
        ),
        '14' => array(
            'pid'   => '12',
            'title' => '编辑',
            'url'   => 'Weixin/CustomReply/edit',
        ),
        '25' => array(
            'pid'   => '12',
            'title' => '设置状态',
            'url'   => 'Weixin/CustomReply/setStatus',
        ),
    ),
);
