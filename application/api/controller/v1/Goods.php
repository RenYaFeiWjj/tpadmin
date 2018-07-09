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

class Goods extends Controller
{
    public function index()
    {
        $c = new \TopClient();
        $c->appkey = 24922818;
        $c->secretKey = '3816fbc584ee3e5d571dbe8baf4a65f6';
        $req = new \TbkItemGetRequest();
        $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick");
        $req->setQ("女装");
        $req->setCat("16,18");
        $req->setItemloc("杭州");
        $req->setSort("tk_rate_des");
        $req->setIsTmall("false");
        $req->setIsOverseas("false");
        $req->setStartPrice("10");
        $req->setEndPrice("10");
        $req->setStartTkRate("123");
        $req->setEndTkRate("123");
        $req->setPlatform("1");
        $req->setPageNo("123");
        $req->setPageSize("20");
        $resp = $c->execute($req);
        print_r($resp);exit;
    }
}