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
    /**
     * @return array
     * User: 任亚飞
     */
    public function index()
    {
        $this->search();
//        $this->getEpubDown($url);
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