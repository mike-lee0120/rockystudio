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
namespace Forum\Admin;

use Admin\Controller\AdminController;
use lyf\Page;

/**
 * 板块控制器
 * @author jry <598821125@qq.com>
 */
class PlateAdmin extends AdminController
{
    /**
     * 板块列表
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 获取所有板块
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $data_list     = D('Plate')
            ->where($map)
            ->order('id asc')
            ->select();

        // 转换成树状列表
        $tree      = new \lyf\Tree();
        $data_list = $tree->array2tree($data_list);

        $attr['name']  = 'post';
        $attr['title'] = '帖子管理';
        $attr['class'] = 'label label-success-outline label-pill';
        $attr['href']  = U('Forum/Index/index', array('plate_id' => '__data_id__'));

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('板块列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addTopButton('delete') // 添加删除按钮
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title_show', '标题')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->addRightButton('self', $attr) // 添加帖子管理按钮
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    /**
     * 新增板块
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            $plate_object = D('Plate');
            $data         = $plate_object->create();
            if ($data) {
                $id = $plate_object->add();
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($plate_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增板块') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('pid', 'select', '上级板块', '所属的上级板块', select_list_as_tree('Plate', null, '一级板块'))
                ->addFormItem('title', 'text', '板块名称', '板块名称')
                ->display();
        }
    }

    /**
     * 编辑板块
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $plate_object = D('Plate');
            $data         = $plate_object->create();
            if ($data) {
                if ($plate_object->save() !== false) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($plate_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑板块') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('pid', 'select', '上级板块', '所属的上级板块', select_list_as_tree('Plate', null, '一级板块'))
                ->addFormItem('title', 'text', '板块名称', '板块名称')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(D('Plate')->find($id))
                ->display();
        }
    }
}
