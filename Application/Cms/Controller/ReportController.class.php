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
namespace Cms\Controller;

use Home\Controller\HomeController;

/**
 * 举报控制器
 * @author jry <598821125@qq.com>
 */
class ReportController extends HomeController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index($data_id)
    {
        if (request()->isPost()) {
            $report_object = D('Cms/Report');
            $data          = $report_object->create();
            if ($data) {
                $result = $report_object->add($data);
                if ($result) {
                    $this->success('您的举报提交成功，请您耐心等待！');
                } else {
                    $this->error($report_object->getError());
                }
            } else {
                $this->error($report_object->getError());
            }
        } else {
            $this->assign('info', D('Cms/Index')->detail($data_id));
            $this->assign('reason_list', D('Cms/Report')->reason_list());
            $this->assign('meta_title', '举报页面');
            $this->display($template);
        }
    }
}
