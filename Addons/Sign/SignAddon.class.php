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
namespace Addons\Sign;

use Common\Controller\Addon;

/**
 * 签到插件
 * @author jry <598821125@qq.com>
 */
class SignAddon extends Addon
{
    /**
     * 插件信息
     * @author jry <598821125@qq.com>
     */
    public $info = array(
        'name'        => 'Sign',
        'title'       => '签到',
        'description' => '实现签到获取积分功能',
        'status'      => 1,
        'author'      => '零云',
        'version'     => '1.6.2',
        'icon'        => 'fa fa-calendar-check-o',
        'icon_color'  => '#F3812A',
    );

    /**
     * 插件所需钩子
     * @author jry <598821125@qq.com>
     */
    public $hooks = array(
        '0' => 'SignClick',
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
            'title' => '签到记录',
            'model' => 'Sign',
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
    public function SignClick($param = null)
    {
        // 今天是否签到的信息
        $con           = array();
        $con['status'] = 1;
        $con['uid']    = is_login();
        $con['date']   = date('Y-m-d', time());
        $sign_object   = D('Addons://Sign/Sign');
        $is_signed     = $sign_object->where($con)->count();

        // 单独按钮还是日历形式显示
        $sign_config = $this->getConfig();
        if (isset($param['type'])) {
            // 获取当月签到列表
            $map            = array();
            $map['uid']     = is_login();
            $start_time     = strtotime(date('Y-m', time()));
            $end_time       = strtotime(date('Y-m', $start_time) . '+1 month -1day');
            $start_date     = date('Y-m-d', $start_time);
            $end_date       = date('Y-m-d', $end_time);
            $map['date']    = array('between', array($start_date, $end_date));
            $sign_date_list = D('Addons://Sign/Sign')->where($map)->getField('date', true);
        } else {
            $sign_date_list = array();
        }
        $sign_config['type'] = isset($param['type']) ? $param['type'] : '';

        // 签到按钮样式
        $sign_config['btn_class'] = $param['btn_class'] ?: 'btn btn-info btn-pill';

        $this->assign('date_list', json_encode($sign_date_list));
        $this->assign("sign_config", $sign_config);
        $this->assign('is_signed', $is_signed);
        $this->display('sign');
    }
}
