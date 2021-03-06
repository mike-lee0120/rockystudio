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
use lyf\Page;

/**
 * 收藏控制器
 * @author jry <598821125@qq.com>
 */
class MarkController extends HomeController
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
     * 我的
     * @author jry <598821125@qq.com>
     */
    public function my()
    {
        $map['status'] = array('eq', '1'); // 禁用和正常状态
        $map['uid']    = $this->is_login();
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $mark_object   = D('Mark');
        $data_list     = $mark_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id asc')
            ->select();
        $page = new Page(
            $mark_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 获取标题
        $index_object = D('Cms/Index');
        foreach ($data_list as &$val) {
            $temp             = $index_object->detail($val['data_id']);
            $val['title_url'] = '<a target="_blank" href="' . U(D('Index')->moduleName . '/Index/detail', array('id' => $temp['id'])) . '">' . $temp['title'] . '</a>';
        }

        // 取消收藏按钮
        $attr['name']  = 'cencel';
        $attr['title'] = '取消收藏';
        $attr['class'] = 'label label-danger-outline label-pill ajax-get';
        $attr['href']  = U(D('Index')->moduleName . '/Mark/add', array(
            'data_id' => __data_id__,
        ));

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle("我收藏的文档") // 设置页面标题
            ->addTableColumn("id", "ID")
            ->addTableColumn("title_url", "标题")
            ->addTableColumn("create_time", "创建时间", "time")
            ->addTableColumn("status", "状态", "status")
            ->addTableColumn("right_button", "操作", "btn")
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton("self", $attr) // 添加取消收藏按钮
            ->setTemplate(C('USER_CENTER_LIST'))
            ->setTableDataListKey('data_id')
            ->display();
    }

    /**
     * 收藏
     * @param $type 消息类型
     * @author jry <598821125@qq.com>
     */
    public function add($data_id)
    {
        $mark_object    = D('Cms/Mark');
        $con['data_id'] = $data_id;
        $con['uid']     = $this->is_login();
        $find           = $mark_object->where($con)->find();
        if ($find) {
            if ($find['status'] === '1') {
                $where['id'] = $find['id'];
                $result      = $mark_object
                    ->where($where)
                    ->setField(array('status' => 0, 'update_time' => time()));
                if ($result) {
                    $return['status']        = 1;
                    $return['info']          = '取消收藏成功' . $mark_object->getError();
                    $return['follow_status'] = 0;

                    // 收藏数量-1
                    $result = D('Index')->where(array('id' => $data_id))->SetDec('mark');
                    $this->ajaxReturn($return);
                } else {
                    $return['status']        = 0;
                    $return['info']          = '取消收藏失败' . $mark_object->getError();
                    $return['follow_status'] = 1;
                    $this->ajaxReturn($return);
                }
            } else {
                $where['id'] = $find['id'];
                $result      = $mark_object
                    ->where($where)
                    ->setField(array('status' => 1, 'update_time' => time()));
                if ($result) {
                    $return['status']        = 1;
                    $return['info']          = '收藏成功' . $mark_object->getError();
                    $return['follow_status'] = 1;

                    // 收藏数量+1
                    $result = D('Index')->where(array('id' => $data_id))->SetInc('mark');
                    $this->ajaxReturn($return);
                } else {
                    $return['status'] = 0;
                    $return['info']   = '收藏失败' . $mark_object->getError();
                    $this->ajaxReturn($return);
                }
            }
        } else {
            $data = $mark_object->create($con);
            if ($data) {
                $result = $mark_object->add($data);
                if ($result) {
                    $return['status']        = 1;
                    $return['info']          = '收藏成功' . $mark_object->getError();
                    $return['follow_status'] = 1;

                    // 收藏数量+1
                    $result = D('Index')->where(array('id' => $data_id))->SetInc('mark');
                    $this->ajaxReturn($return);
                } else {
                    $return['status'] = 0;
                    $return['info']   = '收藏失败' . $mark_object->getError();
                    $this->ajaxReturn($return);
                }
            }
        }
    }
}
