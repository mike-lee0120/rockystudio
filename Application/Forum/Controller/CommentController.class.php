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
namespace Forum\Controller;

use Home\Controller\HomeController;

/**
 * 评论控制器
 * @author jry <598821125@qq.com>
 */
class CommentController extends HomeController
{
    /**
     * 评论列表
     * @author jry <598821125@qq.com>
     */
    public function index($data_id, $limit = 10, $page = 1, $order = '', $con = null)
    {
        $comment_object = D('Comment');
        $list           = $comment_object->getCommentList($data_id, $limit, $page, $order, $con);
        $this->success('评论列表', '', array('data' => $list));
    }

    /**
     * 新增评论
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            $uid            = $this->is_login();
            $comment_object = D('Forum/Comment');
            $data           = $comment_object->create();
            if ($data) {
                $result = $comment_object->addNew($data);
                if ($result) {
                    $this->success('评论成功');
                } else {
                    $this->error('评论失败' . $comment_object->getError());
                }
            } else {
                $this->error($comment_object->getError());
            }
        }
    }
}
