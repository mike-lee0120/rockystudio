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
 * 通用支付接口类
 * @author yunwuxin<448901948@qq.com>
 * @alter jry <59821125@qq.com> <http://www.corethink.cn>
 */
namespace Addons\Pay\ThinkPay;

/**
 * 通用支付接口类
 */
class Pay
{
    /**
     * 支付驱动实例
     * @var Object
     */
    private $payer;

    /**
     * 配置参数
     * @var type
     */
    private $config;

    /**
     * 构造方法，用于构造支付实例
     * @param string $driver 要使用的支付驱动
     * @param array  $config 配置
     */
    public function __construct($driver, $config = array())
    {
        $pos     = strrpos($driver, '\\');
        $pos     = $pos === false ? 0 : $pos + 1;
        $apitype = strtolower(substr($driver, $pos));

        // 兼容支付宝App支付
        if ('alipayapp' === $driver) {
            $driver         = 'alipay';
            $config['type'] = 'app';
        }

        // 设置支付驱动
        $class = strpos($driver, '\\') ? $driver : 'Addons\\Pay\\ThinkPay\\Pay\\Driver\\' . ucfirst(strtolower($driver));
        $this->setDriver($class, $config);
    }

    /**
     * 生成订单号
     * 可根据自身的业务需求更改
     */
    public function createOrderNo()
    {
        $year_code = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j');
        return 'ly' . $year_code[intval(date('Y')) - 2010] . strtolower(dechex(date('m'))) .
        date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%d', rand(0, 99));
    }

    /**
     * 设置支付页面
     * @param array $pay_data 支付参数
     */
    public function buildRequestForm($pay_data)
    {
        $this->payer->check();
        if ($check !== false) {
            return $this->payer->buildRequestForm($pay_data);
        } else {
            E(M("Pay")->getDbError());
        }
    }

    /**
     * 设置支付驱动
     * @param string $class 驱动类名称
     */
    private function setDriver($class, $config)
    {
        $this->payer = new $class($config);
        if (!$this->payer) {
            E("不存在支付驱动：{$class}");
        }
    }

    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array(&$this, $method), $arguments);
        } elseif (!empty($this->payer) && $this->payer instanceof Pay\Pay && method_exists($this->payer, $method)) {
            return call_user_func_array(array(&$this->payer, $method), $arguments);
        }
    }
}
