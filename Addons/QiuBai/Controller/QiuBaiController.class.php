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
namespace Addons\QiuBai\Controller;

use Home\Controller\AddonController;

class QiuBaiController extends AddonController
{
    //获取糗事百科列表
    public function getList()
    {
        if (!extension_loaded('curl')) {
            $this->error('糗事百科插件需要开启PHP的CURL扩展');
        }
        $lists = S('QiuBai_content');
        if (!$lists) {
            $config  = \Common\Controller\Addon::getConfig('QiuBai');
            $content = \lyf\Http::fsockopenDownload('http://www.qiushibaike.com');
            if ($content) {
                $regex = "/<div class=\"content\".*?>(.*?)<\/div>/ism";
                preg_match_all($regex, $content, $match);
                dump($match);
                $lists = array_map(function ($a) {
                    return array('content' => $a);
                }, $match[0]);
                S('QiuBai_content', $lists, $config['cache_time']);
            }
        }
        if ($lists) {
            $this->success('成功', '', array('data' => $lists));
        } else {
            $this->error('获取糗事百科列表失败');
        }
        $this->assign('qiubai_list', $lists);
    }
}
