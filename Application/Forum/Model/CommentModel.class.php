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
use lyf\Page;

/**
 * 评论模型
 * @author jry <598821125@qq.com>
 */
class CommentModel extends Model
{
    /**
     * 模块名称
     * @author jry <598821125@qq.com>
     */
    public $moduleName = 'Forum';

    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'Forum_comment';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('data_id', 'require', '数据ID', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('data_id', 'checkTime', '回帖频繁请30秒后重试', self::MUST_VALIDATE, 'callback'),
        array('content', 'require', '内容不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('content', '1,1280', '内容长度不多于1280个字符', self::VALUE_VALIDATE, 'length'),
        array('content', 'checkRepeat', '最少2个中文且不重复', self::MUST_VALIDATE, 'callback'),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('content', 'html2text', self::MODEL_BOTH, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('sort', '0', self::MODEL_INSERT),
        array('status', 1, self::MODEL_INSERT, 'string'),
        array('ip', 'get_client_ip', self::MODEL_INSERT, 'function'),
    );

    /**
     * 检测回帖
     * @return boolean ture 正常，false 频繁注册
     * @author jry <598821125@qq.com>
     */
    protected function checkTime()
    {
        $limit_time         = 30;
        $map['uid']         = is_login();
        $map['create_time'] = array('GT', time() - (int) $limit_time);
        $list               = $this->where($map)->select();
        if ($list) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检测相同发帖
     * @return boolean ture 正常，false 频繁注册
     * @author jry <598821125@qq.com>
     */
    protected function checkRepeat($content)
    {
        preg_match_all("/([\一-\龥]){1}/u", $content, $num);
        if (2 > count($num[0])) {
            return false;
        }
        $map['uid']     = is_login();
        $map['content'] = array('eq', $content);
        $list           = $this->where($map)->select();
        if ($list) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_find(&$result, $options)
    {
        $result['user']               = D('Admin/User')->getUserInfo($result['uid']);
        $result['create_time_format'] = friendly_date($result['create_time']);
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
     * 发表评论
     * @author jry <598821125@qq.com>
     */
    public function addNew($data)
    {
        $add_result = $this->add($data);
        if ($add_result) {
            //更新评论数
            $article_object = D($this->moduleName . '/Index');
            $article_object->where(array('id' => (int) $data['data_id']))->setInc('comment_count');

            // 更新最后评论时间
            $article_object->where(array('id' => (int) $data['data_id']))->setField('last_reply_time', $data['create_time']);

            //获取当前被评论文档的基础信息
            $current_document_info = $article_object->find($data['data_id']);

            //查看详情连接
            $view_detail = '<a href="' . U($this->moduleName . '/Index/detail', array('id' => $current_document_info['id']), true, true) . '"> 查看详情... </a>';

            //当前发表评论的用户信息
            $uid               = is_login();
            $current_user_info = D('Admin/User')->getUserInfo($uid);

            //给评论用户用户名加上链接以便于直接点击
            $current_username = '<a href="' . U('User/Index/home', array('uid' => $current_user_info['id']), true, true) . '">' . $current_user_info['nickname'] . '</a>';

            //如果是对别人的评论进行回复则获取被评论的那个人的UID以便于发消息骚扰他
            if ($data['pid']) {
                $previous_comment_uid = D($this->moduleName . '/Comment')->getFieldById($data['pid'], 'uid');
            }

            //定义消息结构
            $msg_data['title']    = $current_username . '回复了您！' . $view_detail;
            $msg_data['type']     = 1;
            $msg_data['form_uid'] = $uid;

            //给文档作者发送消息
            //自己给自己发表的文档评论时不发送 要求$current_document_info['uid'] !== $current_user_info['id']
            if ($current_document_info['uid'] !== $current_user_info['id']) {
                //给文档发表者发消息
                $msg_data['to_uid'] = $current_document_info['uid'];
                $result             = D('User/Message')->sendMessage($msg_data);
            }

            //给被回复者发送消息
            //自己回复自己的评论时不发送 要求$current_document_info['uid'] !== $previous_comment_uid
            //如果是对别人的评论进行回复则获取被评论的那个人的UID以便于发消息骚扰他
            if ($data['pid']) {
                $previous_comment_uid = D($this->moduleName . '/Comment')->getFieldById($data['pid'], 'uid');
                if ($current_document_info['uid'] !== $previous_comment_uid) {
                    $msg_data['to_uid'] = $previous_comment_uid;
                    $result             = D('User/Message')->sendMessage($msg_data);
                }
            }
        }
        return $add_result;
    }

    /**
     * 根据条件获取评论列表
     * @author jry <598821125@qq.com>
     */
    public function getCommentList($data_id, $limit = 10, $page = 1, $order = 'id desc', $con = null)
    {
        $map['status']  = 1;
        $map['data_id'] = $data_id;
        if ($con) {
            $map = array_merge($map, $con);
        }
        $lists = $this->where($map)->page($page, $limit)->order($order)->select();
        $page  = new Page(
            $this->where($map)->count(),
            $limit
        );
        foreach ($lists as $key => &$val) {
            if ($val['pid'] > 0) {
                $parent_comment                 = $this->find($val['pid']);
                $val['parent_comment_nickname'] = $parent_comment['user']['nickname'];
            }
        }
        $return['lists'] = $lists;
        $return['page']  = $page->show();
        return $return;
    }
}
