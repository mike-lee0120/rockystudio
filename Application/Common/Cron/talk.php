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
/**
 * 如果超时用户未查阅私信则发消息和邮件给他
 */
if (defined('BIND_MODULE') && BIND_MODULE === 'Install') {
    return;
}

$sql   = 'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = "' . C('DB_NAME') . '" AND table_name = "' . C('DB_PREFIX') . 'im_message";';
$exist = M('')->query($sql);
if ($exist[0]['COUNT(*)'] === '1') {
    $con                = array();
    $con['status']      = 1;
    $con['is_read']     = 0;
    $con['is_pushed']   = 0;
    $con['create_time'] = array('lt', time() - 5);
    $talk_object        = D('Im/Message');
    $message_object     = D('User/Message');
    $unread_talk        = $talk_object->where($con)->select();
    foreach ($unread_talk as $t) {
        // 构造消息数据
        $msg_data['from_uid'] = $t['from_uid'];
        $msg_data['to_uid']   = $t['to_uid'];
        $msg_data['url']      = U('Im/Index/detail', array('id' => $t['from_uid']));
        $msg_data['title']    = $t['user']['nickname'] . '：' . $t['message'];
        $msg_data['content']  = '您好：<br>'
        . '您有来自' . $t['user']['nickname'] . '新消息：' . $t['message'] . '，马上登录<a href="' . C('HOME_PAGE') . '">' . C('WEB_SITE_TITLE') . '</a>查看<br>'
            . '<br>';
        $result = $message_object->sendMessage($msg_data, array('push', 'weixin')); // 用微信和APP推送提醒用户
        if ($result) {
            $talk_object->where(array('id' => $t['id']))->setField('is_pushed', '1');
        }
    }
}
