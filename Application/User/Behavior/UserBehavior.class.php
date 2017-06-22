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
namespace User\Behavior;

use Think\Behavior;

defined('THINK_PATH') or exit();

/**
 * 用户消息
 * @author jry <598821125@qq.com>
 */
class UserBehavior extends Behavior
{
    /**
     * 行为扩展的执行入口必须是run
     * @author jry <598821125@qq.com>
     */
    public function run(&$content)
    {
        $uid = is_login();
        if ($uid) {
            // 获取用户未读消息数量
            $_new_message = D('User/Message')->newMessageCount();
            if (D('Admin/Module')->where('name="Im" and status="1"')->count()) {
                $_new_message = $_new_message + D('Im/Message')->newTalkCount();
            }

            cookie('_new_message', $_new_message ?: null, array('path' => __ROOT__));

            // 更新session用户信息
            if ((time() - session('user_auth_expire')) > 60) {
                $user_info = D('Admin/User')->getUserInfo($uid);
                if (D('User/User')->auto_login($user_info)) {
                    session('user_auth_expire', time());
                }
            }
        }
    }
}
