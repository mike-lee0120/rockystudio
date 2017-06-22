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
namespace Addons\Sign\Model;

use Common\Model\Model;

/**
 * 签到模型
 * @author jry <598821125@qq.com>
 */
class SignModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'addon_sign';

    /**
     * 后台列表管理相关定义
     * @author jry <598821125@qq.com>
     */
    public $adminList = array(
        'title'      => '签到记录',
        'model'      => 'addon_sign',
        'search_key' => 'uid',
        'order'      => 'id desc',
        'map'        => null,
        'list_grid'  => array(
            'id'          => array(
                'title' => 'ID',
                'type'  => 'text',
            ),
            'uid'         => array(
                'title' => 'UID',
                'type'  => 'text',
            ),
            'date'        => array(
                'title' => '日期',
                'type'  => 'text',
            ),
            'create_time' => array(
                'title' => '签到时间',
                'type'  => 'datetime',
            ),
            'status'      => array(
                'title' => '状态',
                'type'  => 'status',
            ),
        ),
        'field'      => array( //后台新增、编辑字段
            'uid'  => array(
                'name'  => 'uid',
                'title' => 'UID',
                'type'  => 'text',
                'tip'   => '签到用户(后台补签不会获得积分)',
            ),
            'date' => array(
                'name'  => 'date',
                'title' => '签到日期',
                'type'  => 'date',
                'tip'   => '签到日期(后台补签不会获得积分)',
            ),
        ),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('uid', 'require', 'UID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('sort', '0', self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
        array('date', 'date', self::MODEL_INSERT, 'function'),
    );

    /**
     * 签到功能
     * @return boolean
     * @author jry <598821125@qq.com>
     */
    public function sign($uid)
    {
        $addon_config = \Common\Controller\Addon::getConfig('Sign');
        if ($addon_config['status']) {
            if (!$uid) {
                $this->error('请登陆系统');
                return false;
            }
            $data['uid']  = $uid;
            $data['date'] = date('Y-m-d', time());
            $exist        = $this->where($data)->find();
            if ($exist) {
                $this->error = '今日已经签到';
            } else {
                $ret = $this->create($data);
                if ($ret) {
                    $result = $this->add($ret);
                    if ($result) {
                        $ret = D('User/ScoreLog')->changeScore(1, $uid, $addon_config['score'], $data['date'] . '每日签到');
                    }
                    return $result;
                }
            }
            return false;
        } else {
            $this->error = '插件关闭';
            return false;
        }
    }
}
