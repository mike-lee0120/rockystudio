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
namespace Forum\TagLib;

use lyf\template\TagLib;

/**
 * 标签库
 * @author jry <598821125@qq.com>
 */
class Forum extends TagLib
{
    /**
     * 定义标签列表
     * @author jry <598821125@qq.com>
     */
    protected $tags = array(
        'comment_list' => array('attr' => 'name,data_id,limit,page,order', 'close' => 1), //评论列表
    );

    /**
     * 评论列表
     * @author jry <598821125@qq.com>
     */
    public function _comment_list($tag, $content)
    {
        $name    = $tag['name'];
        $data_id = $tag['data_id'];
        $limit   = $tag['limit'] ?: 10;
        $page    = $tag['page'] ?: 1;
        $order   = $tag['order'] ?: 'sort desc,id asc';
        $parse   = '<?php ';
        $parse .= '$__COMMENT_LIST__ = D("Forum/Comment")->getCommentList(' . $data_id . ', ' . $limit . ', ' . $page . ', "' . $order . '");';
        $parse .= ' ?>';
        $parse .= '<volist name="__COMMENT_LIST__" id="' . $name . '">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }
}
