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
namespace Manual\Admin;

use Admin\Controller\AdminController;
use lyf\Page;

/**
 * 成员控制器
 * @author jry <598821125@qq.com>
 */
class MemberAdmin extends AdminController
{
    /**
     * 成员列表
     * @author jry <598821125@qq.com>
     */
    public function index($mid)
    {
        // 获取手册
        $map           = array();
        $map['status'] = 1;
        $map['id']     = $mid;
        $manua_model   = D('Index');
        $info          = $manua_model->where($map)->find();

        // 权限检测
        $uid = $this->is_login();
        if ($info['uid'] !== $uid) {
            $this->error('权限不足 ');
        }

        // 获取成员列表
        $map            = array();
        $map['status']  = array('eq', '1');
        $map['data_id'] = array('eq', $mid);
        $data_list      = D('Member')->where($map)->select();
        $user_model     = D('Admin/User');
        foreach ($data_list as $key => &$val) {
            $user_info       = $user_model->getUserInfo($val['uid']);
            $val['nickname'] = $user_info['nickname'];
            $val['email']    = $user_info['email'];
        }

        $attr['name']  = 'addnew';
        $attr['title'] = '新增成员';
        $attr['class'] = 'btn btn-primary-outline btn-pill';
        $attr['href']  = U('Manual/Member/add', array('mid' => $mid));

        $attr1['name']  = 'delete';
        $attr1['title'] = '移除成员';
        $attr1['class'] = 'label label-danger-outline label-pill ajax-get';
        $attr1['href']  = U('Manual/Member/add', array('mid' => $mid, 'uid' => '__data_id__', 'isPost' => '1'));

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('成员列表') // 设置页面标题
            ->addTopButton('self', $attr) // 添加新增按钮
            ->addTableColumn('uid', 'UID')
            ->addTableColumn('nickname', '昵称')
            ->addTableColumn('email', '邮箱')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataListKey('uid')
            ->addRightButton('self', $attr1)
            ->display();
    }

    /**
     * 新增成员
     * @author jry <598821125@qq.com>
     */
    public function add($mid)
    {
        // 获取手册
        $map           = array();
        $map['status'] = 1;
        $map['id']     = $mid;
        $manua_model   = D('Index');
        $info          = $manua_model->where($map)->find();

        // 权限检测
        $uid = $this->is_login();
        if ($info['uid'] !== $uid) {
            $this->error('权限不足 ');
        }

        if (request()->isPost() || input('isPost')) {
            $member_object = D('Member');
            $member_uid    = input('uid');
            if (!$member_uid) {
                $this->error('请选择用户');
            }
            $con            = array();
            $con['data_id'] = $mid;
            $con['uid']     = $member_uid;
            $find           = $member_object->where($con)->find();
            if ($find) {
                if ($find['status'] === '1') {
                    $where['id'] = $find['id'];
                    $result      = $member_object
                        ->where($where)
                        ->setField(array('status' => 0, 'update_time' => time()));
                    if ($result) {
                        $return['status']        = 1;
                        $return['info']          = '移除成功' . $member_object->getError();
                        $return['follow_status'] = 0;
                    } else {
                        $return['status']        = 0;
                        $return['info']          = '移除失败' . $member_object->getError();
                        $return['follow_status'] = 1;
                    }
                } else {
                    $where['id'] = $find['id'];
                    $result      = $member_object
                        ->where($where)
                        ->setField(array('status' => 1, 'update_time' => time()));
                    if ($result) {
                        $return['status']        = 1;
                        $return['info']          = '添加成功' . $member_object->getError();
                        $return['member_status'] = 1;
                    } else {
                        $return['status'] = 0;
                        $return['info']   = '添加失败' . $member_object->getError();
                    }
                }
            } else {
                $data = $member_object->create($con);
                if ($data) {
                    $result = $member_object->add($data);
                    if ($result) {
                        $return['status']        = 1;
                        $return['info']          = '添加成功' . $member_object->getError();
                        $return['member_status'] = 1;
                    } else {
                        $return['status'] = 0;
                        $return['info']   = '添加失败' . $member_object->getError();
                    }
                }
            }

            // 获取数据
            $user_info   = D('Admin/User')->getUserInfo($member_uid);
            $manual_info = D('Manual/Manual')->find($mid);

            // 发送消息
            if (1 == $return['member_status']) {
                $msg_data['content'] = $user_info['nickname'] . '您好：<br>' . '您已成功被加入手册' . $manual_info['title_url'];
            } else {
                $msg_data['content'] = $user_info['nickname'] . '您好：<br>' . '您已成功被移出手册' . $manual_info['title_url'];
            }
            $msg_data['to_uid'] = $member_uid;
            $msg_data['title']  = '手册通知';
            $msg_data['url']    = oc_url('Manual/Index/read/', array('name' => $manual_info['name']), true, true);
            $msg_return         = D('User/Message')->sendMessage($msg_data);

            // 返回结果
            $return['url'] = U('Manual/Member/index', array('mid' => $mid));
            $this->ajaxReturn($return);
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增成员') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('mid', 'hidden', '手册ID', '手册ID')
                ->addFormItem('uid', 'uid', 'UID', 'UID')
                ->setFormData(array('mid' => $mid))
                ->display();
        }
    }

    /**
     * 手册列表
     * @author jry <598821125@qq.com>
     */
    public function my()
    {
        // 获取所有手册
        $map           = array();
        $map['status'] = 1;
        $map['uid']    = $this->is_login();
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list     = D('Member')
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            D('Index')->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        $manual_model = D('Index');
        foreach ($data_list as $key => &$val) {
            $info             = $manual_model->find($val['data_id']);
            $val['title_url'] = $info['title_url'];
        }

        $attr['name']  = 'page';
        $attr['title'] = '章节管理';
        $attr['class'] = 'label label-success-outline label-pill';
        $attr['href']  = U('Manual/ManualPage/index', array('mid' => '__data_id__'));

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('手册列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addTopButton('delete') // 添加删除按钮
            ->addTableColumn('title_url', '手册标题')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->setTableDataListKey('data_id')
            ->addRightButton('self', $attr) // 添加章节管理按钮
            ->display();
    }
}
