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
 * 帖子控制器
 * @author jry <598821125@qq.com>
 */
class IndexAdmin extends AdminController
{
    /**
     * 帖子列表
     * @author jry <598821125@qq.com>
     */
    public function index($plate_id)
    {
        // 获取所有帖子
        $map['plate_id'] = array('eq', $plate_id); // 帖子ID
        $map['status']   = array('egt', '0'); // 禁用和正常状态
        $data_list       = D('Index')->where($map)->order('id desc')->select();

        $page = new Page(
            D('Index')->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        $top              = array();
        $top['name']      = 'top';
        $top['title']     = '置顶帖子';
        $top['class']     = 'label label-warning-outline label-pill ajax-get';
        $top['href']      = U('Forum/Index/top', array('id' => '__data_id__'));
        $top['condition'] = array('top' => array('eq', '0'));

        $top1              = array();
        $top1['name']      = 'canel_top';
        $top1['title']     = '取消置顶';
        $top1['class']     = 'label label-warning-outline label-pill ajax-get';
        $top1['href']      = U('Forum/Index/top', array('id' => '__data_id__'));
        $top1['condition'] = array('top' => array('eq', '1'));

        $recommend              = array();
        $recommend['name']      = 'recommend';
        $recommend['title']     = '推荐帖子';
        $recommend['class']     = 'label label-info-outline label-pill ajax-get';
        $recommend['href']      = U('Forum/Index/recommen', array('id' => '__data_id__'));
        $recommend['condition'] = array('recommend' => array('eq', '0'));

        $recommend1              = array();
        $recommend1['name']      = 'canel_top';
        $recommend1['title']     = '取消推荐';
        $recommend1['class']     = 'label label-info-outline label-pill ajax-get';
        $recommend1['href']      = U('Forum/Index/recommen', array('id' => '__data_id__'));
        $recommend1['condition'] = array('recommend' => array('eq', '1'));

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('帖子列表') // 设置页面标题
            ->addTopButton('addnew', array('href' => U('add', array('plate_id' => $plate_id)))) // 添加新增按钮
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title', '标题')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('self', $top) // 添加置顶按钮
            ->addRightButton('self', $top1) // 添加取消置顶按钮
            ->addRightButton('self', $recommend) // 添加推荐按钮
            ->addRightButton('self', $recommend1) // 添加取消推荐你按钮
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    /**
     * 置顶/取消
     * @author jry <598821125@qq.com>
     */
    public function top($id)
    {
        $index_model = D('Index');
        $info        = $index_model->find($id);
        if ($info['top'] === '1') {
            $result = $index_model->where(array('id' => $id))->setField('top', '0');
            if ($result) {
                $this->success('取消置顶成功');
            } else {
                $this->error('取消置顶失败:' . $index_model->getError());
            }
        } else {
            $result = $index_model->where(array('id' => $id))->setField('top', '1');
            if ($result) {
                $this->success('置顶成功');
            } else {
                $this->error('置顶失败:' . $index_model->getError());
            }
        }
    }

    /**
     * 推荐/取消
     * @author jry <598821125@qq.com>
     */
    public function recommend($id)
    {
        $index_model = D('Index');
        $info        = $index_model->find($id);
        if ($info['recommend'] === '1') {
            $result = $index_model->where(array('id' => $id))->setField('recommend', '0');
            if ($result) {
                $this->success('取消推荐成功');
            } else {
                $this->error('取消推荐失败:' . $index_model->getError());
            }
        } else {
            $result = $index_model->where(array('id' => $id))->setField('recommend', '1');
            if ($result) {
                $this->success('推荐成功');
            } else {
                $this->error('推荐失败:' . $index_model->getError());
            }
        }
    }

    /**
     * 新增帖子
     * @author jry <598821125@qq.com>
     */
    public function add($plate_id)
    {
        if (request()->isPost()) {
            $index_object = D('Index');
            $data         = $index_object->create();
            if ($data) {
                $id = $index_object->add();
                if ($id) {
                    $this->success('新增成功', U('index', array('plate_id' => $plate_id)));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($index_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增帖子') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('plate_id', 'hidden', '板块ID', '板块ID')
                ->addFormItem('title', 'text', '标题', '帖子标题')
                ->addFormItem('cover', 'picture', '封面', '帖子封面')
                ->addFormItem('content', 'summernote', '正文内容', '帖子正文内容')
                ->setFormData(array('plate_id' => $plate_id))
                ->display();
        }
    }

    /**
     * 编辑帖子
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            if ($_POST['mark']) {
                $_POST['mark'] = implode(',', $_POST['mark']);
            }
            $index_object = D('Index');
            $data         = $index_object->create();
            if ($data) {
                if ($index_object->save() !== false) {
                    $this->success('更新成功', U('index', array('plate_id' => I('plate_id'))));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($index_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑帖子') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('plate_id', 'hidden', 'plate_id', 'plate_id')
                ->addFormItem('title', 'text', '标题', '帖子标题')
                ->addFormItem('cover', 'picture', '封面', '帖子封面')
                ->addFormItem('content', 'summernote', '正文内容', '正文内容')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(D('Index')->find($id))
                ->display();
        }
    }
}
