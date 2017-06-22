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
namespace Weixin\Behavior;

use Think\Behavior;

defined('THINK_PATH') or exit();

/**
 * 微信下自动登录
 * @author jry <598821125@qq.com>
 */
class WeixinBehavior extends Behavior
{
    /**
     * 行为扩展的执行入口必须是run
     * @author jry <598821125@qq.com>
     */
    public function run(&$content)
    {
        // 判断判断是微信浏览器自动跳转登录页面
        if (request()->isWeixin() && !(is_login()) && C('weixin_config.weixin_login') === '1' && request()->controller() !== 'UserBind') {
            $url = U('Weixin/UserBind/index', null, true, true);
            redirect($url, 1, '进入微信登录');
        }
    }
}
