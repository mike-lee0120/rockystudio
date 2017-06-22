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
namespace Addons\Pay\Model;

/**
 * 支付插件控制器
 * @author jry <598821125@qq.com>
 */
class PayModel
{
    /**
     * 获取支付方式列表
     * @author jry <598821125@qq.com>
     */
    public function type_list($type)
    {
        $addon_config = \Common\Controller\Addon::getConfig('Pay');
        if ($addon_config['status']) {
            foreach ($addon_config['allow_pay_type'] as &$val) {
                $val1['type'] = $val;
                if (C('STATIC_DOMAIN')) {
                    $val1['logo'] = C('STATIC_DOMAIN') . '/Addons/Pay/logo/' . $val . '.jpg';
                } else {
                    $val1['logo'] = C('HOME_PAGE') . '/Addons/Pay/logo/' . $val . '.jpg';
                }
                $val = $val1;
            }
            if ($type) {
                return $addon_config['allow_pay_type']['type'];
            } else {
                return $addon_config['allow_pay_type'];
            }
        } else {
            return false;
        }
    }

    /**
     * 获取支付配置
     * @author jry <598821125@qq.com>
     */
    public function pay_config($type)
    {
        $addon_config = \Common\Controller\Addon::getConfig('Pay');
        if ($addon_config['status']) {
            if ($type === 'wxpay') {
                if (C('IS_API')) {
                    $pay_config              = array();
                    $type                    = 'wxapppay';
                    $pay_config['appid']     = $addon_config[$type . '_appid'];
                    $pay_config['appsecret'] = $addon_config[$type . '_appsecret'];
                    $pay_config['mchid']     = $addon_config[$type . '_mchid'];
                    $pay_config['key']       = $addon_config[$type . '_key'];
                } else {
                    $pay_config              = array();
                    $pay_config['appid']     = $addon_config[$type . '_appid'];
                    $pay_config['appsecret'] = $addon_config[$type . '_appsecret'];
                    $pay_config['mchid']     = $addon_config[$type . '_mchid'];
                    $pay_config['key']       = $addon_config[$type . '_key'];
                }
            } else if ($type === 'wxmpapppay') {
                $pay_config              = array();
                $type                    = 'wxmpapppay';
                $pay_config['appid']     = $addon_config[$type . '_appid'];
                $pay_config['appsecret'] = $addon_config[$type . '_appsecret'];
                $pay_config['mchid']     = $addon_config[$type . '_mchid'];
                $pay_config['key']       = $addon_config[$type . '_key'];
            } else {
                // 支付宝统一参数
                if ('aliwappay' === $type || 'alipayapp' === $type) {
                    $type = 'alipay';
                }
                $pay_config             = array();
                $pay_config['email']    = $addon_config[$type . '_email'];
                $pay_config['partner']  = $addon_config[$type . '_partner'];
                $pay_config['key']      = $addon_config[$type . '_key'];
                $pay_config['business'] = $addon_config[$type . '_business'];
                if ($type === 'alipay') {
                    $pay_config['private_key']    = $addon_config[$type . '_private_key'];
                    $pay_config['ali_public_key'] = $addon_config[$type . '_ali_public_key'];
                }
            }
            return $pay_config;
        } else {
            return false;
        }
    }
}
