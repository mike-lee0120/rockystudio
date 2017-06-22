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
namespace Vip\Controller;

use Home\Controller\HomeController;

/**
 * 订单控制器
 * @author jry <598821125@qq.com>
 */
class OrderController extends HomeController
{
    /**
     * 开通会员
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 会员检测
        $uid = $this->is_login();
        if (D('Vip/Index')->isVip($uid)) {
            $this->error('您已经开通了会员');
        }
        if (request()->isPost()) {
            $type_object = D('Vip/Type');
            $vip_type    = I('vip_type');
            if (!$vip_type || !I('buy_period')) {
                $this->error('会员类型和开通时间不完整');
            }
            $vip_type_info = $type_object->find($vip_type);
            if (!$vip_type_info) {
                $this->error('会员类型错误');
            }

            // 创建订单
            $data['out_trade_no']   = \lyf\Str::createOutTradeNo();
            $data['vip_type']       = $vip_type;
            $data['buy_period']     = I('buy_period');
            $data['original_price'] = sprintf("%.2f", $vip_type_info['price'] * I('buy_period'));
            $data['total_price']    = $data['original_price'];
            $order_object           = D('Vip/Order');
            $add_data               = $order_object->create($data);
            if ($add_data) {
                $result = $order_object->add($add_data);
                if ($result) {
                    $pay_data['out_trade_no'] = $add_data['out_trade_no'];
                    $pay_data['title']        = '开通VIP';
                    $pay_data['money']        = $add_data['total_price'];
                    $pay_data['callback']     = 'model:Vip/Order:callback';
                    $pay_data['return_url']   = U('Vip/Index/index', '', true, true);
                    $pay_data['allow_redbag'] = true;
                    $pay_data['allow_coupon'] = true;
                    $pay_data['allow_third']  = true;
                    $pay_data['allow_money']  = true;
                    session('pay_data_' . $pay_data['out_trade_no'], $pay_data);
                    $this->success('订单创建成功', U('Wallet/Index/pay', array('out_trade_no' => $pay_data['out_trade_no']), true, true), array('out_trade_no' => $pay_data['out_trade_no']));
                } else {
                    $this->error('订单错误' . $order_object->getError());
                }
            } else {
                $this->error('订单错误' . $order_object->getError());
            }
        } else {
            // 会员类型列表
            $vip_type_list = D('Vip/Type')->where('status="1"')->select();
            $this->assign('vip_type_list', $vip_type_list);
            $this->assign('meta_title', "开通会员");
            $this->display();
        }
    }

    /**
     * 会员升级
     * 用于从低level升级到高level
     * @author jry <598821125@qq.com>
     */
    public function upgrade()
    {
        // 会员检测
        $uid = $this->is_login();
        if (!D('Vip/Index')->isVip($uid)) {
            $this->error('请先开通会员');
        }

        // 会员信息
        $vip_info = D('Vip/Index')->vipInfo($uid);

        // 计算会员剩余时间，不足一月按一月计算，比如1.5月按2月计算
        $left_month = ceil(($vip_info['expire'] - time()) / (86400 * 30));

        // 升级
        if (request()->isPost()) {
            if (!I('post.vip_type')) {
                $this->error('请选择会员类型');
            }
            $type_object   = D('Vip/Type');
            $vip_type_info = $type_object->find(I('post.vip_type'));
            if (!$vip_type_info) {
                $this->error('会员类型错误');
            }
            if ($vip_type_info['level'] <= $vip_info['type_info']['level']) {
                $this->error('升级的会员等级必须大于当前等级');
            }

            // 创建订单
            $data['out_trade_no']   = \lyf\Str::createOutTradeNo();
            $data['vip_type']       = I('post.vip_type');
            $data['buy_period']     = $left_month;
            $data['order_type']     = 1; // 表示该订单是升级VIP
            $data['original_price'] = sprintf("%.2f", ($vip_type_info['price'] - $vip_info['type_info']['price']) * $left_month);
            $data['total_price']    = $data['original_price'];
            $order_object           = D('Vip/Order');
            $add_data               = $order_object->create($data);
            if ($add_data) {
                $result = $order_object->add($add_data);
                if ($result) {
                    $pay_data['out_trade_no'] = $add_data['out_trade_no'];
                    $pay_data['title']        = '升级VIP';
                    $pay_data['money']        = $add_data['total_price'];
                    $pay_data['callback']     = 'model:Vip/Order:callback';
                    $pay_data['return_url']   = U('Vip/Index/index', '', true, true);
                    $pay_data['allow_redbag'] = true;
                    $pay_data['allow_coupon'] = true;
                    $pay_data['allow_third']  = true;
                    $pay_data['allow_money']  = true;
                    session('pay_data_' . $pay_data['out_trade_no'], $pay_data);
                    $this->success('订单创建成功', U('Wallet/Index/pay', array('out_trade_no' => $pay_data['out_trade_no']), true, true), array('out_trade_no' => $pay_data['out_trade_no']));
                } else {
                    $this->error('订单错误' . $order_object->getError());
                }
            } else {
                $this->error('订单错误' . $order_object->getError());
            }

        } else {
            // 会员类型列表
            $con           = array();
            $con['status'] = 1;
            $con['level']  = array('gt', $vip_info['type_info']['level']);
            $vip_type_list = D('Vip/Type')->where($con)->select();
            if (!$vip_type_list) {
                $this->error('无需升级');
            }
            foreach ($vip_type_list as $key => &$val) {
                $val['upgrade_price'] = ($val['price'] - $vip_info['type_info']['price']) * $left_month;
            }

            $this->assign('vip_info', $vip_info);
            $this->assign('vip_type_list', $vip_type_list);
            $this->assign('meta_title', "会员升级");
            $this->display();
        }
    }
}
