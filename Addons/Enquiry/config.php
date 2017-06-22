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

return array(
    'status'       => array(
        'title'   => '是否开启',
        'type'    => 'radio',
        'options' => array(
            '1' => '开启',
            '0' => '关闭',
        ),
        'value'   => '1',
    ),
    'need_login'   => array(
        'title'   => '是否需要登录',
        'type'    => 'radio',
        'options' => array(
            '1' => '需要',
            '0' => '不需要',
        ),
        'value'   => '1',
    ),
    'title'        => array(
        'title' => '页面标题',
        'type'  => 'text',
        'value' => '在线留言',
    ),
    'color'        => array(
        'title' => '主题色',
        'type'  => 'text',
        'value' => '#2699ed',
    ),
    'abstract'     => array(
        'title' => '页面描述',
        'type'  => 'textarea',
        'value' => '您好，很高兴为您提供服务，如需帮助，请留言，我们将尽快联系并解决您的问题。',
    ),
    'success_tips' => array(
        'title' => '提交成功页面提示:',
        'type'  => 'textarea',
        'value' => '我们会在3个工作日内联系你!',
    ),
    'image'        => array(
        'title' => '链接图片:',
        'type'  => 'picture',
        'value' => '',
    ),
);
