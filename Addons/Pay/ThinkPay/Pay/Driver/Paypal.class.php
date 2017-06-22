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
namespace Addons\Pay\ThinkPay\Pay\Driver;

/**
 * Paypal驱动
 */
class Paypal extends \Addons\Pay\ThinkPay\Pay\Pay
{
    protected $gateway = 'https://www.paypal.com/cgi-bin/webscr';
    protected $config  = array(
        'business' => '',
    );

    public function check()
    {
        if (!$this->config['business']) {
            E("贝宝设置有误！");
        }
        return true;
    }

    public function buildRequestForm($pay_data)
    {
        $param = array(
            'cmd'           => '_xclick',
            'charset'       => 'utf-8',
            'business'      => $this->config['business'],
            'currency_code' => 'USD',
            'notify_url'    => $this->config['notify_url'],
            'return'        => $this->config['return_url'],
            'invoice'       => $pay_data['out_trade_no'],
            'item_name'     => $pay_data['title'],
            'amount'        => $pay_data['money'],
            'no_note'       => 1,
            'no_shipping'   => 1,
        );
        $sHtml = $this->_buildForm($param, $this->gateway);
        return $sHtml;
    }

    public function verifyNotify($notify)
    {
        if (empty($notify['txn_id'])) {
            return false;
        }

        $tmpAr = array_merge($notify, array("cmd" => "_notify-validate"));

        $ppResponseAr = $this->fsockOpen($this->gateway, 0, $tmpAr);
        if ((strcmp($ppResponseAr, "VERIFIED") == 0) && $notify['receiver_email'] == $this->config['business']) {
            $info = array();
            //支付状态
            $info['status']       = $notify['payment_status'] == 'Completed' ? true : false;
            $info['money']        = $notify['mc_gross'];
            $info['out_trade_no'] = $notify['invoice'];
            $this->info           = $info;
            return true;
        }
        return false;
    }
}
