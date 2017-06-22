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
    'status'  => array(
        'title'   => '是否开启:',
        'type'    => 'radio',
        'options' => array(
            '1' => '开启',
            '0' => '关闭',
        ),
        'value'   => '1',
    ),
    'skin'    => array(
        'title'   => '客服主题:',
        'type'    => 'select',
        'options' => array(
            'blue1'   => 'blue1',
            'blue2'   => 'blue2',
            'green1'  => 'green1',
            'red1'    => 'red1',
            'yellow1' => 'yellow1',
        ),
        'value'   => 'blue1',
    ),
    'group'   => array(
        'type'    => 'group',
        'options' => array(
            'qq1_group' => array(
                'title'   => 'QQ1',
                'options' => array(
                    'qq1_title' => array(
                        'title' => 'QQ1标题：',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'qq1'       => array(
                        'title' => 'QQ2号码：',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'qq2_group' => array(
                'title'   => 'QQ2',
                'options' => array(
                    'qq2_title' => array(
                        'title' => 'QQ2标题：',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'qq2'       => array(
                        'title' => 'QQ2号码：',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'qq3_group' => array(
                'title'   => 'QQ3',
                'options' => array(
                    'qq3_title' => array(
                        'title' => 'QQ3标题：',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'qq3'       => array(
                        'title' => 'QQ3号码：',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'qq4_group' => array(
                'title'   => 'QQ4',
                'options' => array(
                    'qq4_title' => array(
                        'title' => 'QQ4标题：',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'qq4'       => array(
                        'title' => 'QQ4号码：',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
        ),
    ),
    'tel1'    => array(
        'title' => '售前:',
        'type'  => 'text',
        'value' => '88888888',
    ),
    'tel2'    => array(
        'title' => '售后:',
        'type'  => 'text',
        'value' => '88888888',
    ),
    'qr_code' => array(
        'title' => '二维码:',
        'type'  => 'picture',
        'value' => '',
    ),
    'more'    => array(
        'title' => '更多:',
        'type'  => 'text',
        'value' => 'http://www.corethink.cn',
    ),
    'foot'    => array(
        'title' => '底部:',
        'type'  => 'text',
        'value' => '9:30-18:00',
    ),
);
