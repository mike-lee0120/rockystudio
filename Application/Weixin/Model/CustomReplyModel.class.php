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
namespace Weixin\Model;

use Common\Model\Model;

/**
 * 公众号模型
 * @author jry <598821125@qq.com>
 */
class CustomReplyModel extends Model
{
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'weixin_custom_reply';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('keyword', 'require', '关键词不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('reply_type', 'require', '回复类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
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
     * 自定义回复类型
     * @author jry <598821125@qq.com>
     */
    public function reply_type($id)
    {
        $list['text']     = '文本回复';
        $list['material'] = '图文回复';
        $list['addon']    = '插件回复';
        return $id ? $list[$id] : $list;
    }
}
