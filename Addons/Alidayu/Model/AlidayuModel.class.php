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
namespace Addons\Alidayu\Model;

/**
 * 短信模型
 * @author jry <598821125@qq.com>
 */
class AlidayuModel
{
    private $error = '';

    /**
     * 短信发送函数
     * @param string $sms_data 短信信息结构
     * @$sms_data['RecNum'] 收件人手机号码
     * @$sms_data['code'] 验证码内容
     * @$sms_data['product'] 文字说明
     * @$sms_data['SmsFreeSignName']短信签名
     * @$sms_data['SmsTemplateCode']短信模版ID
     * @return boolean
     * @author jry <598821125@qq.com>
     */
    public function send($sms_data)
    {
        $addon_config = \Common\Controller\Addon::getConfig('Alidayu');
        if ($addon_config['status']) {
            require_once "Addons/Alidayu/sdk/TopSdk.php";
            date_default_timezone_set('Asia/Shanghai');

            // 自定义参数
            if ($sms_data['code']) {
                $SmsParam['code'] = $sms_data['code'];
            }
            if ($sms_data['product']) {
                $SmsParam['product'] = $sms_data['product'];
            }

            // 调用SDK
            $c            = new \TopClient;
            $c->appkey    = $addon_config['appkey'];
            $c->secretKey = $addon_config['secret'];
            $c->format    = "json";
            $req          = new \AlibabaAliqinFcSmsNumSendRequest;
            $req->setSmsType("normal");
            $req->setSmsFreeSignName($sms_data['SmsFreeSignName'] ?: $addon_config['sign_name']);
            if (count($SmsParam) > 0) {
                $req->setSmsParam(json_encode($SmsParam));
            }
            $req->setRecNum($sms_data['RecNum']);
            $req->setSmsTemplateCode($sms_data['SmsTemplateCode'] ?: $addon_config['template_code']);
            $resp = $c->execute($req);
            if ($resp->result->success === true) {
                return true;
            } else {
                $this->error = $resp->sub_msg;
                return false;
            }
        } else {
            $this->error = '阿里大于插件未开启！';
            return false;
        }
    }

    // 返回错误
    public function getError()
    {
        return $this->error;
    }
}
