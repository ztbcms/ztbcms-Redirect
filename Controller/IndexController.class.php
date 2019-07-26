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
        $actualurl = I('url');
        $sort=I('sort');
        $data = RedirectService::getUrls($start_date, $end_date, $page, $limit, $actualurl,$sort);
        $this->ajaxReturn(self::createReturn(true, $data));

    }

    public function link($redirect = '')
    {
        $data = RedirectService::url($redirect);
        if (empty($data))
            return;
        redirect($data);
    }

    public function add()
    {
        $this->display('add');
    }

    public function addUrl()
    {
        if (IS_POST) {
            $data = RedirectService::addUrl($_POST['url']);
            $this->ajaxReturn($data);
        }
    }

    public function update()
    {
        $msg='';
        $data = RedirectService::urlFromId($_GET['id']);
        if (empty($data)) {
            $msg="原链接已不存在";
        }
        $this->assign(["id" => $_GET['id'], "url" => $data,'msg'=>$msg]);
        $this->display("update");
    }

    public function updateUrl()
    {
        if (IS_POST) {
            $data = RedirectService::updateUrl($_POST['url'], $_POST['id']);
            $this->ajaxReturn($data);
        }
    }

    public function deleteUrl()
    {
        $data = RedirectService::deleteUrl($_POST['id']);
        $this->ajaxReturn($data);
    }
}
