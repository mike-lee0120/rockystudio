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
namespace Addons\Weather;

use Common\Controller\Addon;

/**
 * 天气插件
 * @author cepljxiongjun
 */
class WeatherAddon extends Addon
{
    /**
     * 插件信息
     * @author jry <598821125@qq.com>
     */
    public $info = array(
        'name'        => 'Weather',
        'title'       => '天气预报',
        'description' => '天气预报',
        'status'      => 1,
        'author'      => '零云',
        'version'     => '1.6.2',
        'icon'        => 'fa fa-sun-o',
        'icon_color'  => '#FF9933',
    );

    /**
     * 插件所需钩子
     * @author jry <598821125@qq.com>
     */
    public $hooks = array(
        '0' => 'AdminIndex',
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
     * 实现的AdminIndex钩子方法
     * @author jry <598821125@qq.com>
     */
    public function AdminIndex()
    {
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        if ($config['status']) {
            $this->display('widget');
        }
    }
}
