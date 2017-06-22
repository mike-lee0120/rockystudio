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
 * 提现模型
 * @author jry <598821125@qq.com>
 */
class WithdrawModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'wallet_withdraw';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('uid', 'require', 'UID必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('uid', 'number', 'UID必须数字', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('money', 'require', '金额必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('money', 'checkMoney', '超出您的余额', self::MUST_VALIDATE, 'callback', self::MODEL_INSERT),
        array('type', 'require', '提现方式必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('realname', 'require', '账户姓名必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('account', 'require', '账户/卡号必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '0', self::MODEL_INSERT),
    );

    /**
     * 提现状态
     * @author jry <598821125@qq.com>
     */
    public function pay_status($id)
    {
        $list[1]  = '提现成功';
        $list[-1] = '提现失败';
        $list[0]  = '未处理';
        return isset($id) ? $list[$id] : $list;
    }

    /**
     * 检测余额
     * @return boolean ture 未超出，false 超出
     */
    protected function checkMoney($money)
    {
        $my_money = D('Admin/User')->getUserInfo(is_login(), 'money');
        if (($my_money - $money) >= '0') {
            return true;
        }
        return false;
    }
}
