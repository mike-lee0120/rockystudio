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
namespace Im\Model;

use Common\Model\Model;

/**
 * 群聊模型
 * @author jry <598821125@qq.com>
 */
class QunModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'im_qun';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('title', 'require', '请填写群名称', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('abstract', 'require', '请填写群简介', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_find(&$result, $options)
    {
        $result['user'] = D('Admin/User')->getUserInfo($result['uid']);
        if ($result['cover']) {
            $result['cover_url'] = get_cover($result['cover'], 'default');
        }

        // 加入信息
        $result['member_status'] = D('Im/QunMember')->get_status($result['id']);
    }

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_select(&$result, $options)
    {
        foreach ($result as &$record) {
            $this->_after_find($record, $options);
        }
    }
}
