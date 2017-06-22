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
namespace User\Model;

use Common\Model\Model;

/**
 * 用户积分模型
 * @author jry <598821125@qq.com>
 */
class ScoreLogModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'user_score_log';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('uid', 'require', 'UID必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('uid', 'number', 'UID必须数字', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('type', 'number', '变动方式必须数字', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('type', 'require', '变动方式必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('score', 'require', '变动数量必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('score', 'number', '变动数量必须数字', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('message', 'require', '变动说明必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('message', '1,255', '变动说明长度为1-255个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 积分变动类型
     * @author jry <598821125@qq.com>
     */
    public function change_type($id)
    {
        $list[1] = '增加';
        $list[2] = '减少';
        return $id ? $list[$id] : $list;
    }

    /**
     * 积分变动
     * @author jry <598821125@qq.com>
     */
    public function changeScore($type, $uid, $score, $message, $field = 'score')
    {
        $data['type']    = $type;
        $data['uid']     = $uid;
        $data['score']   = $score;
        $data['message'] = $message;
        $data            = $this->create($data);
        if ($data) {
            $map['id'] = $data['uid'];
            switch ($data['type']) {
                case 1:
                    $result = D('User/User')->where($map)->setInc($field, $data['score']);
                    $msg    = '增加' . $data['score'];
                    break;
                case 2:
                    $result = D('User/User')->where($map)->setDec($field, $data['score']);
                    $msg    = '扣除' . $data['score'];
                    break;
            }
            if ($result) {
                $result = $this->add($data);
                // 构造消息数据
                $msg_data['to_uid']  = $data['uid'];
                $msg_data['title']   = '积分变动';
                $msg_data['content'] = '您好：<br>'
                . '您在' . C('WEB_SITE_TITLE') . '的积分由于' . $data['message'] . $msg . '<br>';
                D('User/Message')->sendMessage($msg_data);
                return true;
            }
        } else {
            return $this->getError();
        }
    }
}
