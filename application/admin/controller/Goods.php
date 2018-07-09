<?php
/**
 * Created by PhpStorm.
 * User: nicc
 * Date: 2018/7/9
 * Time: 15:13
 * Auth: QQ:1358971278
 */

namespace app\admin\controller;

use app\admin\Controller;

class Goods extends controller
{
    public function index()
    {
        $list = [];
        $page = 1;
        $count = 0;
        $param = $this->request->param();
        if ($param) {
            $Goods = new \app\api\controller\v1\Goods();
            $data = $Goods->getGoods($param['q'], '16,18', '_desc', $page, 20);
            if ($data) {
                $data = json_decode($data, true);
                $count = $data['count'];
                $list = $data['data'];
            }
        }
        $this->view->assign('list', $list);
        $this->view->assign('count', $count);
        $this->view->assign('page', $page);
        return $this->view->fetch();
    }
}