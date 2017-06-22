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
 * VIP订单模型
 * @author jry <598821125@qq.com>
 */
namespace Vip\Model;

use Common\Model\Model;

class OrderModel extends Model
{
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'vip_order';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('out_trade_no', 'require', '订单号不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('vip_type', 'require', 'VIP类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('buy_period', 'require', '购买时长不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('original_price', 'require', '原价不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('total_price', 'require', '现价不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('is_pay', '0', self::MODEL_INSERT),
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
        $result['vip_type_title'] = D('Vip/Type')->getFieldById($result['vip_type'], 'title');
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
     * 开通会员回调接口
     * @author jry <598821125@qq.com>
     */
    public function callback($out_trade_no)
    {
        // 验证参数
        if (!$out_trade_no) {
            $this->error = '参数错误';
            return false;
        }

        // 根据订单号获取订单信息
        $order_info = $this->where(array('out_trade_no' => $out_trade_no))->find();
        if (!$order_info) {
            $this->error = '不存在该订单';
            return false;
        }
        if ($order_info['data_id'] > 0) {
            $this->error = '已回调过';
            return false;
        }
        $result = $this->where(array('id' => $order_info['id']))->setField('is_pay', 1);
        if ($result !== false) {
            switch ($order_info['order_type']) {
                case '1': // 会员升级订单
                    // 先查找是否有存在的VIP记录
                    $index_object = D('Vip/Index');
                    $con          = array();
                    $con['uid']   = $order_info['uid'];
                    $exist        = $index_object->where($con)->find();
                    if (!$exist) {
                        $this->error = '升级会员时找不到会员记录' . $index_object->getError();
                        return false;
                    }
                    // 更新会员类型
                    $index_id = $index_object->where(array('id' => $exist['id']))->setField(array('vip_type' => $order_info['vip_type']));
                    break;

                default:
                    // 先查找是否有存在的VIP记录
                    $index_object = D('Vip/Index');
                    $con          = array();
                    $con['uid']   = $order_info['uid'];
                    $exist        = $index_object->where($con)->find();
                    if ($exist) {
                        // 创建会员记录
                        $vip_data             = array();
                        $vip_data['id']       = $exist['id'];
                        $vip_data['uid']      = $order_info['uid'];
                        $vip_data['vip_type'] = $order_info['vip_type'];
                        $vip_data['expire']   = time() + ($order_info['buy_period'] * 30 * 86400);
                        $index_data           = $index_object->create($vip_data);
                        if (!$index_data) {
                            $this->error = '创建会员记录异常' . $index_object->getError();
                            return false;
                        }
                        $index_id = $index_object->save($index_data);
                    } else {
                        // 创建会员记录
                        $vip_data             = array();
                        $vip_data['uid']      = $order_info['uid'];
                        $vip_data['vip_type'] = $order_info['vip_type'];
                        $vip_data['expire']   = time() + ($order_info['buy_period'] * 30 * 86400);
                        $index_data           = $index_object->create($vip_data);
                        if (!$index_data) {
                            $this->error = '创建会员记录异常' . $index_object->getError();
                            return false;
                        }
                        $index_id = $index_object->add($index_data);
                    }
                    break;
            }

            // 记录创建结果
            if ($index_id) {
                $status = $this->where(array('id' => $order_info['id']))->setField('data_id', $index_id);
            } else {
                $this->error = '创建会员记录异常' . $index_object->getError();
                return false;
            }
            if (!$status) {
                return false;
            }

            // 通用钩子
            $hook_data['url']  = request()->module() . '/' . request()->controller() . '/' . request()->action();
            $hook_data['data'] = $order_info;
            hook('CommonHook', $hook_data);

            return true;
        } else {
            return false;
        }
        return false;
    }
}
