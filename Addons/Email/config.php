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
$default_tpl = <<<EOF
<div style="background-color:#2699ed;width: 760px;margin:auto;">
    <table width="760" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse;width:760px;border-spacing:0;padding:0;margin:0 auto;" class="ke-zeroborder">
        <tbody>
            <tr>
                <td height="62" style="padding-left:15px;">
                    <a href="https://www.lingyun.net" target="_blank" title="零云"> <img src="https://www.lingyun.net/Application/Home/View/Public/img/default/logo_title_inverse.png" border="0"  height="48" title="零云" alt="零云" /> </a>
                </td>
                <td align="right" style="padding-right:15px;">
                    <a style="font-size:14px;font-weight:400;text-decoration:none;color: #fff;margin-right: 20px;" href="https://www.lingyun.net/" target="_blank" title="首页">首页</a>
                    <a style="font-size:14px;font-weight:400;text-decoration:none;color: #fff;margin-right: 20px;" href="https://bbs.lingyun.net/" target="_blank" title="社区">社区</a>
                    <a style="font-size:14px;font-weight:400;text-decoration:none;color: #fff;margin-right: 20px;" href="https://doc.lingyun.net/lingyun.html" target="_blank" title="文档">文档</a>
                    <a style="font-size:14px;font-weight:400;text-decoration:none;color: #fff;margin-right: 20px;" href="https://www.lingyun.net/appstore" target="_blank" title="应用市场">应用市场</a>
                    <a style="font-size:14px;font-weight:400;text-decoration:none;color: #fff;margin-right: 20px;" href="https://www.lingyun.net/price.html" target="_blank" title="商业版">商业版</a>
                    <a style="font-size:14px;font-weight:400;text-decoration:none;color: #fff;margin-right: 20px;" href="https://lyshop.lingyun.net/" target="_blank" title="商城解决方案lyshop">商城解决方案lyshop</a>
                </td>
            </tr>
            <tr>
                <td bgcolor="#FFFFFF" colspan="2">
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td style="padding:15px;">
                                    [MAILBODY]
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <table width="760" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse;width:760px;border-spacing:0;padding:0;margin:0 auto;background-color: #fafbfc" class="ke-zeroborder">
        <tbody>
            <tr>
                <td colspan="3" style='background-color: #eceff1;'>
                    <p style="font-size:14px;padding:0px 24px;font-family:Tahoma;color: #607d8b">
                        联系我们：
                    </p>
                </td>
            </tr>
            <tr>
                <td valign="top" style="line-height:24px;padding:24px;">
                    <p style="font-size:12px;margin:0px;">
                        <span>官方网站：</span><a href="https://www.lingyun.net" style='text-decoration:none;'><span style="font-family:Tahoma;color:#3399ff;"><b>零云网</b></span></a>
                    </p>
                    <p style="font-size:12px;margin:0px;">
                        <span>微信公众号：</span><span style="font-family:Tahoma;"><b>零云网</b></span>
                    </p>
                    <p style="font-size:12px;margin:0;">
                        用户交流1群：<strong style="font-family:Tahoma;">105108204</strong>
                    </p>
                    <p style="font-size:12px;margin:0;">
                        用户交流2群：<strong style="font-family:Tahoma;">219842410</strong>
                    </p>
                    <p style="font-size:12px;margin:0px;">
                        <span>官方微博：</span><a href="http://weibo.com/u/5667168319?is_hot=1" style='text-decoration:none;'><span style="font-family:Tahoma;color:#3399ff;"><b>零云_CoreThink_OpenCMF</b></span></a>
                    </p>
                    <p style="font-size:8px;margin:0;">
                        为了您能够正常收到来自零云的最新信息和会员邮件，请将<a style="text-decoration:underline;color:#6cb4ff;cursor:pointer;">noreply@lingyun.net</a>添加为联系人。
                    </p>
                </td>
                <td style="padding:24px 20px 22px 0;">
                    <img src="https://www.lingyun.net/Uploads/2016-09-28/57eb653b913fb.jpg" width="150" height="150" border="0" />
                </td>
                <td width="17" style="font-size:0;">
                </td>
            </tr>
            <tr style="height:10px;" bgcolor="#fbfbfb">
                <td colspan="3">
                </td>
            </tr>
        </tbody>
    </table>
</div>
EOF;

return array(
    'status' => array(
        'title'   => '是否开启邮件:',
        'type'    => 'radio',
        'options' => array(
            '1' => '开启',
            '0' => '关闭',
        ),
        'value'   => '1',
    ),
    'debug'  => array(
        'title'   => '调试模式:',
        'type'    => 'radio',
        'options' => array(
            '1' => '开启',
            '0' => '关闭',
        ),
        'value'   => '0',
    ),
    'group'  => array(
        'type'    => 'group',
        'options' => array(
            'server'   => array(
                'title'   => '发信设置',
                'options' => array(
                    'MAIL_SMTP_TYPE'   => array(
                        'title'   => '邮件发信类型：',
                        'type'    => 'select',
                        'options' => array(
                            'mail'     => 'PHP自带函数发送',
                            'smtp'     => 'SMTP服务器发送',
                            'sendmail' => 'sendmail程序发送',
                            'qmail'    => 'qmail程序发送',
                        ),
                        'value'   => 'mail',
                        'tip'     => '邮件发信类型',
                    ),
                    'MAIL_SMTP_SECURE' => array(
                        'title'   => '安全协议类型：',
                        'type'    => 'select',
                        'options' => array(
                            '0'   => ' 不使用 ',
                            'ssl' => 'SSL',
                            'tls' => 'TLS',
                        ),
                        'value'   => '0',
                        'tip'     => '安全协议类型',
                    ),
                    'MAIL_SMTP_PORT'   => array(
                        'title' => 'SMTP服务器端口：',
                        'type'  => 'text',
                        'value' => '25',
                        'tip'   => '普通端口一般为25，SSL端口为465，TLS端口为587',
                    ),
                    'MAIL_SMTP_HOST'   => array(
                        'title' => 'SMTP服务器地址：',
                        'type'  => 'text',
                        'value' => 'smtp.qq.com',
                        'tip'   => '邮箱服务器名称[如：smtp.qq.com]',
                    ),
                    'MAIL_SMTP_USER'   => array(
                        'title' => 'SMTP服务器用户名：',
                        'type'  => 'text',
                        'value' => '',
                        'tip'   => 'SMTP服务器用户名',
                    ),
                    'MAIL_SMTP_PASS'   => array(
                        'title' => 'SMTP服务器密码：',
                        'type'  => 'password',
                        'value' => '',
                        'tip'   => 'SMTP服务器密码',
                    ),
                ),
            ),
            'template' => array(
                'title'   => '发信模版',
                'options' => array(
                    'default' => array(
                        'title' => '默认发信模版：',
                        'type'  => 'kindeditor',
                        'value' => $default_tpl,
                        'tip'   => '默认发信模版',
                        'extra' => array(
                            'self' => array(
                                'urlType' => 'false',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
