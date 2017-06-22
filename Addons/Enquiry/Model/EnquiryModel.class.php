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

namespace Addons\Enquiry\Model;

use Common\Model\Model;

/**
 * 友情链接模型
 * @author jry <598821125@qq.com>
 */
class EnquiryModel extends Model
{
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'addon_enquiry';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('name', 'require', '姓名不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('name', '2,20', '联系方式长度为2-20个字符', self::EXISTS_VALIDATE, 'length'),
        array('content', 'require', '留言内容不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
        array('handle_uid', 'is_login', self::MODEL_UPDATE, 'function'),
    );

    /**
     * 状态列表
     * @author jry <598821125@qq.com>
     */
    public function status_list($id = null)
    {
        $list[-1] = '无效';
        $list[1]  = '有效';
        $list[2]  = '已处理';
        return is_null($id) ? $list : ($list[$id] ?: $id);

    }

}
