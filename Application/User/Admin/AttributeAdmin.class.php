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
use lyf\Page;

/**
 * 用户属性控制器
 * @author jry <598821125@qq.com>
 */
class AttributeAdmin extends AdminController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index($type_id)
    {
        // 搜索
        $keyword              = I('keyword', '', 'string');
        $condition            = array('like', '%' . $keyword . '%');
        $map['id|name|title'] = array($condition, $condition, $condition, '_multi' => true);

        if ($type_id) {
            $map['type_id'] = $type_id;
        }
        $map['status']  = array('egt', 0);
        $attribute_list = D('Attribute')
            ->page(!empty($_GET["p"]) ? $_GET["p"] : 1, C('ADMIN_PAGE_ROWS'))
            ->order('id desc')
            ->where($map)
            ->select();
        $page = new Page(D('Attribute')->where($map)->count(), C('ADMIN_PAGE_ROWS'));

        $attr['name']  = 'addnew';
        $attr['title'] = '新 增';
        $attr['class'] = 'btn btn-primary-outline btn-pill';
        $attr['href']  = U('add', array('type_id' => $type_id));

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('属性管理') // 设置页面标题
            ->addTopButton('self', array( // 添加返回按钮
                'title'   => '<i class="fa fa-reply"></i> 返回模型列表',
                'class'   => 'btn btn-warning-outline btn-pill',
                'onclick' => 'javascript:history.back(-1);return false;')
            )
            ->addTopButton('self', $attr) // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->setSearch('请输入ID/名称/标题', U('index', array('type_id' => $type_id)))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('name', '名称')
            ->addTableColumn('title', '标题')
            ->addTableColumn('type', '类型', 'type')
            ->addTableColumn('create_time', '发布时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($attribute_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    /**
     * 新增字段
     * @author jry <598821125@qq.com>
     */
    public function add($type_id)
    {
        if (request()->isPost()) {
            $attribute_object = D('Attribute');
            $data             = $attribute_object->create();
            if ($data) {
                $id = $attribute_object->add();
                if ($id) {
                    $this->success('新增字段成功', U('index', array('type_id' => $type_id)));
                } else {
                    $this->error('新增字段出错！');
                }
            } else {
                $this->error($attribute_object->getError());
            }
        } else {
            // 获取Builder表单类型转换成一维数组
            $form_item_type = C('FORM_ITEM_TYPE');
            foreach ($form_item_type as $key => $val) {
                $new_form_item_type[$key]['title'] = $val[0];
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增字段') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('type_id', 'select', '用户模型', '用户模型', select_list_as_tree('Type'))
                ->addFormItem('name', 'text', '字段名称', '字段名称，如“title”')
                ->addFormItem('title', 'text', '字段标题', '字段标题，如“标题”')
                ->addFormItem('type', 'select', '字段类型', '字段类型', $new_form_item_type)
                ->addFormItem('value', 'text', '字段默认值', '字段默认值')
                ->addFormItem('options', 'textarea', '额外选项', '额外选项radio/select等需要配置此项')
                ->addFormItem('tip', 'text', '字段补充说明', '字段补充说明')
                ->setFormData(array('type_id' => $type_id))
                ->display();
        }
    }

    /**
     * 编辑分类
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $attribute_object = D('Attribute');
            $data             = $attribute_object->create();
            if ($data) {
                $status = $attribute_object->save(); // 更新字段信息
                if ($status) {
                    $this->success('更新字段成功', U('index', array('type_id' => I('type_id'))));
                } else {
                    $this->error('更新属性出错！');
                }
            } else {
                $this->error($attribute_object->getError());
            }
        } else {
            // 获取Builder表单类型转换成一维数组
            $form_item_type = C('FORM_ITEM_TYPE');
            foreach ($form_item_type as $key => $val) {
                $new_form_item_type[$key]['title'] = $val[0];
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑字段') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('type_id', 'select', '用户模型', '用户模型', select_list_as_tree('Type'))
                ->addFormItem('name', 'text', '字段名称', '字段名称，如“title”')
                ->addFormItem('title', 'text', '字段标题', '字段标题，如“标题”')
                ->addFormItem('type', 'select', '字段类型', '字段类型', $new_form_item_type)
                ->addFormItem('value', 'text', '字段默认值', '字段默认值')
                ->addFormItem('options', 'textarea', '额外选项', '额外选项radio/select等需要配置此项')
                ->addFormItem('tip', 'text', '字段补充说明', '字段补充说明')
                ->setFormData(D('Attribute')->find($id))
                ->display();
        }
    }
}
