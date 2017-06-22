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
namespace User\Controller;

use Home\Controller\HomeController;
use lyf\Page;

/**
 * 用户中心控制器
 * @author jry <598821125@qq.com>
 */
class ScoreLogController extends HomeController
{
    /**
     * 积分记录
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 获取所有帖子
        $map['status']   = array('eq', '1');
        $map['uid']      = array('eq', $this->is_login());
        $score_log_model = D('ScoreLog');
        $data_list       = $score_log_model->where($map)->order('id desc')->select();

        $page = new Page(
            $score_log_model->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('积分记录') // 设置页面标题
            ->addTableColumn('id', 'ID')
            ->addTableColumn('type', '变动', 'callback', array(D('ScoreLog'), 'change_type'))
            ->addTableColumn('score', '数量')
            ->addTableColumn('message', '消息')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->setTemplate(C('USER_CENTER_LIST'))
            ->display();
    }
}
