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
        $data = RedirectService::getUrls($start_date, $end_date, $page, $limit, $actualurl);
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
            $this->ajaxReturn(self::createReturn($data['status'], '', $data['info'], '200', U('Index/index')));
        }
    }

    public function update()
    {
        $data = RedirectService::urlFromId($_GET['id']);
        if (empty($data)) {
            $this->error("该地址已被删除！", U('Index/index'));
        }
        $this->assign(["id" => $_GET['id'], "url" => $data]);
        $this->display("update");
    }

    public function updateUrl()
    {
        if (IS_POST) {
            $data = RedirectService::updateUrl($_POST['url'], $_POST['id']);
            $this->ajaxReturn( self::createReturn($data['status'], '',$data['info'] , 200, U('Index/index')));
        }
    }

    public function deleteUrl()
    {
        $data = RedirectService::deleteUrl($_GET['id']);
        $result = array('delete' => $data);
        $this->ajaxReturn(self::createReturn(true, $result));
    }
}
