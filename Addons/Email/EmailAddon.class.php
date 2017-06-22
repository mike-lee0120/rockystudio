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
namespace Addons\Email;

use Common\Controller\Addon;

/**
 * 邮件插件
 * @author jry <598821125@qq.com>
 */
class EmailAddon extends Addon
{
    /**
     * 插件信息
     * @author jry <598821125@qq.com>
     */
    public $info = array(
        'name'        => 'Email',
        'title'       => '邮件插件',
        'description' => '实现系统发邮件功能',
        'status'      => 1,
        'author'      => '零云',
        'version'     => '1.6.2',
        'icon'        => 'fa fa-envelope-o',
        'icon_color'  => '#FF6600',
    );

    /**
     * 插件所需钩子
     * @author jry <598821125@qq.com>
     */
    public $hooks = array(
        '0' => 'SendMessage',
    );

    /**
     * 自定义插件后台
     * @author jry <598821125@qq.com>
     */
    //public $custom_adminlist = './Addons/Email/admin.html';

    /**
     * 插件后台数据表配置
     * @author jry <598821125@qq.com>
     */
    public $admin_list = array(
        '1' => array(
            'title' => '邮件列表',
            'model' => 'Email',
        ),
    );

    /**
     * 插件安装方法
     * @author jry <598821125@qq.com>
     */
    public function install()
    {
        return true;
    }

    /**
     * 插件卸载方法
     * @author jry <598821125@qq.com>
     */
    public function uninstall()
    {
        return true;
    }

    /**
     * 发送消息钩子
     * @author jry <598821125@qq.com>
     */
    public function SendMessage($param = null)
    {
        $user_info = D('Admin/User')->getUserInfo($param['to_uid']);
        if ($user_info['email'] && $user_info['email_bind']) {
            $param['receiver'] = $user_info['email'];
            $email_object      = D('Addons://Email/Email');
            $data              = $email_object->create($param);
            if ($data) {
                $con                = array();
                $con['title']       = $data['title'];
                $con['content']     = $data['content'];
                $con['receiver']    = $data['receiver'];
                $con['to_uid']      = $data['to_uid'];
                $con['create_time'] = array('gt', time() - 3);
                $exist              = $email_object->where($con)->count();
                if ($exist > 0) {
                    return false;
                }
                $email_object->add($data);
            }
        }
    }
}
