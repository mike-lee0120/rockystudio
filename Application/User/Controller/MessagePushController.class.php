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

/**
 * 推送设备记录控制器
 * @author jry <598821125@qq.com>
 */
class MessagePushController extends HomeController
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
    public function add($token)
    {
        $data['token'] = $token;
        $data['uid']   = is_login();
        $push_object   = D('MessagePush');
        $create_data   = $push_object->create($data);
        if ($create_data) {
            $exist = $push_object->where(array('session_id' => session_id()))->find();
            if ($exist) {
                $result = $push_object->where(array('id' => $exist['id']))->save($create_data);
            } else {
                $create_data['session_id'] = session_id();
                $result                    = $push_object->add($create_data);
            }
            if ($result) {
                $this->success('推送Token上传成功');
            } else {
                $this->error('错误：' . $push_object->getError());
            }
        } else {
            $this->error('错误：' . $push_object->getError());
        }
    }
}
