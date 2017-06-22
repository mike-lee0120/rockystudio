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
namespace Addons\Jpush\Model;

require_once dirname(dirname(__FILE__)) . '/jpush/autoload.php';
use JPush\Client as JPush;

/**
 * 推送模型
 * @author jry <598821125@qq.com>
 */
class JpushModel
{
    /**
     * 单个用户推送消息发送函数
     * @param string $push_data['title'] 推送消息标题
     * @param string $push_data['token_list'] 推送目标手机的token列表
     * @param string $push_data['url'] 消息地址，一般是APP内部某个页面的资源地址，一般用于定向到当前消息对应的APP内页面。
     * @param array $push_data['extras'] 发送给APP的自定义参数
     * @param bool $push_data['message_only'] 仅穿透模式，APP手机端无任何显示。
     * @return boolean
     * @author jry <598821125@qq.com>
     */
    public function send($push_data)
    {
        $addon_config = \Common\Controller\Addon::getConfig('Jpush');
        if ($addon_config['status']) {

            // IOS开发模式&部署模式
            if ($addon_config['production']) {
                $production = true;
            } else {
                $production = false;
            }

            // 调用极光推送SDK
            if ($push_data['token_list']) {
                // 初始化
                $client = new \JPush\Client($addon_config['app_key'], $addon_config['master_secret'], RUNTIME_PATH . 'jpush.log');
                $result = null;

                // 发送给APP的自定义参数
                $extras['url'] = $push_data['url'];
                if (is_array($push_data['extras'])) {
                    $extras = array_merge($extras, $push_data['extras']);
                }

                // 推送消息
                try {
                    $result = $client->push()
                        ->setPlatform('all')
                        ->addRegistrationId($push_data['token_list']);
                    // 是否仅穿透模式
                    if ($push_data['message_only'] !== true) {
                        $result->setNotificationAlert($push_data['title'])
                            ->iosNotification($push_data['title'], array(
                                'sound'             => $push_data['title'],
                                'badge'             => 1,
                                'content-available' => true,
                                'category'          => 'ly',
                                'extras'            => $extras,
                            ))
                            ->androidNotification($push_data['title'], array(
                                'title'    => $push_data['title'],
                                'build_id' => 1,
                                'extras'   => $extras,
                            ));
                    }

                    // 穿透消息
                    $result->message($push_data['title'], array(
                            'title'        => $push_data['title'],
                            'content_type' => 'text',
                            'extras'       => $extras,
                        ))
                        ->options(array(
                            'apns_production' => $production,
                        ))
                        ->send();
                } catch (\JPush\Exceptions\APIConnectionException $e) {
                    // try something here
                    print $e;
                } catch (\JPush\Exceptions\APIRequestException $e) {
                    // try something here
                    print $e;
                }
                if ($result) {
                    return $result;
                } else {
                    $this->error = '推送失败';
                    return false;
                }
            } else {
                $this->error = '未指定推送token';
                return false;
            }
        } else {
            $this->error = '插件关闭';
            return false;
        }
    }
}
