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
namespace Forum\Controller;

use Home\Controller\HomeController;
use lyf\Page;

/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexController extends HomeController
{
    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize()
    {
        parent::_initialize();
        if (C('forum_config.need_cert')) {
            $this->is_cert();
        }
    }

    /**
     * 首页
     * @author jry <598821125@qq.com>
     */
    public function index($sort = 'time', $type = '')
    {
        switch ($type) {
            case 'recommend':
                // 条件
                $con              = array();
                $con['status']    = 1;
                $con['recommend'] = 1;
                $sort             = 'create_time desc, id desc';
                break;
            case 'nonereply':
                // 条件
                $con                  = array();
                $con['status']        = 1;
                $con['comment_count'] = 0;
                $sort                 = 'create_time desc, id desc';
                break;

            default:
                // 条件
                $con           = array();
                $con['status'] = 1;
                $con['top']    = 0;
                if ($sort == 'time') {
                    $sort = 'create_time desc, id desc';
                } else if ($sort == 'reply') {
                    $sort = 'last_reply_time desc, create_time desc, id desc';
                } else if ($sort == 'view') {
                    $sort = 'view_count desc, create_time desc, id desc';
                }
                $keyword = I('keyword', '', 'string');
                if ($keyword) {
                    $condition            = array('like', '%' . $keyword . '%');
                    $con['title|content'] = array(
                        $condition,
                        $condition,
                        '_multi' => true,
                    );
                }
                break;
        }

        // 获取帖子列表
        $p            = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $index_object = D('Index');
        $data_list    = $index_object
            ->page($p, 10)
            ->where($con)
            ->order($sort)
            ->select();
        $page = new Page(
            $index_object->where($con)->count(),
            10
        );

        // 获取置顶帖子列表
        $con           = array();
        $con['status'] = 1;
        $con['top']    = 1;
        $top_list      = $index_object->where($con)->order('sort desc, id desc')->select();
        $this->assign('top_list', $top_list);

        // 获取板块列表
        $plate_model   = D('Plate');
        $con           = array();
        $con['status'] = 1;
        $plate_list    = $plate_model->where($con)->limit(5)->order('post_num desc,reply_num desc')->select();

        // 获取热门用户
        $comment_model = D('Comment');
        $hot_user      = D('Admin/User')->order('score desc')->limit(5)->select();
        foreach ($hot_user as $key => &$val) {
            $val['post_count']  = $index_object->where(array('uid' => $val['id']))->count();
            $val['reply_count'] = $comment_model->where(array('uid' => $val['id']))->count();
        }

        $this->assign('data_list', $data_list);
        $this->assign('hot_user', $hot_user);
        $this->assign('page', $page->show());
        $this->assign('plate_list', $plate_list);
        $this->assign('meta_title', "论坛");
        $this->display();
    }

    /**
     * 话题
     * @author jry <598821125@qq.com>
     */
    public function plate()
    {
        // 获取所有板块列表
        $con           = array();
        $con['status'] = 1;
        $plate_model   = D('Plate');
        $data_list     = $plate_model->where($con)->order('post_num desc,reply_num desc')->select();

        // 转换成树状列表
        $tree           = new \lyf\Tree();
        $data_list_tree = $tree->list2tree($data_list);

        // 获取最新10个话题
        $con           = array();
        $con['status'] = 1;
        $plate_model   = D('Plate');
        $data_list_new = $plate_model->where($con)->order('create_time desc,id desc')->select();

        $this->assign('data_list', $data_list);
        $this->assign('data_list_tree', $data_list_tree);
        $this->assign('data_list_new', $data_list_new);
        $this->assign('meta_title', "话题广场");
        $this->display();
    }

    /**
     * 话题详情
     * @author jry <598821125@qq.com>
     */
    public function topic($id = 0)
    {
        // 获取当前板块信息
        $con           = array();
        $con['status'] = 1;
        $plate_model   = D('Plate');
        $info          = $plate_model->where($con)->find($id);
        if (!$info) {
            $this->error('分类不存在或已禁用');
        }

        // 根话题
        if ($info['pid']) {
            $con               = array();
            $map['id']         = $info['pid'];
            $info['top_plate'] = $plate_model->where($con)->find();
        } else {
            $info['top_plate'] = $info;
        }

        // 相关话题
        $con                  = array();
        $con['status']        = 1;
        $map['title']         = array('like', '%' . $info['title'] . '%');
        $info['similar_list'] = $plate_model->where($con)->select();

        // 获取当前话题的帖子
        $p               = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $con             = array();
        $con['status']   = 1;
        $con['plate_id'] = $id;
        $index_model     = D('Index');
        $data_list       = $index_model->where($con)->order('id desc')->page($p, 10)->select();
        $page            = new Page(
            $index_model->where($con)->count(),
            10
        );

        $this->assign('info', $info);
        $this->assign('data_list', $data_list);
        $this->assign('page', $page->show());
        $this->assign('meta_title', $info['title']);
        $this->display();
    }

    /**
     * 帖子列表
     * @author jry <598821125@qq.com>
     */
    public function my()
    {
        // 获取所有帖子
        $uid           = $this->is_login();
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $map['uid']    = array('eq', $uid);
        $data_list     = D('Index')->where($map)->order('id desc')->select();

        $page = new Page(
            D('Index')->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('帖子列表') // 设置页面标题
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title', '标题')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('recycle', array('title' => '删除')) // 添加删除按钮
            ->display();
    }

    /**
     * 个人主页帖子列表
     * @author jry <598821125@qq.com>
     */
    public function home($uid)
    {
        // 获取所有帖子
        $uid           = $uid;
        $map['status'] = array('eq', '1'); // 禁用和正常状态
        $map['uid']    = array('eq', $uid);
        $data_list     = D('Index')->where($map)->order('id desc')->select();

        $page = new Page(
            D('Index')->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        $this->assign('data_list', $data_list);
        $this->assign('page', $page->show());
        $this->assign('meta_title', '个人主页');
        $this->display();
    }

    /**
     * 新增帖子
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        $uid = $this->is_login();
        if (request()->isPost()) {
            $index_post = D('Index');
            $data       = $index_post->create();
            if ($data) {
                $id = $index_post->add();
                if ($id) {
                    // 板块记录贴数目更新
                    $con           = array();
                    $con['id']     = $data['plate_id'];
                    $con['status'] = 1;
                    D('Plate')->where($con)->SetInc('post_num');
                    $this->success('新增成功', U('Forum/Index/index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($index_post->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增帖子') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('plate_id', 'select', '板块', '板块', select_list_as_tree('forum_plate'))
                ->addFormItem('title', 'text', '标题', '帖子标题')
                ->addFormItem('content', 'summernote', '正文内容', '帖子正文内容')
                ->setFormData(array('plate_id' => I('plate_id')))
                ->display();
        }
    }

    /**
     * 编辑帖子
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        $uid = $this->is_login();
        if (request()->isPost()) {
            $index_post = D('Index');
            $data       = $index_post->create();
            if ($data) {
                if ($index_post->save() !== false) {
                    $this->success('更新成功', U('Forum/Index/index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($index_post->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑帖子') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('plate_id', 'select', '板块', '板块', select_list_as_tree('forum_plate'))
                ->addFormItem('title', 'text', '标题', '帖子标题')
                ->addFormItem('content', 'summernote', '正文内容', '正文内容')
                ->setFormData(D('Index')->find($id))
                ->display();
        }
    }

    /**
     * 浏览帖子
     * @author jry <598821125@qq.com>
     */
    public function detail($id)
    {
        // 获取帖子主题信息
        $con['id']       = $id;
        $con['status']   = 1;
        $indel_model     = D('Index');
        $info            = $indel_model->where($con)->find();
        $result          = $indel_model->where($con)->setInc('view_count');
        $info['content'] = parse_content($info['content']);

        // 获取板块列表
        $plate_model = D('Plate');

        // 获取当前板块信息
        $con           = array();
        $con['status'] = 1;
        $plate_info    = $plate_model->where($con)->find($info['plate_id']);
        if (!$plate_info) {
            $this->error('分类不存在或已禁用');
        }
        $info['plate_info'] = $plate_info;

        // 获取相似文章，目前以同一话题为准
        $info['similar_list'] = $indel_model->order('view_count desc, id desc')->limit(18)->select();

        // 获取帖子回复信息
        $comment_p            = I('p') ?: 1;
        $comment_order        = I('comment_order') ?: 'id desc';
        $comment_list         = D("Forum/Comment")->getCommentList($id, 10, $comment_p, $comment_order);
        $info['comment_list'] = $comment_list['lists'];

        $this->assign('info', $info);
        $this->assign('page', $comment_list['page']);
        $this->assign('meta_title', $info['title']);
        $this->display();
    }
}
