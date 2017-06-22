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

use Home\Controller\HomeController;
use lyf\Page;

/**
 * 提现控制器
 * @author jry <598821125@qq.com>
 */
class WithdrawController extends HomeController
{
    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize()
    {
        parent::_initialize();
        $this->is_login();
    }

    /**
     * 提现记录
     * @author jry <598821125@qq.com>
     */
    public function my()
    {
        //获取所有
        $p            = $_GET["p"] ?: 1;
        $index_object = D('Wallet/Withdraw');
        $map['uid']   = $this->is_login();
        $data_list    = $index_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->order('id desc')
            ->where($map)
            ->select();
        $page = new Page(
            $index_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 按钮
        $right_button1['pay']['title']     = '提现成功';
        $right_button1['pay']['attribute'] = 'class="label label-success" href="#"';
        $right_button2['pay']['title']     = '提现失败';
        $right_button2['pay']['attribute'] = 'class="label label-danger" href="#"';

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('提现记录') //设置页面标题
            ->addTopButton('addnew', array('title' => '提现')) // 添加新增按钮
            ->addTableColumn('id', 'ID')
            ->addTableColumn('money', '提现金额')
            ->addTableColumn('type', '提现方式')
            ->addTableColumn('realname', '账户姓名')
            ->addTableColumn('account', '账号/卡号')
            ->addTableColumn('bankname', '开户行名称')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('remark', '备注')
            ->addTableColumn('status', '状态')
            ->setTableDataList($data_list) //数据列表
            ->setTableDataPage($page->show()) //数据列表分页
            ->setTemplate(C('USER_CENTER_LIST'))
            ->alterTableData( // 修改列表数据
                array('key' => 'status', 'value' => '1'),
                array('status' => '<span class="label label-success">提现成功</span>')
            )
            ->alterTableData( // 修改列表数据
                array('key' => 'status', 'value' => '-1'),
                array('status' => '<span class="label label-danger">提现失败</span>')
            )
            ->alterTableData( // 修改列表数据
                array('key' => 'status', 'value' => '0'),
                array('status' => '<span class="label label-info">未处理</span>')
            )
            ->display();
    }

    /**
     * 提现申请
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            $uid              = $this->is_login();
            $project_object   = D('Wallet/Withdraw');
            $post_data        = format_data();
            $post_data['uid'] = $uid;
            $data             = $project_object->create($post_data);
            if ($data) {
                $result = $project_object->add($data);
                if ($result) {
                    $this->success('申请成功', U('my'));
                } else {
                    $this->error('错误' . $project_object->getError());
                }
            } else {
                $this->error($project_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle("提现申请") // 设置页面标题
                ->setPostUrl(U("add")) // 设置表单提交地址
                ->addFormItem("money", "text", "提现金额", "提现金额")
                ->addFormItem("type", "radio", "提现方式", "提现方式", array('支付宝' => '支付宝', '银行卡' => '银行卡'))
                ->addFormItem("realname", "text", "账户姓名", "与支付宝或银行卡绑定姓名一致")
                ->addFormItem("account", "text", "账户/卡号", "账户/卡号")
                ->addFormItem("bankname", "text", "开户行名称", "详细分行名称（支付宝不需要填写此项）")
                ->setFormData(array('type' => '支付宝'))
                ->setTemplate(C('USER_CENTER_FORM'))
                ->setExtraHtml('<div class="alert alert-danger">注意：部分第三方金融机构可能会收取手续费，实际金额以到账为准。</div>')
                ->display();
        }
    }
}
