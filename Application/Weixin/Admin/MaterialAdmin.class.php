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
namespace Weixin\Admin;

use Admin\Controller\AdminController;
use lyf\Page;

/**
 * 素材控制器
 * @author jry <598821125@qq.com>
 */
class MaterialAdmin extends AdminController
{
    /**
     * 素材列表
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        //获取所有素材
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list     = D('Material')
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            D('Material')->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('素材列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addTopButton('delete') // 添加删除按钮
            ->addTableColumn('id', 'ID')
            ->addTableColumn('cover', '封面', 'picture')
            ->addTableColumn('title', '标题')
            ->addTableColumn('ctime', '创建时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    /**
     * 新增素材
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            $material_object = D('Material');
            $data            = $material_object->create();
            if ($data) {
                $id = $material_object->add();
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($material_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增素材') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('title', 'text', '素材名称', '素材名称')
                ->addFormItem('author', 'text', '作者', '作者')
                ->addFormItem('cover', 'picture', '封面', '封面图片')
                ->addFormItem('abstract', 'textarea', '简介', '简介')
                ->addFormItem('content', 'kindeditor', '正文内容', '正文内容')
                ->addFormItem('url', 'text', '外链', '外链／原文地址')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->display();
        }
    }

    /**
     * 编辑素材
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $material_object = D('Material');
            $data            = $material_object->create();
            if ($data) {
                if ($material_object->save() !== false) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($material_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑素材') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('title', 'text', '素材名称', '素材名称')
                ->addFormItem('author', 'text', '作者', '作者')
                ->addFormItem('cover', 'picture', '封面', '封面图片')
                ->addFormItem('abstract', 'textarea', '简介', '简介')
                ->addFormItem('content', 'kindeditor', '正文内容', '正文内容')
                ->addFormItem('url', 'text', '外链', '外链／原文地址')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(D('Material')->find($id))
                ->display();
        }
    }
}
