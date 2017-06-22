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
namespace Addons\Upload;

use Common\Controller\Addon;

/**
 * 云存储插件
 * @author jry 598821125@qq.com
 */
class UploadAddon extends Addon
{
    /**
     * 插件信息
     * @author jry <598821125@qq.com>
     */
    public $info = array(
        'name'        => 'Upload',
        'title'       => '云存储插件',
        'description' => '支持七牛云、又拍云、新浪Sae、百度云、贴图库、FTP等平台',
        'status'      => 1,
        'author'      => '零云',
        'version'     => '1.6.2',
        'icon'        => 'fa fa-cloud-upload',
        'icon_color'  => '#45BBF4',
    );

    /**
     * 插件所需钩子
     * @author jry <598821125@qq.com>
     */
    public $hooks = array(
        '0' => 'UploadFile',
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
     * 实现的UploadFiler钩子方法
     * @author jry <598821125@qq.com>
     */
    public function UploadFile($param)
    {
        //检查插件是否开启
        $config = $this->getConfig();
        if ($config['status']) {
            C('UPLOAD_DRIVER', $config['type']);
            switch ($config['type']) {
                case 'Qiniu':
                    $driver_config['accessKey'] = $config[$config['type'] . '_accessKey'];
                    $driver_config['secretKey'] = $config[$config['type'] . '_secretKey'];
                    $driver_config['domain']    = $config[$config['type'] . '_domain'];
                    $driver_config['bucket']    = $config[$config['type'] . '_bucket'];
                    break;
                case 'Upyun':
                    $driver_config['host']     = $config[$config['type'] . '_host'];
                    $driver_config['username'] = $config[$config['type'] . '_username'];
                    $driver_config['password'] = $config[$config['type'] . '_password'];
                    $driver_config['bucket']   = $config[$config['type'] . '_bucket'];
                    break;
                case 'Sae':
                    $driver_config['domain'] = $config[$config['type'] . '_domain'];
                    break;
                case 'Bcs':
                    $driver_config['AccessKey'] = $config[$config['type'] . '_host'];
                    $driver_config['SecretKey'] = $config[$config['type'] . '_SecretKey'];
                    $driver_config['bucket']    = $config[$config['type'] . '_bucket'];
                    break;
                case 'Tietuku':
                    $driver_config['host']     = $config[$config['type'] . '_host'];
                    $driver_config['username'] = $config[$config['type'] . '_username'];
                    $driver_config['password'] = $config[$config['type'] . '_password'];
                    break;
                case 'Ftp':
                    $driver_config['accessKey'] = $config[$config['type'] . '_accessKey'];
                    $driver_config['secretKey'] = $config[$config['type'] . '_secretKey'];
                    $driver_config['aid']       = $config[$config['type'] . '_aid'];
                    break;
            }
            C("UPLOAD_" . $config['type'] . "_CONFIG", $driver_config);
        }
    }
}
