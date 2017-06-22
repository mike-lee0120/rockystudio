<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Manual\TagLib;

use lyf\template\TagLib;

/**
 * 标签库
 * @author jry <598821125@qq.com>
 */
class Manual extends TagLib
{
    /**
     * 定义标签列表
     * @author jry <598821125@qq.com>
     */
    protected $tags = array(
        'ad' => array('attr' => 'name,width,height', 'close' => 0), //广告调用
    );

    /**
     * 广告调用
     */
    public function _ad($tag, $content)
    {
        $name   = $tag['name'];
        $width  = $tag['width'] ?: '100%';
        $height = $tag['height'] ?: '100%';
        $parse  = '<?php ';
        $parse .= '$map["status"] = array("eq", "1");';
        $parse .= '$map["name"] = array("eq", "' . $name . '");';
        $parse .= '$__AD__ = D("Manual/Ad")->where($map)->find();';
        $parse .= 'if($__AD__):?>';
        $parse .= '<?php switch ($__AD__["type"]):?>';
        $parse .= '<?php case "1":?>';
        $parse .= '<a target="_blank" style="width:' . $width . ';height:' . $height . ';" href="$__AD__[\"url\"]">$__AD__["value"]</a>';
        $parse .= '<?php break;?>';
        $parse .= '<?php case "2":?>';
        $parse .= '<a target="_blank" href="{$__AD__[\"url\"]}"><img style="width:' . $width . ';height:' . $height . ';" src="{$__AD__.value|get_cover}"></a>';
        $parse .= '<?php break;?>';
        $parse .= '<?php case "3":?>';
        $parse .= '<div class="ad-code" style="width:' . $width . ';height:' . $height . ';">{$__AD__.value}</div>';
        $parse .= '<?php break;?>';
        $parse .= '<?php endswitch;?>';
        $parse .= "<?php endif;?>";
        return $parse;
    }
}
