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
    'status' => array(
        'title'   => '是否开启签到',
        'type'    => 'radio',
        'options' => array(
            '1' => '开启',
            '0' => '关闭',
        ),
        'value'   => '1',
    ),
    'score'  => array(
        'title' => '每次签到积分',
        'type'  => 'num',
        'value' => 1,
    ),
);
