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
namespace Wallet\Admin;

use Admin\Controller\AdminController;
use lyf\Page;

/**
 * 用户的红包控制器
 * @author jry <598821125@qq.com>
 */
class RedbagAdmin extends AdminController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 获取列表
        $map["status"] = array("egt", "0"); // 禁用和正常状态
        $p             = input('get.p', 1);
        $model_object  = D("Redbag");
        $data_list     = $model_object
            ->page($p, C("ADMIN_PAGE_ROWS"))
            ->where($map)
            ->order("id desc")
            ->select();
        $page = new Page(
            $model_object->where($map)->count(),
            C("ADMIN_PAGE_ROWS")
        );

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle("红包列表") // 设置页面标题
            ->addTopButton("addnew") // 添加新增按钮
            ->addTopButton("resume") // 添加启用按钮
            ->addTopButton("forbid") // 添加禁用按钮
            ->setSearch("请输入ID/账号", U("index"))
            ->addTableColumn("id", "ID")
            ->addTableColumn("title", "标题")
            ->addTableColumn("account", "手机号")
            ->addTableColumn("money", "金额")
            ->addTableColumn("expire", "过期时间", 'time')
            ->addTableColumn("limit", "最低消费")
            ->addTableColumn("is_used", "是否使用")
            ->addTableColumn("create_time", "创建时间", "time")
            ->addTableColumn("status", "状态", "status")
            ->addTableColumn("right_button", "操作", "btn")
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton("edit") // 添加编辑按钮
            ->addRightButton("forbid") // 添加禁用/启用按钮
            ->addRightButton("delete") // 添加删除按钮
            ->display();
    }

    /**
     * 新增
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            $model_object = D("Redbag");
            $data         = $model_object->create(format_data());
            if ($data) {
                $id = $model_object->add($data);
                if ($id) {
                    $this->success("新增成功", U("index"));
                } else {
                    $this->error("新增失败" . $model_object->getError());
                }
            } else {
                $this->error($model_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle("新增") // 设置页面标题
                ->setPostUrl(U("add")) // 设置表单提交地址
                ->addFormItem("account", "num", "手机号", "手机号")
                ->addFormItem("title", "text", "标题", "标题")
                ->addFormItem("money", "num", "金额", "金额")
                ->addFormItem("expire", "datetime", "过期时间", "过期时间")
                ->addFormItem("limit", "num", "最低消费", "最低消费")
                ->display();
        }
    }

    /**
     * 编辑
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $model_object = D("Redbag");
            $data         = $model_object->create(format_data());
            if ($data) {
                $id = $model_object->save($data);
                if ($id !== false) {
                    $this->success("更新成功", U("index"));
                } else {
                    $this->error("更新失败" . $model_object->getError());
                }
            } else {
                $this->error($model_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle("编辑") // 设置页面标题
                ->setPostUrl(U("edit")) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem("account", "num", "手机号", "手机号")
                ->addFormItem("title", "text", "标题", "标题")
                ->addFormItem("money", "num", "金额", "金额")
                ->addFormItem("expire", "datetime", "过期时间", "过期时间")
                ->addFormItem("limit", "num", "最低消费", "最低消费")
                ->addFormItem("is_used", "radio", "是否使用", "是否使用", array('1' => '是', '0' => '否'))
                ->addFormItem("share_id", "num", "分享活动ID", "分享活动ID")
                ->setFormData(D("Redbag")->find($id))
                ->display();
        }
    }
}
