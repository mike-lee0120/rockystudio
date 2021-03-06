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
namespace Cms\Admin;

use Admin\Controller\AdminController;
use lyf\Page;

/**
 * 后台文档模型控制器
 * @author jry <598821125@qq.com>
 */
class TypeAdmin extends AdminController
{
    /**
     * 模型列表
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 搜索
        $keyword              = I('keyword', '', 'string');
        $condition            = array('like', '%' . $keyword . '%');
        $map['id|title|name'] = array($condition, $condition, $condition, '_multi' => true);

        // 获取所有模型
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $data_list     = D('Type')
            ->page(!empty($_GET["p"]) ? $_GET["p"] : 1, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('sort asc,id asc')
            ->select();
        $page = new Page(D('Type')->where($map)->count(), C('ADMIN_PAGE_ROWS'));

        $attr['name']  = 'attribute';
        $attr['title'] = '字段管理';
        $attr['class'] = 'label label-success-outline label-pill';
        $attr['href']  = U(D('Index')->moduleName . '/Attribute/index', array('doc_type' => '__data_id__'));

        $right_button['no']['title']     = '系统模型无需操作';
        $right_button['no']['attribute'] = 'class="label label-warning" href="#"';

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('模型列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->setSearch('请输入ID/模型标题', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('icon', '图标', 'icon')
            ->addTableColumn('name', '名称')
            ->addTableColumn('title', '标题')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('self', $attr) // 添加字段管理按钮
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->alterTableData( // 修改列表数据
                array('key' => 'system', 'value' => '1'),
                array('right_button' => $right_button)
            )
            ->display();
    }

    /**
     * 新增模型
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
            $builder->setMetaTitle('新增模型') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('name', 'text', '模型名称', '模型名称')
                ->addFormItem('title', 'text', '模型标题', '模型标题')
                ->addFormItem('icon', 'icon', '图标', '模型图标')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->display();
        }
    }

    /**
     * 编辑模型
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $_POST['list_field']   = implode(',', $_POST['list_field']);
            $_POST['filter_field'] = implode(',', $_POST['filter_field']);
            $type_object           = D('Type');
            $data                  = $type_object->create();
            if ($data) {
                if ($type_object->save() !== false) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($type_object->getError());
            }
        } else {
            $document_type_info        = D('Type')->find($id);
            $document_type_field_group = \lyf\Str::parseAttr($document_type_info['field_group']);
            $document_type_field_sort  = json_decode($document_type_info['field_sort'], true);

            // 获取文档字段
            $map['status']   = array('eq', '1');
            $map['show']     = array('eq', '1');
            $map['doc_type'] = array('in', '0,' . $id);
            $attribute_list  = D('Attribute')->where($map)->select();

            // 获取用于列表显示字段表单复选框的内容
            $map['doc_type']         = array('eq', $id);
            $attribute_list_checkbox = select_list_as_tree('Attribute', $map);

            // 解析字段
            $new_attribute_list = array();
            foreach ($attribute_list as $attr) {
                $new_attribute_list[$attr['id']] = $attr['title'];
            }

            // 构造拖动排序options
            foreach ($document_type_field_sort as $key => $val) {
                $field[$key]['title'] = $document_type_field_group[$key];
                $temp                 = array();
                foreach ($val as $val2) {
                    if ($new_attribute_list[$val2]) {
                        $temp[$val2] = $new_attribute_list[$val2];
                    }
                    unset($new_attribute_list[$val2]);
                }
                $field[$key]['field'] = $temp;
            }

            // 计算未排序字段分组的key
            $unsort_key = array_pop(array_keys($field)) + 1;

            // 未排序字段
            if ($new_attribute_list) {
                $field[$unsort_key]['title'] = "未排序";
                $field[$unsort_key]['field'] = $new_attribute_list;
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑模型') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('name', 'text', '模型名称', '模型名称')
                ->addFormItem('title', 'text', '模型标题', '模型标题')
                ->addFormItem('main_field', 'radio', '主要字段', '该模型的主要字段，如：文章的标题，商品的名称，用于前台列表及搜索列表显示', $attribute_list_checkbox)
                ->addFormItem('list_field', 'checkbox', '列表字段', '后台列表显示的字段', $attribute_list_checkbox)
                ->addFormItem('filter_field', 'checkbox', '筛选字段', '前台列表条件筛选字段', $attribute_list_checkbox)
                ->addFormItem('field_group', 'textarea', '字段分组', '字段分组')
                ->addFormItem('field_sort', 'board', '字段排序', '字段排序', $field)
                ->addFormItem('icon', 'icon', '图标', '模型图标')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(D('Type')->find($id))
                ->display();
        }
    }

    /**
     * 设置一条或者多条数据的状态
     * @author jry <598821125@qq.com>
     */
    public function setStatus($model = '', $strict = null)
    {
        if ('' == $model) {
            $model = request()->controller();
        }
        $ids    = I('request.ids');
        $status = I('request.status');
        if (empty($ids)) {
            $this->error('请选择要操作的数据');
        }
        $map['id'] = array('in', $ids);
        switch ($status) {
            case 'delete': // 删除条目
                // 获取当前文档模型信息
                $type_object = D('Type');
                $type_info   = $type_object->where($map)->find();

                // 系统模型无需操作
                if ($type_info['system'] === '1') {
                    $this->error('系统模型无需操作');
                } else {
                    $con['doc_type'] = $ids;
                    $count           = D('Category')->where($con)->count();
                    if ($count > 0) {
                        $this->error('存在该文档模型的分类，无法删除！');
                    } else {
                        $result = $type_object->where($map)->delete();
                        if ($result) {
                            $this->success('删除成功，不可恢复！');
                        } else {
                            $this->error('删除失败');
                        }
                    }
                }
                break;
            default:
                parent::setStatus($model);
                break;
        }
    }
}
