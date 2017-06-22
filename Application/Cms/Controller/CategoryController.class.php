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
namespace Cms\Controller;

use Home\Controller\HomeController;

/**
 * 分类控制器
 * @author jry <598821125@qq.com>
 */
class CategoryController extends HomeController
{
    /**
     * 分类列表
     * @author jry <598821125@qq.com>
     */
    public function index($group = 1)
    {
        // 获取所有分类
        $map['status'] = array('eq', '1'); // 禁用和正常状态
        if (I('get.pid')) {
            $map['pid'] = array('eq', I('get.pid')); // 父分类ID
        }
        $map['group'] = array('eq', $group);
        $data_list    = D('Category')->field('id,pid,group,doc_type,title,url,icon,create_time,sort,status')
            ->where($map)->order('sort asc,id asc')->select();

        // 转换成树状列表
        $tree          = new \lyf\Tree();
        $category_list = $tree->list2tree($data_list);

        $this->success('分类列表', '', array('data' => $category_list));
    }

    /**
     * 分类详情
     * @author jry <598821125@qq.com>
     */
    public function detail($id)
    {
        $map['status'] = array('egt', 1); // 正常、隐藏两种状态是可以访问的
        $info          = D('Category')->where($map)->find($id);
        if (!$info) {
            $this->error('您访问的分类已禁用或不存在');
        }
        if ($info['detail_template']) {
            $template = 'Index/' . $info['detail_template'];
        } else {
            $template = 'Index/detail_cate';
        }
        $info['content'] = parse_content($info['content']);

        $this->assign('info', $info);
        $this->assign('__current_category__', $info['id']);
        $this->assign('meta_title', $info['title']);
        $this->display($template);
    }
}
