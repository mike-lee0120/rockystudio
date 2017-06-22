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
 * 银联支付驱动
 */
class Unionpay extends \Addons\Pay\ThinkPay\Pay\Pay
{
    protected $gateway = 'https://unionpaysecure.com/api/Pay.action';
    protected $config  = array(
        'key'     => '',
        'partner' => '',
    );

    public function check()
    {
        if (!$this->config['key'] || !$this->config['partner']) {
            E("银联支付设置有误！");
        }
        return true;
    }

    public function buildRequestForm($pay_data)
    {
        $param = array(
            'version'       => '1.0.0',
            'charset'       => 'UTF-8',
            'merId'         => $this->config['partner'],
            'transType'     => "01",
            'orderAmount'   => $pay_data['money'] * 100,
            'orderNumber'   => $pay_data['out_trade_no'],
            'orderTime'     => date('YmdHis'),
            'orderCurrency' => "156",
            'customerIp'    => get_client_ip(),
            'frontEndUrl'   => $this->config['return_url'],
            'backEndUrl'    => $this->config['notify_url'],
            'merAbbr'       => $pay_data['title'],
            'merReserved'   => '',
        );

        $param['signature']  = $this->createSign($param);
        $param['signMethod'] = "md5";

        $sHtml = $this->_buildForm($param, $this->gateway);

        return $sHtml;
    }

    /**
     * 创建签名
     * @param type $params
     */
    protected function createSign($params)
    {
        ksort($params);
        $sign_str = "";
        foreach ($params as $key => $val) {
            $sign_str .= sprintf("%s=%s&", $key, $val);
        }
        return md5($sign_str . md5($this->config['key']));
    }

    public function verifyNotify($notify)
    {
        //提取服务器端的签名
        if (!isset($notify['signature']) || !isset($notify['signMethod'])) {
            return false;
        }
        $sign = $notify['signature'];
        unset($notify['signature']);
        unset($notify['signMethod']);

        //验证签名
        $mysign = $this->createSign($notify);
        if ($sign != $mysign) {
            return false;
        } else {
            $info = array();
            //支付状态
            $info['status']       = $notify['respCode'] == '00' ? true : false;
            $info['money']        = $notify['orderAmount'] / 100;
            $info['out_trade_no'] = $notify['orderNumber'];
            $this->info           = $info;
            return true;
        }
    }
}
