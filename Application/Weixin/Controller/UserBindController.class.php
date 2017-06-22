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
class UserBindController extends HomeController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 判断判断是微信浏览器自动跳转登录页面
        if (request()->isWeixin() && !(is_login())) {
            //加载微信SDK
            $options = array(
                'token'          => C('weixin_config.token'), //填写你设定的key
                'encodingaeskey' => C('weixin_config.crypt'), //填写加密用的EncodingAESKey
                'appid'          => C('weixin_config.appid'), //填写高级调用功能的app id, 请在微信开发模式后台查询
                'appsecret'      => C('weixin_config.appsecret'), //填写高级调用功能的密钥
            );
            $wechat = new \Wechat($options);

            // 重定向至微信登录页面
            $callback_uri = U('login', '', true, true); // 重要回调地址必须含有http
            $redirect_uri = $wechat->getOauthRedirect($callback_uri);
            redirect($redirect_uri, 1, '即将访问微信服务器');
        } else {
            redirect(U('Weixin/Index/index', '', true, true), 1, '不是微信内打开，转向首页');
        }
    }

    /**
     * 微信登录回调接口
     * @author jry <598821125@qq.com>
     */
    public function login()
    {
        //加载微信SDK
        $options = array(
            'token'          => C('weixin_config.token'), //填写你设定的key
            'encodingaeskey' => C('weixin_config.crypt'), //填写加密用的EncodingAESKey
            'appid'          => C('weixin_config.appid'), //填写高级调用功能的app id, 请在微信开发模式后台查询
            'appsecret'      => C('weixin_config.appsecret'), //填写高级调用功能的密钥
        );
        $wechat = new \Wechat($options);

        // 获取微信用户信息
        $user_token       = $wechat->getOauthAccessToken();
        $weixin_user_info = $wechat->getOauthUserinfo($user_token['access_token'], $user_token['openid']);

        // 验证openid
        if (!$weixin_user_info['openid']) {
            redirect(C('TOP_HOME_PAGE'), 1, '未获取到微信服务器返回的openid');
        }

        // 查询微信登录表是否已经有用户
        $con             = array();
        $con['status']   = 1;
        $con['openid']   = $weixin_user_info['openid'];
        $user_bind_model = D('UserBind');
        $exist_user      = $user_bind_model->where($con)->find();

        // 如果存在则直接自动登录否则跳转到绑定界面
        if ($exist_user) {
            $user_object         = D('User/User');
            $corethink_user_info = $user_object->find($exist_user['uid']);
            if ($corethink_user_info) {
                $result = $user_object->auto_login($corethink_user_info);
                redirect(C('TOP_HOME_PAGE'));
            } else {
                $user_bind_model->delete($exist_user['id']);
                $this->error('该用户已禁用或不存在！');
            }
        } else {
            session('weixin_user_info', $weixin_user_info);
            if (C('weixin_config.no_reg')) {
                redirect(U('Weixin/UserBind/register', '', true, true));
            } else {
                $this->assign('weixin_user_info', $weixin_user_info);
                $this->assign('meta_title', "微信登录");
                $this->display();
            }
        }
    }

    /**
     * 创建新用户
     * @author jry <598821125@qq.com>
     */
    public function register()
    {
        //上传头像，发现相同文件直接返回
        $weixin_user_info = session('weixin_user_info');
        if (!$weixin_user_info) {
            redirect(U('Weixin/UserBind/login', '', true, true));
        }

        // 验证openid
        if (!$weixin_user_info['openid']) {
            redirect(C('TOP_HOME_PAGE'));
        }

        // 再次验证当前openid是否是已注册用户
        $user_bind_model = D('UserBind');
        $con             = array();
        $con['status']   = 1;
        $con['openid']   = $weixin_user_info['openid'];
        $exist_user      = $user_bind_model->where($con)->find();
        if ($exist_user) {
            $user_object         = D('User/User');
            $corethink_user_info = $user_object->find($exist_user['uid']);
            if ($corethink_user_info) {
                $result = $user_object->auto_login($corethink_user_info);
                if ($result) {
                    if (request()->isAjax()) {
                        $this->success('登陆成功', C('TOP_HOME_PAGE'));
                    } else {
                        redirect(C('TOP_HOME_PAGE'));
                    }
                } else {
                    redirect(C('TOP_HOME_PAGE'));
                }
            } else {
                $user_bind_model->delete($exist_user['id']);
                $this->error('该用户已禁用或不存在！');
            }
        }

        // 注册用户
        $username = 'U' . time();
        $password = $weixin_user_info['openid'];

        // 构造注册数据
        $reg_data              = array();
        $reg_data['user_type'] = 1;
        $reg_data['nickname']  = $weixin_user_info['nickname'];
        $reg_data['username']  = $username;
        $reg_data['password']  = cut_str($password, 30);
        $reg_data['reg_type']  = 'weixin';
        $reg_data['avatar']    = I('avatar');
        $user_object           = D('User/User');
        $user_data             = $user_object->create($reg_data);
        if ($user_data) {
            // 新增用户
            $uid = $user_object->add($user_data);
            if (!$uid) {
                $this->error('新增用户失败' . $user_object->getError(), C('TOP_HOME_PAGE'));
            }

            // 新增微信登录账号
            $weixin_data           = array();
            $weixin_data['uid']    = $uid;
            $weixin_data['openid'] = $weixin_user_info['openid'];
            $weixin_data           = $user_bind_model->create($weixin_data);
            if (!$weixin_data) {
                $this->error('错误' . $user_bind_model->getError(), C('TOP_HOME_PAGE'));
            }

            // 新增微信绑定记录
            $result = $user_bind_model->add($weixin_data);

            // 微信绑定记录新增失败
            if (!$result) {
                $user_object->delete($uid);
                redirect(C('TOP_HOME_PAGE'));
            }

            //登录用户
            $user_info = $user_object->find($uid);
            if (!$user_info) {
                $this->error('错误' . $user_object->getError(), C('TOP_HOME_PAGE'));
            }
            $res = D('User/User')->auto_login($user_info);
            if ($res) {
                session('weixin_user_info', null);
                if (request()->isAjax()) {
                    $this->success('注册成功', C('TOP_HOME_PAGE'));
                } else {
                    redirect(C('TOP_HOME_PAGE'));
                }
            } else {
                redirect(C('TOP_HOME_PAGE'));
            }
        } else {
            $this->error('注册失败' . $user_object->getError(), C('TOP_HOME_PAGE'));
        }
    }

    /**
     * 绑定用户
     * @author jry <598821125@qq.com>
     */
    public function bind()
    {
        $username    = $_POST['username'];
        $password    = $_POST['password'];
        $user_object = D('User/User');
        $uid         = $user_object->login($username, $password);
        if ($uid > 0) {
            //新增微信登录账号
            $weixin_user_info      = session('weixin_user_info');
            $weixin_data['uid']    = $uid;
            $weixin_data['openid'] = $weixin_user_info['openid'];
            $weixin_login          = D('UserBind');
            $weixin_data           = $weixin_login->create($weixin_data);
            if ($weixin_data) {
                $result = $weixin_login->add($weixin_data);
                if ($result) {
                    session('weixin_user_info', null);
                    $this->success('微信账号绑定成功', cookie('forward') ?: C('HOME_PAGE'));
                }
            } else {
                $this->error('错误' . $weixin_login->getError());
            }
        } else {
            $this->error('绑定失败' . $user_object->getError()); // 绑定失败
        }
    }
}
