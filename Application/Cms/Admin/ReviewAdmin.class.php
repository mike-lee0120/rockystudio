<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Cms\Admin;

use Admin\Controller\AdminController;
use lyf\Page;

/**
 * 投稿审核控制器
 * @author jry <598821125@qq.com>
 */
class ReviewAdmin extends AdminController
{
    /**
     * 文档列表
     * @author jry <598821125@qq.com>
     */
    public function index($review_status = '0')
    {
        $map                  = array();
        $map['source']        = '1';
        $map['review_status'] = $review_status;
        $index_object         = D('Index');

        // 获取列表
        $p         = !empty($_GET["p"]) ? $_GET["p"] : 1;
        $data_list = $index_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            $index_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 获取主要字段
        $attribute_object = D('Attribute');
        foreach ($data_list as $key => &$val) {
            $main_field_id           = $val['category_info']['doc_type_info']['main_field'];
            $article_type_main_field = $attribute_object->where(array('id' => $main_field_id))->find();
            $article_table           = M(strtolower(D('Index')->moduleName . '_' . $val['category_info']['doc_type_info']['name']));
            $extend_info             = $article_table->find($val['id']);
            $val                     = array_merge($val, $extend_info);
            if (!$article_type_main_field) {
                $val['main_field'] = $val['title'];
            } else {
                $val['main_field'] = $val[$article_type_main_field['name']];
            }
        }

        // 审核按钮
        $review          = array();
        $review['name']  = 'review';
        $review['title'] = '审核';
        $review['class'] = 'label label-primary btn-pill';
        $review['href']  = U('Cms/Review/review', array('id' => '__data_id__'));

        // 已通过
        $reviewed1['name']  = 'reviewed';
        $reviewed1['title'] = '已审核';
        $reviewed1['class'] = 'label label-success';

        // 已拒绝
        $reviewed2['name']  = 'reviewed';
        $reviewed2['title'] = '已拒绝';
        $reviewed2['class'] = 'label label-danger';

        // 设置Tab导航数据列表
        $status_list = $index_object->getReviewStatus();
        $tab_list    = array();
        foreach ($status_list as $key1 => $val1) {
            $tab_list[$key1]['title'] = $val1;
            $tab_list[$key1]['href']  = U('index', array('review_status' => $key1));
        }

        // 使用Builder快速建立列表页面。
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('投稿列表') // 设置页面标题
            ->setTabNav($tab_list, $review_status) // 设置页面Tab导航
            ->addTableColumn('id', 'ID')
            ->addTableColumn('main_field', '文档')
            ->addTableColumn('uid', '投稿人UID')
            ->addTableColumn('review_uid', '审核人UID')
            ->addTableColumn('review_time', '审核时间', 'time')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()); // 数据列表分页
        if ($review_status === '0') {
            $builder->addRightButton('self', $review); // 添加审核按钮
        } elseif ($review_status === '1') {
            $builder->addRightButton('self', $reviewed1); // 添加按钮
        } else {
            $builder->addRightButton('self', $reviewed2); // 添加按钮
        }
        $builder->display();
    }

    /**
     * 审核文档
     * @author jry <598821125@qq.com>
     */
    public function review($id)
    {
        // 获取文档信息
        $index_object = D('Index');
        $article_info = $index_object->detail($id);

        if (request()->isPost()) {
            //更新文档
            $result = $index_object->update();
            if (!$result) {
                $this->error($index_object->getError());
            } else {
                $data                  = array();
                $data['status']        = '1';
                $data['review_status'] = '1';
                $data['review_time']   = time();
                $data['review_uid']    = is_login();
                $status                = $index_object->where(array('id' => $id))->setField($data);
                if ($status) {
                    // 构造消息数据
                    $msg_data            = array();
                    $msg_data['to_uid']  = $article_info['uid'];
                    $msg_data['title']   = '文档审核成功';
                    $msg_data['url']     = oc_url('Cms/Index/my');
                    $msg_data['content'] = '您好：<br>'
                    . '您在' . C('WEB_SITE_TITLE') . '的文档（' . $article_info['main_field'] . '）已成功通过审核。<br>'
                        . '<br>';
                    D('User/Message')->sendMessage($msg_data);
                    $this->success('通过审核成功', U('index'));
                } else {
                    $this->error('通过审核出错' . $index_object->getError());
                }
            }
        } else {
            //获取当前分类
            $category_info = D('Category')->find($article_info['cid']);
            $doc_type      = D('Type')->find($category_info['doc_type']);
            $field_sort    = json_decode($doc_type['field_sort'], true);
            $field_group   = \lyf\Str::parseAttr($doc_type['field_group']);

            //获取文档字段
            $map['status']   = array('eq', '1');
            $map['show']     = array('eq', '1');
            $map['doc_type'] = array('in', '0,' . $category_info['doc_type']);
            $attribute_list  = D('Attribute')->where($map)->select();

            //解析字段options
            $new_attribute_list = array();
            foreach ($attribute_list as $attr) {
                if ($attr['name'] == 'cid') {
                    $con['group']    = $category_info['group'];
                    $con['doc_type'] = $category_info['doc_type'];
                    $con['status']   = array('egt', 0);
                    $attr['options'] = select_list_as_tree('Category', $con);
                } else {
                    $attr['options'] = \lyf\Str::parseAttr($attr['options']);
                }
                $new_attribute_list[$attr['id']]          = $attr;
                $new_attribute_list[$attr['id']]['value'] = $article_info[$attr['name']];
            }

            //表单字段排序及分组
            if ($field_sort) {
                $new_attribute_list_sort = array();
                foreach ($field_sort as $k1 => &$v1) {
                    $new_attribute_list_sort[0]['type']                            = 'group';
                    $new_attribute_list_sort[0]['options']['group' . $k1]['title'] = $field_group[$k1];
                    foreach ($v1 as $k2 => $v2) {
                        $new_attribute_list_sort[0]['options']['group' . $k1]['options'][] = $new_attribute_list[$v2];
                    }
                }
                $new_attribute_list = $new_attribute_list_sort;
            }

            $button          = array();
            $button['title'] = '拒绝通过';
            $button['type']  = 'link';
            $button['class'] = 'btn btn-warning';
            $button['href']  = U('reject', array('id' => $id));

            //使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('审核文档') //设置页面标题
                ->setPostUrl(U('')) //设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->setExtraItems($new_attribute_list)
                ->setFormData($article_info)
                ->setSubmitTitle('通过审核')
                ->addBottomButton($button)
                ->display();
        }
    }

    /**
     * 拒绝通过
     * @author jry <598821125@qq.com>
     */
    public function reject($id)
    {
        if (request()->isPost()) {
            $index_object           = D('Index');
            $data                   = array();
            $data['status']         = '-2';
            $data['review_status']  = '-1';
            $data['review_time']    = time();
            $data['review_uid']     = is_login();
            $data['review_message'] = I('post.review_message');
            $status                 = $index_object->where(array('id' => $id))->setField($data);
            if ($status) {
                // 构造消息数据
                $msg_data            = array();
                $msg_data['to_uid']  = $article_info['uid'];
                $msg_data['title']   = '文档审核未通过';
                $msg_data['url']     = oc_url('Cms/Index/my');
                $msg_data['content'] = '您好：<br>'
                . '您在' . C('WEB_SITE_TITLE') . '的文档因为' . I('post.review_message') . '未能通过审核。<br>'
                    . '<br>';
                D('User/Message')->sendMessage($msg_data);
                $this->success('拒绝成功', U('index'));
            } else {
                $this->error('拒绝出错' . $index_object->getError());
            }
        } else {

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('拒绝通过') //设置页面标题
                ->setPostUrl(U('')) //设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('review_message', 'textarea', '拒绝原因', '拒绝原因')
                ->setFormData(array('id' => $id))
                ->setSubmitTitle('确认拒绝')
                ->display();
        }
    }
}
