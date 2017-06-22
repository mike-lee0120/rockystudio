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
 * 发送邮件列表里的邮件
 */
if (defined('BIND_MODULE') && BIND_MODULE === 'Install') {
    return;
}

if (D('Admin/Addon')->where('name="Email" and status="1"')->count()) {
    $sql   = 'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = "' . C('DB_NAME') . '" AND table_name = "' . C('DB_PREFIX') . 'addon_email";';
    $exist = M('')->query($sql);
    if ($exist[0]['COUNT(*)'] === '1') {
        $email_object = D('Addons://Email/Email');
        $info         = $email_object->where(array('status' => 1, 'is_sent' => 0))->order('id asc')->find();
        do {
            $result = false;
            $result = $email_object->send($info);
            if ($result) {
                $email_object->where(array('id' => $info['id']))->setField('is_sent', 1);
            } else {
                $email_object->where(array('id' => $info['id']))->setField('status', 0);
            }
            $info = $email_object->where(array('status' => 1, 'is_sent' => 0))->order('id asc')->find();
        } while ($info);
    }
}
