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
namespace User\Controller;

use Home\Controller\HomeController;

/**
 * 用户中心控制器
 * @author jry <598821125@qq.com>
 */
class CenterController extends HomeController
{
    /**
     * 修改昵称
     * @author jry <598821125@qq.com>
     */
    public function nickname()
    {
        $uid = $this->is_login();
        if (request()->isPost()) {
            if (I('post.nickname')) {
                $user_object = D('User/User');
                $result      = $user_object->where(array('id' => $uid))->setField('nickname', I('post.nickname'));
                if ($result) {
                    $this->success('昵称修改成功');
                } else {
                    $this->error('昵称修改失败' . $user_object->getError());
                }
            } else {
                $this->error('请填写昵称');
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('修改昵称') // 设置页面标题
                ->setPostUrl(U('')) // 设置表单提交地址
                ->addFormItem('nickname', 'text', '用户昵称')
                ->setFormData(D('User/User')->detail($uid))
                ->setTemplate(C('USER_CENTER_FORM'))
                ->display();
        }
    }

    /**
     * 修改用户名
     * @author jry <598821125@qq.com>
     */
    public function username()
    {
        $uid = $this->is_login();
        if (request()->isPost()) {
            if (I('post.username')) {
                // 验证用户名格式及唯一性
                $user_object = D('User/User');
                if ($user_object->where(array('username' => I('post.username')))->count() > 0) {
                    $this->error('该用户名已被占用');
                }
                $validate = array(
                    array('username', 'require', '请填写用户名', 1, 'regex', 3),
                    array('username', '3,32', '用户名长度为1-32个字符', 1, 'length', 3),
                    array('username', '', '用户名被占用', 1, 'unique', 3),
                    array('username', '/^(?!_)(?!\d)(?!.*?_$)[\w]+$/', '用户名只可含有数字、字母、下划线且不以下划线开头结尾，不以数字开头！', 1, 'regex', 3),
                );
                $user_object->setProperty("_validate", $validate);
                $data = $user_object->create($_POST); //调用自动验证
                if (!$data) {
                    $this->error($user_object->getError());
                }

                // 修改用户名
                $result      = $user_object->where(array('id' => $uid))->setField('username', I('post.username'));
                if ($result) {
                    $this->success('用户名修改成功');
                } else {
                    $this->error('用户名修改失败' . $user_object->getError());
                }
            } else {
                $this->error('请填写用户名');
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('修改用户名') // 设置页面标题
                ->setPostUrl(U('')) // 设置表单提交地址
                ->addFormItem('username', 'text', '用户名')
                ->setFormData(D('User/User')->detail($uid))
                ->setTemplate(C('USER_CENTER_FORM'))
                ->display();
        }
    }

    /**
     * 修改头像
     * @author jry <598821125@qq.com>
     */
    public function avatar()
    {
        $uid = $this->is_login();
        if (request()->isPost()) {
            if ($_POST) {
                if (!$_POST['avatar']['src'] || !$_POST['avatar']['w'] || !$_POST['avatar']['h'] || $_POST['avatar']['x'] === '' || $_POST['avatar']['y'] === '') {
                    $this->error('参数不完整');
                }
                $result = D('Admin/Upload')->crop($_POST['avatar']);
                if ($result && $result['error'] != 1) {
                    $user_object = D('User/User');
                    $status      = $user_object->where(array('id' => $uid))->setField('avatar', $result['id']);
                    if ($status) {
                        $result['url'] = C('TOP_HOME_DOMAIN') . $result['url'];
                        $this->success('头像修改成功', '', array('data' => $result));
                    } else {
                        $this->error('头像修改失败' . $user_object->getError());
                    }
                } else {
                    $this->error('头像保存失败');
                }
            } else {
                $this->error('请选择文件');
            }
        } else {
            $this->assign('user_info', D('User/User')->detail($uid));
            $this->assign('meta_title', '修改头像');
            $this->display();
        }
    }

    /**
     * 用户修改信息
     * @author jry <598821125@qq.com>
     */
    public function profile()
    {
        $uid = $this->is_login();

        // 获取当前用户
        $user_object = D('User/User');
        $user_info   = $user_object->detail($uid);
        $user_type   = $user_info['user_type'];

        // 获取扩展字段
        $map['type_id']             = array('eq', $user_type);
        $attribute_list[$user_type] = D('User/Attribute')->where($map)->order('id asc')->select();

        // 修改信息
        if (request()->isPost()) {
            // 强制设置用户ID
            $_POST['uid'] = $uid;
            $_POST        = format_data();

            // 保存昵称
            if (I('post.nickname')) {
                $result = $user_object->where(array('id' => $uid))->setField('nickname', I('post.nickname'));
                if ($result === false) {
                    $this->error('昵称修改失败' . $user_object->getError());
                }
            }

            // 存储用户扩展信息
            if ($user_type) {
                $type_data = array();
                $index_attr_model = D('UserAttr');
                foreach ($attribute_list[$user_type] as $key => $val) {
                    if (isset($_POST[$val['name']])) {
                        $type_data[$key]['uid']     = $uid;
                        $type_data[$key]['attr_id'] = $val['id'];
                        if (is_array(I($val['name']))) {
                            $type_data[$key]['value'] = implode(',', I($val['name']));
                        } else {
                            $type_data[$key]['value'] = I($val['name']);
                        }

                        // 删除旧值保存新值
                        if ($type_data[$key]['value']) {
                            $index_attr_model->where(array('uid' => $uid, 'attr_id' => $val['id']))->delete();
                            $type_data_result = $index_attr_model->add($type_data[$key]);
                            if (!$type_data_result) {
                                $this->error('修改用户信息出错' . $index_attr_model->getError());
                            }
                        }
                    }
                }
            }
            $this->success('修改信息成功');
        } else {
            // 解析字段options
            $user_type_info = D('User/Type')->find($user_type);
            if ($attribute_list[$user_type]) {
                // 增加昵称表单
                $nick['name']                      = 'nickname';
                $nick['title']                     = '昵称';
                $nick['type']                      = 'text';
                $nick['value']                     = $user_info['nickname'];
                $new_attribute_list[$user_type][0] = $nick;
                foreach ($attribute_list[$user_type] as $attr) {
                    $attr['options']                                      = \lyf\Str::parseAttr($attr['options']);
                    $new_attribute_list[$user_type][$attr['id']]          = $attr;
                    $new_attribute_list[$user_type][$attr['id']]['value'] = $user_info[$attr['name']];
                }
                $new_attribute_list_sort['group']['type']                                        = 'group';
                $new_attribute_list_sort['group']['options'][$user_type_info['name']]['title']   = $user_type_info['title'] . '信息';
                $new_attribute_list_sort['group']['options'][$user_type_info['name']]['options'] = $new_attribute_list[$user_type];
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('修改信息') // 设置页面标题
                ->setPostUrl(U('')) // 设置表单提交地址
                ->setExtraItems($new_attribute_list_sort)
                ->setTemplate(C('USER_CENTER_FORM'))
                ->display();
        }
    }
}
