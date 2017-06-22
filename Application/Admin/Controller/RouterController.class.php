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
namespace Admin\Controller;

use lyf\Page;

/**
 * 路由控制器
 * @author jry <598821125@qq.com>
 */
class RouterController extends AdminController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 搜索
        $keyword                 = I('keyword', '', 'string');
        $condition               = array('like', '%' . $keyword . '%');
        $map['id|pathinfo|rule'] = array($condition, $condition, $condition, '_multi' => true);

        // 获取所有分类
        $p = $_GET["p"] ?: 1;
        if (I('module')) {
            $module = $map['module'] = I('module');
        }
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $router_model  = D('Admin/Router');
        $data_list     = $router_model
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            $router_model->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('路由列表') // 设置页面标题
            ->addTopButton('addnew', array('href' => U('add'))) // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->setSearch('请输入ID/规则', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('type', '类型')
            ->addTableColumn('rule', '规则')
            ->addTableColumn('module', '模块')
            ->addTableColumn('pathinfo', 'pathinfo')
            ->addTableColumn('params', '参数')
            ->addTableColumn('create_time', '创建时间', 'time')
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
     * 新增
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            $router_model = D('Admin/Router');
            $data         = $router_model->create(format_data());
            if ($data) {
                $id = $router_model->add();
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败：' . $router_model->getError());
                }
            } else {
                $this->error($router_model->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('type', 'radio', '类型', '动态路由', array('0' => '静态路由', '1' => '动态路由'), array('must' => 1))
                ->addFormItem('module', 'text', '模块名', '模块名如：Shop', '', array('must' => 1))
                ->addFormItem('pathinfo', 'text', 'pathinfo', 'pathinfo如：index/detail', '', array('must' => 1))
                ->addFormItem('params', 'array', '参数', '参数数组', '', array('must' => 1))
                ->addFormItem('rule', 'text', '路由规则', '路由规则如：post/:id', '', array('must' => 1))
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
            $router_model = D('Admin/Router');
            $data         = $router_model->create(format_data());
            if ($data) {
                $id = $router_model->save();
                if ($id !== false) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败：' . $router_model->getError());
                }
            } else {
                $this->error($router_model->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('type', 'radio', '类型', '动态路由', array('0' => '静态路由', '1' => '动态路由'), array('must' => 1))
                ->addFormItem('module', 'text', '模块名', '模块名如：Shop', '', array('must' => 1))
                ->addFormItem('pathinfo', 'text', 'pathinfo', 'pathinfo如：index/detail', '', array('must' => 1))
                ->addFormItem('params', 'array', '参数', '参数数组', '', array('must' => 1))
                ->addFormItem('rule', 'text', '路由规则', '路由规则如：post/:id', '', array('must' => 1))
                ->setFormData(D('Admin/Router')->find($id))
                ->display();
        }
    }
}
