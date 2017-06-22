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
namespace Admin\Controller;

/**
 * 主题控制器
 * @author jry <598821125@qq.com>
 */
class ThemeController extends AdminController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 获取主题列表
        $theme_object = D('Theme');
        $p            = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list    = $theme_object->getAll();

        // 获取当前主题
        $current_theme = $theme_object->where(array('current' => 1))->order('id asc')->getField('name');
        $this->assign('current_theme', $current_theme);

        $attr['name']  = 'cencel';
        $attr['title'] = '使用内置主题';
        $attr['class'] = 'btn btn-primary-outline btn-pill ajax-get';
        $attr['href']  = U('Admin/Theme/cancel');

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('主题列表') // 设置页面标题
            ->addTopButton('self', $attr)
            ->addTableColumn('name', '名称')
            ->addTableColumn('title', '标题')
            ->addTableColumn('description', '描述')
            ->addTableColumn('developer', '开发者')
            ->addTableColumn('version', '版本')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('status_icon', '状态')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->display();
    }

    /**
     * 安装主题
     * @author jry <598821125@qq.com>
     */
    public function install($name)
    {
        // 演示模式
        if (APP_DEMO === true) {
            $this->error('演示模式不允许该操作！');
        }

        // 获取当前主题信息
        $config_file = realpath('./Theme/' . $name) . '/'
        . D('Theme')->install_file();
        if (!$config_file) {
            $this->error('安装失败');
        }
        $config = include $config_file;
        $data   = $config['info'];
        if ($config['config']) {
            $data['config'] = json_encode($config['config']);
        }

        // 写入数据库记录
        $theme_object = D('Theme');
        $data         = $theme_object->create($data);
        if ($data) {
            $id = $theme_object->add($data);
            if ($id) {
                $this->success('安装成功', U('index'));
            } else {
                $this->error('安装失败：' . $theme_object->getError());
            }
        } else {
            $this->error($theme_object->getError());
        }
    }

    /**
     * 卸载主题
     * @author jry <598821125@qq.com>
     */
    public function uninstall($id)
    {
        // 演示模式
        if (APP_DEMO === true) {
            $this->error('演示模式不允许该操作！');
        }

        // 当前主题禁止卸载
        $theme_object = D('Theme');
        $result       = $theme_object->delete($id);
        if ($result) {
            $this->success('卸载成功！');
        } else {
            $this->error('卸载失败', $theme_object->getError());
        }
    }

    /**
     * 更新主题信息
     * @author jry <598821125@qq.com>
     */
    public function updateInfo($id)
    {
        $theme_object = D('Theme');
        $name         = $theme_object->getFieldById($id, 'name');
        $config_file  = realpath('./Theme/' . $name) . '/'
        . D('Theme')->install_file();
        if (!$config_file) {
            $this->error('不存在安装文件');
        }
        $config = include $config_file;
        $data   = $config['info'];
        if ($config['config']) {
            $data['config'] = json_encode($config['config']);
        }
        $data['id'] = $id;
        $data       = $theme_object->create($data);
        if ($data) {
            $id = $theme_object->save($data);
            if ($id) {
                $this->success('更新成功', U('index'));
            } else {
                $this->error('更新失败');
            }
        } else {
            $this->error($theme_object->getError());
        }
    }

    /**
     * 切换主题
     * @author jry <598821125@qq.com>
     */
    public function setCurrent($id)
    {
        $theme_object = D('Theme');
        $theme_info   = $theme_object->find($id);
        if ($theme_info) {
            // 当前主题current字段置为1
            $map['id'] = array('eq', $id);
            $result1   = $theme_object->where($map)->setField('current', 1);
            if ($result1) {
                // 其它主题current字段置为0
                $map       = array();
                $map['id'] = array('neq', $id);
                if ($theme_object->where($map)->count() == 0) {
                    $this->success('前台主题设置成功！');
                }
                $con['id'] = array('neq', $id);
                $result2   = $theme_object->where($con)->setField('current', 0);
                if ($result2) {
                    $this->success('前台主题设置成功！');
                } else {
                    $this->error('设置当前主题失败', $theme_object->getError());
                }
            } else {
                $this->error('设置当前主题失败', $theme_object->getError());
            }
        } else {
            $this->error('主题不存在');
        }
    }

    /**
     * 取消主题
     * @author jry <598821125@qq.com>
     */
    public function cancel()
    {
        $theme_object = D('Theme');
        $theme_object->where(true)->setField('current', 0);
        $map            = array();
        $map['current'] = array('eq', 1);
        if ($theme_object->where($map)->count() == 0) {
            $this->success('取消主题成功！');
        } else {
            $this->error('取消主题失败', $theme_object->getError());
        }
    }
}
