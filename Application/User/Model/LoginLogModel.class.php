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
 * 用户登录历史模型
 * @author jry <598821125@qq.com>
 */
class LoginLogModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'user_login_log';

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
        array('device', 'get_device_info', self::MODEL_INSERT, 'callback'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 登录类型
     * @author jry <598821125@qq.com>
     */
    public function login_type($id)
    {
        $list[0] = '网页';
        $list[1] = 'IOS';
        $list[2] = 'Android';
        return $id ? $list[$id] : $list;
    }

    /**
     * 登录类型
     * @author jry <598821125@qq.com>
     */
    protected function get_device_info()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }
}
