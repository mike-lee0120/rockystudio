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
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexController extends HomeController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 登录检测
        $this->is_login();

        $this->assign('user_info', D('Admin/User')->getUserInfo(is_login())); // 用户信息
        $this->assign('meta_title', '我的钱包');
        $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
        $this->assign('meta_cover', $info['cover']);
        $this->display();
    }

    /**
     * 我的账单
     * @author jry <598821125@qq.com>
     */
    public function my()
    {
        // 登录检测
        $this->is_login();

        //获取所有
        $p             = input('get.p', 1);
        $index_object  = D('Wallet/Index');
        $map['status'] = 1;
        $map['uid']    = $this->is_login();
        $data_list     = $index_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->order('id desc')
            ->where($map)
            ->select();
        $page = new Page(
            $index_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 数据处理
        foreach ($data_list as $key => &$val) {
            $val['type_text']     = $index_object->change_type($val['type']);
            $val['type_code']     = $index_object->change_type_code($val['type']);
            $val['is_pay_text']   = $index_object->is_pay($val['is_pay']);
            $val['pay_type_text'] = $index_object->pay_type($val['pay_type']);
        }

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('我的账单') //设置页面标题
            ->addTopButton('self', array( //添加返回按钮
                'title' => '余额充值',
                'class' => 'btn btn-warning-outline btn-pill',
                'href'  => U('Wallet/Recharge/index'),
            ))
            ->addTableColumn('title', '标题')
            ->addTableColumn('type_text', '变动')
            ->addTableColumn('money', '金额')
            ->addTableColumn('third_money', '第三方金额')
            ->addTableColumn('pay_type', '支付方式')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn("is_pay_text", "支付")
            ->addTableColumn('status', '状态', 'status')
            ->setTableDataList($data_list) //数据列表
            ->setTableDataPage($page->show()) //数据列表分页
            ->setTemplate(C('USER_CENTER_LIST'))
            ->display();
    }

    /**
     * 通用支付接口页面
     * 参数 out_trade_no title money callback
     * @author jry <598821125@qq.com>
     */
    public function pay($out_trade_no)
    {
        // 登录检测
        $uid = $this->is_login();

        // 验证调用接口时传来的数据
        $index_object = D('Wallet/Index');
        $pay_data     = session('pay_data_' . $out_trade_no);
        $result       = $index_object->verify_pay_data($pay_data);
        if (!$result) {
            $this->error('接口调用出错' . $index_object->getError());
        }

        // 补充数据
        $pay_data['original_money'] = $pay_data['money']; // 订单原价
        $pay_data['type']           = 2; // 表示支出
        $pay_data['uid']            = $uid;

        // 订单状态
        $exist = $index_object->where(array('out_trade_no' => $out_trade_no, 'uid' => $uid))->find();
        if (!$exist || ($exist && !$exist['is_pay'] && !$exist['third_is_pay'])) {
            if ($exist) {
                $pay_data['id'] = $exist['id'];
                $result         = $index_object->save($pay_data);
            } else {
                // 创建一个新订单
                $data = $index_object->create($pay_data);
                if (!$data) {
                    $this->error('创建订单出错' . $index_object->getError());
                }
                $result = $index_object->add($data);
            }
            if ($result === false) {
                $this->error('创建订单出错' . $index_object->getError());
            }

            // 允许红包支付
            if ($pay_data['allow_redbag']) {
                $redbag_list = D('Wallet/Redbag')->get_available($pay_data['money']);
                $this->assign('redbag_list', $redbag_list);
            }

            // 允许折扣券支付
            if ($pay_data['allow_coupon']) {
                $coupon_list = D('Wallet/Coupon')->get_available($pay_data['money']);
                $this->assign('coupon_list', $coupon_list);
            }

            $info = $index_object->where(array('out_trade_no' => $out_trade_no, 'uid' => $uid))->find();

            // 获取支付插件支持的三方支付方式列表
            if ($info['allow_third']) {
                $allow_pay_type = D('Addons://Pay/Pay')->type_list();
            }

            $this->assign('pay_data', $info);
            $this->assign('allow_pay_type', $allow_pay_type);
            $this->assign('meta_title', '确认付款');
            $this->display();
        } else {
            $this->pay_before($exist);
        }
    }

    /**
     * 有过支付行为订单处理
     * 参数 out_trade_no title money callback
     * @author jry <598821125@qq.com>
     */
    public function pay_before($exist)
    {
        // 订单已存在的各种情况
        if ($exist['is_pay'] && $exist['is_callback']) {
            // 支付全部完成，并且回调成功
            $this->error('该订单已完成', U('Wallet/Index/my', '', true, true));
        }

        $index_object = D('Wallet/Index');
        $index_object->startTrans();

        // 第三方支付完成，内部扣款错误
        if (!$exist['is_pay'] && $exist['third_is_pay']) {
            $result = $index_object->pay($exist);
            if ($result !== true) {
                $index_object->rollback();
                $this->error('扣款出错' . $index_object->error);
            }
            $index_object->commit();
            $exist['is_pay'] = 1;
        }

        // 支付全部完成，但是回调失败
        if ($exist['is_pay'] && !$exist['is_callback']) {
            // 回调
            if (0 !== strpos($exist['callback'], 'http')) {
                $result = $index_object->callback($exist['out_trade_no']);
                if ($result !== true) {
                    $index_object->rollback();
                    $this->error('账单已支付，再次回调错误' . $index_object->getError());
                }
                $index_object->commit();
                $this->success('支付成功');
            } else {
                // 第一代回调直接调转回去出发回调逻辑，即将废弃，请勿再次使用
                session('pay_data_' . $out_trade_no . '.pay_verify', true);
                $this->success('支付成功', $pay_data['callback']);
            }
        }
        return false;
    }

    /**
     * 确认支付
     * 参数 out_trade_no title money callback
     * @author jry <598821125@qq.com>
     */
    public function dopay($out_trade_no)
    {
        // 登录检测
        $uid          = $this->is_login();
        $index_object = D('Wallet/Index');

        $exist = $index_object->where(array('out_trade_no' => $out_trade_no, 'uid' => $uid))->find();

        if ($exist) {
            // 没有支付行为完成
            if ($exist['is_pay'] || $exist['third_is_pay']) {
                $this->pay_before($exist);
            } else {
                if (request()->isPost()) {
                    if (!D('User/User')->password_auth(I('password')) && C('wallet_config.pay_need_password')) {
                        $this->error('密码错误');
                    }

                    // 获取用户选择的红包或者折扣券
                    $field           = array();
                    $field['id']     = $exist['id'];
                    $field['remark'] = '全款';
                    if (I('post.redbag_id') && $exist['allow_redbag']) {
                        $field['redbag_id'] = I('post.redbag_id');
                    }
                    if (I('post.coupon_id') && $exist['allow_coupon']) {
                        $field['coupon_id'] = I('post.coupon_id');
                    }
                    if ($field['redbag_id'] && $field['coupon_id']) {
                        $this->error('红包与折扣券只可使用一种');
                    }

                    //初始化数据
                    $redbag_money = 0.00;
                    $coupon       = 10;
                    $user_money   = 0.00;

                    // 红包支付
                    if ($field['redbag_id'] && !$field['coupon_id']) {
                        $redbag_object = D('Wallet/Redbag');
                        $redbag_info   = $redbag_object->get_redbag($field['redbag_id'], $exist['original_money']);
                        if ($redbag_info) {
                            $redbag_money    = $redbag_info['money'];
                            $field['remark'] = '红包(' . $redbag_info['id'] . ')抵扣' . $redbag_info['money'] . '元';
                        } else {
                            $this->error('红包出错' . $redbag_object->getError());
                        }
                    }
                    // 折扣券支付
                    if ($field['coupon_id'] && !$field['redbag_id']) {
                        $coupon_object = D('Wallet/Coupon');
                        $coupon_info   = $coupon_object->get_coupon($field['coupon_id'], $exist['original_money']);
                        if ($coupon_info) {
                            $coupon          = $coupon_info['coupon'];
                            $field['remark'] = '折扣券(' . $coupon_info['id'] . ')享' . $coupon_info['coupon'] . '折优惠';
                        } else {
                            $this->error('折扣券出错' . $coupon_object->getError());
                        }
                    }

                    // 用户余额参与支付
                    if ($exist['allow_third']) {
                        if ($exist['allow_money']) {
                            if (I('allow_money')) {
                                $user_money = D('Admin/User')->getFieldById($uid, 'money');
                            }
                        }
                    } else {
                        // 调用接口时不允许第三方支付，则必须用余额支付
                        $user_money = D('Admin/User')->getFieldById($uid, 'money');
                    }

                    // 计算需要的第三方支付金额，必须用bc系列函数计算不然不准确。
                    $third_money = bcsub(bcsub(bcmul($exist['original_money'], $coupon / 10, 2), $redbag_money, 2), $user_money, 2);

                    // 如果站内资产足够支付就不需要调用三方支付直接站内支付即可
                    if ($third_money <= 0) {
                        /**
                         * 以下为直接站内余额支付
                         */
                        // 支付流程1：更新订单
                        $field['ready']       = time();
                        $field['pay_type']    = 0;
                        $field['third_money'] = 0;
                        // 需要扣除余额必须用bc系列函数计算不然不准确。
                        $field['money'] = bcsub(bcmul($exist['original_money'], $coupon / 10, 2), $redbag_money, 2);
                        $index_object->startTrans();
                        $result1 = $index_object->save($field);
                        if ($result1 === false) {
                            $index_object->rollback();
                            $this->error('更新订单出错' . $index_object->getError());
                        }
                        $index_object->commit();

                        // 支付流程2：调用pay进行余额扣款、红包使用、优惠券使用等进行支付
                        $result2 = $index_object->pay($exist['out_trade_no']);
                        if (!$result2) {
                            $index_object->rollback();
                            $this->error('扣款出错' . $index_object->getError());
                        }
                        $index_object->commit();

                        // 支付流程3：回调处理有两代
                        // 如果$pay_data['callback']不包含http说明是第二代回调
                        if (0 !== strpos($exist['callback'], 'http')) {
                            $result3 = $index_object->callback($exist['out_trade_no']);
                            if ($result3 !== true) {
                                $index_object->rollback();
                                $this->error('账单已支付，但回调错误' . $index_object->getError());
                            }
                            $index_object->commit();
                            $this->success('付款成功！', $exist['return_url']);
                        } else {
                            // 第一代回调直接调转回去出发回调逻辑，即将废弃，请勿再次使用
                            session('pay_data_' . $exist['out_trade_no'] . '.pay_verify', true);
                            $index_object->where(array('out_trade_no' => $exist['out_trade_no']))->setField('is_callback', '1');
                            $this->success('付款成功，开始回调！', $exist['callback']);
                        }
                    } else {
                        /**
                         * 以下为第三方支付
                         */
                        if ($exist['allow_third']) {
                            // 第三方支付禁止使用URL回调
                            if (false !== strpos($exist['callback'], 'http')) {
                                $this->error('第三方支付不支持一代回调，详见升级文档');
                            }

                            // 订单数据
                            $pay_type = I('post.pay_type');
                            if (!$pay_type) {
                                $this->error('支付方式未选择');
                            }

                            // 金额
                            $field['ready']       = time();
                            $field['pay_type']    = $pay_type;
                            $field['third_money'] = $third_money;
                            $field['money']       = $user_money;
                            $field['remark'] .= '第三方支付' . $field['third_money'] . '元';

                            // 支付方式兼容支付宝Wap和App
                            if (request()->isMobile() && $pay_type === 'alipay' && !C('IS_API')) {
                                $field['pay_type'] = 'aliwappay';
                            }
                            if ($pay_type === 'alipay' && C('IS_API')) {
                                $field['pay_type'] = 'alipayapp';
                            }

                            // 支付流程1：更新订单
                            $index_object->startTrans();
                            $result1 = $index_object->save($field);
                            if ($result1 === false) {
                                $index_object->rollback();
                                $this->error('创建订单出错' . $index_object->getError());
                            }
                            $index_object->commit();

                            // 准备跳转到第三方支付
                            $pay_data['money'] = $pay_data['third_money'];
                            $out_trade_no      = $pay_data['out_trade_no'];

                            // APP支付则返回签名字符串
                            if (C('IS_API')) {
                                // 获取订单
                                $info         = $index_object->where(array('out_trade_no' => $exist['out_trade_no']))->find();
                                $info['body'] = C('WEB_SITE_TITLE') . "第三方支付";

                                // 获取支付配置
                                $pay_config                = D('Addons://Pay/Pay')->pay_config($pay_type);
                                $pay_config['notify_url']  = U("notify", array('apitype' => $pay_type, 'out_trade_no' => $exist['out_trade_no']), true, true);
                                $pay_config['return_url']  = U('payUp', array('out_trade_no' => $exist['out_trade_no']), true, true); //一般是支付成功页面,回调通知可能有延迟
                                $pay                       = new Pay($pay_type, $pay_config);
                                $_pay_data                 = array();
                                $_pay_data['out_trade_no'] = $info['out_trade_no'];
                                $_pay_data['title']        = $info['title'];
                                $_pay_data['body']         = $info['title'];
                                $_pay_data['money']        = $info['third_money']; // 这里注意一定要是third_money字段
                                $sign                      = $pay->buildRequestForm($_pay_data);
                                if ($sign) {
                                    // 前端仍然只识别alipay不识别alipayapp
                                    if ($pay_type === 'alipayapp') {
                                        $pay_type = 'alipay';
                                    }
                                    $this->success('打开支付:第三方支付' . '￥' . sprintf('%.2f', $field['third_money']) . '元', null, array('pay_type' => $pay_type, 'json' => $sign['json'], 'string' => $sign['string']));
                                } else {
                                    $this->error('预支付订单生成失败');
                                }
                            } else {
                                $this->success('打开第三方支付页面' . '￥' . sprintf('%.2f', $field['third_money']) . '元', U('thirdPay', array('out_trade_no' => $exist['out_trade_no']), true, true));
                            }
                        } else {
                            $this->error('余额不足，请先充值！');
                        }
                    }
                }
            }
        } else {
            $this->error('订单不存在');
        }
    }

    /**
     * 第三方支付代码
     * @author jry <598821125@qq.com>
     */
    public function thirdPay($out_trade_no)
    {
        // 登录检测
        $this->is_login();

        // 获取订单
        $index_object = D('Wallet/Index');
        $info         = $index_object->where(array('out_trade_no' => $out_trade_no))->find();

        $this->pay_before($info);

        if ($info['third_money'] <= 0) {
            $this->error('第三方支付金额异常');
        }

        // 订单数据
        $info['title'] = C('WEB_SITE_TITLE') . $info['title'];
        $info['body']  = $info['title'];

        //必须覆盖该数值
        $info['money'] = $info['third_money'];

        // 获取支付配置
        $pay_config               = D('Addons://Pay/Pay')->pay_config($info['pay_type']);
        $pay_config['notify_url'] = U("notify", array('apitype' => $pay_type, 'out_trade_no' => $info['out_trade_no']), true, true);
        $pay_config['return_url'] = $info['return_url'];

        $pay = new Pay($info['pay_type'], $pay_config);
        echo $pay->buildRequestForm($info);
    }

    /**
     * 支付结果返回
     * 事务支持 admin_user须为innodb
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
        $index_object = D('Wallet/Index');

        $info = $index_object->where(array('out_trade_no' => $out_trade_no))->find();

        if ($info && $info['out_trade_no'] === $out_trade_no && $notify) {
            // 获取支付配置
            $pay_config = D('Addons://Pay/Pay')->pay_config($info['pay_type']);
            $pay        = new Pay($info['pay_type'], $pay_config);
            // 验证
            if ($pay->verifyNotify($notify)) {
                //获取订单信息
                $pay_info = $pay->getInfo();

                // 支付成功将订单记录标记为已支付
                if ($pay_info['status'] === true) {
                    $index_object->startTrans();
                    $ret = $index_object->where(array('out_trade_no' => $out_trade_no))->setField('third_is_pay', 1);
                    if (false === $ret) {
                        $index_object->rollback();
                        E('第三方支付确认错误：' . $index_object->getError());

                    }
                    $index_object->commit();

                    //第三方支付，钱包模块支付，订单回调
                    $index_object->startTrans();

                    if (!$info['is_pay']) {
                        // 开始处理余额/积分/优惠券
                        // 设置支付成功标记
                        $ret = $index_object->pay($info['out_trade_no']);

                        //非事务执行
                        if ($ret === true) {
                            //开始执行订单回调
                            $callback_status = $index_object->callback($info['out_trade_no']);
                            if (!$callback_status) {
                                $index_object->rollback();
                                E("订单回调错误！ !");
                            }

                            $index_object->commit();
                            $pay->notifySuccess();
                        } else {
                            $index_object->rollback();
                            E("付款处理错误！ !");
                        }
                    } else {
                        $index_object->commit();
                        $pay->notifySuccess();
                    }
                } else {
                    E("支付失败！ !");
                }
            } else {
                E("信息验证失败!");
            }
        } else {
            E("订单不存在！ ");
        }
    }

    /**
     * 查询订单状态
     * @param string $out_trade_no
     * @throws WxPayException
     */
    public function query_order()
    {
        if (!I('out_trade_no')) {
            $data['info']   = '缺少订单号';
            $data['status'] = 0;
            $this->ajaxReturn($data);
        }
        $result = D('Wallet/Index')->where(array('out_trade_no' => I('out_trade_no')))->find();
        if ($result['is_pay']) {
            $data['info']   = '订单已支付';
            $data['status'] = '1';
            $this->ajaxReturn($data);
        } else {
            $data['info']   = '订单未支付';
            $data['status'] = '0';
            $this->ajaxReturn($data);
        }
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
