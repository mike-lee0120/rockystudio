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
namespace Wallet\Admin;

use Admin\Controller\AdminController;
use lyf\Page;

/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexAdmin extends AdminController
{
    /**
     * 账单
     * @author jry <598821125@qq.com>
     */
    public function index($is_pay = 1)
    {
        // 搜索
        $keyword       = I('keyword', '', 'string');
        $condition     = array('like', '%' . $keyword . '%');
        $map['id|UID'] = array($condition, $condition, '_multi' => true);

        // 获取所有
        $map['is_pay'] = $is_pay;
        $p             = input('get.p', 1);
        $index_object  = D('Wallet/Index');
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $data_list     = $index_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->order('id desc')
            ->where($map)
            ->select();
        $page = new Page(
            $index_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 设置Tab导航数据列表
        $type_list = D('Index')->is_pay(); // 获取分类分组
        foreach ($type_list as $key => $val) {
            $tab_list[$key]['title'] = $val;
            $tab_list[$key]['href']  = U('index', array('is_pay' => $key));
        }

        // 人工充值按钮
        $attr1['name']  = 'recharge';
        $attr1['title'] = '人工充值';
        $attr1['class'] = 'btn btn-primary-outline btn-pill';
        $attr1['href']  = U('Wallet/Index/recharge');

        // 人工扣款按钮
        $attr2['name']  = 'pay';
        $attr2['title'] = '人工扣款';
        $attr2['class'] = 'btn btn-info-outline btn-pill';
        $attr2['href']  = U('Wallet/Index/pay');

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('账单') // 设置页面标题
            ->addTopButton("self", $attr1) // 添加人工充值按钮
            ->addTopButton("self", $attr2) // 添加人工扣款按钮
            ->setSearch('请输入ID/订单号', U('index'))
            ->setTabNav($tab_list, $is_pay) // 设置页面Tab导航
            ->addTableColumn('id', 'ID')
            ->addTableColumn('uid', 'UID')
            ->addTableColumn('out_trade_no', '订单号')
            ->addTableColumn('title', '标题', '', '', '10%')
            ->addTableColumn('type', '变动', 'callback', array($index_object, 'change_type'))
            ->addTableColumn('money', '金额')
            ->addTableColumn('third_money', '第三方金额')
            ->addTableColumn('pay_type', '支付方式', 'callback', array($index_object, 'pay_type'))
            ->addTableColumn('before_money', '变动前余额')
            ->addTableColumn('after_money', '变动后余额')
            ->addTableColumn('remark', '备注')
            ->addTableColumn("is_pay", "支付", "callback", array($index_object, 'is_pay'));
        if ($is_pay) {
            $builder->addTableColumn("is_callback", "回调", "callback", array($index_object, 'is_callback'));
        }
        $builder->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->display();
    }

    /**
     * 人工扣款
     * @author jry <598821125@qq.com>
     */
    public function pay()
    {
        if (request()->isPost()) {
            $receipt_data = $_POST;
            if (!$receipt_data['money']) {
                $this->error('请填写扣款金额');
            }
            if (!$receipt_data['uid']) {
                $this->error('请填写UID');
            }
            $user_model                      = D('Admin/User');
            $index_model                     = D('Wallet/Index');
            $_pay_data                       = array();
            $_receipt_data['add_uid']        = $this->is_login();
            $_receipt_data['type']           = 2;
            $_receipt_data['is_callback']    = 1;
            $_receipt_data['is_pay']         = 1;
            $_receipt_data['pay_type']       = '0';
            $_receipt_data['out_trade_no']   = $receipt_data['out_trade_no'] ?: \lyf\Str::createOutTradeNo();
            $_receipt_data['original_money'] = $receipt_data['money'];
            $_receipt_data['money']          = $receipt_data['money'];
            $_receipt_data['uid']            = $receipt_data['uid'];
            $_receipt_data['before_money']   = $user_model->getFieldById($receipt_data['uid'], 'money');
            $_receipt_data['after_money']    = bcsub($user_model->getFieldById($receipt_data['uid'], 'money'), $receipt_data['money'], 2);
            $_receipt_data['title']          = $receipt_data['title'] ?: '人工扣款';
            $data                            = $index_model->create($_receipt_data);
            if ($data) {
                $add_result = $index_model->add($data);
                if (!$add_result) {
                    $this->error('人工扣款失败：' . $index_model->getError());
                }
                $map       = array();
                $map['id'] = $data['uid'];
                $result    = $user_model->where($map)->setDec('money', $data['money']);
                if ($result) {
                    // 构造消息数据
                    $msg_data            = array();
                    $msg_data['to_uid']  = $data['uid'];
                    $msg_data['title']   = '人工扣款成功';
                    $msg_data['content'] = '您好：<br>'
                    . '您在' . C('WEB_SITE_TITLE') . $data['title'] . '的款项共' . $data['money'] . '元已扣款成功。<br>'
                        . '<br>';
                    $status = D('User/Message')->sendMessage($msg_data);
                    $this->success('人工扣款成功', U('Wallet/Index/index'));
                } else {
                    $this->delete($add_result);
                    $this->error('人工扣款失败：' . $user_model->getError());
                }
            } else {
                $this->error('人工扣款失败：' . $index_model->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle("人工扣款") // 设置页面标题
                ->setPostUrl(U("")) // 设置表单提交地址
                ->addFormItem("uid", "num", "UID", "UID")
                ->addFormItem("money", "price", "金额", "金额")
                ->addFormItem("title", "text", "订单标题", "订单标题")
                ->addFormItem("remark", "textarea", "订单备注", "订单备注")
                ->display();
        }
    }

    /**
     * 人工充值
     * @author jry <598821125@qq.com>
     */
    public function recharge()
    {
        if (request()->isPost()) {
            $receipt_data = $_POST;
            if (!$receipt_data['money']) {
                $this->error('请填写充值金额');
            }
            if (!$receipt_data['uid']) {
                $this->error('请填写UID');
            }
            $user_model                      = D('Admin/User');
            $index_model                     = D('Wallet/Index');
            $_pay_data                       = array();
            $_receipt_data['add_uid']        = $this->is_login();
            $_receipt_data['type']           = 1;
            $_receipt_data['is_callback']    = 1;
            $_receipt_data['is_pay']         = 1;
            $_receipt_data['pay_type']       = $receipt_data['pay_type'] ?: 'offline';
            $_receipt_data['out_trade_no']   = $receipt_data['out_trade_no'] ?: \lyf\Str::createOutTradeNo();
            $_receipt_data['original_money'] = $receipt_data['money'];
            $_receipt_data['money']          = $receipt_data['money'];
            $_receipt_data['uid']            = $receipt_data['uid'];
            $_receipt_data['before_money']   = $user_model->getFieldById($receipt_data['uid'], 'money');
            $_receipt_data['after_money']    = bcadd($user_model->getFieldById($receipt_data['uid'], 'money'), $_receipt_data['money'], 2);
            $_receipt_data['title']          = $receipt_data['title'] ?: '人工充值';
            $data                            = $index_model->create($_receipt_data);
            if ($data) {
                $add_result = $index_model->add($data);
                if (!$add_result) {
                    $this->error('人工充值失败：' . $index_model->getError());
                }
                $map       = array();
                $map['id'] = $data['uid'];
                $result    = $user_model->where($map)->setInc('money', $data['money']);
                if ($result) {
                    // 构造消息数据
                    $msg_data            = array();
                    $msg_data['to_uid']  = $data['uid'];
                    $msg_data['title']   = '人工充值到账成功';
                    $msg_data['content'] = '您好：<br>'
                    . '您在' . C('WEB_SITE_TITLE') . $data['title'] . '的款项共' . $data['money'] . '元已到账。<br>'
                        . '<br>';
                    $status = D('User/Message')->sendMessage($msg_data);
                    $this->success('人工充值成功', U('Wallet/Index/index'));
                } else {
                    $this->delete($add_result);
                    $this->error('人工充值失败：' . $user_model->getError());
                }
            } else {
                $this->error('人工充值失败：' . $index_model->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle("人工充值") // 设置页面标题
                ->setPostUrl(U("")) // 设置表单提交地址
                ->addFormItem("uid", "num", "UID", "UID")
                ->addFormItem("money", "price", "金额", "金额")
                ->addFormItem("pay_type", "radio", "付款方式", "付款方式", array('0' => '系统余额', 'offline' => '线下付款'))
                ->addFormItem("title", "text", "订单标题", "订单标题")
                ->addFormItem("remark", "textarea", "订单备注", "订单备注")
                ->display();
        }
    }
}
