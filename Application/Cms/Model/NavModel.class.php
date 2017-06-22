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
namespace Cms\Model;

/**
 * 导航模型
 * @author jry <598821125@qq.com>
 */
class NavModel
{
    /**
     * 获取导航树，指定导航则返回指定导航的子导航树，不指定则返回所有导航树，指定导航若无子导航则返回同级导航
     * @param  integer $id    导航ID
     * @param  boolean $field 查询字段
     * @return array          导航树
     * @author jry <598821125@qq.com>
     */
    public function getNavTree($id = 0, $group = 'main', $field = true)
    {
        return D('Cms/Category')->getCategoryTree();
    }
}
