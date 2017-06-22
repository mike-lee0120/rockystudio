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
namespace Addons\Email\Model;

use Common\Model\Model;

require_once dirname(dirname(__FILE__)) . '/PHPMailer/class.phpmailer.php';
require_once dirname(dirname(__FILE__)) . '/PHPMailer/class.smtp.php';
/**
 * 邮件模型
 * @author jry <598821125@qq.com>
 */
class EmailModel extends Model
{
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'addon_email';

    /**
     * 后台列表管理相关定义
     * @author jry <598821125@qq.com>
     */
    public $adminList = array(
        'title'      => '邮件列表',
        'model'      => 'addon_email',
        'search_key' => 'title',
        'order'      => 'id desc',
        'map'        => null,
        'list_grid'  => array(
            'title'    => array(
                'title' => '标题',
                'type'  => 'text',
            ),
            'receiver' => array(
                'title' => '收件人',
                'type'  => 'text',
            ),
            'is_sent'  => array(
                'title' => '发送',
                'type'  => 'status',
            ),
            'status'   => array(
                'title' => '状态',
                'type'  => 'status',
            ),
        ),
        'field'      => array( //后台新增、编辑字段
            'title'    => array(
                'name'  => 'title',
                'title' => '标题',
                'type'  => 'text',
                'tip'   => '邮件标题',
            ),
            'content'  => array(
                'name'  => 'content',
                'title' => '正文',
                'type'  => 'kindeditor',
                'tip'   => '邮件正文',
            ),
            'receiver' => array(
                'name'  => 'receiver',
                'title' => '收件人',
                'type'  => 'text',
                'tip'   => '填写all表示发给所有用户',
            ),
        ),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('content', 'require', '正文不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('is_sent', '0', self::MODEL_INSERT),
        array('title', 'html2text', self::MODEL_BOTH, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('sort', '0', self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 系统邮件发送函数
     * @param string $mail_data 邮件信息结构
     * @$mail_data['receiver'] 收件人
     * @$mail_data['title'] 邮件主题
     * @$mail_data['content']邮件内容
     * @$mail_data['attachment'] 附件列表
     * @return boolean
     * @author jry <598821125@qq.com>
     */
    public function send($mail_data)
    {
        $addon_config = \Common\Controller\Addon::getConfig('Email');
        if ($addon_config['status']) {
            $mail          = new \PHPMailer;
            $mail->CharSet = 'utf-8';

            //发信服务
            switch ($addon_config['MAIL_SMTP_TYPE']) {
                case 'mail':
                    $mail->isMail();
                    break;
                case 'smtp':
                    $mail->isSMTP();
                    break;
                case 'sendmail':
                    $mail->isSendmail();
                    break;
                case 'qmail':
                    $mail->isQmail();
                    break;
            }

            $mail->Host       = $addon_config['MAIL_SMTP_HOST']; // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true; // Enable SMTP authentication
            $mail->Username   = $addon_config['MAIL_SMTP_USER']; // SMTP username
            $mail->Password   = $addon_config['MAIL_SMTP_PASS']; // SMTP password
            $mail->SMTPSecure = $addon_config['MAIL_SMTP_SECURE'] ?: ''; // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = $addon_config['MAIL_SMTP_PORT'] ?: 25; // TCP port to connect to

            if ($addon_config['MAIL_SMTP_TYPE'] === 'mail' && $mail_data['from']) {
                $mail->setFrom($mail_data['from'], $mail_data['from_user']);
            } else {
                $mail->setFrom($addon_config['MAIL_SMTP_USER'], C('WEB_SITE_TITLE'));
            }
            //$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            $mail->addAddress($mail_data['receiver']); // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true); // Set email format to HTML

            $mail_body_template = $addon_config['default']; //获取邮件模版配置
            $mail_body          = str_replace("[MAILBODY]", $mail_data['content'], $mail_body_template); //使用邮件模版

            $mail->Subject = '=?utf-8?B?' . base64_encode($mail_data['title'] . '｜' . C('WEB_SITE_TITLE')) . '?=';
            $mail->Body    = $mail_body;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if (isset($addon_config['debug'])) {
                $mail->SMTPDebug = $addon_config['debug'];
            }
            if (!$mail->send()) {
                $this->error = 'Mailer Error: ' . $mail->ErrorInfo;
                return false;
            } else {
                return true;
            }
        } else {
            $this->error = '插件关闭';
            return false;
        }
    }
}
