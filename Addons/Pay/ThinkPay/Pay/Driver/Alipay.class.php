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
 * 支付宝驱动
 */
class Alipay extends \Addons\Pay\ThinkPay\Pay\Pay
{
    protected $gateway    = 'https://mapi.alipay.com/gateway.do';
    protected $verify_url = 'http://notify.alipay.com/trade/notify_query.do';
    protected $config     = array(
        'partner' => '',
        'email'   => '',
        'key'     => '',
        'type'    => '',
    );

    public function check()
    {
        if (!$this->config['email'] || !$this->config['key'] || !$this->config['partner']) {
            E("支付宝设置有误！");
        }
        return true;
    }

    public function buildRequestForm($pay_data)
    {
        // 是否调用APP移动支付
        if (C('IS_API')) {
            $param = array(
                'service'        => 'mobile.securitypay.pay',
                'partner'        => $this->config['partner'],
                '_input_charset' => 'utf-8',
                'notify_url'     => urlencode($this->config['notify_url']),
                'out_trade_no'   => $pay_data['out_trade_no'],
                'subject'        => $pay_data['title'],
                'body'           => $pay_data['body'],
                'payment_type'   => '1',
                'seller_id'      => $this->config['email'],
                'total_fee'      => $pay_data['money'],
            );

            ksort($param);
            reset($param);

            $arg = '';
            foreach ($param as $key => $value) {
                if ($value) {
                    $arg .= "$key=\"$value\"&";
                }
            }
            $arg = (substr($arg, 0, -1));

            $param['sign']      = $this->rsaSign($arg, $this->config['private_key']);
            $param['sign_type'] = 'RSA';

            $sHtml = $arg . '&sign="' . $param['sign'] . '"&sign_type="RSA"';

            $return['json']   = json_encode($param);
            $return['string'] = $sHtml;

            return $return;
        } else {
            $param = array(
                'service'        => 'create_direct_pay_by_user',
                'partner'        => $this->config['partner'],
                '_input_charset' => 'utf-8',
                'notify_url'     => $this->config['notify_url'],
                'out_trade_no'   => $pay_data['out_trade_no'],
                'subject'        => $pay_data['title'],
                'body'           => $pay_data['body'],
                'payment_type'   => '1',
                'seller_id'      => $this->config['partner'],
                'total_fee'      => $pay_data['money'],
                'return_url'     => $this->config['return_url'],
            );

            ksort($param);
            reset($param);

            $arg = '';
            foreach ($param as $key => $value) {
                if ($value) {
                    $arg .= "$key=$value&";
                }
            }

            $param['sign']      = md5(substr($arg, 0, -1) . $this->config['key']);
            $param['sign_type'] = 'MD5';

            $sHtml = $this->_buildForm($param, $this->gateway, 'get');

            return $sHtml;
        }
    }

    // 对签名字符串转义
    private function createLinkstring($para)
    {
        $arg = "";
        while (list($key, $val) = each($para)) {
            $arg .= $key . '="' . $val . '"&';
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {$arg = stripslashes($arg);}
        return $arg;
    }

    /**
     * RSA签名
     * @param $data 待签名数据
     * @param $private_key 商户私钥
     * return 签名结果
     */
    private function rsaSign($data, $private_key)
    {
        $res = openssl_get_privatekey($private_key);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        $sign = urlencode($sign);
        return $sign;
    }

    /**
     * RSA验签
     * @param $data 待签名数据
     * @param $ali_public_key 支付宝的公钥
     * @param $sign 要校对的的签名结果
     * return 验证结果
     */
    private function rsaVerify($data, $ali_public_key, $sign)
    {
        $res    = openssl_get_publickey($ali_public_key);
        $result = (bool) openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);
        return $result;
    }

    /**
     * RSA解密
     * @param $content 需要解密的内容，密文
     * @param $private_key 商户私钥
     * return 解密后内容，明文
     */
    private function rsaDecrypt($content, $private_key)
    {
        $res = openssl_get_privatekey($private_key);
        //用base64将内容还原成二进制
        $content = base64_decode($content);
        //把需要解密的内容，按128位拆开解密
        $result = '';
        for ($i = 0; $i < strlen($content) / 128; $i++) {
            $data = substr($content, $i * 128, 128);
            openssl_private_decrypt($data, $decrypt, $res);
            $result .= $decrypt;
        }
        openssl_free_key($res);
        return $result;
    }

    /**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
    protected function getSignVeryfy($param, $sign)
    {
        //除去待签名参数数组中的空值和签名参数
        $param_filter = array();
        while (list($key, $val) = each($param)) {
            if ($key == "sign" || $key == "sign_type" || $val == "") {
                continue;
            } else {
                $param_filter[$key] = $param[$key];
            }
        }

        ksort($param_filter);
        reset($param_filter);

        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = "";
        while (list($key, $val) = each($param_filter)) {
            $prestr .= $key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $prestr = substr($prestr, 0, -1);

        // App支付异步通知验证
        if ('app' === $this->config['type']) {
            return $this->rsaVerify($prestr, $this->config['ali_public_key'], $sign);
        } else {
            $prestr = $prestr . $this->config['key'];
            $mysgin = md5($prestr);
            if ($mysgin == $sign) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 针对notify_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
    public function verifyNotify($notify)
    {

        //生成签名结果
        $isSign = $this->getSignVeryfy($notify, $notify["sign"]);
        //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
        $responseTxt = 'true';
        if (!empty($notify["notify_id"])) {
            $responseTxt = $this->getResponse($notify["notify_id"]);
        }

        if (preg_match("/true$/i", $responseTxt) && $isSign) {
            $this->setInfo($notify);
            return true;
        } else {
            return false;
        }
    }

    protected function setInfo($notify)
    {
        $info = array();
        //支付状态
        $info['status']       = ($notify['trade_status'] == 'TRADE_FINISHED' || $notify['trade_status'] == 'TRADE_SUCCESS') ? true : false;
        $info['money']        = $notify['total_fee'];
        $info['out_trade_no'] = $notify['out_trade_no'];
        $this->info           = $info;
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    protected function getResponse($notify_id)
    {
        $partner     = $this->config['partner'];
        $veryfy_url  = $this->verify_url . "?partner=" . $partner . "&notify_id=" . $notify_id;
        $responseTxt = $this->fsockOpen($veryfy_url);
        return $responseTxt;
    }
}
