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
namespace Addons\Ipdata\Model;

/**
 * IP地址归属地查询
 * @author jry <598821125@qq.com>
 */
class IpdataModel
{
    private $error = '';

    public function getIpInfo($ip)
    {
        $addon_config = \Common\Controller\Addon::getConfig('Ipdata');
        if ($addon_config['status']) {
            $host    = "https://dm-81.data.aliyun.com";
            $path    = "/rest/160601/ip/getIpInfo.json";
            $method  = "GET";
            $appcode = $addon_config['appcode'];
            $headers = array();
            array_push($headers, "Authorization:APPCODE " . $appcode);
            $querys = "ip=" . $ip;
            $bodys  = "";
            $url    = $host . $path . "?" . $querys;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_FAILONERROR, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($curl, CURLOPT_HEADER, true);
            if (1 == strpos("$" . $host, "https://")) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            }
            $result = curl_exec($curl);
            curl_close($curl);
            return $result;
        } else {
            $this->error = 'IP地址归属地查询插件未开启！';
            return false;
        }
    }

    // 返回错误
    public function getError()
    {
        return $this->error;
    }
}
