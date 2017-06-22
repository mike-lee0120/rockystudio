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
        'title'   => '是否开启:',
        'type'    => 'radio',
        'options' => array(
            '1' => '开启',
            '0' => '关闭',
        ),
    ),
    'type'   => array(
        'title'   => '存储类型',
        'type'    => 'select',
        'options' => array(
            'Qiniu'   => '七牛云',
            'Upyun'   => '又拍云',
            'Sae'     => '新浪Sae',
            'Bcs'     => '百度云',
            'Tietuku' => '贴图库',
            'Ftp'     => 'FTP',
        ),
    ),
    'group'  => array(
        'type'    => 'group',
        'options' => array(
            'Qiniu'   => array(
                'title'   => '七牛云',
                'options' => array(
                    'Qiniu_accessKey' => array(
                        'title' => 'AK',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'Qiniu_secretKey' => array(
                        'title' => 'SK',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'Qiniu_bucket'    => array(
                        'title' => 'bucket',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'Qiniu_domain'    => array(
                        'title' => 'domain',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'Upyun'   => array(
                'title'   => '又拍云',
                'options' => array(
                    'Upyun_host'     => array(
                        'title' => '又拍云服务器',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'Upyun_username' => array(
                        'title' => '又拍云用户',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'Upyun_password' => array(
                        'title' => '又拍云密码',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'Upyun_bucket'   => array(
                        'title' => '空间名称',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'Sae'     => array(
                'title'   => '新浪Sae',
                'options' => array(
                    'Sae_domain' => array(
                        'title' => '域名',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'Bcs'     => array(
                'title'   => '百度云',
                'options' => array(
                    'Bcs_AccessKey' => array(
                        'title' => 'AccessKey',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'Bcs_SecretKey' => array(
                        'title' => 'SecretKey',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'Bcs_bucket'    => array(
                        'title' => '空间名称',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'Ftp'     => array(
                'title'   => 'FTP',
                'options' => array(
                    'Ftp_host'     => array(
                        'title' => 'FTP服务器',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'Ftp_username' => array(
                        'title' => 'FTP用户',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'Ftp_password' => array(
                        'title' => 'FTP密码',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
            'Tietuku' => array(
                'title'   => '贴图库',
                'options' => array(
                    'Tietuku_accessKey' => array(
                        'title' => 'FTP用户',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'Tietuku_secretKey' => array(
                        'title' => 'secretKey',
                        'type'  => 'text',
                        'value' => '',
                    ),
                    'Tietuku_aid'       => array(
                        'title' => '相册ID',
                        'type'  => 'text',
                        'value' => '',
                    ),
                ),
            ),
        ),
    ),
);
