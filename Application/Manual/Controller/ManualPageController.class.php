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
namespace Manual\Controller;

use Home\Controller\HomeController;

/**
 * 章节控制器
 * @author jry <598821125@qq.com>
 */
class ManualPageController extends HomeController
{
    /**
     * 章节列表
     * @author jry <598821125@qq.com>
     */
    public function index($mid)
    {
        // 获取手册信息
        $uid  = $this->is_login();
        $info = D('Index')->find($mid);
        if ($info['status'] !== '1') {
            $this->error('该文档不存在或已禁用');
        }
        if ($info['uid'] !== $uid && !D('Member')->get_status($mid)) {
            $this->error('权限不足');
        }

        // 获取所有章节
        $map['mid']    = array('eq', $mid); // 章节ID
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $data_list     = D('ManualPage')->where($map)->order('sort asc,id asc')->select();

        // 转换成树状列表
        $tree      = new \lyf\Tree();
        $data_list = $tree->array2tree($data_list, 'title', 'id', 'pid', 0, false);

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('章节列表') // 设置页面标题
            ->addTopButton('addnew', array('href' => U('add', array('mid' => $mid)))) // 添加新增按钮
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title_show', '标题')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->addRightButton('edit'); // 添加编辑按钮
        if ($info['uid'] === $uid) {
            $builder->addRightButton('forbid') // 添加禁用/启用按钮
                ->addRightButton('delete'); // 添加删除按钮
        }
        $builder->setTemplate(C('USER_CENTER_LIST'))
            ->display();
    }

    /**
     * 新增章节
     * @author jry <598821125@qq.com>
     */
    public function add($mid = 0)
    {
        // 获取手册信息
        $uid  = $this->is_login();
        $info = D('Index')->find($mid);
        if ($info['status'] !== '1') {
            $this->error('该文档不存在或已禁用');
        }
        if ($info['uid'] !== $uid && !D('Member')->get_status($mid)) {
            $this->error('权限不足');
        }

        if (request()->isPost()) {
            $manual_object = D('ManualPage');
            $data          = $manual_object->create();
            if ($data) {
                $id = $manual_object->add();
                if ($id) {
                    $this->success('新增成功', U('index', array('mid' => $mid)));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($manual_object->getError());
            }
        } else {
            // 获取内容解析类型
            switch ($info['parse_type']) {
                case 'markdown':
                    $parse_type = 'editormd';
                    break;
                default:
                    $parse_type = 'summernote';
                    break;
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增章节') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('mid', 'hidden', 'mid', 'mid')
                ->addFormItem('pid', 'select', '父章节', '父章节', select_list_as_tree('ManualPage', array('mid' => $mid)))
                ->addFormItem('title', 'text', '标题', '章节标题')
                ->addFormItem('content', $parse_type, '正文内容', '正文内容')
                ->addFormItem('expanded', 'radio', '是否展开', '是否展开', array('0' => '不展开', '1' => '展开'))
                ->addFormItem('authority', 'radio', '阅读权限', '阅读权限', D('Vip/Type')->type_list('游客'))
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(array('mid' => $mid))
                ->setTemplate(C('USER_CENTER_FORM'))
                ->display();
        }
    }

    /**
     * 编辑章节
     * @author jry <598821125@qq.com>
     */
    public function edit($id, $mid = 0)
    {
        // 获取手册信息
        $uid              = $this->is_login();
        $manual_page_info = D('ManualPage')->find($id);
        $info             = D('Index')->find($manual_page_info['mid']);
        if ($info['status'] !== '1') {
            $this->error('该文档不存在或已禁用');
        }
        if ($info['uid'] !== $uid && !D('Member')->get_status($manual_page_info['mid'])) {
            $this->error('权限不足');
        }

        if (request()->isPost()) {
            $manual_object = D('ManualPage');
            $data          = $manual_object->create();
            if ($data) {
                if ($manual_object->save() !== false) {
                    $this->success('更新成功', U('index', array('mid' => $mid)));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($manual_object->getError());
            }
        } else {
            // 获取内容解析类型
            switch ($info['parse_type']) {
                case 'markdown':
                    $parse_type = 'editormd';
                    break;
                default:
                    $parse_type = 'summernote';
                    break;
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑章节') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('mid', 'hidden', 'mid', 'mid')
                ->addFormItem('pid', 'select', '父章节', '父章节', select_list_as_tree('ManualPage', array('mid' => $manual_page_info['mid'])))
                ->addFormItem('title', 'text', '标题', '章节标题')
                ->addFormItem('content', $parse_type, '正文内容', '正文内容')
                ->addFormItem('expanded', 'radio', '是否展开', '是否展开', array('0' => '不展开', '1' => '展开'))
                ->addFormItem('authority', 'radio', '阅读权限', '阅读权限', D('Vip/Type')->type_list('游客'))
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData($manual_page_info)
                ->setTemplate(C('USER_CENTER_FORM'))
                ->display();
        }
    }
}
