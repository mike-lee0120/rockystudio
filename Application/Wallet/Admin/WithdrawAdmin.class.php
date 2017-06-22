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
 * 提现控制器
 * @author jry <598821125@qq.com>
 */
class WithdrawAdmin extends AdminController
{
    /**
     * 提现记录
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        //获取所有
        $p               = $_GET["p"] ?: 1;
        $withdraw_object = D('Wallet/Withdraw');
        $map['status']   = array('egt', '-1'); // 禁用和正常状态
        $data_list       = $withdraw_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->order('id desc')
            ->where($map)
            ->select();
        $page = new Page(
            $withdraw_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 余额
        foreach ($data_list as &$val) {
            $val['my_money'] = D('Admin/User')->getUserInfo($val['uid'], 'money');
        }

        // 按钮
        $right_button1['pay']['title']     = '提现成功';
        $right_button1['pay']['attribute'] = 'class="label label-success" href="#"';
        $right_button2['pay']['title']     = '提现失败';
        $right_button2['pay']['attribute'] = 'class="label label-danger" href="#"';

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('提现记录') //设置页面标题
            ->addTableColumn('id', 'ID')
            ->addTableColumn('uid', 'UID')
            ->addTableColumn('my_money', '账户金额')
            ->addTableColumn('money', '提现金额')
            ->addTableColumn('type', '提现方式')
            ->addTableColumn('realname', '账户姓名')
            ->addTableColumn('account', '账号/卡号')
            ->addTableColumn('bankname', '开户行名称')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('remark', '备注')
            ->addTableColumn("right_button", "操作", "btn")
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton("edit", array('title' => '处理')) // 添加编辑按钮
            ->alterTableData( // 修改列表数据
                array('key' => 'status', 'value' => '1'),
                array('right_button' => $right_button1)
            )
            ->alterTableData( // 修改列表数据
                array('key' => 'status', 'value' => '-1'),
                array('right_button' => $right_button2)
            )
            ->display();
    }

    /**
     * 处理提现申请
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        $project_object = D('Wallet/Withdraw');
        if (request()->isPost()) {
            $post_data = format_data();
            if ($post_data['status'] === '1') {
                $pay_data['out_trade_no'] = \lyf\Str::createOutTradeNo();
                $pay_data['uid']          = $post_data['uid'];
                $pay_data['money']        = $post_data['money'];
                $pay_data['title']        = '余额提现';
                $wallet_object            = D('Wallet/Index');
                $pay_status               = $wallet_object->pay($pay_data);
                if (!$pay_status) {
                    $this->error('提现失败' . $wallet_object->getError());
                }
            }

            // 保存
            $data = $project_object->create($post_data);
            if ($data) {
                $result = $project_object->save($data);
                if ($result) {
                    $this->success('保存成功', U('index'));
                } else {
                    $this->error('错误' . $project_object->getError());
                }
            } else {
                $this->error($project_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle("处理提现") // 设置页面标题
                ->setPostUrl(U("edit")) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('uid', 'hidden', 'UID', 'UID')
                ->addFormItem('money', 'hidden', '提现金额', '提现金额')
                ->addFormItem("status", "radio", "状态", "状态", $project_object->pay_status())
                ->addFormItem("remark", "text", "备注", "提现订单号、处理人姓名、提现失败原因等信息")
                ->setFormData($project_object->find($id))
                ->display();
        }
    }
}
