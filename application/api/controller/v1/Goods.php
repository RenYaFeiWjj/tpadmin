<?php
/**
 * Created by PhpStorm.
 * User: nicc
 * Date: 2018/7/9
 * Time: 10:32
 * Auth: QQ:1358971278
 */

namespace app\api\controller\v1;

use ETaobao\Factory;
use think\Controller;
use think\Cache;


class Goods extends Controller
{
    public $code;
    public $session_key;
    public $config = [
        'appkey' => '24922818',
        'secretKey' => '3816fbc584ee3e5d571dbe8baf4a65f6',
        'format' => 'json',
        'sandbox' => false,
    ];

    public function index()
    {
        $param = $this->request->param();
        $this->code = $param['code'];
        $url = 'https://oauth.taobao.com/token';
        $postfields = array('grant_type' => 'authorization_code',
            'client_id' => '24922818',
            'client_secret' => '3816fbc584ee3e5d571dbe8baf4a65f6',
            'code' => $this->code,
            'redirect_uri' => '127.0.0.1:8686/v1/goods/key');

        $post_data = '';
        foreach ($postfields as $key => $value) {
            $post_data .= "$key=" . urlencode($value) . "&";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, substr($post_data, 0, -1));
        $output = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpStatusCode == 200) {
            $output = json_decode($output, true);
            $this->session_key = $output['access_token'];
            Cache::set('session_key', $output['access_token'], 86400);
            print_r(Cache::get('session_key'));
            exit;

        }
    }

    public function key()
    {
        print_r($this->request->param());
        exit;
    }

    public function getGoods($q = '', $cat = '', $sort = '_desc', $start_price = 1, $end_price = '100000000', $page_no = 1)
    {

        $this->session_key = Cache::get('session_key');
        if (!$this->session_key) {
            echo '没有session_key';
        }
        $app = Factory::Tbk($this->config);
        $param = [
            'q' => $q,
            'cat' => $cat,
            'site_id' => 45874161,
            'adzone_id' => 743554016,
            'session' => $this->session_key,
            'has_coupon' => "true", //有优惠券
            'need_free_shipment' => 'true',//包邮
            'platform' => 2,
            'end_price' => $end_price,
            'start_price' => $start_price,
//            'end_tk_rate' => '5678',
//            'start_tk_rate' => '1678',
            'page_no' => $page_no,
            'sort' => $sort,
        ];
        $resp = $app->sc->optionalMaterial($param);
        if (isset($resp->result_list)) {
            $resp = json_encode($resp);
            $resp = json_decode($resp, true);
            $res = $resp['result_list']['map_data'];
            foreach ($res as &$v) {
                $preg = '/减(.*?)元/i';//匹配img标签的正则表达式
                preg_match_all($preg, $v['coupon_info'], $preg_data);//这里匹配所有的img
                $v['coupon_info'] = $preg_data[1][0];
                $v['quan_hou'] = $v['zk_final_price'] - $preg_data[1][0];
            }
            $data = json_encode(['code' => 0, 'msg' => 'ok', 'data' => $res, 'count' => $resp['total_results']]);
            return $data;
        }
    }


    public function cid()
    {
        $param = $this->request->param();
        $data = $param['itemcats_get_response']['item_cats']['item_cat'];
        $is_parent = [];
        foreach ($data as $v) {
            $cat = db('cat')->where(['cid' => $v['cid']])->find();
            if (!$cat) {
                $param = [
                    'cid' => $v['cid'],
                    'name' => $v['name'],
                    'pid' => $v['parent_cid'],
                    'create_time' => time(),
                    'update_time' => time(),
                ];
                db('cat')->insert($param);
            }
            if ($v['is_parent']) {
                $is_parent[] = $v['cid'];
            }
        }
        print_r($is_parent);
        exit;
    }


    public function getBanner()
    {
        $banner = db('banner')->where(['isdelete' => 0, 'status' => 1])->order('sort desc')->select();
        if ($banner) {
            return json_encode(['code' => 0, 'msg' => 'ok', 'data' => $banner]);
        }else{
            return json_encode(['code' => 1, 'msg' => 'null']);
        }
    }
}
