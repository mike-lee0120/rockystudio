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

/**
 * 加群控制器
 * @author jry <598821125@qq.com>
 */
class QunMemberController extends HomeController
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
     * 加群
     * @author jry <598821125@qq.com>
     */
    public function add($data_id)
    {
        $qun_member_object = D('Im/QunMember');
        $con['data_id']    = $data_id;
        $con['uid']        = $this->is_login();
        $find              = $qun_member_object->where($con)->find();
        if ($find) {
            if ($find['status'] === '1') {
                $where['id'] = $find['id'];
                $result      = $qun_member_object
                    ->where($where)
                    ->setField(array('status' => -1, 'update_time' => time()));
                if ($result) {
                    $return['status']        = 1;
                    $return['info']          = '退群成功' . $qun_member_object->getError();
                    $return['member_status'] = 0;

                    $this->ajaxReturn($return);
                } else {
                    $return['status']        = 0;
                    $return['info']          = '退群失败' . $qun_member_object->getError();
                    $return['member_status'] = 1;
                    $this->ajaxReturn($return);
                }
            } else {
                $where['id'] = $find['id'];
                $result      = $qun_member_object
                    ->where($where)
                    ->setField(array('status' => 0, 'update_time' => time()));
                if ($result) {
                    $return['status']        = 1;
                    $return['info']          = '申请加入成功，请等待审核！';
                    $return['member_status'] = 1;

                    $this->ajaxReturn($return);
                } else {
                    $return['status'] = 0;
                    $return['info']   = '加群失败' . $qun_member_object->getError();
                    $this->ajaxReturn($return);
                }
            }
        } else {
            $data = $qun_member_object->create($con);
            if ($data) {
                $result = $qun_member_object->add($data);
                if ($result) {
                    $return['status']        = 1;
                    $return['info']          = '申请加入成功，请等待审核！';
                    $return['member_status'] = 1;

                    $this->ajaxReturn($return);
                } else {
                    $return['status'] = 0;
                    $return['info']   = '加群失败' . $qun_member_object->getError();
                    $this->ajaxReturn($return);
                }
            }
        }
    }
}
