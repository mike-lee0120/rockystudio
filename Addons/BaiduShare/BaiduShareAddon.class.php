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
namespace Addons\BaiduShare;

use Common\Controller\Addon;

/**
 * 百度分享插件
 * @author jry
 */
class BaiduShareAddon extends Addon
{
    /**
     * 插件信息
     * @author jry <598821125@qq.com>
     */
    public $info = array(
        'name'        => 'BaiduShare',
        'title'       => '百度分享',
        'description' => '用户将网站内容分享到第三方网站',
        'status'      => 1,
        'author'      => '零云',
        'version'     => '1.6.2',
        'icon'        => 'fa fa-share-square-o',
        'icon_color'  => '#D80F16',
    );

    /**
     * 插件所需钩子
     * @author jry <598821125@qq.com>
     */
    public $hooks = array(
        '0' => 'BaiduShare',
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
     * 实现的BaiduShare钩子方法
     * @author jry <598821125@qq.com>
     */
    public function BaiduShare($param)
    {
        $this->assign('info', $param);
        $this->assign('addons_config', $this->getConfig());
        $this->display('share');
    }
}
