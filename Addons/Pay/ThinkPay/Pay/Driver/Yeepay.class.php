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
 * 易宝驱动
 */
class Yeepay extends \Addons\Pay\ThinkPay\Pay\Pay
{
    protected $gateway = 'https://www.yeepay.com/app-merchant-proxy/node';
    protected $config  = array(
        'key'     => '',
        'partner' => '',
    );

    public function check()
    {
        if (!$this->config['key'] || !$this->config['partner']) {
            E("易宝设置有误！");
        }
        return true;
    }

    public function buildRequestForm($pay_data)
    {
        $param = array(
            'p0_Cmd'          => 'Buy',
            'p1_MerId'        => $this->config['partner'],
            'p4_Cur'          => 'CNY',
            'p8_Url'          => $this->config['return_url'],
            'p2_Order'        => $pay_data['out_trade_no'],
            'p5_Pid'          => $this->toGbk($pay_data['title']),
            'p3_Amt'          => $pay_data['money'],
            'p7_Pdesc'        => $this->toGbk($pay_data['body']),
            'pr_NeedResponse' => 1,
        );

        $param['hmac'] = $this->createSign($param);

        $sHtml = $this->_buildForm($param, $this->gateway, 'post', 'gbk');

        return $sHtml;
    }

    /**
     * 易宝支付平台统一使用GBK/GB2312编码方式。
     * @param type $str
     * @return type
     */
    protected function toGbk($str, $from = "utf-8", $to = 'gbk')
    {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($str, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $str);
        } else {
            return $str;
        }
    }

    /**
     * 创建签名
     * @param type $params
     */
    protected function createSign($params)
    {

        ksort($params);
        reset($params);
        $arg = '';
        foreach ($params as $value) {
            if (request()->isPost()) {
                $arg .= $value;
            } else {
                if (in_array($key, array('p1_MerId', 'r0_Cmd', 'r1_Code', 'r2_TrxId', 'r3_Amt', 'r4_Cur', 'r5_Pid', 'r6_Order', 'r7_Uid', 'r8_MP', 'r9_BType')) == true) {
                    $arg .= $value;
                }
            }
        }
        $key = $this->config['key'];

        $arg = $this->toGbk($arg, "gbk", "utf-8");

        $b = 64; // byte length for md5
        if (strlen($key) > $b) {
            $key = pack("H*", md5($key));
        }
        $key    = str_pad($key, $b, chr(0x00));
        $ipad   = str_pad('', $b, chr(0x36));
        $opad   = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad;
        $k_opad = $key ^ $opad;

        return md5($k_opad . pack("H*", md5($k_ipad . $arg)));
    }

    public function verifyNotify($notify)
    {
        $hmac = $notify['hmac'];
        unset($notify['hmac']);
        if ($hmac == $this->createSign($notify)) {
            $info = array();
            //支付状态
            $info['status']       = $notify['r1_Code'] == 1 ? true : false;
            $info['money']        = $notify['r3_Amt'];
            $info['out_trade_no'] = $notify['r6_Order'];
            $this->info           = $info;
            if ($notify['r9_BType'] == 2) {
                $_GET['method'] = 'notify';
            }
            return true;
        } else {
            return false;
        }
    }
}
