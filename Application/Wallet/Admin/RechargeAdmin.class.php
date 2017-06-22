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
 * 充值控制器
 * @author jry <598821125@qq.com>
 */
class RechargeAdmin extends AdminController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index($is_pay = 1)
    {
        // 获取所有记录
        $map['status']   = array('egt', '0'); // 禁用和正常状态
        $map['is_pay']   = $is_pay;
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

        // 设置Tab导航数据列表
        $type_list = D('Recharge')->is_pay(); // 获取分类分组
        foreach ($type_list as $key => $val) {
            $tab_list[$key]['title'] = $val;
            $tab_list[$key]['href']  = U('index', array('is_pay' => $key));
        }

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('充值纪录') // 设置页面标题
            ->setTabNav($tab_list, $is_pay) // 设置页面Tab导航
            ->addTableColumn('id', 'ID')
            ->addTableColumn('uid', '用户ID')
            ->addTableColumn('out_trade_no', '订单号')
            ->addTableColumn('money', '充值金额')
            ->addTableColumn('pay_type', '付款方式')
            ->addTableColumn("is_pay", "支付", "callback", array($recharge_object, 'is_pay'));
        if ($is_pay) {
            $builder->addTableColumn("is_callback", "回调", "callback", array($recharge_object, 'is_callback'));
        }
        $builder->addTableColumn('create_time', '充值时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->display();
    }
}
