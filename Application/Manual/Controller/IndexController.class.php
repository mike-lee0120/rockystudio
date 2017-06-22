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
use lyf\Page;

/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexController extends HomeController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        $con['status'] = 1;
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list     = D('Index')->page($p, 28)->where($con)->select();
        foreach ($data_list as &$val) {
            $val['user'] = D('Admin/User')->getUserInfo($val['uid']); // 获取该模块作者信息
        }
        $page = new Page(D('Index')->where($con)->count(), 28);
        $this->assign('data_list', $data_list);
        $this->assign('meta_title', "手册列表");
        $this->display();
    }

    /**
     * 手册列表
     * @author jry <598821125@qq.com>
     */
    public function my()
    {
        // 获取所有手册
        $map['status'] = array('egt', '0');
        $map['uid']    = $this->is_login();
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

        $attr['name']  = 'page';
        $attr['title'] = '章节管理';
        $attr['class'] = 'label label-primary-outline label-pill';
        $attr['href']  = U('Manual/ManualPage/index', array('mid' => '__data_id__'));

        $attr1['name']  = 'member';
        $attr1['title'] = '成员管理';
        $attr1['class'] = 'label label-info-outline label-pill';
        $attr1['href']  = U('Manual/Member/index', array('mid' => '__data_id__'));

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('手册列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addTableColumn('id', 'ID')
            ->addTableColumn('cover', '封面', 'picture')
            ->addTableColumn('title', '标题')
            ->addTableColumn('ctime', '创建时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('self', $attr) // 添加章节管理按钮
            ->addRightButton('self', $attr1) // 添加成员管理按钮
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('delete') // 添加删除按钮
            ->setTemplate(C('USER_CENTER_LIST'))
            ->display();
    }

    /**
     * 新增手册
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        $uid = $this->is_login();
        if (request()->isPost()) {
            $manual_object = D('Index');
            $data          = $manual_object->create();
            if ($data) {
                $id = $manual_object->add($data);
                if ($id) {
                    $this->success('新增成功', U('my'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($manual_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增手册') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('name', 'text', '手册名称', '英文单词')
                ->addFormItem('title', 'text', '手册标题', '手册标题')
                ->addFormItem('cover', 'picture', '封面', '封面图片')
                ->addFormItem('abstract', 'textarea', '简介', '简介')
                ->addFormItem('content', 'summernote', '正文内容', '正文内容')
                ->addFormItem('version', 'text', '版本', '如：1.0')
                ->addFormItem('parse_type', 'radio', '内容解析类型', 'HTML和Markdown两种', array('html' => 'html', 'markdown' => 'markdown'))
                ->setTemplate(C('USER_CENTER_FORM'))
                ->display();
        }
    }

    /**
     * 编辑手册
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        $uid  = $this->is_login();
        $info = D('Index')->find($id);
        if ($info['uid'] !== $uid) {
            $this->error('权限不足');
        }

        if (request()->isPost()) {
            $manual_object = D('Index');
            $data          = $manual_object->create();
            if ($data) {
                if ($manual_object->save() !== false) {
                    $this->success('更新成功', U('my'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($manual_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑手册') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('name', 'text', '手册名称', '英文单词')
                ->addFormItem('title', 'text', '手册标题', '手册标题')
                ->addFormItem('cover', 'picture', '封面', '封面图片')
                ->addFormItem('abstract', 'textarea', '简介', '简介')
                ->addFormItem('content', 'summernote', '正文内容', '正文内容')
                ->addFormItem('version', 'text', '版本', '如：1.0')
                ->addFormItem('parse_type', 'radio', '内容解析类型', 'HTML和Markdown两种', array('html' => 'html', 'markdown' => 'markdown'))
                ->setFormData($info)
                ->setTemplate(C('USER_CENTER_FORM'))
                ->display();
        }
    }

    /**
     * 手册在线阅读
     * @author jry <598821125@qq.com>
     */
    public function read($name, $keyword = '')
    {
        // 获取手册信息
        $con         = array();
        $con['name'] = $name;
        $manual_info = D('Index')->where($con)->find();
        if (!$manual_info) {
            $this->error('不存在该手册');
        }
        if ($manual_info['status'] !== '1') {
            $this->error('手册被禁用或删除');
        }

        // 获取手册列表
        if (request()->isMobile() || C('IS_API')) {
            $data_list = S('wap_manual_' . $name);
        } else {
            $data_list = S('manual_' . $name);
        }
        if (!$data_list || $keyword) {
            $con                = array();
            $con['status']      = 1;
            $con['mid']         = $manual_info['id'];
            $manual_page_object = D('ManualPage');
            $manual_page_list   = $manual_page_object
                ->field('id,pid,title,title as text,expanded,sort,status')
                ->order('sort asc,id asc')
                ->where($con)
                ->select();

            if ($keyword) {
                $where                  = array();
                $where['status']        = 1;
                $where['mid']           = $manual_info['id'];
                $condition              = array('like', '%' . $keyword . '%');
                $where['title|content'] = array(
                    $condition,
                    $condition,
                    '_multi' => true,
                );
                $search_ids = $manual_page_object->where($where)->getField('id', true);
            }

            // 遍历处理
            $expanded = array();
            foreach ($manual_page_list as $key => &$val) {
                $val['href'] = '#' . $val['id'];
                if (in_array($val['id'], $search_ids)) {
                    $val['color'] = '#f0ad4e';
                    if ($val['pid']) {
                        $expanded[] = $val['pid'];
                    }
                }
                $val['state']['expanded'] = $val['expanded'] ? true : false;
            }

            // 展开
            if ($expanded) {
                foreach ($manual_page_list as $key => &$val) {
                    if (in_array($val['id'], $expanded)) {
                        $val['state']['expanded'] = true;
                    }
                }
            }

            // 构造树形结构
            $tree = new \lyf\Tree();
            if (request()->isMobile()) {
                $data_list = $tree->array2tree($manual_page_list);
                if (!$keyword) {
                    S('wap_manual_' . $name, $data_list);
                }
            } else {
                $data_list = $tree->list2tree($manual_page_list, $pk = 'id', $pid = 'pid', $child = 'nodes', $root = 0, $strict = true);
                $data_list = $tree->listSortBy($data_list, 'sort', 'asc');
                if (!$keyword) {
                    S('manual_' . $name, $data_list);
                }
            }
        }

        // 支持通过get传自定义手册标题来用于一些特殊场景
        if (I('get.title')) {
            $manual_info['title'] = I('get.title');
        }

        $this->assign('info', $manual_info);
        $this->assign('data_list', $data_list);
        $this->assign('meta_title', $manual_info['title']);
        $this->assign('meta_keywords', $manual_info['abstract'] ?: C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', $manual_info['abstract'] ?: C('WEB_SITE_DESCRIPTION'));
        $this->assign('meta_cover', $manual_info['cover_url']);
        $this->display();
    }

    /**
     * 手册详情
     * @author jry <598821125@qq.com>
     */
    public function detail($id, $keyword = '')
    {
        $map           = array();
        $map['status'] = array('eq', 1); //正常
        $page_object   = D('ManualPage');
        $info          = $page_object->where($map)->find($id);
        if ($info) {
            // 解析Markdown
            $manual_info = D('Index')->find($info['mid']);
            if (!$manual_info) {
                $this->error('不存在该手册');
            }
            if ($manual_info['status'] !== '1') {
                $this->error('手册被禁用或删除');
            }
            if ($manual_info['need_login'] && !is_login()) {
                $this->error('登录后才能阅读该手册');
            }
            if ($manual_info['parse_type'] === 'markdown') {
                $parsedown            = new \Parsedown();
                $info['content_html'] = $parsedown->text($info['content']);
            } else {
                $info['content_html'] = $info['content'];
            }
            $info['content_html'] = parse_content($info['content_html'], false);

            // 关键字搜索
            if ($keyword) {
                $info['content_html'] = preg_replace('/' . $keyword . '/', '<span style="background: #f0ad4e;color: #fff;">' . $keyword . '</span>', $info['content_html']);
            }
            $parent_info = $page_object->find($info['pid']);
            if ($parent_info) {
                $parent_parent_info = $page_object->find($parent_info['pid']);
            }

            // VIP阅读权限
            if ($info['authority']) {
                $this->is_vip($info['authority']);
            }
            if ($parent_info['authority']) {
                $this->is_vip($parent_info['authority']);
            }
            if ($parent_parent_info['authority']) {
                $this->is_vip($parent_parent_info['authority']);
            }
        } else {
            $this->error('获取数据失败');
        }

        // 获取child
        $con['status'] = 1;
        $con['pid']    = $id;
        $child_list    = $page_object->field('id,pid,title')->where($con)->select();
        $html          = '<ul>';
        foreach ($child_list as $key => $val) {
            $html = $html . '<li><a href="#" class="child-node" data-id="' . $val['id'] . '" data-pid="' . $val['pid'] . '">' . $val['title'] . '</a></li>';
        }
        $html               = $html . '</ul>';
        $info['child_list'] = $html;

        $this->assign('info', $info);
        $this->assign('manual_info', $manual_info);
        $this->assign('meta_title', $info['title']);
        $this->assign('meta_keywords', $info['abstract'] ?: C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', $info['abstract'] ?: C('WEB_SITE_DESCRIPTION'));
        $this->assign('meta_cover', $info['cover_url']);
        $this->display();
    }
}
