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
namespace Wallet\Controller;

use Addons\Pay\ThinkPay\Pay;
use Home\Controller\HomeController;
use lyf\Page;

/**
 * 充值控制器
 * @author jry <598821125@qq.com>
 */
class RechargeController extends HomeController
{
    /**
     * 充值页面
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        $this->is_login();
        if (!C('wallet_config.toggle_recharge')) {
            $this->error('充值已关闭！');
        }

        // 充值
        if (request()->isPost()) {
            // 最少充值金额
            if (bcsub(I('post.money'), C('wallet_config.min_recharge'), 2) < 0) {
                $this->error('最少充值' . C('wallet_config.min_recharge') . '元！');
            }

            // 订单数据
            $pay_type                 = I('post.pay_type');
            $pay_data['out_trade_no'] = \lyf\Str::createOutTradeNo();
            $pay_data['money']        = sprintf("%0.2f", I('post.money'));
            $pay_data['pay_type']     = $pay_type;

            // 验证支付方式
            if (!$pay_data['pay_type']) {
                $this->error('请选择付款方式');
            }

            // 兼容支付宝Wap和App
            if (request()->isMobile() && $pay_type === 'alipay' && !C('IS_API')) {
                $pay_data['pay_type'] = 'aliwappay';
            }
            if ($pay_type === 'alipay' && C('IS_API')) {
                $pay_data['pay_type'] = 'alipayapp';
            }

            // 获取订单号
            $out_trade_no = $pay_data['out_trade_no'];

            // 创建订单
            $recharge_object = D('Wallet/Recharge');
            $data            = $recharge_object->create($pay_data);
            if (!$data) {
                $this->error('订单创建错误' . $recharge_object->getError());
            } else {
                $add_result = $recharge_object->add($data);
            }
            if (!$add_result) {
                $this->error('订单创建错误');
            }

            // APP支付则返回签名字符串
            if (C('IS_API')) {
                // 获取订单
                $recharge_object = D('Wallet/Recharge');
                $info            = $recharge_object->where(array('out_trade_no' => $out_trade_no))->find();
                $info['title']   = C('WEB_SITE_TITLE') . "余额充值";
                $info['body']    = C('WEB_SITE_TITLE') . "余额充值";

                // 获取支付配置
                $pay_config               = D('Addons://Pay/Pay')->pay_config($info['pay_type']);
                $pay_config['notify_url'] = U("Wallet/Recharge/notify", array('apitype' => $pay_type, 'out_trade_no' => $info['out_trade_no']), true, true);
                $pay_config['return_url'] = U("Wallet/Recharge/my", array('apitype' => $pay_type, 'out_trade_no' => $info['out_trade_no']), true, true);
                $pay                      = new Pay($info['pay_type'], $pay_config);
                $sign                     = $pay->buildRequestForm($info);
                if ($sign) {
                    // 前端仍然只识别alipay不识别alipayapp
                    if ($info['pay_type'] === 'alipayapp') {
                        $info['pay_type'] = 'alipay';
                    }
                    $this->success('打开支付', null, array('pay_type' => $info['pay_type'], 'json' => $sign['json'], 'string' => $sign['string']));
                } else {
                    $this->error('预支付订单生成失败');
                }
            } else {
                $this->success('打开支付页面', U('Wallet/Recharge/pay', array('out_trade_no' => $pay_data['out_trade_no']), true, true));
            }
        } else {
            // 是否首次充值
            $con               = array();
            $con['uid']        = $pay_data['uid'];
            $con['is_pay']     = 1;
            $is_index_recharge = D('Wallet/Recharge')->where($con)->count();
            if ($is_index_recharge) {
                if (C('wallet_config.toggle_recharge_bonuses_index')) {
                    $con           = array();
                    $con['type']   = 1; // type为1表示首次充值优惠类型
                    $con['status'] = 1;
                    $bonuses_list  = D('Wallet/RechargeBonuses')->where($con)->order('money asc')->select();
                    $this->assign('bonuses_title', '首次充值优惠');
                    $this->assign('bonuses_list', $bonuses_list);
                }
            } else {
                if (C('wallet_config.toggle_recharge_bonuses')) {
                    $con           = array();
                    $con['type']   = 0;
                    $con['status'] = 1;
                    $bonuses_list  = D('Wallet/RechargeBonuses')->where($con)->order('money asc')->select();
                    $this->assign('bonuses_title', '充值优惠');
                    $this->assign('bonuses_list', $bonuses_list);
                }
            }

            $allow_pay_type = D('Addons://Pay/Pay')->type_list();
            $this->assign('allow_pay_type', $allow_pay_type);
            $this->assign('meta_title', '余额充值');
            $this->assign('user_info', D('Admin/User')->getUserInfo(is_login())); // 用户信息
            $this->display();
        }
    }

    /**
     * 充值页面
     * @author jry <598821125@qq.com>
     */
    public function pay($out_trade_no)
    {
        // 获取订单
        $recharge_object = D('Wallet/Recharge');
        $info            = $recharge_object->where(array('out_trade_no' => $out_trade_no))->find();
        $info['title']   = C('WEB_SITE_TITLE') . "余额充值";
        $info['body']    = C('WEB_SITE_TITLE') . "余额充值";

        // 获取支付配置
        $pay_config               = D('Addons://Pay/Pay')->pay_config($info['pay_type']);
        $pay_config['notify_url'] = U("Wallet/Recharge/notify", array('apitype' => $pay_type, 'out_trade_no' => $info['out_trade_no']), true, true);
        $pay_config['return_url'] = U("Wallet/Recharge/my", array('apitype' => $pay_type, 'out_trade_no' => $info['out_trade_no']), true, true);
        $pay                      = new Pay($info['pay_type'], $pay_config);
        echo $pay->buildRequestForm($info);
    }

    /**
     * 支付结果返回
     */
    public function notify($out_trade_no)
    {
        // 获取异步通知信息
        if (request()->isPost()) {
            if ($_POST && count($_POST) > 1) {
                $notify = $_POST;
            } else {
                $notify = $this->xml2aray($GLOBALS['HTTP_RAW_POST_DATA']);
            }
        } else {
            E("Access Denied！");
        }

        // 支付成功将订单记录标记为已支付
        $recharge_object = D('Wallet/Recharge');
        $info            = $recharge_object->where(array('out_trade_no' => $out_trade_no))->find();
        if ($info && $info['out_trade_no'] === $out_trade_no && $notify) {
            // 获取支付配置
            $pay_config = D('Addons://Pay/Pay')->pay_config($info['pay_type']);
            $pay        = new Pay($info['pay_type'], $pay_config);
            // 验证
            if ($pay->verifyNotify($notify)) {
                //获取订单信息
                $pay_info = $pay->getInfo();
                if ($pay_info['status'] === true) {
                    if (!$info['is_pay']) {
                        // 设置支付成功标记
                        $con['out_trade_no']   = $pay_info['out_trade_no'];
                        $pay_success['is_pay'] = 1;
                        $is_pay                = $recharge_object->where($con)->setField($pay_success);
                        if ($is_pay) {
                            // 执行回调函数完成比如充值后的数据操作
                            $callback_status = $this->callback($out_trade_no);
                            if ($callback_status) {
                                $pay->notifySuccess();
                            } else {
                                \Think\Log::record('回调失败');
                                E("回调失败！");
                            }
                        } else {
                            \Think\Log::record('标记支付状态失败');
                            E("标记支付状态失败！ ");
                        }
                    } else {
                        if (!$info['is_callback']) {
                            // 执行回调函数完成比如充值后的数据操作
                            $callback_status = $this->callback($out_trade_no);
                            if ($callback_status) {
                                $pay->notifySuccess();
                            } else {
                                \Think\Log::record('再次回调失败');
                                E("再次回调失败！");
                            }
                        }
                    }
                } else {
                    \Think\Log::record('支付失败');
                    E("支付失败！ !");
                }
            } else {
                \Think\Log::record('信息验证失败');
                E("信息验证失败!");
            }
        } else {
            \Think\Log::record('订单不存在');
            E("订单不存在！ ");
        }
    }

    /**
     * 余额充值成功回调接口
     * @param type $money
     * @param type $param
     */
    private function callback($out_trade_no)
    {
        $recharge_object = D('Wallet/Recharge');
        $pay_data        = $recharge_object->where(array('out_trade_no' => $out_trade_no))->find();
        if ($pay_data && $pay_data['out_trade_no'] === $out_trade_no && $pay_data['is_pay'] && !$pay_data['is_callback']) {
            // 是否首次充值
            $con               = array();
            $con['uid']        = $pay_data['uid'];
            $con['is_pay']     = 1;
            $is_index_recharge = D('Wallet/Recharge')->where($con)->count();
            if ($is_index_recharge) {
                if (C('wallet_config.toggle_recharge_bonuses_index')) {
                    $con           = array();
                    $con['type']   = 1; // type为1表示首次充值优惠类型
                    $con['status'] = 1;
                    $con['money']  = array('elt', $pay_data['money']);
                    $bonuses       = D('Wallet/RechargeBonuses')->where($con)->order('bonuses desc')->find();
                    if ($bonuses) {
                        $pay_data['money']  = bcadd($pay_data['money'], $bonuses['bonuses'], 2);
                        $pay_data['remark'] = '首次充值充' . $bonuses['money'] . '送' . $bonuses['bonuses'];
                    }
                } else {
                    $pay_data['remark'] = '首次充值';
                }
            } else {
                if (C('wallet_config.toggle_recharge_bonuses')) {
                    $con           = array();
                    $con['status'] = 1;
                    $con['money']  = array('elt', $pay_data['money']);
                    $bonuses       = D('Wallet/RechargeBonuses')->where($con)->order('bonuses desc')->find();
                    if ($bonuses) {
                        $pay_data['money']  = bcadd($pay_data['money'], $bonuses['bonuses'], 2);
                        $pay_data['remark'] = '充' . $bonuses['money'] . '送' . $bonuses['bonuses'];
                    }
                } else {
                    $pay_data['remark'] = '普通充值';
                }
            }

            // 充值
            $pay_data['title'] = C('WEB_SITE_TITLE') . "余额充值";
            $index_object      = D('Wallet/Index');
            $result            = $index_object->receipt($pay_data); // 充值
            if ($result) {
                // 设置回调成功标记
                $is_callback = $recharge_object->where(array('out_trade_no' => $out_trade_no))->setField('is_callback', 1);

                // 通用钩子
                $hook_data['url']  = request()->module() . '/' . request()->controller() . '/' . request()->action();
                $hook_data['data'] = $order_info;
                hook('CommonHook', $hook_data);

                return true;
            } else {
                \Think\Log::record('收款错误：' . $index_object->getError());
                return false;
            }
        } else {
            $this->error('非法：该订单尚未支付！', C('HOME_PAGE'));
        }
    }

    /**
     * 充值记录
     * @author jry <598821125@qq.com>
     */
    public function my()
    {
        // 获取所有记录
        $map['uid']      = $this->is_login();
        $map['status']   = 1;
        $p               = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $recharge_object = D('Wallet/Recharge');
        $data_list       = $recharge_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            $recharge_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('充值纪录') // 设置页面标题
            ->addTopButton('self', array( //添加返回按钮
                'title' => '余额充值',
                'class' => 'btn btn-warning-outline btn-pill',
                'href'  => U('Wallet/Recharge/index'),
            ))
            ->addTableColumn('out_trade_no', '订单号')
            ->addTableColumn('money', '充值金额')
            ->addTableColumn('pay_type', '付款方式')
            ->addTableColumn("is_pay", "状态", "callback", array($recharge_object, 'is_pay'))
            ->addTableColumn("is_callback", "状态", "callback", array($recharge_object, 'is_callback'))
            ->addTableColumn('create_time', '充值时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->setTemplate(C('USER_CENTER_LIST'))
            ->display();
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function xml2aray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $array = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array;
    }
}
