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
 * 消息控制器
 * @author jry <598821125@qq.com>
 */
class MessageAdmin extends AdminController
{
    /**
     * 默认方法
     * @param $type 消息类型
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        //搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map['id|title'] = array($condition, $condition, '_multi' => true);

        //获取所有消息
        $p              = $_GET["p"] ?: 1;
        $message_object = D('Message');
        $map['status']  = array('egt', '0'); //禁用和正常状态
        $data_list      = $message_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->order('id desc')
            ->where($map)
            ->select();
        $page = new Page(
            $message_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('消息列表') //设置页面标题
            ->addTopButton('addnew') //添加新增按钮
            ->addTopButton('resume', array('model' => 'user_message')) //添加启用按钮
            ->addTopButton('forbid', array('model' => 'user_message')) //添加禁用按钮
            ->addTopButton('delete', array('model' => 'user_message')) //添加删除按钮
            ->setSearch('请输入ID/消息标题', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('to_uid', 'UID')
            ->addTableColumn('title', '消息')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) //数据列表
            ->setTableDataPage($page->show()) //数据列表分页
            ->addRightButton('edit') //添加编辑按钮
            ->addRightButton('forbid') //添加禁用/启用按钮
            ->addRightButton('delete') //添加删除按钮
            ->display();
    }

    /**
     * 新增消息
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            $message_object = D('Message');
            $result         = $message_object->sendMessage($_POST);
            if ($result) {
                $this->success('发送消息成功', U('index'));
            } else {
                $this->error('发送消息失败' . $message_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增消息') //设置页面标题
                ->setPostUrl(U('add')) //设置表单提交地址
                ->addFormItem('to_uid', 'num', '消息收信用户', '收信用户ID')
                ->addFormItem('title', 'textarea', '消息标题', '消息标题')
                ->addFormItem('content', 'kindeditor', '消息内容', '消息内容')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->display();
        }
    }

    /**
     * 编辑消息
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $message_object = D('Message');
            $data           = $message_object->create();
            if ($data) {
                if ($message_object->save() !== false) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($message_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑消息') //设置页面标题
                ->setPostUrl(U('edit')) //设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('title', 'textarea', '消息标题', '消息标题')
                ->addFormItem('content', 'kindeditor', '消息内容', '消息内容')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(D('Message')->find($id))
                ->display();
        }
    }

}
