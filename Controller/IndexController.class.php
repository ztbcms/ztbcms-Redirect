<?php

namespace Redirect\Controller;

use Common\Controller\AdminBase;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
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
//        //print_r($data);
//        //echo $data[0]['actualurl'];
        if(empty($data))
            return ;
        redirect($data);
        $tmp=substr($data[0]['actualurl'],0,strrpos($data[0]['actualurl'],'?'));
        $str=substr($data[0]['actualurl'],strripos($data[0]['actualurl'],'?')+1);
        $html=RedirectService::link($tmp,$str);
        echo $html;
    }
    public function add(){
        $this->display('add');
    }
    public function addUrl(){
        if(IS_POST){

        }
        $status=RedirectService::addUrl();
    }
}
