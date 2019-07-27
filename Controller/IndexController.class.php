<?php

namespace Redirect\Controller;

use Common\Controller\AdminBase;
use Redirect\Service\RedirectService;

class IndexController extends AdminBase
{
    public function index()
    {
        $this->display();
    }

    public function getUrls()
    {
        //设置时间范围，从 $start_date 到 $end_date
        $start_date = I('start_date');
        $end_date = I('end_date');
        //指定获取分页结果的第几页
        $page = I('page', 1);
        $limit = I('limit', 20);
        //按内容搜索时的日志内容关键字
        $actual_url = I('url');
        $sort = I('sort');
        $data = RedirectService::getUrls($start_date, $end_date, $page, $limit, $actual_url, $sort);
        $this->ajaxReturn($data);

    }


    public function add()
    {
        $this->display('add');
    }

    public function addUrl()
    {
        if (IS_POST) {
            $url = I('url');
            $data = RedirectService::addUrl($url);
            $this->ajaxReturn($data);
        }
    }

    public function update()
    {
        $id = I('id');
        $data = RedirectService::getUrlById($id);
        if ($data['status']) {
            $this->assign(["id" => $id, "url" => $data['data'][0]['url']]);
            $this->display("update");
        }
    }

    public function updateUrl()
    {
        if (IS_POST) {
            $url = I('url');
            $id = I('id');
            $data = RedirectService::updateUrl($url, $id);
            $this->ajaxReturn($data);
        }
    }

    public function deleteUrl()
    {
        $id = I('post.id');
        $data = RedirectService::deleteUrl($id);
        $this->ajaxReturn($data);
    }
}
