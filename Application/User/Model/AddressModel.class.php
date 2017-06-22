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
 * 收货地址模型
 * @author jry <598821125@qq.com>
 */
class AddressModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'user_address';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('uid', 'require', '用户ID必须', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('title', 'require', '请输入收货人姓名', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('gender', 'require', '请输入收货人称呼', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('mobile', 'require', '请输入收货人电话', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('city', 'require', '请选择城市', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('address', 'require', '请输入详细地址', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('post_code', 'require', '请输入邮编', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', 1, self::MODEL_INSERT, 'string'),
    );

    /**
     * 称呼列表
     * @author jry <598821125@qq.com>
     */
    public function gender_list($id = null)
    {
        $list[1] = '先生';
        $list[2] = '女士';
        return is_null($id) ? $list : ($list[$id] ?: $id);
    }

}
