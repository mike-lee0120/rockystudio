<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Addons\Enquiry\Controller;

use Home\Controller\AddonController;
use lyf\Page;

/**
 * 留言
 */
class EnquiryController extends AddonController
{
    /**
     * 首页
     */
    public function index()
    {
        // 搜索
        $keyword                                = I('keyword', '', 'string');
        $condition                              = array('like', '%' . $keyword . '%');
        $map['id|name|content|qq|mobile|email'] = $condition;

        // 获取所有链接
        $p = !empty($_GET["p"]) ? $_GET["p"] : 1;
        if ($status = I('param.status')) {
            $map['status'] = $status; // 禁用和正常状态
        }

        $enquiry_object = D('Addons://Enquiry/Enquiry');
        $data_list      = $enquiry_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            $enquiry_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );
        foreach ($data_list as &$value) {
            $value['handle_uid'] = D('Admin/User')->getFieldById($value['handle_uid'], 'nickname');
        }
        unset($value);

        $handled['name']  = 'handled';
        $handled['title'] = '已处理';
        $handled['class'] = 'label label-success-outline label-pill ajax-get';
        $handled['href']  = addons_url('Enquiry://enquiry/setStatus', array('ids' => '__data_id__', 'status' => 2));

        $invalid['name']  = 'invalid';
        $invalid['title'] = '无效';
        $invalid['class'] = 'label label-danger-outline label-pill ajax-get';
        $invalid['href']  = addons_url('Enquiry://enquiry/setStatus', array('ids' => '__data_id__', 'status' => -1));

        $tab_list = array(
            '0'  => array(
                'title' => '全部',
                'href'  => addons_url('Enquiry://enquiry/index', array('status' => 0)),
            ),
            '-1' => array(
                'title' => '无效',
                'href'  => addons_url('Enquiry://enquiry/index', array('status' => -1)),
            ),
            '1'  => array(
                'title' => '有效',
                'href'  => addons_url('Enquiry://enquiry/index', array('status' => 1)),
            ),
            '2'  => array(
                'title' => '已处理',
                'href'  => addons_url('Enquiry://enquiry/index', array('status' => 2)),

            ),
        );

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('留言列表') // 设置页面标题
            ->addTopButton('self', array( // 添加返回按钮
                'title' => '<i class="fa fa-reply"></i> 返回插件列表',
                'class' => 'btn btn-warning-outline btn-pill',
                'href'  => U('Admin/Addon/index'))
            )
            ->setSearch('请输入联系方式/内容', addons_url('Enquiry://Enquiry/index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('uid', 'UID')
            ->addTableColumn('name', '姓名')
            ->addTableColumn('content', '内容')
            ->addTableColumn('mobile', '手机号')
            ->addTableColumn('qq', 'QQ')
            ->addTableColumn('email', 'Email')
            ->addTableColumn('handle_uid', '处理人')
            ->addTableColumn('status', '状态', 'callback', array($enquiry_object, 'status_list'))
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->setTabNav($tab_list, $status)
            ->addRightButton('edit', array('href' => addons_url('Enquiry://Enquiry/edit', array('id' => '__data_id__')), 'title' => '查看')) // 添加编辑按钮
            ->addRightButton('self', $handled) // 添加禁用/启用按钮
            ->addRightButton('self', $invalid) // 添加删除按钮
            ->display();
    }

    /**
     * 新增
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        $data         = I('post.');
        $data['uid']  = '';
        $addon_config = \Common\Controller\Addon::getConfig('Enquiry');
        if (!$addon_config['status']) {
            $this->error('留言已关闭');
        }
        if ($addon_config['need_login'] && !is_login()) {
            $this->error('请登陆后留言');
        } else {
            $data['uid'] = is_login();
        }
        $enquiry_object = D('Addons://Enquiry/Enquiry');
        $data           = $enquiry_object->create($data);
        if ($data) {
            $id = $enquiry_object->add();
            if ($id) {
                $this->success('新增成功', addons_url('Enquiry://Enquiry/none'));
            } else {
                $this->error('新增失败');
            }
        } else {
            $this->error($enquiry_object->getError());
        }
    }

    /**
     * 编辑
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        $enquiry_object = D('Addons://Enquiry/Enquiry');
        $id             = I('param.id');
        if (request()->isPost()) {
            $status_data['status'] = I('param.status');
            $data                  = $enquiry_object->create($status_data, 2);
            if ($data) {
                $id = $enquiry_object->where(array('id' => $id))->setField($status_data);
                if ($id !== false) {
                    $this->success('更新成功', addons_url('Enquiry://Enquiry/index'));
                } else {
                    $this->error('更新失败' . $enquiry_object->getError());
                }
            } else {
                $this->error($enquiry_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑留言') // 设置页面标题
                ->setPostUrl(addons_url('Enquiry://Enquiry/edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('contact', 'text', '联系方式', '联系方式')
                ->addFormItem('content', 'textarea', '留言内容', '留言内容')
                ->addFormItem('status', 'radio', '状态', '状态', $enquiry_object->status_list())
                ->setFormData($enquiry_object->find($id))
                ->display();
        }
    }

    /**
     * 设置状态
     * @author jry <598821125@qq.com>
     */
    public function setStatus()
    {
        $data = I('param.');
        if (!$data['ids'] || !$data['status']) {
            $this->error('参数错误');
        }
        if (is_array($ids)) {
            $map['id'] = array('in', $data['ids']);
        } else {
            $map['id'] = $data['ids'];
        }
        $enquiry_object        = D('Addons://Enquiry/Enquiry');
        $status_data['status'] = $data['status'];
        $status_data           = $enquiry_object->create($status_data, 2);
        if (!$status_data) {
            $this->error('数据验证错误' . $enquiry_object->getError());
        }
        $ret = $enquiry_object->where($map)->setField($status_data);
        if ($ret) {
            $this->success('状态设置成功');
        } else {
            $this->error('状态设置错误' . $enquiry_object->getError());
        }

    }
}
