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
namespace Wallet\Model;

use Common\Model\Model;

/**
 * 红包模型
 * @author jry <598821125@qq.com>
 */
class RedbagModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'wallet_redbag';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('account', 'require', '手机号必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', 'require', '红包标题必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('money', 'require', '金额必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('expire', 'require', '过期时间必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('limit', 'require', '最低消费必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('is_used', '0', self::MODEL_INSERT),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 根据ID获取可用红包
     * @author jry <598821125@qq.com>
     */
    public function get_redbag($id, $money)
    {
        if ($id <= 0) {
            $this->error = '红包ID错误';
            return false;
        }
        $user_info = D('Admin/User')->getUserInfo(is_login());
        if ($user_info['mobile'] && $user_info['mobile_bind']) {
            // 获取红包信息
            $info = $this->where(array('account' => $user_info['mobile']))->find($id);
            if (!$info) {
                $this->error = '不存在该红包';
                return false;
            }
            if (!$info['status']) {
                $this->error = '该红包已禁用';
                return false;
            }
            if ($info['is_used']) {
                $this->error = '该红包已使用';
                return false;
            }
            if (time() > $info['expire']) {
                $this->error = '该红包已过期';
                return false;
            }
            if ($money < $info['limit']) {
                $this->error = '该红包满' . $info['limit'] . '元才可以使用';
                return false;
            }
            return $info;
        } else {
            $this->error = '用户信息不完整';
            return false;
        }
    }

    /**
     * 获取可用的红包列表
     * @author jry <598821125@qq.com>
     */
    public function get_available($money)
    {
        $user_info = D('Admin/User')->getUserInfo(is_login());
        if ($user_info['mobile'] && $user_info['mobile_bind']) {
            $con['status']  = 1;
            $con['account'] = $user_info['mobile'];
            $con['expire']  = array('gt', time());
            $con['is_used'] = 0;
            $con['limit']   = array('elt', $money);
            return $this->where($con)->select();
        }
    }
}
