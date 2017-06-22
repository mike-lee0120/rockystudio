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
namespace Im\Model;

use Common\Model\Model;

/**
 * 群聊消息模型
 * @author jry <598821125@qq.com>
 */
class QunMessageModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'im_qun_message';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('message', 'require', '请输入消息', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('is_pushed', '0', self::MODEL_INSERT),
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
        $result['user']               = D('Admin/User')->getUserInfo($result['uid']);
        $result['create_time_format'] = time_format($result['create_time'], 'Y-m-d H:i:s');
        $result['message']            = \lyf\Str::str2url($result['message']); // URL处理

        // 表情处理
        $face_list = array();
        for ($i = 1; $i <= 75; $i++) {
            $face_list['[ocface:' . $i . ']'] = '<span class="ocface" style="display: inline-block;width: 24px;height: 24px;background: url(' . C('TMPL_PARSE_STRING.__PUBLIC__') . '/libs/jquery_qqFace/img/' . $i . '.gif) no-repeat"></span>';
        }
        $result['message'] = strtr($result['message'], $face_list);
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
     * 发送消息
     * @author jry <598821125@qq.com>
     */
    public function sendMessage($send_data)
    {
        $msg_data['message'] = $send_data['message'] ?: ''; //消息内容

        // 表情处理
        $face_list = array();
        for ($i = 1; $i <= 75; $i++) {
            $face_list['<span class="ocface" style="display: inline-block;width: 24px;height: 24px;background: url(' . C('TMPL_PARSE_STRING.__PUBLIC__') . '/libs/jquery_qqFace/img/' . $i . '.gif) no-repeat"></span>'] = '[ocface:' . $i . ']';
        }
        $msg_data['message'] = strtr($msg_data['message'], $face_list);

        $data = $this->create($msg_data);
        if ($data) {
            $result = $this->add($data);
            if (!$result) {
                $this->error = '发送失败';
            }

            return $status;
        }
    }

    /**
     * 获取当前用户未读数量
     * @author jry <598821125@qq.com>
     */
    public function newTalkCount()
    {
        $map['status']  = array('eq', 1);
        $map['to_uid']  = array('eq', is_login());
        $map['is_read'] = array('eq', 0);
        return $this->where($map)->count();
    }
}
