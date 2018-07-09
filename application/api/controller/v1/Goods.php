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

    public function index()
    {
        $param = $this->request->param();
        $this->code = $param['code'];
        $url = 'https://oauth.taobao.com/token';
        $postfields = array('grant_type' => 'authorization_code',
            'client_id' => '24922818',
            'client_secret' => '3816fbc584ee3e5d571dbe8baf4a65f6',
            'code' => $this->code,
            'redirect_uri' => 'http://127.0.0.1:8686/v1/goods/key');
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
            Cache::set('session_key', $this->session_key , 86400);
        }
    }

    public function key()
    {
        print_r($this->request->param());
        exit;
    }

    public function getGoods($q = '女装', $sort = '_desc', $PageNo = 1, $PageSize = 20)
    {
        $config = [
            'appkey' => '24922818',
            'secretKey' => '3816fbc584ee3e5d571dbe8baf4a65f6',
            'format' => 'json',
            'sandbox' => false,
        ];

        $app = Factory::Tbk($config);
        $param = [
            'site_id' => 45874161,
            'adzone_id' => 743554016,

        ];
        $resp = $app->sc->optionalMaterial($param);
        print_r($resp);
        exit;
        if (isset($resp->results)) {
            $resp = json_encode($resp);
            $resp = json_decode($resp, true);
            $res = $resp['results']['n_tbk_item'];
            return json_encode(['code' => 0, 'msg' => 'ok', 'data' => $res, 'count' => $resp['total_results']], JSON_UNESCAPED_UNICODE);
        }
    }
}