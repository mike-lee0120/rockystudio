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
namespace Weixin\Controller;

use Home\Controller\HomeController;

require_once dirname(dirname(__FILE__)) . '/Util/Wechat/wechat.class.php';
/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexController extends HomeController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 判断判断是微信浏览器自动跳转登录页面
        if (request()->isWeixin() && !(is_login())) {
            $this->redirect('Weixin/UserBind/index');
        } else {
            $this->assign('meta_title', '微信');
            $this->display();
        }
    }

    /**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     * @author jry <598821125@qq.com>
     */
    public function api()
    {
        //调试
        try {
            //加载微信SDK
            $options = array(
                'token'          => C('weixin_config.token'), //填写你设定的key
                'encodingaeskey' => C('weixin_config.crypt'), //填写加密用的EncodingAESKey
                'appid'          => C('weixin_config.appid'), //填写高级调用功能的app id, 请在微信开发模式后台查询
                'appsecret'      => C('weixin_config.appsecret'), //填写高级调用功能的密钥
            );
            $wechat = new \Wechat($options);

            // 接口验证
            $wechat->valid();

            // 获取请求类型
            $type = $wechat->getRev()->getRevType();

            if ($type) {
                $this->push($wechat, $type); // 响应请求
            }
        } catch (\Exception $e) {
            file_put_contents(RUNTIME_PATH . 'error.json', json_encode($e->getMessage()));
        }
    }

    /**
     * 响应请求
     * @param  Object $wechat Wechat对象
     * @param  array  $data   接受到微信推送的消息
     */
    private function push($wechat, $type)
    {
        $data = $wechat->getRev()->getRevData();
        switch ($type) {
            case \Wechat::MSGTYPE_TEXT:
                $map['keyword']    = $data['Content'];
                $map['status']     = 1;
                $custom_reply_info = D('weixin_custom_reply')->where($map)->find();
                if ($custom_reply_info) {
                    switch ($custom_reply_info['reply_type']) {
                        case 'text':
                            $wechat->text($custom_reply_info['content'])->reply();
                            break;
                        case 'material':
                            $key_list = explode(',', $custom_reply_info['reply_key']);
                            foreach ($key_list as $key => $val) {
                                $reply_info                = D('Material')->find($val);
                                $news[$key]['Title']       = $reply_info['title'];
                                $news[$key]['Description'] = $reply_info['abstract'];
                                $news[$key]['PicUrl']      = get_cover($reply_info['cover']);
                                $news[$key]['Url']         = 'http://' . $_SERVER['HTTP_HOST'] . U('Weixin/Material/detail/', array('id' => $reply_info['id']));
                            }
                            if ($news) {
                                $wechat->news($news)->reply();
                            }
                            break;
                        case 'addon':
                            $wechat->text($custom_reply_info['content'])->reply();
                            break;
                    }
                } else {
                    $con['openid']  = $data['FromUserName'];
                    $user_bind_info = D('UserBind')->where($con)->find();

                    // 图灵机器人智能聊天
                    $api_key = C('weixin_config.tuling_apikey');
                    if ($api_key) {
                        $api_url   = "http://www.tuling123.com/openapi/api";
                        $req_info  = $data['Content'];
                        $openid    = $data['FromUserName'];
                        $longitude = $user_bind_info['longitude'] * 1000000;
                        $latitude  = $user_bind_info['latitude'] * 1000000;

                        // 设置报文头, 构建请求报文
                        header("Content-type: text/html; charset=utf-8");

                        $url = $api_url
                            . "?key=$api_key"
                            . "&info=$req_info"
                            . "&userid=$openid"
                            . "&lon=$longitude"
                            . "&lat=$latitude";
                        $res     = json_decode(file_get_contents($url), true);
                        $message = html2text($res['text']);
                        if ($res['url']) {
                            $message .= "\r\n<a href='" . $res['url'] . "'>点击查看图片</a>";
                        } else if ($res['list']) {
                            foreach ($res['list'] as $key => $news) {
                                $message .= "\r\n" . $key++ . "、" . $news['source'] . "：<a href='" . $news['detailurl'] . "'>" . $news['article'] . "</a>";
                            }
                        }
                        $wechat->text($message)->reply();
                    } else {
                        $wechat->text(C('weixin_config.subscribe_msg'))->reply();
                    }
                }
                break;
            case \Wechat::MSGTYPE_IMAGE:
                break;
            case \Wechat::MSGTYPE_LOCATION:
                break;
            case \Wechat::MSGTYPE_LINK:
                break;
            case \Wechat::MSGTYPE_EVENT:
                switch ($data['Event']) {
                    case \Wechat::EVENT_SUBSCRIBE:
                        $wechat->text(C('weixin_config.subscribe_msg'))->reply();
                        break;
                    case \Wechat::EVENT_UNSUBSCRIBE:
                        $wechat->text(C('weixin_config.unsubscribe_msg'))->reply();
                        break;
                    case \Wechat::EVENT_SCAN:
                        break;
                    case \Wechat::EVENT_LOCATION:
                        // 存储用户的地理位置信息
                        $con['openid']    = $data['FromUserName'];
                        $add['latitude']  = $data['Latitude'];
                        $add['longitude'] = $data['Longitude'];
                        $add['precision'] = $data['Precision'];
                        $result           = D('UserBind')->where($con)->save($add);
                        break;
                    case \Wechat::EVENT_MENU_CLICK:
                        $map['keyword']    = $data['EventKey'];
                        $map['status']     = 1;
                        $custom_reply_info = D('weixin_custom_reply')->where($map)->find();
                        if ($custom_reply_info) {
                            switch ($custom_reply_info['reply_type']) {
                                case 'text':
                                    $wechat->text($custom_reply_info['content'])->reply();
                                    break;
                                case 'material':
                                    $key_list = explode(',', $custom_reply_info['reply_key']);
                                    foreach ($key_list as $key => $val) {
                                        $reply_info                = D('Material')->find($val);
                                        $news[$key]['Title']       = $reply_info['title'];
                                        $news[$key]['Description'] = $reply_info['abstract'];
                                        $news[$key]['PicUrl']      = get_cover($reply_info['cover']);
                                        $news[$key]['Url']         = 'http://' . $_SERVER['HTTP_HOST'] . U('Weixin/Material/detail/', array('id' => $reply_info['id']));
                                    }
                                    if ($news) {
                                        $wechat->news($news)->reply();
                                    }
                                    break;
                                case 'addon':
                                    $wechat->text($custom_reply_info['content'])->reply();
                                    break;
                            }
                        }
                        break;
                    case \Wechat::EVENT_MENU_VIEW:
                        break;
                }
                break;
            case \Wechat::MSGTYPE_MUSIC:
                break;
            case \Wechat::MSGTYPE_NEWS:
                break;
            case \Wechat::MSGTYPE_VOICE:
                break;
            case \Wechat::MSGTYPE_VIDEO:
                break;
        }
    }
}
