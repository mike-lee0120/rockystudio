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
namespace Common\Behavior;

use Think\Behavior;

defined('THINK_PATH') or exit();

/**
 * 初始化允许访问模块信息
 * @author jry <598821125@qq.com>
 */
class InitModuleBehavior extends Behavior
{
    /**
     * 行为扩展的执行入口必须是run
     * @author jry <598821125@qq.com>
     */
    public function run(&$content)
    {
        // 安装模式下直接返回
        if (defined('BIND_MODULE') && BIND_MODULE === 'Install') {
            return;
        }

        // 允许跨域
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Headers: X-Requested-With,Lingyun-Api,Session-Id');
        header("Access-Control-Allow-Credentials: true");

        // 请求类型是Options直接应答
        if (request()->isOptions()) {
            exit('yes');
        }

        // 数据缓存前缀
        $config['DATA_CACHE_PREFIX'] = strtolower(ENV_PRE . MODULE_MARK . '_');

        // 获取数据库存储的配置
        $database_config = D('Admin/Config')->lists();

        // 兼容2.0规范
        $config['APP_DEBUG']       = APP_DEBUG;
        $config['SHOW_PAGE_TRACE'] = $database_config['APP_TRACE'];

        // URL_MODEL必须在app_init阶段就从数据库读取出来应用
        // 不然系统就会读取config.php中的配置导致后台的配置失效
        $config['URL_MODEL'] = $database_config['URL_MODEL'];

        // 允许访问模块列表加上安装的功能模块
        $module_name_list = D('Admin/Module')
            ->where(array('status' => 1, 'is_system' => 0))
            ->getField('name', true);
        $module_allow_list = array_merge(
            C('MODULE_ALLOW_LIST'),
            $module_name_list
        );
        if (MODULE_MARK === 'Admin') {
            $module_allow_list[] = 'Admin';
            $config['URL_MODEL'] = 3;

            // 后台只输入{域名}/admin.php即可进入后台首页
            if (request()->pathinfo() === '/') {
                $_SERVER['PATH_INFO'] = 'Admin/Index/index';
            }
        }
        C('MODULE_ALLOW_LIST', $module_allow_list);

        // 设置默认模块
        if ($database_config['DEFAULT_MODULE'] && MODULE_MARK === 'Home') {
            $config['DEFAULT_MODULE'] = $database_config['DEFAULT_MODULE'];
        }

        // 如果http请求头里包含会话信息则优先
        if (isset($_SERVER['HTTP_SESSION_ID'])) {
            $_POST[C('VAR_SESSION_ID')] = $_SERVER['HTTP_SESSION_ID'];
        }

        // API请求
        if ((strstr($_SERVER['HTTP_USER_AGENT'], "lingyun-api") || $_SERVER['HTTP_LINGYUN_API'])&& MODULE_MARK === 'Home') {
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
            C('IS_API', true);
        }

        // API请求需要返回html_dom
        if (strstr($_SERVER['HTTP_USER_AGENT'], "lingyun-api-html") && MODULE_MARK === 'Home') {
            C('IS_API_HTML', true);
        }

        // 兼容旧版(0.5.9之前)APP
        if (strstr($_SERVER['HTTP_USER_AGENT'], "OpenCMF/api") && MODULE_MARK === 'Home') {
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
            C('IS_API', true);
            C('IS_API_HTML', true);
        }

        // 子域名部署
        if (!C('IS_API') && (request()->hostname() !== 'localhost') && !filter_var(request()->hostname(), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $config['APP_SUB_DOMAIN_DEPLOY'] = $database_config['APP_SUB_DOMAIN_DEPLOY'];
            $config['APP_SUB_DOMAIN_RULES']  = $database_config['APP_SUB_DOMAIN_RULES'];
            if ($database_config['APP_DOMAIN_SUFFIX']) {
                $config['APP_DOMAIN_SUFFIX'] = $database_config['APP_DOMAIN_SUFFIX'];
            }
        }

        // 请求接收为json时认为是ajax请求
        if ($_SERVER['HTTP_ACCEPT'] === 'application/json, text/javascript, */*; q=0.01') {
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        }

        // 系统主页地址配置
        $config['TOP_HOME_DOMAIN'] = request()->domain();
        if (isset($config['APP_SUB_DOMAIN_DEPLOY']) && $config['APP_SUB_DOMAIN_DEPLOY']) {
            $host = explode('.', request()->hostname());
            if (count($host) > 2) {
                $config['TOP_HOME_DOMAIN'] = request()->scheme() . '://www' . strstr(request()->hostname(), '.');

                // 设置cookie和session的作用域
                $config['COOKIE_DOMAIN']             = strstr(request()->hostname(), '.');
                $config['SESSION_OPTIONS']           = C('SESSION_OPTIONS');
                $config['SESSION_OPTIONS']['domain'] = $config['COOKIE_DOMAIN'];
            }
        }

        // 支持后台指定根域名，网站改新域名的同时关闭TP调试模式会导致有旧域名访问刚好作为缓存存储影响页面
        if ($database_config['ROOT_DOMAIN']) {
            $config['TOP_HOME_DOMAIN'] = $database_config['ROOT_DOMAIN'];
        }
        $config['HOME_DOMAIN']   = request()->domain();
        $config['HOME_PAGE']     = $config['HOME_DOMAIN'] . __ROOT__;
        $config['TOP_HOME_PAGE'] = $config['TOP_HOME_DOMAIN'] . __ROOT__;

        // 全局路由
        if ($database_config['URL_ROUTER_ON'] && MODULE_MARK === 'Home') {
            $router_list = S('ROUTER_LIST');
            if (!$router_list) {
                // 加载默认默认路由
                $con           = array();
                $con['status'] = '1';
                $module_router = D('Admin/Module')->select();
                $router_list   = array();
                foreach ($module_router as $k1 => $v1) {
                    $v1['router'] = json_decode($v1['router'], true);
                    foreach ($v1['router'] as $k2 => $v2) {
                        $v2['module']  = $v1['name'];
                        $router_list[] = $v2;
                    }
                }

                // 加载数据库全局路由配置
                $db_router = D('Admin/Router')->where($con)->select();
                if ($db_router) {
                    foreach ($router_list as $k3 => &$v3) {
                        foreach ($db_router as $k4 => $v4) {
                            if ($v3['type'] === $v4['type'] && $v3['module'] === $v4['module'] && $v3['pathinfo'] === $v4['pathinfo'] && $v3['params'] === $v4['params']) {
                                unset($router_list[$k3]);
                                break;
                            }
                        }
                    }

                    // 合并路由规则
                    $router_list = array_merge($router_list, $db_router);
                }

                S('ROUTER_LIST', $router_list, 3600); // 缓存配置
            }

            // 路由规则加载
            if (count($router_list) > 0) {
                $config['URL_MAP_RULES']   = C('URL_MAP_RULES');
                $config['URL_ROUTE_RULES'] = C('URL_ROUTE_RULES');
                foreach ($router_list as $key => $val) {
                    if ($val['params']) {
                        $params = \lyf\Str::parseAttr($val['params']);
                    } else {
                        $params = array();
                    }
                    if ($val['type'] == '1') {
                        $config['URL_ROUTE_RULES'][$val['rule']] = array($val['module'] . $val['pathinfo'], $params);
                    } else {
                        $config['URL_MAP_RULES'][$val['rule']] = array($val['module'] . $val['pathinfo'], $params);
                    }
                }
            }
        }

        C($config);
    }
}
