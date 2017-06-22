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
namespace User\Controller;

use Home\Controller\HomeController;
use lyf\Page;

/**
 * 消息控制器
 * @author jry <598821125@qq.com>
 */
class MessageController extends HomeController
{
    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize()
    {
        parent::_initialize();
        $this->is_login();
    }

    /**
     * 默认方法
     * @param $type 消息类型
     * @author jry <598821125@qq.com>
     */
    public function index($type = 0)
    {
        $map['type']    = array('eq', $type);
        $map['status']  = array('eq', 1);
        $map['to_uid']  = array('eq', is_login());
        $message_object = D('User/Message');
        $p              = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list      = $message_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('sort desc,id desc')
            ->select();
        $page = new Page(
            $message_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );
        $message_type     = $message_object->message_type();
        $new_message_type = array();
        foreach ($message_type as $key => $val) {
            if ($count = D('User/Message')->newMessageCount($key)) {
                $new_message_type[$key] = $count;
            }
        }
        $this->assign('message_list', $data_list);
        $this->assign('page', $page->show());
        $this->assign('message_type', $message_type);
        $this->assign('new_message_type', $new_message_type);
        $this->assign('current_type', $type);
        $this->assign('meta_title', "消息中心");
        $this->display();
    }

    /**
     * 查看消息
     * @param $type 消息类型
     * @author jry <598821125@qq.com>
     */
    public function detail($id)
    {
        $message_object    = D('User/Message');
        $user_message_info = $message_object->find($id);
        if (!$user_message_info) {
            $this->error('该消息已禁用或不存在');
        }
        $map['id'] = array('eq', $id);
        $message_object->where($map)->setField('is_read', 1);
        $this->assign('user_message_info', $user_message_info);
        $this->assign('current_type', $user_message_info['type']);
        $this->assign('meta_title', $user_message_info['title']);
        $this->display();
    }

    /**
     * 获取当前用户未读消息数量
     * @param $type 消息类型
     * @author jry <598821125@qq.com>
     */
    public function newMessageCount($type = null)
    {
        $data['status']      = 1;
        $data['new_message'] = D('User/Message')->newMessageCount($type);
        $this->ajaxReturn($data);
    }

    /**
     * 设置已读
     * @param $type 消息类型
     * @author jry <598821125@qq.com>
     */
    public function setRead($ids = null, $type = null)
    {
        $map['status']  = array('eq', 1);
        $map['to_uid']  = array('eq', is_login());
        $map['is_read'] = array('eq', 0);
        if ($ids !== null) {
            $map['id'] = array('in', $ids);
        }
        if ($type !== null) {
            $map['type'] = array('eq', $type);
        } else {
            if ($ids === null) {
                $this->error('请勾选消息');
            }
        }
        $result = D('User/Message')->where($map)->setField('is_read', 1);
        if ($result) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }
}
