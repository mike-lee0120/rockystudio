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
return array(
    'status'         => array(
        'title'   => '支付总开关',
        'type'    => 'radio',
        'options' => array(
            '1' => '开启',
            '0' => '关闭',
        ),
        'value'   => '1',
    ),
    'allow_pay_type' => array(
        'title'   => '启用的支付方式',
        'type'    => 'checkbox',
        'options' => array(
            'alipay'   => '支付宝',
            'wxpay'    => '微信支付',
            'unionpay' => '银联支付',
            'tenpay'   => '财付通',
            'paypal'   => '贝宝paypal',
            'kuaiqian' => '快钱支付',
            'yeepay'   => '易宝支付',
        ),
        'value'   => '',
    ),
    'group'          => array(
        'type'    => 'group',
        'options' => array(
            'alipay'     => array(
                'title'   => '支付宝',
                'options' => array(
                    'alipay_email'          => array(
                        'title' => '支付宝账户',
                        'type'  => 'text',
                        'value' => '25',
                        'tip'   => '一般为邮箱',
                    ),
                    'alipay_partner'        => array(
                        'title' => 'partner',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => '类似：2088911710861332',
                    ),
                    'alipay_key'            => array(
                        'title' => 'MD5Key',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'alipay_private_key'    => array(
                        'title' => 'RSA私钥',
                        'type'  => 'textarea',
                        'value' => '',
                    ),
                    'alipay_ali_public_key' => array(
                        'title' => '支付宝公钥',
                        'type'  => 'textarea',
                        'value' => '',
                    ),
                ),
            ),
            'wxpay'      => array(
                'title'   => '微信支付',
                'options' => array(
                    'wxpay_appid'     => array(
                        'title' => 'appid',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => 'appid',
                    ),
                    'wxpay_appsecret' => array(
                        'title' => 'appsecret',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => 'appsecret',
                    ),
                    'wxpay_mchid'     => array(
                        'title' => '商户ID',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'wxpay_key'       => array(
                        'title' => 'Key',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'wxapppay'   => array(
                'title'   => '微信支付(APP)',
                'options' => array(
                    'wxapppay_appid'     => array(
                        'title' => 'appid',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => 'appid',
                    ),
                    'wxapppay_appsecret' => array(
                        'title' => 'appsecret',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => 'appsecret',
                    ),
                    'wxapppay_mchid'     => array(
                        'title' => '商户ID',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'wxapppay_key'       => array(
                        'title' => 'Key',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'wxmpapppay' => array(
                'title'   => '微信支付(小程序)',
                'options' => array(
                    'wxmpapppay_appid'     => array(
                        'title' => 'appid',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => 'appid',
                    ),
                    'wxmpapppay_appsecret' => array(
                        'title' => 'appsecret',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => 'appsecret',
                    ),
                    'wxmpapppay_mchid'     => array(
                        'title' => '商户ID',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'wxmpapppay_key'       => array(
                        'title' => 'Key',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'unionpay'   => array(
                'title'   => '银联支付',
                'options' => array(
                    'unionpay_partner' => array(
                        'title' => 'partner',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'unionpay_key'     => array(
                        'title' => 'Key',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'tenpay'     => array(
                'title'   => '财付通',
                'options' => array(
                    'tenpay_partner' => array(
                        'title' => 'partner',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'tenpay_key'     => array(
                        'title' => 'Key',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'paypal'     => array(
                'title'   => '贝宝paypal',
                'options' => array(
                    'paypal_business' => array(
                        'title' => 'paypal账户',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'kuaiqian'   => array(
                'title'   => '快钱支付',
                'options' => array(
                    'kuaiqian_partner' => array(
                        'title' => 'partner',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'kuaiqian_key'     => array(
                        'title' => 'Key',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'yeepay'     => array(
                'title'   => '易宝支付',
                'options' => array(
                    'yeepay_partner' => array(
                        'title' => 'partner',
                        'type'  => 'text',
                    ),
                    'yeepay_key'     => array(
                        'title' => 'Key',
                        'type'  => 'text',
                    ),
                ),
            ),
        ),
    ),
);
