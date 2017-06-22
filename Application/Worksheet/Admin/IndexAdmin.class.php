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
namespace Worksheet\Admin;

use Admin\Controller\AdminController;
use lyf\Page;

/**
 * 工单控制器
 * @author jry <598821125@qq.com>
 */
class IndexAdmin extends AdminController
{
    /**
     * 工单列表
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 获取所有工单
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list     = D('Index')
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            D('Index')->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        $attr['name']  = 'resume';
        $attr['title'] = '设为已处理';
        $attr['class'] = 'label label-danger-outline label-pill ajax-get';
        $attr['href']  = U('Worksheet/Index/setStatus', array('ids' => '__data_id__', 'status' => 'resume'));

        $right_button['forbid']['title']     = '已处理';
        $right_button['forbid']['attribute'] = 'class="label label-success" href="#"';

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('工单列表') // 设置页面标题
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title', '标题')
            ->addTableColumn('name', '姓名')
            ->addTableColumn('mobile', '电话')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit', array('title' => '查看'))
            ->addRightButton('self', $attr)
            ->alterTableData( // 修改列表数据
                array('key' => 'status', 'value' => '<i class="fa fa-check text-success"></i>'),
                array('right_button' => $right_button)
            )
            ->display();
    }

    /**
     * 编辑
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $default_object = D('Index');
            $data           = $default_object->create();
            if ($data) {
                if ($default_object->save() !== false) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($default_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('title', 'text', '标题', '标题')
                ->addFormItem('abstract', 'textarea', '详情', '详情')
                ->addFormItem('name', 'text', '姓名', '姓名')
                ->addFormItem('mobile', 'text', '电话', '电话')
                ->addFormItem('address', 'text', '地址', '地址')
                ->setFormData(D('Index')->find($id))
                ->display();
        }
    }

    /**
     * 已处理
     * @author jry <598821125@qq.com>
     */
    public function setStatus()
    {
        parent::setStatus('Worksheet/Index');
    }
}
