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
namespace Addons\SyncLogin;

use Common\Controller\Addon;

/**
 * 同步登陆插件
 * @author jry <598821125@qq.com>
 */
class SyncLoginAddon extends Addon
{
    /**
     * 插件信息
     * @author jry <598821125@qq.com>
     */
    public $info = array(
        'name'        => 'SyncLogin',
        'title'       => '第三方账号登陆',
        'description' => '第三方账号登陆',
        'status'      => 1,
        'author'      => '零云',
        'version'     => '1.6.2',
        'icon'        => 'fa fa-openid',
        'icon_color'  => '#6CC4B3',
    );

    /**
     * 插件所需钩子
     * @author jry <598821125@qq.com>
     */
    public $hooks = array(
        '0' => 'SyncLogin',
    );

    /**
     * 自定义插件后台
     * @author jry <598821125@qq.com>
     */
    //public $custom_adminlist = './Addons/SyncLogin/admin.html';

    /**
     * 插件后台数据表配置
     * @author jry <598821125@qq.com>
     */
    public $admin_list = array(
        '1' => array(
            'title' => '第三方登录列表',
            'model' => 'sync_login',
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
     * 登录按钮钩子
     * @author jry <598821125@qq.com>
     */
    public function SyncLogin($param)
    {
        $this->assign($param);
        $config = $this->getConfig();
        $this->assign('config', $config);
        $this->display('login');
    }

    /**
     * meta代码钩子
     * @author jry <598821125@qq.com>
     */
    public function PageHeader($param)
    {
        $platform_options = $this->getConfig();
        echo $platform_options['meta'];
    }
}
