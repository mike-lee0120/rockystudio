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
 * 用户余额模型
 * @author jry <598821125@qq.com>
 */
class IndexModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'wallet_index';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('out_trade_no', 'require', '订单号必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('out_trade_no', '', '订单已存在', self::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
        array('uid', 'require', 'UID必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('original_money', 'require', '原金额必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('money', 'require', '金额必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('title', 'require', '标题必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('title', '1,255', '标题长度为1-255个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_INSERT),
        array('type', 'require', '订单类型必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
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
        $list['0'] = '<span class="label label-danger">未完成</span>';
        $list['1'] = '<span class="label label-success">已成功</span>';
        return isset($id) ? $list[$id] : $list;
    }

    /**
     * 余额变动类型
     * @author jry <598821125@qq.com>
     */
    public function change_type($id)
    {
        $list[1] = '收入';
        $list[2] = '支出';
        return $id ? $list[$id] : $list;
    }

    /**
     * 余额变动类型
     * @author jry <598821125@qq.com>
     */
    public function change_type_code($id)
    {
        $list[1] = '+';
        $list[2] = '-';
        return $id ? $list[$id] : $list;
    }

    /**
     * 支付方式
     * @author jry <598821125@qq.com>
     */
    public function pay_type($id = null)
    {
        $list[0]            = '余额';
        $list['offline']    = '线下付款';
        $list['alipay']     = '支付宝';
        $list['aliwappay']  = '支付宝WAP';
        $list['alipayapp']  = '支付宝APP';
        $list['wxpay']      = '微信';
        $list['wxmpapppay'] = '微信(小程序)';
        $list['unionpay']   = '银联';
        $list['paypal']     = 'paypal';
        $list['yeepay']     = '易宝';
        $list['tenpay']     = '财付通';
        $list['kuaiqian']   = '快钱';
        return is_null($id) ? $list : ($list[$id] ?: $id);
    }

    /**
     * 余额
     * @author jry <598821125@qq.com>
     */
    public function money()
    {
        return '¥ ' . D('Admin/User')->getUserInfo(is_login(), 'money');
    }

    /**
     * 验证调用接口时传递的数据
     * @author jry <598821125@qq.com>
     */
    public function verify_pay_data($pay_data)
    {
        // 数据检查
        if (!$pay_data) {
            $this->error = '订单错误';
            return false;
        }
        if (!$pay_data['out_trade_no']) {
            $this->error = '缺少订单号';
            return false;
        }
        if (!$pay_data['title']) {
            $this->error = '缺少订单标题';
            return false;
        }
        if (!preg_match("/(^[1-9]\d*(\.\d{1,2})?$)|(^[0]{1}(\.\d{1,2})?$)/", $pay_data['money'])) {
            $this->error = '金额格式错误';
            return false;
        }
        if ($pay_data['money'] <= 0) {
            $this->error = '金额错误';
            return false;
        }
        if (!$pay_data['callback']) {
            $this->error = '缺少回调地址';
            return false;
        }
        if (0 !== strpos($pay_data['callback'], 'http')) {
            if (!$pay_data['return_url']) {
                $this->error = '缺少返回地址';
                return false;
            }
        }
        if ($pay_data['allow_third'] === true) {
            // 开启第三方支付必须用二代回调
            if (0 === strpos($pay_data['callback'], 'http')) {
                $this->error = '回调方法格式错误';
                return false;
            }
        }
        return true;
    }

    /**
     * 付款
     * 已自动获取变动前后余额，自动标记为已支付 严格状态下必须先调用check_order校验数据
     * @author jry <598821125@qq.com>
     */
    public function pay($out_trade_no)
    {
        $user_model = D('Admin/User');
        if (is_array($out_trade_no)) {
            // 不通过前端页面支付，系统内部支付
            $pay_data = $out_trade_no;
            if (!$pay_data['money']) {
                $this->error = '订单金额错误';
                return false;
            }
            if (!$pay_data['uid']) {
                $this->error = '缺少UID';
                return false;
            }
            $user_info = $user_model->find($pay_data['uid']);
            if ($user_info['money'] < $pay_data['money']) {
                $this->error = '余额不足';
                return false;
            }
            $_pay_data                   = array();
            $_pay_data['type']           = 2;
            $_pay_data['is_callback']    = 1;
            $_pay_data['is_pay']         = 1;
            $_pay_data['pay_type']       = $pay_data['pay_type'] ?: '0';
            $_pay_data['out_trade_no']   = $pay_data['out_trade_no'] ?: \lyf\Str::createOutTradeNo();
            $_pay_data['original_money'] = $pay_data['money'];
            $_pay_data['money']          = $pay_data['money'];
            $_pay_data['uid']            = $pay_data['uid'];
            $_pay_data['before_money']   = $user_model->getFieldById($pay_data['uid'], 'money');
            $_pay_data['after_money']    = bcsub($user_model->getFieldById($pay_data['uid'], 'money'), $_pay_data['money'], 2);
            $_pay_data['title']          = $pay_data['title'] ?: '余额付款';
            $data                        = $this->create($_pay_data);
            if ($data) {
                $add_result = $this->add($data);
                if (!$add_result) {
                    \Think\Log::record('pay付款失败');
                    return false;
                }
                $map       = array();
                $map['id'] = $data['uid'];
                $result    = $user_model->where($map)->setDec('money', $data['money']);
                if ($result) {
                    // 构造消息数据
                    $msg_data            = array();
                    $msg_data['to_uid']  = $data['uid'];
                    $msg_data['title']   = '付款成功';
                    $msg_data['content'] = '您好：<br>'
                    . '您在' . C('WEB_SITE_TITLE') . '关于' . $data['title'] . '的款项共' . $data['money'] . '元已支付。<br>'
                        . '<br>';
                    $status = D('User/Message')->sendMessage($msg_data);
                    return $_pay_data['out_trade_no'];
                } else {
                    $this->error = $user_model->getError();
                    \Think\Log::record('pay余额支付失败');
                    $this->delete($add_result);
                    return false;
                }
            } else {
                return false;
            }

        }

        //前端页面支付流程
        $pay_data = $this->where(array('out_trade_no' => $out_trade_no))->find();
        if (!$pay_data) {
            $this->error = '订单不存在';
            return false;
        }
        if ($pay_data['is_pay']) {
            $this->error = '订单已支付';
            return false;
        }
        if ($pay_data['ready'] <= 0) {
            $this->error = '订单未就绪';
            return false;
        }

        //付款前用户余额
        $user_info = $user_model->find($pay_data['uid']);
        if (!$user_info) {
            $this->error = '用户不存在';
        }
        $pay_success                 = array();
        $pay_success['before_money'] = $user_info['money'];

        // 如果是不允许三方支付方式则强制要求余额必须大于0.01
        if (!$pay_data['allow_third']) {
            if ($pay_data['money'] < 0.01) {
                $this->error = '支付金额必须大于0.01元！';
                return false;
            }
        }

        // 余额处理
        $map       = array();
        $map['id'] = $pay_data['uid'];
        if ($pay_data['money'] > 0) {
            if (($user_info['money'] - $pay_data['money']) < 0) {
                $this->error = '余额不足，请充值！';
                return false;
            }
            $result = $user_model->where($map)->setDec('money', $pay_data['money']);
            if ($result === false) {
                $this->error = '余额变动错误' . $user_model->getError();
                return false;
            }
        }

        // 红包处理
        $redbag_object = D('Wallet/Redbag');
        $map           = array();
        $map['id']     = $pay_data['redbag_id'];
        if ($pay_data['redbag_id']) {
            $redbag_use = $redbag_object->where($map)->setField('is_used', '1');
            if (!$redbag_use) {
                $this->error = '红包处理错误';
                return false;
            }
        }

        // 折扣券处理
        $coupon_object = D('Wallet/Coupon');
        $map           = array();
        $map['id']     = $pay_data['coupon_id'];
        if ($pay_data['coupon_id']) {
            $coupon_use = $coupon_object->where($map)->setField('is_used', '1');
            if (!$coupon_use) {
                $this->error = '折扣券处理错误';
                return false;
            }
        }

        // 付款后用户余额
        $user_info                  = $user_model->find($pay_data['uid']);
        $pay_success['after_money'] = $user_info['money'];
        $pay_success['is_pay']      = 1;
        $ret                        = $this->where(array('out_trade_no' => $pay_data['out_trade_no']))->setField($pay_success);
        if ($ret === false) {
            return false;
        }

        // 构造消息数据
        $msg_data['to_uid']  = $pay_data['uid'];
        $msg_data['title']   = '付款成功';
        $msg_data['content'] = '您好：<br>'
        . '您在' . C('WEB_SITE_TITLE') . '的订单（' . $pay_data['out_trade_no'] . '）' . $pay_data['title'] . '已成功付款。<br>'
            . '<br>';
        D('User/Message')->sendMessage($msg_data);
        return true;
    }

    // 仅用于校验账单数据
    public function check_order($data)
    {
        if ($data['redbag_id']) {
            if (!$data['redbag_money']) {
                $data['redbag_money'] = D('Wallet/Rebag')->getFieldById($data['redbag_id'], 'money');
            }
        } else {
            $data['redbag_money'] = 0.00;
        }

        if ($data['coupon_id']) {
            $coupon = D('Wallet/Coupon')->getFieldById($data['coupon_id'], 'coupon');
        } else {
            $coupon = 10;
        }
        $ret = ($data['original_money'] * $coupon / 10 - $data['third_money'] - $data['money'] - $data['redbag_money']) == 0;

        if (!$ret) {
            $this->error = '账单校验异常';
            return false;
        }
    }

    /**
     * 仅支持控制器回调和模型回调
     * 余额充值成功回调接口
     * @param type $money
     * @param type $param
     */
    public function callback($out_trade_no)
    {
        $pay_data = $this->where(array('out_trade_no' => $out_trade_no))->find();
        if (!$pay_data) {
            $this->error = '订单不存在';
            return false;
        }
        if (!$pay_data['is_pay']) {
            $this->error = '订单未支付';
            return false;
        }
        if ($pay_data['is_callback']) {
            $this->error = '订单已回调成功';
            return true;
        }

        // 解析回调数据
        $callback = $pay_data['callback'];
        if (!$callback) {
            $this->error = '未找到该订单的回调方法';
            return false;
        }
        $callback = explode(':', $callback);
        if (!($callback[0] && $callback[1] && $callback[2])) {
            $this->error = '回调参数不完整或回调数据格式错误';
            return false;

        }

        // 判断回调类型
        switch ($callback[0]) {
            case 'action':
                $callback_object = A($callback[1]);
                break;
            case 'model':
                $callback_object = D($callback[1]);
                break;
            default:
                $this->error = '不支持的回调类型';
                return false;
                break;
        }

        // 判断回调是否真实存在
        if (!method_exists($callback_object, $callback[2])) {
            $this->error = '回调方法不存在2';
            return false;
        }

        // 调用回调方法
        $callback_status = call_user_func(array($callback_object, $callback[2]), $out_trade_no);
        if ($callback_status !== true) {
            switch ($callback[0]) {
                case 'action':
                    $this->error = $callback_object->error;
                    break;
                case 'model':
                    $this->error = $callback_object->getError();
                    break;
                default:
                    $this->error = '不支持的回调类型,无法捕获回调错误';
                    return false;
                    break;
            }

            $this->error = $callback[1] . '->' . $callback[2] . '()回调方法执行错误:' . $this->error;
            return false;
        }

        // 回调逻辑处理成功将订单标记为已回调
        $map                 = array();
        $map['out_trade_no'] = $out_trade_no;
        $ret                 = $this->where($map)->setField('is_callback', 1);
        if ($ret === false) {
            $this->error = '订单设为已回调错误' . $this->error;
            return false;
        }

        // 回调全部完成
        return true;
    }

    /**
     * 收款
     * @author jry <598821125@qq.com>
     */
    public function receipt($receipt_data)
    {
        if (!$receipt_data['money']) {
            $this->error = '订单金额错误';
            return false;
        }
        if (!$receipt_data['uid']) {
            $this->error = '缺少UID';
            return false;
        }
        $user_model                      = D('Admin/User');
        $_pay_data                       = array();
        $_receipt_data['type']           = 1;
        $_receipt_data['is_callback']    = 1;
        $_receipt_data['is_pay']         = 1;
        $_receipt_data['pay_type']       = $receipt_data['pay_type'] ?: '0';
        $_receipt_data['out_trade_no']   = $receipt_data['out_trade_no'] ?: \lyf\Str::createOutTradeNo();
        $_receipt_data['original_money'] = $receipt_data['money'];
        $_receipt_data['money']          = $receipt_data['money'];
        $_receipt_data['uid']            = $receipt_data['uid'];
        $_receipt_data['before_money']   = $user_model->getFieldById($receipt_data['uid'], 'money');
        $_receipt_data['after_money']    = bcadd($user_model->getFieldById($receipt_data['uid'], 'money'), $_receipt_data['money'], 2);
        $_receipt_data['title']          = $receipt_data['title'] ?: '余额收款';
        $data                            = $this->create($_receipt_data);
        if ($data) {
            $add_result = $this->add($data);
            if (!$add_result) {
                \Think\Log::record('receipt收款失败');
                return false;
            }
            $map       = array();
            $map['id'] = $data['uid'];
            $result    = $user_model->where($map)->setInc('money', $data['money']);
            if ($result) {
                // 构造消息数据
                $msg_data            = array();
                $msg_data['to_uid']  = $data['uid'];
                $msg_data['title']   = '到账成功';
                $msg_data['content'] = '您好：<br>'
                . '您在' . C('WEB_SITE_TITLE') . '关于' . $data['title'] . '的款项共' . $data['money'] . '元已到账。<br>'
                    . '<br>';
                $status = D('User/Message')->sendMessage($msg_data);
                return $_receipt_data['out_trade_no'];
            } else {
                $this->error = $user_model->getError();
                \Think\Log::record('receipt添加余额失败');
                $this->delete($add_result);
                return false;
            }
        } else {
            return false;
        }
    }
}
