<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Addons\Sign\Controller;

use Home\Controller\AddonController;

/**
 * 签到控制器
 */
class SignController extends AddonController
{
    /**
     * 签到地址
     */
    public function sign()
    {
        if (is_dir(APP_DIR . 'User') && (D('Admin/Module')->where('name="User" and status="1"')->count())) {
            $addon_config = \Common\Controller\Addon::getConfig('Sign');
            $sign_object  = D('Addons://Sign/Sign');
            if (!$uid = is_login()) {
                $this->error('请登录', U('User/User/login'));
            }
            $ret = $sign_object->sign($uid);
            if (!$ret) {
                $this->error($sign_object->getError());
            }
            $this->success('签到成功，积分+' . $addon_config['score']);
        } else {
            $this->error('请安装用户模块');
        }

    }
}
