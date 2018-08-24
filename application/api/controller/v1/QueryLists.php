<?php
/**
 * Created by PhpStorm.
 * User: nicc
 * Date: 2018/8/24
 * Time: 11:43
 * Auth: QQ:1358971278
 */

namespace app\api\controller\v1;

use QL\QueryList;

class QueryLists extends \think\Controller
{
    public $in_num = 0;
    public $data = [];

    public function index()
    {
//        $this->search();
        $this->forTypeList();
//        $this->getEpubDown($url);
    }

    /**
     * @param $url
     * @param $type
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * User: 任亚飞
     * 或者各类型下内容
     */
    public function type($url, $type)
    {
        echo 'type:' . $type . PHP_EOL;
        $data = QueryList::get($url)->rules([
            'title' => array('.list_img a', 'title'),
            'b_name' => array('.b_name a', 'text'),
            'auth' => array('.b_info .l1 a', 'text'),
            'num' => array('.b_info .l2', 'text'),
            'x_status' => array('.b_info .l3', 'text'),
            'intro' => array('.b_intro', 'text'),
            'new_info' => array('.b_info .l5 a', 'text'),
            'new_date' => array('.b_info .l5 i', 'text'),
            'img_url' => array('.list_img a img', 'src'),
            'link' => array('.list_img a', 'href'),
            'pagei' => array('.pagei a:last-child', 'href')
        ])->query()->getData();
        $arr = [];
        if ($data) {
            foreach ($data as $v) {
                $v['link'] = 'https://www.ixdzs.com' . $v['link'];
                $arr[] = $v;
//                $list = db('list')->where(['title' => $v['title'], 'link' => $v['link']])->find();
                $insert_data = [
                    'title' => $v['title'],
                    'b_name' => $v['b_name'],
                    'auth' => $v['auth'],
                    'num' => $v['num'],
                    'x_status' => $v['x_status'],
                    'intro' => $v['intro'],
                    'new_info' => $v['new_info'],
                    'new_date' => $v['new_date'],
                    'img_url' => $v['img_url'],
                    'link' => $v['link'],
                    'type' => $type,
                ];
//                if (!$list) {
//                    echo 'insert' . PHP_EOL;
                    db('list')->insert($insert_data);
//                } else {
//                    echo 'update' . PHP_EOL;
//                    db('list')->where(['id' => $list['id']])->update($insert_data);
//                }
                $this->in_num++;
            }
            if ($arr[0]['pagei']) {
                if (preg_match_all('/index_0_0_0_(\d+)./', $arr[0]['pagei'], $matches)) {
                    $pagei = $matches[1][0];
                }
            }
        }
        echo $this->in_num . '完成'  . PHP_EOL;
        return $pagei;

    }

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * User: 任亚飞
     * 从10个分类中获取所有页数的数据
     */
    public function forTypeList()
    {
        $type_list = 10;

        for ($i = 1; $i < $type_list; $i++) {
            $page = $this->type('https://www.ixdzs.com/sort/' . $i . '/index_0_0_0_1.html', $i);
            if ($page > 1) {
                for ($j = 1; $j < $page; $j++) {
                    echo $j;
                    $this->type('https://www.ixdzs.com/sort/' . $i . '/index_0_0_0_' . $j . '.html', $i);
                }
            }
        }

    }

    /**
     * @return array
     * User: 任亚飞
     * 返回搜索内容和跳转链接
     */
    public function search()
    {
        $get = $this->request->get();
        $url = 'https://www.ixdzs.com/search?q=' . urlencode($get['url']) . (isset($get['page']) ? '&page=' . $get['page'] : '');
        $data = QueryList::get($url)->rules([
            'title' => array('.list_img a', 'title'),
            'img_url' => array('.list_img a img', 'src'),
            'link' => array('.list_img a', 'href')
        ])->query()->getData();

        $arr = [];
        foreach ($data as $v) {
            $v['link'] = 'https://www.ixdzs.com' . $v['link'];
            $arr[] = $v;
        }
        return $arr;
    }


    /**
     * @return array
     * User: 任亚飞
     * 获取详情,下载链接
     * https://www.ixdzs.com/d/197/197511/
     */
    public function getEpubDown($url)
    {
        $data = QueryList::get($url)->rules([
            'title' => array('#epub_down a:first-child', 'title'),
            'img_url' => array('.d_af img', 'src'),
            'link' => array('#epub_down a:first-child', 'href')
        ])->encoding('UTF-8')->query()->getData();
        $arr = [];
        foreach ($data as $v) {
            if ($v['title'] != '报告epub错误') {
                $v['link'] = 'https://www.ixdzs.com' . $v['link'];
                $arr[] = $v;
            }
        }
        return $arr;
    }
}