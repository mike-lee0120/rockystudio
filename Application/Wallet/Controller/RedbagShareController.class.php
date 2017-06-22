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
namespace Wallet\Controller;

use Home\Controller\HomeController;

/**
 * 分享红包控制器
 * @author jry <598821125@qq.com>
 */
class RedbagShareController extends HomeController
{
    /**
     * 分享页面
     * @author jry <598821125@qq.com>
     */
    public function detail()
    {
        $con['status'] = 1;
        $id            = I('id');
        $info          = D('Wallet/RedbagShare')->where($con)->find($id);
        if (!$info || (time() - $info['expire'] > 0)) {
            $this->assign('error', '活动已过期');
        }
        if (session('redbag_share' . $info['id'])) {
            $this->assign('redbag_share', session('redbag_share' . $info['id']));
        }
        if (request()->isPost()) {
            $account = I('post.account');
            if (!preg_match("/^1\d{10}$/", $account)) {
                $this->error('请输入手机号！');
            }

            $redbag_object = D('Redbag');
            $exist         = $redbag_object->where(array('account' => $account, 'share_id' => $info['id']))->find();
            if ($exist) {
                $this->error('您已领取过红包！');
            }

            // 构造红包数据
            $data['account']  = $account;
            $data['title']    = $info['title'];
            $data['share_id'] = $info['id'];

            // 随机红包
            $redbag_share_pool = D('Wallet/RedbagSharePool');
            $redbag_pool_list  = $redbag_share_pool->where('status=1')->getField('id', true);
            $random_key        = array_rand($redbag_pool_list, 1);
            $redbag_rand       = $redbag_share_pool->find($redbag_pool_list[$random_key]);
            if ($redbag_rand) {
                //红包数据
                $data['money']  = $redbag_rand['money'];
                $data['expire'] = time() + ($redbag_rand['expire'] * 86400);
                $data['limit']  = $redbag_rand['limit'];
            }

            // 给用户发放红包
            $add_data = $redbag_object->create($data);
            if ($add_data) {
                $result = $redbag_object->add($add_data);
                if ($result) {
                    session('redbag_share' . $info['id'], $redbag_rand);
                    $this->success('领取成功');
                } else {
                    $this->error('错误：' . $redbag_object->getError());
                }
            } else {
                $this->error('错误：' . $redbag_object->getError());
            }
        } else {
            if ($info) {
                $this->assign('info', $info);
                $this->assign('meta_title', C('WEB_SITE_TITLE') . '派发礼包，速来领取');
                $this->display();
            }
        }
    }
}
