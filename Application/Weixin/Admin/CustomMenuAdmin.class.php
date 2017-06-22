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

require_once dirname(dirname(__FILE__)) . '/Util/Wechat/wechat.class.php';
/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class CustomMenuAdmin extends AdminController
{
    /**
     * 自定义菜单列表
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        //获取所有自定义菜单
        $map['status']      = array('egt', '0'); //禁用和正常状态
        $custom_menu_object = D('CustomMenu');
        $p                  = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list          = $custom_menu_object
            ->where($map)
            ->order('sort asc, id asc')
            ->select();

        // 转换成树状列表
        $tree      = new \lyf\Tree();
        $data_list = $tree->array2tree($data_list, 'name');

        $attr['name']  = 'send';
        $attr['title'] = '发送新菜单给微信服务器';
        $attr['class'] = 'btn btn-success-outline btn-pill ajax-get';
        $attr['url']   = U('updateMenu');

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('自定义菜单列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addTopButton('delete') // 添加删除按钮
            ->addTopButton('self', $attr)
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title_show', '菜单名称')
            ->addTableColumn('type', '菜单类型')
            ->addTableColumn('key', '菜单事件key值')
            ->addTableColumn('ctime', '创建时间', 'time')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    //选择菜单事件类型的时候改变页面元素
    private $extra_html = <<<EOF
    <script type="text/javascript">
        //选择菜单事件类型的时候改变页面元素
        $(function(){
            $('select[name="pid"]').change(function(){
                var pid = $(this).val();
                if(pid != ''){
                    $('.item_type option[value="none"]').addClass('disabled').prop('disabled', true);
                } else {
                    $('.item_type option[value="none"]').removeClass('disabled').prop('disabled', false);
                }
            });
            $('select[name="type"]').change(function(){
                var type = $(this).val();
                if(type == 'none'){ //无事件的一级菜单
                    $('.item_key').addClass('hidden');
                }else if(type == 'view'){ //click链接，view类型必须
                    $('.item_key .item-label').html('填写外链URL地址');
                    $('.item_key').removeClass('hidden');
                }else if(type == 'media_id' || type == 'view_limited'){ //media_id类型和view_limited类型必须
                    $('.item_key .item-label').html('填写永久素材的合法media_id');
                    $('.item_key').removeClass('hidden');
                } else {
                    $('.item_key .item-label').html('填写自动回复的关键词');
                    $('.item_key').removeClass('hidden');
                }
            });
        });
    </script>
EOF;

    /**
     * 新增自定义菜单
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            $weixin_mp_object = D('CustomMenu');
            $data             = $weixin_mp_object->create();
            if ($data) {
                $id = $weixin_mp_object->add();
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($weixin_mp_object->getError());
            }
        } else {
            // 获取一级菜单
            $map                = array();
            $map['pid']         = 0;
            $custom_menu_object = D('CustomMenu');
            $custom_menu_top    = $custom_menu_object->where($map)->select();
            foreach ($custom_menu_top as $key => $val) {
                $new_custom_menu_top[$val['id']] = $val['name'];
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增自定义菜单') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('pid', 'select', '一级菜单', '如果是一级菜单不用选择', $new_custom_menu_top)
                ->addFormItem('name', 'text', '自定义菜单名称', '自定义菜单名称')
                ->addFormItem('type', 'select', '菜单类型', '', D('CustomMenu')->menu_type())
                ->addFormItem('key', 'text', '菜单事件KEY值', '用于消息接口推送，不超过128字节')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }

    /**
     * 编辑自定义菜单
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $weixin_mp_object = D('CustomMenu');
            $data             = $weixin_mp_object->create();
            if ($data) {
                if ($weixin_mp_object->save() !== false) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($weixin_mp_object->getError());
            }
        } else {
            // 获取一级菜单
            $map                = array();
            $map['pid']         = 0;
            $custom_menu_object = D('CustomMenu');
            $custom_menu_top    = $custom_menu_object->where($map)->select();
            foreach ($custom_menu_top as $key => $val) {
                $new_custom_menu_top[$val['id']] = $val['name'];
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑自定义菜单') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('pid', 'select', '一级菜单', '如果是一级菜单不用选择', $new_custom_menu_top)
                ->addFormItem('name', 'text', '自定义菜单名称', '自定义菜单名称')
                ->addFormItem('type', 'select', '菜单类型', '', D('CustomMenu')->menu_type())
                ->addFormItem('key', 'text', '菜单事件KEY值，用于消息接口推送，不超过128字节')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(D('CustomMenu')->find($id))
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }

    /**
     * 更新微信自定义菜单
     * @author jry <598821125@qq.com>
     */
    public function updateMenu()
    {
        $map['status']    = 1;
        $custom_menu_list = D('CustomMenu')
            ->field('id,pid,name,type,key')
            ->where($map)
            ->select();

        foreach ($custom_menu_list as $key => &$val) {
            if ($val['type'] === 'none') {
                unset($val['type']);
                unset($val['key']);
            } else if ($val['type'] == 'view') {
                $val['url'] = $val['key'];
                unset($val['key']);
            }
        }

        //转换成树状列表
        $tree             = new \lyf\Tree();
        $custom_menu_list = $tree->list2tree($custom_menu_list, 'id', 'pid', 'sub_button');

        //清除菜单数组多余的值
        foreach ($custom_menu_list as $key => &$val) {
            unset($val['id']);
            unset($val['pid']);
            if ($val['sub_button']) {
                foreach ($val['sub_button'] as $key2 => &$val2) {
                    unset($val2['id']);
                    unset($val2['pid']);
                }
            }
        }
        $button_list['button'] = $custom_menu_list;

        //加载微信SDK
        $options = array(
            'token'          => C('weixin_config.token'), //填写你设定的key
            'encodingaeskey' => C('weixin_config.crypt'), //填写加密用的EncodingAESKey
            'appid'          => C('weixin_config.appid'), //填写高级调用功能的app id, 请在微信开发模式后台查询
            'appsecret'      => C('weixin_config.appsecret'), //填写高级调用功能的密钥
        );
        $wechat = new \Wechat($options);

        $result = $wechat->createMenu($button_list);

        if ($result === true) {
            $this->success('自定义菜单更新成功');
        } else {
            $this->error('自定义菜单更新失败');
        }
    }
}
