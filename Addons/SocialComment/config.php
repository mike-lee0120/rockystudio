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
        'title'   => '是否开启:',
        'type'    => 'radio',
        'options' => array(
            '1' => '开启',
            '0' => '关闭',
        ),
        'value'   => '1',
    ),
    'comment_type' => array(
        'title'   => '使用类型:',
        'type'    => 'select',
        'options' => array(
            '1' => '多说',
            '2' => '畅言',
            '3' => '友言',
        ),
        'value'   => '1',
    ),
    'group'        => array(
        'type'    => 'group',
        'options' => array(
            'duoshuo'  => array(
                'title'   => '多说配置',
                'options' => array(
                    'comment_short_name_duoshuo' => array(
                        'title' => '短域名',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => '每个站点一个域名',
                    ),
                    'comment_form_pos_duoshuo'   => array(
                        'title'   => '表单位置:',
                        'type'    => 'radio',
                        'options' => array(
                            'top'    => '顶部',
                            'buttom' => '底部',
                        ),
                        'value'   => 'buttom',
                    ),
                    'comment_data_list_duoshuo'  => array(
                        'title' => '单页显示评论数',
                        'type'  => 'text',
                        'value' => '10',
                    ),
                    'comment_data_order_duoshuo' => array(
                        'title'   => '评论显示顺序',
                        'type'    => 'radio',
                        'options' => array(
                            'asc'  => '从旧到新',
                            'desc' => '从新到旧',
                        ),
                        'value'   => 'asc',
                    ),
                ),
            ),
            'changyan' => array(
                'title'   => '畅言配置',
                'options' => array(
                    'appid' => array(
                        'title' => 'appid',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'conf'  => array(
                        'title' => 'conf',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'youyan'   => array(
                'title'   => '友言配置',
                'options' => array(
                    'comment_uid_youyan' => array(
                        'title' => '账号id:',
                        'type'  => 'text',
                        'value' => '90040',
                        'tip'   => '填写自己登录友言后的uid,填写后可进相应官方后台',
                    ),
                ),
            ),
        ),
    ),
);
