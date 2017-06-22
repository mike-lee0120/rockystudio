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
 * 实名认证模型
 * @author jry <598821125@qq.com>
 */
class CertModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'user_cert';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('uid', 'require', '用户ID必须', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('type', 'require', '请选择认证类型', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('cert_type', 'require', '请选择证件类型', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('cert_no', 'require', '请填写证件号码', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('cert_title', 'require', '请填写真实名称', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        //array('cert_photo', 'require', '请上传证件照片', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
    );

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_find(&$result, $options)
    {
        $result['cert_photo_url']  = get_cover($result['cert_photo'], 'default');
        $result['type_title']      = $this->type_list($result['type']);
        $result['cert_type_title'] = $this->cert_type_list($result['cert_type']);
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
     * 消息类型
     * @author jry <598821125@qq.com>
     */
    public function type_list($id)
    {
        $list[1] = '个人认证';
        $list[2] = '组织认证';
        return $id ? $list[$id] : $list;
    }

    /**
     * 消息类型
     * @author jry <598821125@qq.com>
     */
    public function cert_type_list($id)
    {
        $list[1] = '身份证';
        $list[2] = '组织机构代码证/营业执照';
        return $id ? $list[$id] : $list;
    }

    /**
     * 是否实名认证
     * @author jry <598821125@qq.com>
     */
    public function isCert($uid)
    {
        if (!$uid) {
            return false;
        }
        $con['uid']    = $uid;
        $con['status'] = 1;
        $result        = $this->where($con)->find();
        if ($result) {
            return $result;
        }
        return false;
    }
}
