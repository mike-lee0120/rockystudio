<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Addons\Link\TagLib;

use lyf\template\TagLib;

/**
 * 标签库
 * @author jry <598821125@qq.com>
 */
class LinkAddon extends TagLib
{
    /**
     * 定义标签列表
     * @author jry <598821125@qq.com>
     */
    protected $tags = array(
        'link' => array('attr' => 'name,type,page,limit', 'close' => 1), //友情链接列表
    );

    /**
     * 友情链接
     * @author jry <598821125@qq.com>
     */
    public function _link($tag, $content)
    {
        $name  = $tag['name'];
        $type  = $tag['type'] ?: 1;
        $page  = $tag['page'] ?: 1;
        $limit = $tag['limit'] ?: 30;
        $parse = '<?php ';
        $parse .= '$map = array(); ';
        $parse .= '$map["status"] = 1; ';
        $parse .= '$map["type"] = ' . $type . ';';
        $parse .= '$__LINKADDONLIST__ = D("Addons://Link/Link")->where($map)->page(' . $page . ', ' . $limit . ')->order("sort asc, id asc")->select();';
        $parse .= ' ?>';
        $parse .= '<volist name="__LINKADDONLIST__" id="' . $name . '">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }
}
