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
namespace Wallet\Controller;

use Home\Controller\HomeController;
use lyf\Page;

/**
 * 用户的红包控制器
 * @author jry <598821125@qq.com>
 */
class RedbagController extends HomeController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function my()
    {
        // 获取列表
        $map["status"] = array("eq", "1");
        $p             = input('get.p', 1);
        $user_info     = D('Admin/User')->getUserInfo(is_login());
        if ($user_info['mobile'] && $user_info['mobile_bind']) {
            $map["account"] = array("eq", $user_info['mobile']);
        } else {
            $map["account"] = array("eq", $user_info['id']);
        }

        $model_object = D("Redbag");
        $data_list    = $model_object
            ->page($p, C("ADMIN_PAGE_ROWS"))
            ->where($map)
            ->order("id desc")
            ->select();
        $page = new Page(
            $model_object->where($map)->count(),
            C("ADMIN_PAGE_ROWS")
        );

        // 过期时间
        foreach ($data_list as $key => &$val) {
            $val['expire_format'] = time_format($val['expire']);
            $val['status_text'] = '有效';
            if ($val['expire'] - time() < 172800) {
                $val['status_text'] = '即将过期';
            }
            if ($val['expire'] < time()) {
                $val['status'] = '0';
                $val['status_text'] = '已过期';
            }
            if ($val['is_used']) {
                $val['status'] = '0';
                $val['status_text'] = '已使用';
            }
        }

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle("我的红包") // 设置页面标题
            ->setSearch("请输入ID/关键字", U("index"))
            ->addTableColumn("title", "标题")
            ->addTableColumn("account", "手机号")
            ->addTableColumn("money", "金额")
            ->addTableColumn("expire_format", "过期时间")
            ->addTableColumn("limit", "最低消费")
            ->addTableColumn("is_used", "是否使用")
            ->addTableColumn("create_time", "创建时间", "time")
            ->addTableColumn("status_text", "状态")
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->setTemplate(C('USER_CENTER_LIST'))
            ->display();
    }
}
