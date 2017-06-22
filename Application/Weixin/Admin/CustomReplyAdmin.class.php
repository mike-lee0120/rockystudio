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
namespace Weixin\Admin;

use Admin\Controller\AdminController;
use lyf\Page;

/**
 * 自定义回复控制器
 * @author jry <598821125@qq.com>
 */
class CustomReplyAdmin extends AdminController
{
    /**
     * 自定义回复列表
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        //获取所有自定义回复
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list     = D('CustomReply')
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            D('CustomReply')->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('自定义回复列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addTopButton('delete') // 添加删除按钮
            ->addTableColumn('id', 'ID')
            ->addTableColumn('keyword', '关键词')
            ->addTableColumn('content', '回复内容')
            ->addTableColumn('reply_type', '回复类型')
            ->addTableColumn('reply_key', '回复其他表数据主键')
            ->addTableColumn('ctime', '创建时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    //选择菜单事件类型的时候改变页面元素
    private $extra_html = <<<EOF
    <script type="text/javascript">
        //选择回复类型的时候改变页面元素
        $(function(){
            $('select[name="reply_type"]').change(function(){
                var type = $(this).val();
                if(type == 'text'){
                    $('.item_content').removeClass('hidden').prop('disabled', false);
                    $('.item_reply_key').addClass('hidden').prop('disabled', true);
                } else {
                    $('.item_content').addClass('hidden').prop('disabled', true);
                    $('.item_reply_key').removeClass('hidden').prop('disabled', false);
                }
            });
        });
    </script>
EOF;

    /**
     * 新增自定义回复
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            $custom_reply_object = D('CustomReply');
            $data                = $custom_reply_object->create();
            if ($data) {
                $id = $custom_reply_object->add();
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($custom_reply_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增自定义回复') //设置页面标题
                ->setPostUrl(U('add')) //设置表单提交地址
                ->addFormItem('keyword', 'text', '关键词', '自定义回复关键词')
                ->addFormItem('reply_type', 'select', '回复类型', '回复类型', D('CustomReply')->reply_type())
                ->addFormItem('content', 'textarea', '文本回复正文', '简文本回复正文介', null, 'hidden')
                ->addFormItem('reply_key', 'text', '回复其他表数据主键', '格式：1,3,6', null, 'hidden')
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }

    /**
     * 编辑自定义回复
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $custom_reply_object = D('CustomReply');
            $data                = $custom_reply_object->create();
            if ($data) {
                if ($custom_reply_object->save() !== false) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($custom_reply_object->getError());
            }
        } else {
            $info = D('CustomReply')->find($id);

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑自定义回复') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('keyword', 'text', '关键词', '自定义回复关键词')
                ->addFormItem('reply_type', 'select', '回复类型', '回复类型', D('CustomReply')->reply_type())
                ->addFormItem('content', 'textarea', '文本回复正文', '简文本回复正文介', null, $info['reply_type'] == 'text' ?: 'hidden')
                ->addFormItem('reply_key', 'text', '回复其他表数据主键', '格式：1,3,6', null, $info['reply_type'] != 'text' ?: 'hidden')
                ->setFormData($info)
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }
}
