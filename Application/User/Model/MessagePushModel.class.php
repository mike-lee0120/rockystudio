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
namespace User\Model;

use Common\Model\Model;

/**
 * 消息推送设备记录模型
 * @author jry <598821125@qq.com>
 */
class MessagePushModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'user_message_push';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('uid', 'require', 'UID必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('token', '1,127', 'token长度为1-127个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * OS类型
     * @author jry <598821125@qq.com>
     */
    public function os_type($id)
    {
        $list[1] = 'iOS';
        $list[2] = 'Android';
        $list[3] = 'Windows';
        return $id ? $list[$id] : $list;
    }
}
