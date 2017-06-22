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
namespace Weixin\Controller;

use Home\Controller\HomeController;

/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class MaterialController extends HomeController
{
    /**
     * 阅读素材
     * @author jry <598821125@qq.com>
     */
    public function detail($id)
    {
        // 获取信息
        $material_object = D('Material');
        $info            = $material_object->find($id);

        // 阅读量加1
        $result = $material_object->where(array('id' => $id))->SetInc('view_count');

        $this->assign('info', $info);
        $this->assign('meta_title', $info['title']);
        $this->display();
    }
}
