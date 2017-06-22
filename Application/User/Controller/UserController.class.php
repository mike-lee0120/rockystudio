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
namespace User\Controller;

use Home\Controller\HomeController;

/**
 * 用户控制器
 * @author jry <598821125@qq.com>
 */
class UserController extends HomeController
{

    /**
     * 用户登录检测(APP用)
     * @author jry <598821125@qq.com>
     */
    public function islogin()
    {
        //用户登录检测
        $uid = is_login();
        if ($uid) {
            $this->success('您已登录系统', C('TOP_HOME_PAGE'), array('uid' => $uid));
        } else {
            $this->error('请先登录系统', U('User/User/login', '', true, true), array('login' => 1));
        }
    }

    /**
     * 登陆
     * @author jry <598821125@qq.com>
     */
    public function login()
    {
        if (is_login()) {
            $this->error("您已登录系统", cookie('forward') ?: C('HOME_PAGE'), array('is_login' => '1'));
        }
        if (request()->isPost()) {
            $account  = I('account');
            $password = I('password');
            if (!$account) {
                $this->error('请输入账号！');
            }
            if (!$password) {
                $this->error('请输入密码！');
            }
            $user_object = D('User/User');
            $uid         = $user_object->login($account, $password);
            if ($uid) {
                $this->success('登录成功！', cookie('forward') ?: C('HOME_PAGE'));
            } else {
                $this->error($user_object->getError());
            }
        } else {
            $this->assign('meta_title', '用户登录');
            $this->display();
        }
    }

    /**
     * 注销
     * @author jry <598821125@qq.com>
     */
    public function logout()
    {
        $logout = true;

        // 注销时是否含有推送token
        if (C('IS_API')) {
            $push_object = D('User/MessagePush');
            $exist       = $push_object->where(array('session_id' => session_id(), 'uid' => $uid))->count();
            if ($exist['token']) {
                $logout = $push_object->where(array('uid' => $uid))->setField('token', '');
            }
        }

        // 注销
        if ($logout) {
            session('user_auth', null);
            session('user_auth_sign', null);
            if (C('SESSION_OPTIONS.type') === 'Sql') {
                $res = M('admin_session')->where(array('session_id' => session_id()))->setField('uid', '0');
            }
            $this->success('注销成功！', cookie('forward') ?: C('HOME_PAGE'));
        } else {
            $this->error('注销出错' . $push_object->getError());
        }
    }

    /**
     * 用户注册
     * @author jry <598821125@qq.com>
     */
    public function register()
    {
        if (request()->isPost()) {
            if (!C('user_config.reg_toggle')) {
                $this->error('注册已关闭！');
            }
            $reg_type = I('post.reg_type');
            if (!in_array($reg_type, C('user_config.allow_reg_type'))) {
                $this->error('该注册方式已关闭，请选择其它方式注册！');
            }
            $reg_data = array();
            switch ($reg_type) {
                case 'username': //用户名注册
                    //图片验证码校验
                    if (!$this->check_verify(I('post.verify'))) {
                        $this->error('验证码输入错误！');
                    }
                    if (I('post.email')) {
                        $reg_data['email'] = I('post.email');
                    }
                    if (I('post.mobile')) {
                        $reg_data['mobile'] = I('post.mobile');
                    }
                    break;
                case 'email': //邮箱注册
                    // 验证码60秒认为是过期的
                    if ((time() - session('reg_verify_ctime')) > 60) {
                        $this->error('验证码已过期，请重新发送！');
                    }

                    //验证码严格加盐加密验证
                    if (user_md5(I('post.verify'), I('post.email')) !== session('reg_verify')) {
                        $this->error('验证码错误！');
                    }
                    $_POST['username']      = I('post.username') ? I('post.username') : 'U' . time();
                    $reg_data['email']      = I('post.email');
                    $reg_data['email_bind'] = 1;
                    if (I('post.mobile')) {
                        $reg_data['mobile'] = I('post.mobile');
                    }
                    break;
                case 'mobile': //手机号注册
                    // 验证码60秒认为是过期的
                    if ((time() - session('reg_verify_ctime')) > 60) {
                        $this->error('验证码已过期，请重新发送！');
                    }

                    //验证码严格加盐加密验证
                    if (user_md5(I('post.verify'), I('post.mobile')) !== session('reg_verify')) {
                        $this->error('验证码错误！');
                    }
                    $_POST['username']       = I('post.username') ? I('post.username') : 'U' . time();
                    $reg_data['mobile']      = I('post.mobile');
                    $reg_data['mobile_bind'] = 1;
                    if (I('post.email')) {
                        $reg_data['email'] = I('post.email');
                    }
                    break;
            }

            // 构造注册数据
            $reg_data['user_type'] = I('post.user_type') ? I('post.user_type') : 1;
            $reg_data['nickname']  = I('post.nickname') ? I('post.nickname') : I('post.username');
            $reg_data['username']  = I('post.username');
            $reg_data['password']  = I('post.password');
            if ($_POST['repassword']) {
                $reg_data['repassword'] = $_POST['repassword'];
            }
            $reg_data['reg_type'] = I('post.reg_type');
            $user_object          = D('User/User');
            $data                 = $user_object->create($reg_data);
            if ($data) {
                $id = $user_object->add($data);
                if ($id) {
                    session('reg_verify', null);
                    $user_info = $user_object->login($data['username'], I('post.password'), true, true);

                    // 构造消息数据
                    $msg_data['to_uid']  = $user_info['id'];
                    $msg_data['title']   = '注册成功';
                    $msg_data['content'] = '少侠/女侠好：<br>'
                    . '恭喜您成功注册' . C('WEB_SITE_TITLE') . '的帐号<br>'
                        . '您的帐号信息如下（请妥善保管）：<br>'
                        . 'UID：' . $user_info['id'] . '<br>'
                        . '昵称：' . $user_info['nickname'] . '<br>'
                        . '用户名：' . $user_info['username'] . '<br>';
                    D('User/Message')->sendMessage($msg_data);
                    if (request()->isMobile()) {
                        $url = U('/user', '', true, true);
                    } else {
                        $url = U('User/User/register2', '', true, true);
                    }
                    $this->success('注册成功', $url);
                } else {
                    $this->error('注册失败' . $user_object->getError());
                }
            } else {
                $this->error('注册失败' . $user_object->getError());
            }
        } else {
            if (is_login()) {
                $this->error("您已登陆系统", cookie('forward') ?: C('HOME_PAGE'));
            }
            $this->assign('meta_title', '用户注册');
            $this->display();
        }
    }

    /**
     * 用户注册2
     * @author jry <598821125@qq.com>
     */
    public function register2()
    {
        $uid = $this->is_login();
        if (request()->isPost()) {
            if ($_POST) {
                if (!$_POST['avatar']['src'] || !$_POST['avatar']['w'] || !$_POST['avatar']['h'] || $_POST['avatar']['x'] === '' || $_POST['avatar']['y'] === '') {
                    $this->error('参数不完整');
                }
                $result = D('Admin/Upload')->crop($_POST['avatar']);
                if ($result['error'] != 1) {
                    $user_object = D('User/User');
                    $result      = $user_object->where(array('id' => $uid))->setField('avatar', $result['id']);
                    if ($result) {
                        $this->success('头像设置成功', U('User/Center/profile'));
                    } else {
                        $this->error('头像设置失败' . $user_object->getError());
                    }
                } else {
                    $this->error('头像保存失败');
                }
            } else {
                $this->error('请选择文件');
            }
        } else {
            $this->assign('user_info', D('User/User')->detail($uid));
            $this->assign('meta_title', '设置头像');
            $this->display();
        }
    }

    /**
     * 密码重置
     * @author jry <598821125@qq.com>
     */
    public function reset_password()
    {
        if (request()->isPost()) {
            $reg_type = I('post.reg_type');
            switch ($reg_type) {
                case 'email':
                    $username                = I('post.email');
                    $condition['email']      = I('post.email');
                    $condition['email_bind'] = 1;
                    $condition['status']     = 1;
                    break;
                case 'mobile':
                    $username                 = I('post.mobile');
                    $condition['mobile']      = I('post.mobile');
                    $condition['mobile_bind'] = 1;
                    $condition['status']      = 1;
                    break;
            }

            // 验证码60秒认为是过期的
            if ((time() - session('reg_verify_ctime')) > 60) {
                $this->error('验证码已过期，请重新发送！');
            }

            //验证码严格加盐加密验证
            if (user_md5(I('post.verify'), $username) !== session('reg_verify')) {
                $this->error('验证码错误！');
            }

            $validate = array(
                array('password', '6,30', '密码长度为6-30位', 1, 'length'),
                array('password', '/(?!^(\d+|[a-zA-Z]+|[~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+)$)^[\w~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+$/', '密码至少由数字、字符、特殊字符三种中的两种组成', 1, 'regex'),
            );
            $user_object = D('User/User');
            $user_object->setProperty("_validate", $validate);
            $data = $user_object->create($_POST); //调用自动验证
            if (!$data) {
                $this->error($user_object->getError());
            }

            // 查找此用户
            $exist = $user_object->where($condition)->find();
            if (!$exist) {
                $this->error('该邮箱不存在或未认证');
            }

            $result = $user_object
                ->where($condition)
                ->setField('password', $data['password']); //重置密码
            if ($result === false) {
                $this->error('密码重置失败' . $user_object->getError());
            }
            $user_info = $user_object->login($username, I('post.password'), true, true); //自动登录

            // 发送消息
            if (is_array($user_info)) {
                // 构造消息数据
                $msg_data['to_uid']  = $user_info['id'];
                $msg_data['title']   = '密码重置成功';
                $msg_data['content'] = '少侠/女侠好：<br>'
                . '恭喜您成功重置您在' . C('WEB_SITE_TITLE') . '的帐号密码<br>'
                    . '您的帐号信息如下（请妥善保管）：<br>'
                    . 'UID：' . $user_info['id'] . '<br>'
                    . '昵称：' . $user_info['nickname'] . '<br>'
                    . '用户名：' . $user_info['username'] . '<br>'
                    . '<br>';
                D('User/Message')->sendMessage($msg_data);
                $this->success('密码重置成功', C('HOME_PAGE'));
            } else {
                $this->error('密码重置失败');
            }
        } else {
            $this->assign('meta_title', '密码重置');
            $this->display();
        }
    }

    /**
     * 图片验证码生成，用于登录和注册
     * @author jry <598821125@qq.com>
     */
    public function verify($vid = 1)
    {
        $verify_config = array(
            'fontSize' => 30,
            'length'   => 4,
            'useNoise' => true,
            'expire'   => 60,
        );
        $verify = new \lyf\Verify($verify_config);
        $verify->entry($vid);
    }

    /**
     * 检测验证码
     * @param  integer $id 验证码ID
     * @return boolean 检测结果
     */
    public function check_verify($code, $vid = 1)
    {
        $verify = new \lyf\Verify();
        return $verify->check($code, $vid);
    }

    /**
     * 邮箱验证码，用于注册
     * @author jry <598821125@qq.com>
     */
    public function send_mail_verify()
    {
        if ((time() - session('reg_verify_ctime')) < 30) {
            $this->error('30秒内不能重复发送');
        }

        // 生成验证码
        $reg_verify = \lyf\Str::randString(6, 1);
        session('reg_verify', user_md5($reg_verify, I('post.email')));

        // 构造邮件数据
        $mail_data['receiver'] = I('post.email');
        $mail_data['title']    = I('post.title') ?: '验证身份';
        $mail_data['content']  = '您好：<br>听闻您正使用该邮箱' . I('post.email') . $mail_data['title'] . '，请在验证码输入框中输入：
        <span style="color:red;font-weight:bold;">' . $reg_verify . '</span>，以完成操作。<br>
        注意：此操作可能会修改您的密码、登录邮箱或绑定手机。如非本人操作，请及时登录并修改
        密码以保证帐户安全 （工作人员不会向您索取此验证码，请勿泄漏！)';
        $email_addon = D('Addons://Email/Email');
        $result      = $email_addon->send($mail_data);

        // 发送邮件
        if ($result) {
            session('reg_verify_ctime', time());
            $this->success('发送成功，请登陆邮箱查收！');
        } else {
            $this->error('发送失败！' . $email_addon->getError());
        }
    }

    /**
     * 短信验证码，用于注册
     * @author jry <598821125@qq.com>
     */
    public function send_mobile_verify()
    {
        if ((time() - session('reg_verify_ctime')) < 30) {
            $this->error('30秒内不能重复发送');
        }

        // 生成验证码
        $reg_verify = \lyf\Str::randString(6, 1);
        session('reg_verify', user_md5($reg_verify, I('post.mobile')));

        // 构造短信数据
        $sms_data['RecNum']  = I('post.mobile');
        $sms_data['code']    = $reg_verify;
        $sms_data['product'] = I('post.title') ? I('post.title') : C('WEB_SITE_TITLE');
        //$sms_data['SmsFreeSignName'] = '注册验证';
        //$sms_data['SmsTemplateCode'] = '';
        $alidayu_addon = D('Addons://Alidayu/Alidayu');
        $result        = $alidayu_addon->send($sms_data);
        if ($result) {
            session('reg_verify_ctime', time());
            $this->success('发送成功，请查收！');
        } else {
            $this->error('发送失败！' . $alidayu_addon->getError());
        }
    }
}
