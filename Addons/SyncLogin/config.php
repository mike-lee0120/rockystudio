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
    'type'  => array(
        'title'   => '开启同步登录：',
        'type'    => 'checkbox',
        'options' => array(
            'Lingyun' => '零云',
            'Weixin'  => '微信',
            'Qq'      => 'QQ',
            'Sina'    => '新浪',
            'Github'  => 'Github',
        ),
    ),
    'meta'  => array(
        'title' => '接口验证代码：',
        'type'  => 'textarea',
        'value' => '',
        'tip'   => '需要在Meta标签中写入验证信息时，拷贝代码到这里。',
    ),
    'group' => array(
        'type'    => 'group',
        'options' => array(
            'Lingyun' => array(
                'title'   => '零云配置',
                'options' => array(
                    'LingyunKey'    => array(
                        'title' => '零云APPKEY：',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => '申请地址：http://www.lingyun.net/oauth2/clients/add',
                    ),
                    'LingyunSecret' => array(
                        'title' => '零云APPSECRET：',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => '申请地址：http://www.lingyun.net/oauth2/clients/add',
                    ),
                ),
            ),
            'Wexin'   => array(
                'title'   => '微信配置',
                'options' => array(
                    'WeixinKey'    => array(
                        'title' => '微信APPKEY：',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => '申请地址：http://open.weixin.qq.com/',
                    ),
                    'WeixinSecret' => array(
                        'title' => '微信APPSECRET：',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => '申请地址：http://open.weixin.qq.com/',
                    ),
                ),
            ),
            'Qq'      => array(
                'title'   => 'QQ配置',
                'options' => array(
                    'QqKey'    => array(
                        'title' => 'QQ互联APPKEY：',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => '申请地址：http://connect.qq.com',
                    ),
                    'QqSecret' => array(
                        'title' => 'QQ互联APPSECRET：',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => '申请地址：http://connect.qq.com',
                    ),
                ),
            ),
            'Sina'    => array(
                'title'   => '新浪配置',
                'options' => array(
                    'SinaKey'    => array(
                        'title' => '新浪APPKEY：',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => '申请地址：http://open.weibo.com/',
                    ),
                    'SinaSecret' => array(
                        'title' => '新浪APPSECRET：',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => '申请地址：http://open.weibo.com/',
                    ),
                ),
            ),
            'Github'  => array(
                'title'   => 'Github配置',
                'options' => array(
                    'GithubKey'    => array(
                        'title' => 'GithubAPPKEY：',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => '申请地址：https://github.com/settings/applications',
                    ),
                    'GithubSecret' => array(
                        'title' => 'GithubAPPSECRET：',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => '申请地址：https://github.com/settings/applications',
                    ),
                ),
            ),
        ),
    ),
);
