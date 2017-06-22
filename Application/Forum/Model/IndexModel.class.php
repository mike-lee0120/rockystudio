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
namespace Forum\Model;

use Common\Model\Model;

/**
 * 帖子模型
 * @author jry <598821125@qq.com>
 */
class IndexModel extends Model
{
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'forum_index';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('plate_id', 'require', '请选择板块', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', 'require', '请填写帖子标题', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '5,200', '帖子标题长度为5-200个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
        array('title', '', '该帖已存在', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
        array('content', 'require', '请填写帖子正文', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('content', '1,20000', '帖子正文长度为1-20000个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('sort', '0', self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_find(&$result, $options)
    {
        $result['title_href'] = U('Forum/Index/detail', array('id' => $result['id']), true, true);
        $result['title_url']  = '<a href="' . $result['title_href'] . '">' . $result['title'] . '</a>';
        $result['user']       = D('Admin/User')->getUserInfo($result['uid']);
        if ($result['cover']) {
            $result['cover_url'] = get_cover($result['cover'], 'default');
        }
    }

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_select(&$result, $options)
    {
        foreach ($result as &$record) {
            $this->_after_find($record, $options);
        }
    }

    /**
     * 获取帖子列表
     * @author jry <598821125@qq.com>
     */
    public function getList($type = 'time', $limit = 10, $order = null, $map = null)
    {
        $con["status"] = array("eq", '1');
        if ($map) {
            $map = array_merge($con, $map);
        }
        if (!$order) {
            switch ($type) {
                case 'time': // 按时间排序
                    $order = 'create_time desc, id desc';
                    break;
                case 'reply': //按回帖时间排序
                    $order = 'last_reply_time desc, create_time desc, id desc';
                    break;
                case 'hot': //按阅读量排序
                    $order = 'view desc, create_time desc, id desc';
                    break;
                case 'cover': //含有封面的帖子
                    $order        = 'create_time desc, id desc';
                    $map['cover'] = array('neq', 0);
                    break;
            }
        }
        $list = $this->where($map)
            ->page(!empty($_GET["p"]) ? $_GET["p"] : 1, $limit)
            ->order($order)
            ->select();
        return $list;
    }
}
