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
namespace User\Admin;

use Admin\Controller\AdminController;
use lyf\Page;

/**
 * 用户控制器
 * @author jry <598821125@qq.com>
 */
class UserAdmin extends AdminController
{
    /**
     * 用户列表
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 多条件筛选
        if (isset($_GET['status'])) {
            $map['status'] = $_GET['status'];
        } else {
            $_GET['status'] = '1';
            $map['status']  = $_GET['status'];
        }
        if ($_GET['reg_type']) {
            $map['reg_type'] = $_GET['reg_type'];
        }
        if ($_GET['create_time']) {
            $create_time        = explode('~', $_GET['create_time']);
            $map['create_time'] = array(array('gt', strtotime($create_time[0])), array('lt', strtotime($create_time[1]) + 86400));
        }
        $keyword = I('keyword', '', 'string');
        if ($keyword) {
            $condition                                = array('like', '%' . $keyword . '%');
            $map['id|username|nickname|email|mobile'] = array(
                $condition,
                $condition,
                $condition,
                $condition,
                $condition,
                '_multi' => true,
            );
        }

        // 获取用户吧列表
        $p           = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $user_object = D('User/User');
        $data_list   = $user_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            $user_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        $attr['name']  = 'add';
        $attr['title'] = '新增';
        $attr['class'] = 'btn btn-primary-outline btn-pill';

        // 获取用户类型
        $user_type_list = D('User/Type')->where(array('status' => 1))->select();
        foreach ($user_type_list as $key => $val) {
            $attr['dropdown'][$key]['title'] = $val['title'];
            $attr['dropdown'][$key]['href']  = U('add', array('user_type' => $val['id']));
        }

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('用户列表') // 设置页面标题
            ->addTopButton('dropdown', $attr) // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->addTopButton('recycle') // 添加删除按钮
            ->addSearchItem('status', 'select', '状态', '状态', array('1' => '正常', '0' => '已禁用', '-1' => '已删除'))
            ->addSearchItem('reg_type', 'select', '注册来源', '注册来源', array('username' => '用户名', 'email' => '邮箱', 'mobile' => '手机号'))
            ->addSearchItem('create_time', 'dateranger', '注册时间', '注册时间')
            ->addSearchItem('keyword', 'text', '关键字', '用户名/邮箱/手机号等')
            ->addTableColumn('id', 'UID')
            ->addTableColumn('avatar', '头像', 'picture')
            ->addTableColumn('nickname', '昵称')
            ->addTableColumn('username', '用户名')
            ->addTableColumn('email', '邮箱')
            ->addTableColumn('mobile', '手机号')
            ->addTableColumn('score', '积分')
            ->addTableColumn('money', '余额')
            ->addTableColumn('create_time', '注册时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('recycle') // 添加删除按钮
            ->display();
    }

    /**
     * 新增用户
     * @author jry <598821125@qq.com>
     */
    public function add($user_type)
    {
        // 获取扩展字段
        $map['type_id']             = array('eq', $user_type);
        $attribute_list[$user_type] = D('User/Attribute')->where($map)->order('id asc')->select();

        // 新增用户
        if (request()->isPost()) {
            $user_object = D('User/User');
            $data        = $user_object->create();
            if ($data) {
                $id = $user_object->add();
                if ($id) {
                    // 存储用户扩展信息
                    if ($user_type) {
                        $type_data = array();
                        foreach ($attribute_list[$user_type] as $key => $val) {
                            if (I($val['name'])) {
                                $type_data[$key]['uid']     = $id;
                                $type_data[$key]['attr_id'] = $val['id'];
                                if (is_array(I($val['name']))) {
                                    $type_data[$key]['value'] = implode(',', I($val['name']));
                                } else {
                                    $type_data[$key]['value'] = I($val['name']);
                                }
                            }
                        }
                        if (count($type_data) > 0) {
                            $index_attr_model = D('UserAttr');
                            $type_data_result = $index_attr_model->addAll($type_data);
                            if (!$type_data_result) {
                                $this->error('添加用户扩展信息出错' . $index_attr_model->getError(), U('edit', array('id' => $id)));
                            }
                        }
                    }
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($user_object->getError());
            }
        } else {
            // 解析字段options
            $user_type_info = D('User/Type')->find($user_type);
            if ($attribute_list[$user_type]) {
                foreach ($attribute_list[$user_type] as $attr) {
                    $attr['options']                             = \lyf\Str::parseAttr($attr['options']);
                    $new_attribute_list[$user_type][$attr['id']] = $attr;
                }
                $new_attribute_list_sort['group']['type']                                        = 'group';
                $new_attribute_list_sort['group']['options'][$user_type_info['name']]['title']   = $user_type_info['title'] . '扩展信息';
                $new_attribute_list_sort['group']['options'][$user_type_info['name']]['options'] = $new_attribute_list[$user_type];
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增用户') //设置页面标题
                ->setPostUrl(U('add')) //设置表单提交地址
                ->addFormItem('reg_type', 'hidden', '注册方式', '注册方式')
                ->addFormItem('user_type', 'hidden', '用户类型', '用户类型', select_list_as_tree('User/Type'))
                ->addFormItem('nickname', 'text', '昵称', '昵称')
                ->addFormItem('username', 'text', '用户名', '用户名')
                ->addFormItem('password', 'password', '密码', '密码')
                ->addFormItem('email', 'text', '邮箱', '邮箱')
                ->addFormItem('email_bind', 'radio', '邮箱绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                ->addFormItem('mobile', 'text', '手机号', '手机号')
                ->addFormItem('mobile_bind', 'radio', '手机绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                ->addFormItem('avatar', 'picture', '头像', '头像')
                ->setExtraItems($new_attribute_list_sort)
                ->setFormData(array('reg_type' => 'admin', 'user_type' => $user_type))
                ->display();
        }
    }

    /**
     * 编辑用户
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        // 获取当前用户
        $user_object = D('User/User');
        $user_info   = $user_object->detail($id);
        $user_type   = $user_info['user_type'];

        // 获取扩展字段
        $map['type_id']             = array('eq', $user_type);
        $attribute_list[$user_type] = D('User/Attribute')->where($map)->order('id asc')->select();

        // 编辑用户
        if (request()->isPost()) {
            // 密码为空表示不修改密码
            if ($_POST['password'] === '') {
                unset($_POST['password']);
            }

            // 提交数据
            $user_object = D('User/User');
            $data        = $user_object->create();
            if ($data) {
                $result = $user_object
                    ->field('id,nickname,username,password,email,email_bind,mobile,mobile_bind,avatar,update_time')
                    ->save($data);
                if ($result) {
                    // 存储用户扩展信息
                    if ($user_type) {
                        $type_data = array();
                        foreach ($attribute_list[$user_type] as $key => $val) {
                            if (I($val['name'])) {
                                $type_data[$key]['uid']     = $id;
                                $type_data[$key]['attr_id'] = $val['id'];
                                if (is_array(I($val['name']))) {
                                    $type_data[$key]['value'] = implode(',', I($val['name']));
                                } else {
                                    $type_data[$key]['value'] = I($val['name']);
                                }
                            }
                        }
                        if (count($type_data) > 0) {
                            $index_attr_model = D('UserAttr');
                            $index_attr_model->where(array('uid' => $id))->delete();
                            $type_data_result = $index_attr_model->addAll($type_data);
                            if (!$type_data_result) {
                                $this->error('修改用户扩展信息出错' . $index_attr_model->getError(), U('edit', array('id' => $id)));
                            }
                        }
                    }
                    $this->success('信息修改成功', U('index'));
                } else {
                    $this->error('更新失败', $user_object->getError());
                }
            } else {
                $this->error($user_object->getError());
            }
        } else {
            // 去掉密码
            unset($user_info['password']);

            // 解析字段options
            $user_type_info = D('User/Type')->find($user_type);
            if ($attribute_list[$user_type]) {
                foreach ($attribute_list[$user_type] as $attr) {
                    $attr['options']                                      = \lyf\Str::parseAttr($attr['options']);
                    $new_attribute_list[$user_type][$attr['id']]          = $attr;
                    $new_attribute_list[$user_type][$attr['id']]['value'] = $user_info[$attr['name']];
                }
                $new_attribute_list_sort['group']['type']                                        = 'group';
                $new_attribute_list_sort['group']['options'][$user_type_info['name']]['title']   = $user_type_info['title'] . '扩展信息';
                $new_attribute_list_sort['group']['options'][$user_type_info['name']]['options'] = $new_attribute_list[$user_type];
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑用户') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('user_type', 'radio', '用户类型', '用户类型', select_list_as_tree('User/Type'))
                ->addFormItem('nickname', 'text', '昵称', '昵称')
                ->addFormItem('username', 'text', '用户名', '用户名')
                ->addFormItem('password', 'password', '密码', '密码')
                ->addFormItem('email', 'text', '邮箱', '邮箱')
                ->addFormItem('email_bind', 'radio', '邮箱绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                ->addFormItem('mobile', 'text', '手机号', '手机号')
                ->addFormItem('mobile_bind', 'radio', '手机绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                ->addFormItem('avatar', 'picture', '头像', '头像')
                ->setExtraItems($new_attribute_list_sort)
                ->setFormData($user_info)
                ->display();
        }
    }

    /**
     * 设置一条或者多条数据的状态
     * @author jry <598821125@qq.com>
     */
    public function setStatus($model = '', $strict = null)
    {
        if ('' == $model) {
            $model = request()->controller();
        }
        $ids = I('request.ids');
        if (is_array($ids)) {
            if (in_array('1', $ids)) {
                $this->error('超级管理员不允许操作');
            }
        } else {
            if ($ids === '1') {
                $this->error('超级管理员不允许操作');
            }
        }
        parent::setStatus($model);
    }
}
