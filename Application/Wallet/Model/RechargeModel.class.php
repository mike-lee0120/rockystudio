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
 * 充值模型
 * @author jry <598821125@qq.com>
 */
class RechargeModel extends Model
{
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'wallet_recharge';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('out_trade_no', 'require', '订单号错误', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('out_trade_no', '', '订单号已存在', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
        array('money', 'require', '请填写金额', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('pay_type', 'require', '支付方式错误', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('is_pay', '0', self::MODEL_INSERT),
        array('is_callback', '0', self::MODEL_INSERT),
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 支付状态
     * @author jry <598821125@qq.com>
     */
    public function is_pay($id)
    {
        $list['1'] = '<span class="label label-success">已支付</span>';
        $list['0'] = '<span class="label label-danger">未支付</span>';
        return isset($id) ? $list[$id] : $list;
    }

    /**
     * 回调状态
     * @author jry <598821125@qq.com>
     */
    public function is_callback($id)
    {
        $list['0'] = '<span class="label label-warning">未到账</span>';
        $list['1'] = '<span class="label label-success">已到账</span>';
        return isset($id) ? $list[$id] : $list;
    }
}
