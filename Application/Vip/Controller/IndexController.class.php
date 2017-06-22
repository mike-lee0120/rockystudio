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
namespace Vip\Controller;

use Home\Controller\HomeController;

/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexController extends HomeController
{
    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize()
    {
        parent::_initialize();
        $this->is_login();
    }

    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        if ($vip = D('Vip/Index')->isVip(is_login())) {
            $con           = array();
            $con['status'] = 1;
            $con['id']     = $vip;
            $vip_info      = D('Vip/Index')->where($con)->find();
            $this->assign('vip_info', $vip_info);
            $this->assign('meta_title', "我的会员");
            $this->display();
        } else {
            $this->redirect('Order/index');
        }
    }
}
