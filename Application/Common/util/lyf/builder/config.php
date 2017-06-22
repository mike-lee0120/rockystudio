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

/**
 * Builder配置文件
 */
return array(
    //表单类型
    'form_item_type' => array(
        'hidden'        => array('隐藏', 'varchar(31) NOT NULL'),
        'static'        => array('不可修改文本', 'varchar(128) NOT NULL'),
        'num'           => array('数字', 'int(11) UNSIGNED NOT NULL'),
        'uid'           => array('UID', 'int(11) UNSIGNED NOT NULL'),
        'uids'          => array('UIDS', 'varchar(127) NOT NULL'),
        'price'         => array('价格', 'int(11) UNSIGNED NOT NULL'),
        'text'          => array('单行文本', 'varchar(127) NOT NULL'),
        'textarea'      => array('多行文本', 'varchar(255) NOT NULL'),
        'array'         => array('数组', 'varchar(31) NOT NULL'),
        'password'      => array('密码', 'varchar(63) NOT NULL'),
        'toggle'        => array('开关', 'varchar(31) NOT NULL'),
        'radio'         => array('单选按钮', 'varchar(31) NOT NULL'),
        'checkbox'      => array('复选框', 'varchar(31) NOT NULL'),
        'select'        => array('下拉框', 'varchar(31) NOT NULL'),
        'selects'       => array('下拉框(多选)', 'varchar(31) NOT NULL'),
        'icon'          => array('字体图标', 'varchar(63) NOT NULL'),
        'date'          => array('日期', 'int(11) UNSIGNED NOT NULL'),
        'datetime'      => array('时间', 'int(11) UNSIGNED NOT NULL'),
        'clock'         => array('时刻', 'varchar(5) NOT NULL'),
        'dateranger'    => array('时间区间', 'varchar(127) NOT NULL'),
        'picture'       => array('单张图片', 'int(11) UNSIGNED NOT NULL'),
        'picture_temp'  => array('单张图片(传统模式)', 'text'),
        'pictures'      => array('多张图片', 'varchar(32) NOT NULL'),
        'pictures_temp' => array('多张图片(传统模式)', 'text'),
        'file'          => array('单个文件', 'varchar(32) NOT NULL'),
        'files'         => array('多个文件', 'varchar(32) NOT NULL'),
        'media'         => array('单个媒体', 'varchar(32) NOT NULL'),
        'medias'        => array('多个媒体', 'varchar(32) NOT NULL'),
        'summernote'    => array('HTML编辑器 summernote', 'text'),
        'kindeditor'    => array('HTML编辑器 kindeditor', 'text'),
        'editormd'      => array('Markdown编辑器 editormd', 'text'),
        'linkage'       => array('三级联动', 'varchar(255) NOT NULL'),
        'bdmap'         => array('百度地图', 'varchar(63) NOT NULL'),
        'gmap'          => array('谷歌地图', 'varchar(63) NOT NULL'),
        'mapbox'        => array('Mapbox', 'varchar(63) NOT NULL'),
        'tags'          => array('标签', 'varchar(127) NOT NULL'),
        'spec'          => array('商品规格', 'varchar(255) NOT NULL'),
        'table'         => array('表格', 'text'),
        'board'         => array('拖动排序', 'varchar(255) NOT NULL'),
    ),
);
