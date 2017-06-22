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
namespace Weixin\Model;

include dirname(dirname(__FILE__)) . '/Util/Wechat/wechat.class.php';
/**
 * 默认模型
 * @author jry <598821125@qq.com>
 */
class IndexModel
{
    /**
     * 发送模板消息
     * @author jry <598821125@qq.com>
     */
    public function SendMessage($msg_data)
    {
        if (!$msg_data || !C('weixin_config.appid')) {
            return false;
        }

        //加载微信SDK
        $options = array(
            'token'          => C('weixin_config.token'), //填写你设定的key
            'encodingaeskey' => C('weixin_config.crypt'), //填写加密用的EncodingAESKey
            'appid'          => C('weixin_config.appid'), //填写高级调用功能的app id, 请在微信开发模式后台查询
            'appsecret'      => C('weixin_config.appsecret'), //填写高级调用功能的密钥
        );
        $wechat = new \Wechat($options);

        // URL没有带http则自动补全
        if (strpos($msg_data['url'], 'http') !== 0) {
            $msg_data['url'] = C('HOME_DOMAIN') . $msg_data['url'];
        }

        // 发送模板消息
        $data = array(
            'touser'      => $msg_data['touser'],
            'template_id' => $msg_data['template_id'] ?: C('weixin_config.template_id'),
            'url'         => $msg_data['url'] ?: C('HOME_PAGE'),
            'topcolor'    => '#FF0000',
            'data'        => array(
                'first'    => array(
                    'value' => $msg_data['first'],
                    "color" => "#173177",
                ),
                'keyword1' => array(
                    'value' => $msg_data['keyword1'],
                    "color" => "#173177",
                ),
                'keyword2' => array(
                    'value' => $msg_data['keyword2'],
                    "color" => "#173177",
                ),
                'keyword3' => array(
                    'value' => $msg_data['keyword3'],
                    "color" => "#173177",
                ),
                'remark'   => array(
                    'value' => $msg_data['remark'] ?: '系统消息',
                    "color" => "#173177",
                ),
            ),
        );
        $result = $wechat->sendTemplateMessage($data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
