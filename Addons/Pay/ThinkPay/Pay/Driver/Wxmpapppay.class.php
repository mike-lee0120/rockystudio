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
 * 微信小程序支付驱动
 */
class Wxmpapppay extends \Addons\Pay\ThinkPay\Pay\Pay
{
    protected $gateway    = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    protected $orderquery = 'https://api.mch.weixin.qq.com/pay/orderquery';
    protected $config     = array(
        'appid'     => '',
        'appsecret' => '',
        'mchid'     => '',
        'key'       => '',
    );

    public function check()
    {
        if (!$this->config['appid'] || !$this->config['appsecret'] || !$this->config['mchid'] || !$this->config['key']) {
            E("微信支付设置有误！");
        }
        return true;
    }

    public function buildRequestForm($pay_data)
    {
        // 获取用户openId，微信小程序支付必须
        $openId = $_POST['openid'];
        if (!$openId) {
            $this->error('openid不存在');
        }
        $param = array(
            'appid'            => $this->config['appid'],
            'mch_id'           => $this->config['mchid'],
            'nonce_str'        => $this->getNonceStr(),
            'body'             => $pay_data['body'],
            'out_trade_no'     => $pay_data['out_trade_no'],
            'fee_type'         => 'CNY',
            'total_fee'        => $pay_data['money'] * 100,
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR'],
            'notify_url'       => $this->config['notify_url'],
            'trade_type'       => 'JSAPI',
            'openid'           => $openId,
        );

        // 签名
        $param['sign'] = $this->MakeSign($param);
        $xml_param     = $this->ToXml($param);
        $result        = $this->FromXml($this->postXmlCurl($xml_param, $this->gateway));
        if ($result['return_code'] === 'SUCCESS') {
            if ($this->CheckSign($result)) {
                // 获取JSAPI所需参数
                $jsApiParameters = $this->GetJsApiParameters($result);
                $return['json']  = $jsApiParameters;
                return $return;
            }
        }
    }

    /**
     * 异步通知验证
     */
    public function verifyNotify($notify)
    {
        //获取通知的数据
        if ($notify['return_code'] === 'SUCCESS') {
            if (!array_key_exists("transaction_id", $notify)) {
                E("输入参数不正确！");
            }
            $param['transaction_id'] = $notify["transaction_id"];
            $param['appid']          = $this->config['appid'];
            $param['mch_id']         = $this->config['mchid'];
            $param['nonce_str']      = $this->getNonceStr();
            $param['sign']           = $this->MakeSign($param);
            $xml_param               = $this->ToXml($param);
            $result                  = $this->FromXml($this->postXmlCurl($xml_param, $this->orderquery));
            if ($this->CheckSign($result)) {
                $result['status'] = ($result['result_code'] == 'SUCCESS') ? true : false;
                $result['money']  = $result['total_fee'] / 100;
                $this->info       = $result;
                return true;
            }
        } else {
            E('通知错误');
        }
    }

    /**
     * 异步通知验证成功返回信息
     */
    public function notifySuccess()
    {
        $return['return_code'] = 'SUCCESS';
        $return['return_msg']  = 'OK';
        echo $this->ToXml($return);
    }

    /**
     *
     * 获取jsapi支付的参数
     * @param array $UnifiedOrderResult 统一支付接口返回的数据
     * @throws WxPayException
     *
     * @return json数据，可直接填入js函数作为参数
     */
    public function GetJsApiParameters($UnifiedOrderResult)
    {
        if (!array_key_exists("appid", $UnifiedOrderResult)
            || !array_key_exists("prepay_id", $UnifiedOrderResult)
            || $UnifiedOrderResult['prepay_id'] == "") {
            E("参数错误");
        }
        $jsapi['appId']     = $UnifiedOrderResult["appid"];
        $jsapi['timeStamp'] = (string) time();
        $jsapi['nonceStr']  = $this->getNonceStr();
        $jsapi['package']   = "prepay_id=" . $UnifiedOrderResult['prepay_id'];
        $jsapi['signType']  = 'MD5';
        $jsapi['paySign']   = $this->MakeSign($jsapi);
        $parameters         = json_encode($jsapi);
        return $parameters;
    }

    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    private static function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //如果有配置代理这里就设置代理
        // if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0"
        //             && WxPayConfig::CURL_PROXY_PORT != 0){
        //             curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
        //             curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
        //         }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); //严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, false);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($useCert == true) {
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            E("curl出错，错误码:$error");
        }
    }

    /*
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    public static function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str   = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    public function MakeSign($param)
    {
        //签名步骤一：按字典序排序参数
        ksort($param);
        $string = $this->ToUrlParams($param);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $this->config['key'];
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     *
     * 检测签名
     */
    public function CheckSign($param)
    {
        $sign = $this->MakeSign($param);
        if ($param['sign'] == $sign) {
            return true;
        } else {
            E("签名错误！");
        }
    }

    /**
     *
     * 拼接签名字符串
     * @param array $urlObj
     *
     * @return 返回已经拼接好的字符串
     */
    public function ToUrlParams($param)
    {
        $buff = "";
        foreach ($param as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 输出xml字符
     * @throws WxPayException
     **/
    public function ToXml($param)
    {
        if (!is_array($param)
            || count($param) <= 0) {
            E("数组数据异常！");
        }

        $xml = "<xml>";
        foreach ($param as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function FromXml($xml)
    {
        if (!$xml) {
            E("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->values;
    }
}
