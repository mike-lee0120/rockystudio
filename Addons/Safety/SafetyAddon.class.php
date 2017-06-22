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
namespace Addons\Safety;

use Common\Controller\Addon;

/**
 * 帐号安全提示插件
 * @author thinkphp
 */
class SafetyAddon extends Addon
{
    /**
     * 插件信息
     * @author jry <598821125@qq.com>
     */
    public $info = array(
        'name'        => 'Safety',
        'title'       => '帐号安全提示插件',
        'description' => '帐号安全提示插件',
        'status'      => 1,
        'author'      => '零云',
        'version'     => '1.6.2',
        'icon'        => 'fa fa-check-circle',
        'icon_color'  => '#FA7A8D',
    );

    /**
     * 插件所需钩子
     * @author jry <598821125@qq.com>
     */
    public $hooks = array(
        '0' => 'PageHeader',
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
     * 实现的PageHeader钩子方法
     * @author jry <598821125@qq.com>
     */
    public function PageHeader($param)
    {
        //检查插件是否开启
        $config = $this->getConfig();
        $uid    = is_login();
        if ($uid) {
            $user_info   = D('Admin/User')->getUserInfo($uid);
            $current_url = request()->module() . '/' . request()->controller() . '/' . request()->action();
            if ($config['status'] && $config['email'] && !$user_info['email_bind']) {
                if ($config['force_email'] && 'User/Safety/bind' !== $current_url) {
                    redirect(U('User/Safety/bind', '', true, true), 2, '为了您的账户安全，请先绑定邮箱，页面跳转中...');
                } else {
                    $demo = '<div class="alert alert-danger m-a-0 b-r-0 text-center"><i class="fa fa-bullhorn"></i> 重要：您尚未绑定邮箱账号，<a href="' . U('User/Safety/index', '', true, true) . '">点击绑定</a>！！！</div>';
                    echo $demo;
                }
            }
            if ($config['status'] && $config['mobile'] && !$user_info['mobile_bind']) {
                if ($config['force_mobile'] && 'User/Safety/bind' !== $current_url) {
                    redirect(U('User/Safety/bind', '', true, true), 2, '为了您的账户安全，请先绑定手机号，页面跳转中...');
                } else {
                    $demo = '<div class="alert alert-danger m-a-0 b-r-0 text-center"><i class="fa fa-bullhorn"></i> 重要：您尚未绑定手机账号，<a href="' . U('User/Safety/index', '', true, true) . '">点击绑定</a>！！！</div>';
                    echo $demo;
                }
            }
        }
    }
}
