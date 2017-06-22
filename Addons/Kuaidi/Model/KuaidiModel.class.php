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
namespace Addons\Kuaidi\Model;

require_once dirname(dirname(__FILE__)) . '/Express.class.php';

/**
 * 快递模型
 * @author jry <598821125@qq.com>
 */
class KuaidiModel
{
    /**
     * 根据快递单号获取信息
     * @param string $order 快递单号
     * @return array
     * @author jry <598821125@qq.com>
     */
    public function getorder($order)
    {
        $addon_config = \Common\Controller\Addon::getConfig('Kuaidi');
        if ($addon_config['status']) {
            $express = new \Express();
            $result  = $express->getorder($order);
            return $result;
        } else {
            $this->error = '插件关闭';
            return false;
        }
    }
}
