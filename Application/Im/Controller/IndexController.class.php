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
namespace Im\Controller;

use Home\Controller\HomeController;
use lyf\Page;

/**
 * 消息控制器
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
        $this->is_login();
    }

    /**
     * 消息列表
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 获取对话信息
        $uid           = $this->is_login();
        $map           = array();
        $map['status'] = 1;
        $map['uid']    = $uid;

        $index_object = D('Index');
        $recent_list  = $index_object->where($map)->order('update_time desc, id desc')->select();

        foreach ($recent_list as $key => &$val) {
            switch ($val['type']) {
                case '0': // 个人与个人聊天
                    $map            = array();
                    $map['status']  = 1;
                    $map['id']      = array('eq', $val['data_id']);
                    $message_object = D('Message');
                    $last_talk      = $message_object->where($map)->find();

                    // 最新消息内容
                    $val['user_info']          = D('Admin/User')->getUserInfo($val['im_id']);
                    $val['message']            = $last_talk['message'];
                    $val['create_time_format'] = time_format($val['create_time']);

                    // 获取新消息数量
                    $map                      = array();
                    $map['from_uid']          = $val['im_id'];
                    $map['to_uid']            = $uid;
                    $map['is_read']           = 0;
                    $val['new_message_count'] = $message_object->where($map)->count();
                    break;
                case '1': // 群聊
                    $map                = array();
                    $map['status']      = 1;
                    $map['id']          = array('eq', $val['data_id']);
                    $qun_message_object = D('QunMessage');
                    $last_talk          = $qun_message_object->where($map)->find();

                    // 最新消息内容
                    $val['qun_info']           = D('Im/Qun')->find($val['im_id']);
                    $val['message']            = $last_talk['message'];
                    $val['create_time_format'] = time_format($val['create_time']);
                    break;
                case '2': // 公众号
                    $map               = array();
                    $map['status']     = 1;
                    $map['id']         = array('eq', $val['data_id']);
                    $mp_message_object = D('MpMessage');
                    $last_talk         = $mp_message_object->where($map)->find();

                    // 最新消息内容
                    $val['mp_info']            = D('Im/Mp')->find($val['im_id']);
                    $val['message']            = $last_talk['message'];
                    $val['create_time_format'] = time_format($val['create_time']);
                    break;
            }
        }

        // 模板变量赋值
        $this->assign('recent_list', $recent_list);
        $this->assign('meta_title', '消息列表');
        $this->display();
    }

    /**
     * 聊天对话
     * @author jry <598821125@qq.com>
     */
    public function detail($id)
    {
        // 获取对话信息
        $uid = $this->is_login();
        if ($id === $uid) {
            $this->error('自己不能与自己对话');
        }
        $con['status']  = 1;
        $con['_string'] = "(to_uid = $id AND from_uid = $uid) OR (to_uid = $uid AND from_uid = $id)";
        $talk_list      = D('Im/Message')->where($con)->page($_GET['p'] ?: 1, 10)->order('create_time desc,id desc')->select();

        // 设为已读
        foreach ($talk_list as $key => &$val) {
            if (!$val['is_read']) {
                $this->setRead($val['id']);
            }
            if ($val['from_uid'] == $uid) {
                $val['type'] = 'sent';
            } else {
                $val['type'] = 'received';
            }
        }

        // 模板变量赋值
        $to_user_info = D('Admin/User')->getUserInfo($id);
        $this->assign('talk_list', array_reverse($talk_list));
        $this->assign('to_user_info', $to_user_info);
        $this->assign('meta_title', $to_user_info['nickname']);
        $this->display();
    }

    /**
     * 删除消息列表记录
     * @author jry <598821125@qq.com>
     */
    public function delete($id)
    {
        $message_object = D('Index');
        $result         = $message_object->delete($id);
        if ($result) {
            $this->success('删除成功');
        } else {
            $this->error($message_object->getError());
        }
    }

    /**
     * 获取新消息
     * @author jry <598821125@qq.com>
     */
    public function get_new_message($to_uid)
    {
        $uid             = $this->is_login();
        $con['status']   = 1;
        $con['is_read']  = 0;
        $con['to_uid']   = array('eq', $uid);
        $con['from_uid'] = array('eq', $to_uid);
        $message_object  = D('Message');
        $talk_list_ids   = $message_object->where($con)->order('create_time desc,id desc')->getField('id', true);
        $talk_list       = $message_object->where($con)->order('create_time desc,id desc')->select();
        if ($talk_list_ids) {
            $this->setRead($talk_list_ids);
        }
        if ($talk_list) {
            $return['status']       = 1;
            $return['info']         = '获取成功';
            $return['data']         = $talk_list;
            $return['to_user_info'] = D('Admin/User')->getUserInfo($to_uid);
        } else {
            $return['status'] = 0;
            $return['info']   = '获取失败';
        }
        $this->ajaxReturn($return);
    }

    /**
     * 设置已读
     * @param $type 消息类型
     * @author jry <598821125@qq.com>
     */
    public function setRead($ids)
    {
        D('Im/Message')->setRead($ids);
    }

    /**
     * 发送消息
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (I('to_uid') === is_login()) {
            $this->error('自己不能与自己对话');
        }
        $message_object = D('Message');
        $result         = $message_object->sendMessage($_POST);
        if ($result) {
            $this->success('发送成功');
        } else {
            $this->error($message_object->getError());
        }
    }
}
