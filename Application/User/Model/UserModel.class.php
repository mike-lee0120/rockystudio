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
namespace User\Model;

use Common\Model\Model;

/**
 * 用户模型
 * @author jry <598821125@qq.com>
 */
class UserModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'admin_user';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        // 验证用户类型
        array('user_type', 'require', '请选择用户类型', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),

        //验证用户名
        array('nickname', 'require', '昵称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),

        // 验证用户名
        array('username', 'require', '请填写用户名', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('username', '3,32', '用户名长度为1-32个字符', self::MUST_VALIDATE, 'length', self::MODEL_INSERT),
        array('username', '', '用户名被占用', self::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
        array('username', '/^(?!_)(?!\d)(?!.*?_$)[\w]+$/', '用户名只可含有数字、字母、下划线且不以下划线开头结尾，不以数字开头！', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('username', 'checkDenyMember', '该用户名禁止使用', self::EXISTS_VALIDATE, 'callback'), //用户名禁止注册

        // 验证密码
        array('password', 'require', '请填写密码', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('password', '6,30', '密码长度为6-30位', self::MUST_VALIDATE, 'length', self::MODEL_INSERT),
        array('password', '/(?!^(\d+|[a-zA-Z]+|[~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+)$)^[\w~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+$/', '密码至少由数字、字符、特殊字符三种中的两种组成', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('repassword', 'password', '两次输入的密码不一致', self::EXISTS_VALIDATE, 'confirm', self::MODEL_INSERT),

        // 验证邮箱
        array('email', 'email', '邮箱格式不正确', self::EXISTS_VALIDATE, 'regex', self::MODEL_INSERT),
        array('email', '1,32', '邮箱长度为1-32个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_INSERT),
        array('email', '', '邮箱被占用', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),

        // 验证手机号码
        array('mobile', '/^1\d{10}$/', '手机号码格式不正确', self::EXISTS_VALIDATE, 'regex', self::MODEL_INSERT),
        array('mobile', '', '手机号被占用', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),

        // 验证注册来源
        array('reg_type', 'require', '注册来源不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('score', '0', self::MODEL_INSERT),
        array('money', '0', self::MODEL_INSERT),
        array('reg_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
        array('password', 'user_md5', self::MODEL_BOTH, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_find(&$result, $options)
    {
        // 获取用户头像地址
        $result['avatar_url'] = get_cover($result['avatar'], 'avatar');

        // 用户识别label
        $cert_info = D('User/Cert')->isCert($result['id']);
        if ($cert_info) {
            $result['label'] = $cert_info['cert_title'] . '(' . $result['id'];
        } else {
            $result['label'] = $result['nickname'] . '(' . $result['id'];
        }
        if ($result['email']) {
            $result['label'] = $result['label'] . '-' . $result['email'];
        }
        $result['label'] = $result['label'] . ')';
    }

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_select(&$result, $options)
    {
        foreach ($result as &$record) {
            $this->_after_find($record, $options);
        }
    }

    /**
     * 用户性别
     * @author jry <598821125@qq.com>
     */
    public function user_gender($id)
    {
        $list[0]  = '其他';
        $list[1]  = '男';
        $list[-1] = '女';
        return isset($id) ? $list[$id] : $list;
    }

    /**
     * 用户性别图标
     * @author jry <598821125@qq.com>
     */
    public function user_gender_icon($id)
    {
        $list[0]  = '<i class="fa fa-genderless"></i>';
        $list[1]  = '<i class="fa fa-mars text-primary color-blue"></i>';
        $list[-1] = '<i class="fa fa-venus text-danger color-pink"></i>';
        return isset($id) ? $list[$id] : '';
    }

    /**
     * 检测用户名是不是被禁止注册
     * @param  string $username 用户名
     * @return boolean ture 未禁用，false 禁止注册
     */
    protected function checkDenyMember($username)
    {
        $deny = C('user_config.deny_username');
        $deny = explode(',', $deny);
        foreach ($deny as $k => $v) {
            if (stristr($username, $v)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 用户登录
     * @author jry <598821125@qq.com>
     */
    public function login($account, $password, $auto_login = true, $return = false)
    {
        if (is_login()) {
            $this->error = '已登录！';
            return false;
        }

        //去除前后空格
        $account = trim($account);

        //匹配登录方式
        if (preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $account)) {
            $map['email']      = array('eq', $account); // 邮箱登陆
            $map['email_bind'] = array('eq', 1);
        } elseif (preg_match("/^1\d{10}$/", $account)) {
            $map['mobile']      = array('eq', $account); // 手机号登陆
            $map['mobile_bind'] = array('eq', 1);
        } else {
            $map['username'] = array('eq', $account); // 用户名登陆
        }

        $map['status'] = array('eq', 1);
        $user_info     = $this->where($map)->find(); //查找用户
        if (!$user_info) {
            $this->error = '用户不存在或被禁用！';
        } else {
            if (user_md5($password) !== $user_info['password']) {
                $this->error = '密码错误！';
            } else {
                if ($auto_login) {
                    if ($this->auto_login($user_info)) {
                        // 登录记录UID
                        if (C('SESSION_OPTIONS.type') === 'Sql') {
                            $res = M('admin_session')->where(array('session_id' => session_id()))->setField('uid', $user_info['id']);
                        }
                        if ($return) {
                            // 记录登录日志
                            $login_log_object = D('User/LoginLog');
                            $login_log_data   = $login_log_object->create();
                            $login_log_result = $login_log_object->add($login_log_data);
                            return $user_info;
                        } else {
                            return $user_info['id'];
                        }
                    }
                } else {
                    return $user_info['id'];
                }
            }
        }
        return false;
    }

    /**
     * 设置登录状态
     * @author jry <598821125@qq.com>
     */
    public function auto_login($user_info)
    {
        // VIP信息
        $sql   = 'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = "' . C('DB_NAME') . '" AND table_name = "' . C('DB_PREFIX') . 'vip_index";';
        $exist = M('')->query($sql);
        if ($exist[0]['COUNT(*)'] === '1') {
            $vip = D('Vip/Index')->vipInfo($user_info['id']);
        }

        // 记录登录SESSION和COOKIES
        $auth = array(
            'uid'        => $user_info['id'],
            'username'   => $user_info['username'],
            'nickname'   => $user_info['nickname'],
            'avatar'     => $user_info['avatar'],
            'avatar_url' => get_cover($user_info['avatar'], 'avatar'),
            'vip'        => $vip,
        );
        session('user_auth', $auth);
        session('user_auth_sign', $this->data_auth_sign($auth));
        if (session('user_auth')) {
            // 记录UID
            if (C('SESSION_OPTIONS.type') === 'Sql') {
                $res = M('admin_session')->where(array('session_id' => session_id()))->setField('uid', $user_info['id']);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 密码认证
     * @author jry <598821125@qq.com>
     */
    public function password_auth($password)
    {
        $user_info = $this->find(is_login());
        if ($user_info) {
            if ($user_info['password'] === user_md5($password)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 注销
     * @author jry <598821125@qq.com>
     */
    public function logout()
    {
        session('user_auth', null);
        session('user_auth_sign', null);
        if (C('SESSION_OPTIONS.type') === 'Sql') {
            $res = M('admin_session')->where(array('session_id' => session_id()))->setField('uid', '0');
        }
        return true;
    }

    /**
     * 数据签名认证
     * @param  array  $data 被认证的数据
     * @return string       签名
     * @author jry <598821125@qq.com>
     */
    public function data_auth_sign($data)
    {
        // 数据类型检测
        if (!is_array($data)) {
            $data = (array) $data;
        }
        ksort($data); //排序
        $code = http_build_query($data); // url编码并生成query字符串
        $sign = sha1($code); // 生成签名
        return $sign;
    }

    /**
     * 获取用户详情
     * @author jry <598821125@qq.com>
     */
    public function detail($id)
    {
        if ($id <= 0) {
            return false;
            $this->error = 'UID为空';
        }

        //获取基础表信息
        $user_info = $this->find($id);
        if (!(is_array($user_info) || 1 !== $user_info['status'])) {
            $this->error = '用户被禁用或已删除！';
            return false;
        }

        // 获取用户扩展信息
        $user_attr_model = D('User/UserAttr');
        $attr_list       = D('User/Attribute')->where(array('type_id' => $user_info['user_type']))->select();
        if ($attr_list) {
            $extend_info = array();
            foreach ($attr_list as $k => $v) {
                $extend_info[$v['name']] = $user_attr_model->where(array('uid' => $id, 'attr_id' => $v['id']))->getField('value');
            }
            if ($extend_info) {
                $user_info = array_merge($extend_info, $user_info);
            }
        }

        // 获取粉丝关注性别等信息
        if (isset($user_info['gender'])) {
            $user_info['gender_icon'] = $this->user_gender_icon($user_info['gender']);
            $user_info['gender_text'] = $this->user_gender($user_info['gender']);
        }
        $user_info['fans_count']   = D('User/Follow')->fansCount();
        $user_info['follow_count'] = D('User/Follow')->followCount();

        // VIP信息
        $sql   = 'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = "' . C('DB_NAME') . '" AND table_name = "' . C('DB_PREFIX') . 'vip_index";';
        $exist = M('')->query($sql);
        if ($exist[0]['COUNT(*)'] === '1') {
            $user_info['vip'] = D('Vip/Index')->vipInfo($user_info['id']);
        }

        // 实名认证信息
        $user_info['cert_info'] = D('User/Cert')->isCert($user_info['id']);

        return $user_info;
    }
}
