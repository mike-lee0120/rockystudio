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
        'name'        => 'Wallet',
        'title'       => '钱包',
        'icon'        => 'fa fa-money',
        'icon_color'  => '#8ECD5D',
        'description' => '钱包支付模块',
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
                'title'    => '我的钱包',
                'icon'     => 'fa fa-rmb',
                'url'      => 'Wallet/Index/index',
                'color'    => '#398CD2',
                'badge'    => array('Wallet/Index', 'money'),
                'hide_wap' => 1,
            ),
        ),
    ),

    // 模块配置
    'config'     => array(
        'toggle_recharge'               => array(
            'title'   => '开启充值',
            'type'    => 'toggle',
            'options' => array(
                '1' => '开启',
                '0' => '关闭',
            ),
            'value'   => '1',
        ),
        'min_recharge'                  => array(
            'title' => '最少充值金额',
            'type'  => 'price',
            'value' => '1.00',
        ),
        'toggle_recharge_bonuses_index' => array(
            'title'   => '首次充值优惠',
            'type'    => 'toggle',
            'options' => array(
                '1' => '优惠',
                '0' => '不优惠',
            ),
            'value'   => '0',
        ),
        'toggle_recharge_bonuses'       => array(
            'title'   => '普通充值优惠',
            'type'    => 'toggle',
            'options' => array(
                '1' => '优惠',
                '0' => '不优惠',
            ),
            'value'   => '0',
        ),
        'pay_need_password'             => array(
            'title'   => '支付需要密码',
            'type'    => 'toggle',
            'options' => array(
                '1' => '开启',
                '0' => '关闭',
            ),
            'value'   => '1',
        ),
    ),

    //后台菜单及权限节点配置
    'admin_menu' => array(
        '1'  => array(
            'id'    => '1',
            'pid'   => '0',
            'title' => '钱包',
            'icon'  => 'fa fa-money',
        ),
        '2'  => array(
            'pid'   => '1',
            'title' => '钱包管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3'  => array(
            'pid'   => '2',
            'title' => '钱包设置',
            'icon'  => 'fa fa-wrench',
            'url'   => 'Wallet/Index/module_config',
        ),
        '4'  => array(
            'pid'   => '2',
            'title' => '账单列表',
            'icon'  => 'fa fa-calendar-check-o',
            'url'   => 'Wallet/Index/index',
        ),
        '5'  => array(
            'pid'   => '4',
            'title' => '人工充值',
            'url'   => 'Wallet/Index/recharge',
        ),
        '6'  => array(
            'pid'   => '4',
            'title' => '人工扣款',
            'url'   => 'Wallet/Index/pay',
        ),
        '15' => array(
            'pid'   => '2',
            'title' => '充值纪录',
            'icon'  => 'fa fa-recharge',
            'url'   => 'Wallet/Recharge/index',
        ),
        '16' => array(
            'pid'   => '2',
            'title' => '充值优惠',
            'icon'  => 'fa fa-calendar-check-o',
            'url'   => 'Wallet/RechargeBonuses/index',
        ),
        '17' => array(
            'pid'   => '16',
            'title' => '新增',
            'url'   => 'Wallet/RechargeBonuses/add',
        ),
        '18' => array(
            'pid'   => '16',
            'title' => '编辑',
            'url'   => 'Wallet/RechargeBonuses/edit',
        ),
        '19' => array(
            'pid'   => '16',
            'title' => '设置状态',
            'url'   => 'Wallet/RechargeBonuses/setStatus',
        ),
        '30' => array(
            'pid'   => '2',
            'title' => '提现列表',
            'icon'  => 'fa fa-withdraw',
            'url'   => 'Wallet/Withdraw/index',
        ),
        '31' => array(
            'pid'   => '30',
            'title' => '处理提现',
            'icon'  => 'fa fa-calendar-check-o',
            'url'   => 'Wallet/Withdraw/pay',
        ),
        '42' => array(
            'pid'   => '1',
            'title' => '红包管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '43' => array(
            'pid'   => '42',
            'title' => '用户的红包',
            'icon'  => 'fa fa-redbag',
            'url'   => 'Wallet/Redbag/index',
        ),
        '44' => array(
            'pid'   => '43',
            'title' => '新增',
            'url'   => 'Wallet/Redbag/add',
        ),
        '45' => array(
            'pid'   => '43',
            'title' => '编辑',
            'url'   => 'Wallet/Redbag/edit',
        ),
        '46' => array(
            'pid'   => '43',
            'title' => '设置状态',
            'url'   => 'Wallet/Redbag/setStatus',
        ),
        '47' => array(
            'pid'   => '42',
            'title' => '分享红包活动',
            'icon'  => 'fa fa-share-alt',
            'url'   => 'Wallet/RedbagShare/index',
        ),
        '48' => array(
            'pid'   => '47',
            'title' => '新增',
            'url'   => 'Wallet/RedbagShare/add',
        ),
        '49' => array(
            'pid'   => '47',
            'title' => '编辑',
            'url'   => 'Wallet/RedbagShare/edit',
        ),
        '60' => array(
            'pid'   => '47',
            'title' => '设置状态',
            'url'   => 'Wallet/RedbagShare/setStatus',
        ),
        '61' => array(
            'pid'   => '42',
            'title' => '红包随机列表',
            'icon'  => 'fa fa-list',
            'url'   => 'Wallet/RedbagSharePool/index',
        ),
        '62' => array(
            'pid'   => '61',
            'title' => '新增',
            'url'   => 'Wallet/RedbagSharePool/add',
        ),
        '63' => array(
            'pid'   => '61',
            'title' => '编辑',
            'url'   => 'Wallet/RedbagSharePool/edit',
        ),
        '64' => array(
            'pid'   => '61',
            'title' => '设置状态',
            'url'   => 'Wallet/RedbagSharePool/setStatus',
        ),
        '65' => array(
            'pid'   => '1',
            'title' => '折扣券管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '66' => array(
            'pid'   => '65',
            'title' => '用户的折扣券',
            'icon'  => 'fa fa-coupon',
            'url'   => 'Wallet/Coupon/index',
        ),
        '67' => array(
            'pid'   => '66',
            'title' => '新增',
            'url'   => 'Wallet/Coupon/add',
        ),
        '68' => array(
            'pid'   => '66',
            'title' => '编辑',
            'url'   => 'Wallet/Coupon/edit',
        ),
        '69' => array(
            'pid'   => '66',
            'title' => '设置状态',
            'url'   => 'Wallet/Coupon/setStatus',
        ),
        '80' => array(
            'pid'   => '65',
            'title' => '分享折扣券活动',
            'icon'  => 'fa fa-share-alt',
            'url'   => 'Wallet/CouponShare/index',
        ),
        '81' => array(
            'pid'   => '80',
            'title' => '新增',
            'url'   => 'Wallet/CouponShare/add',
        ),
        '82' => array(
            'pid'   => '80',
            'title' => '编辑',
            'url'   => 'Wallet/CouponShare/edit',
        ),
        '83' => array(
            'pid'   => '80',
            'title' => '设置状态',
            'url'   => 'Wallet/CouponShare/setStatus',
        ),
        '84' => array(
            'pid'   => '65',
            'title' => '折扣券随机列表',
            'icon'  => 'fa fa-list',
            'url'   => 'Wallet/CouponSharePool/index',
        ),
        '85' => array(
            'pid'   => '84',
            'title' => '新增',
            'url'   => 'Wallet/CouponSharePool/add',
        ),
        '86' => array(
            'pid'   => '84',
            'title' => '编辑',
            'url'   => 'Wallet/CouponSharePool/edit',
        ),
        '87' => array(
            'pid'   => '84',
            'title' => '设置状态',
            'url'   => 'Wallet/CouponSharePool/setStatus',
        ),
    ),
);
