<?php
/**
 * Created by PhpStorm.
 * User: nicc
 * Date: 2018/7/9
 * Time: 10:32
 * Auth: QQ:1358971278
 */

namespace app\api\controller\v1;


use think\Controller;
use think\Loader;


Loader::import('taobao.top.TopClient', EXTEND_PATH, '.php');
Loader::import('taobao.top.request.TbkItemGetRequest', EXTEND_PATH, '.php');

class Goods extends Controller
{


    public function index($q = '', $cat = '', $sort = '', $startPrice = '', $endPrice = '', $startTkRate = '', $endTkRate = '', $Platform = '', $PageNo = 1, $PageSize = 20)
    {
        $c = new \TopClient();
        $c->appkey = '24922818';
        $c->secretKey = '3816fbc584ee3e5d571dbe8baf4a65f6';
        $req = new \TbkItemGetRequest();
        $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick");
        $req->setQ($q);
        $req->setCat($cat);
        $req->setSort($sort);
        $req->setStartPrice($startPrice);
        $req->setEndPrice($endPrice);
        $req->setStartTkRate($startTkRate);
        $req->setEndTkRate($endTkRate);
        $req->setPlatform($Platform);
        $req->setPageNo($PageNo);
        $req->setPageSize($PageSize);
        $resp = $c->execute($req);
        print_r($resp);
        exit;
    }
}