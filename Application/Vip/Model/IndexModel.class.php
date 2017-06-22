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
 * 默认模型
 * @author jry <598821125@qq.com>
 */
namespace Vip\Model;

use Common\Model\Model;

class IndexModel extends Model
{
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'vip_index';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('uid', 'require', '会员ID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('uid', '', '您已经是会员', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
        //array('vip_type', 'require', '会员类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('expire', 'require', '到期时间不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
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
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_find(&$result, $options)
    {
        $result['expire_format'] = time_format($result['expire'], 'Y-m-d H:i:s');
        $result['type_info']     = D('Vip/Type')->find($result['vip_type']);
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

    /**
     * VIP
     * 过期的会员也属于false
     * @author jry <598821125@qq.com>
     */
    public function isVip($uid)
    {
        if (!$uid) {
            return false;
        }
        $con['uid']    = $uid;
        $con['status'] = 1;
        $con['expire'] = array('gt', time());
        $result        = $this->where($con)->find();
        if (!$result['type_info']) {
            return false;
        }
        if ($result) {
            return $result['id'];
        }
        return false;
    }

    /**
     * VIP信息
     * 本方法主要用于显示VIP信息，包括过期等状态。
     * @author jry <598821125@qq.com>
     */
    public function vipInfo($uid)
    {
        if (!$uid) {
            return false;
        }
        $con['uid']    = $uid;
        $con['status'] = 1;
        $result        = $this->where($con)->find();
        if (!$result['type_info']) {
            return false;
        }
        if ($result['expire'] <= time()) {
            $result['is_expired'] = '1';
        }
        return $result;
    }
}
