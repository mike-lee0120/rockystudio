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
namespace User\Admin;

use Admin\Controller\AdminController;

/**
 * 用户类型控制器
 * @author jry <598821125@qq.com>
 */
class TypeAdmin extends AdminController
{
    /**
     * 用户类型列表
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 获取所有用户类型
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $type_object   = D('Type');
        $data_list     = $type_object->where($map)->order('id asc')->select();

        // 字段管理按钮
        $attr['name']  = 'attribute';
        $attr['title'] = '字段管理';
        $attr['class'] = 'label label-success-outline label-pill';
        $attr['href']  = U('User/Attribute/index', array('type_id' => __data_id__));

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('类型列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->addTopButton('delete') // 添加删除按钮
            ->setSearch('请输入ID/用户名', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('name', '名称')
            ->addTableColumn('title', '标题')
            ->addTableColumn('create_time', '注册时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->addRightButton('self', $attr) // 字段管理按钮
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    /**
     * 新增用户类型
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            $type_object = D('Type');
            $data        = $type_object->create();
            if ($data) {
                $id = $type_object->add();
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($type_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增用户类型') //设置页面标题
                ->setPostUrl(U('add')) //设置表单提交地址
                ->addFormItem('name', 'text', '名称', '用户类型名称')
                ->addFormItem('title', 'text', '标题', '用户类型标题')
                ->addFormItem('icon', 'icon', '图标', '模型图标')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->display();
        }
    }

    /**
     * 编辑用户类型
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            // 提交数据
            $type_object = D('Type');
            $data        = $type_object->create();
            if ($data) {
                $result = $type_object->save($data);
                if ($result) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败', $type_object->getError());
                }
            } else {
                $this->error($type_object->getError());
            }
        } else {
            // 获取用户类型信息
            $info = D('Type')->find($id);

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑用户类型') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('name', 'text', '名称', '用户类型名称')
                ->addFormItem('title', 'text', '标题', '用户类型标题')
                ->addFormItem('icon', 'icon', '图标', '模型图标')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->addFormItem('home_template', 'text', '主页模版', '主页模版')
                ->addFormItem('center_template', 'text', '个人中心模版', '个人中心模版')
                ->setFormData($info)
                ->display();
        }
    }
}
