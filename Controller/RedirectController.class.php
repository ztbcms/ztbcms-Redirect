<?php


namespace Redirect\Controller;


use Redirect\Service\RedirectService;
use Think\Controller;

class RedirectController extends Controller
{
    public function link()
    {
        $redirect = I('redirect');
        $data = RedirectService::getUrl($redirect);
        if (!$data['status']) {
            echo '找不到链接';
            return;
        }
        redirect($data['data']);
    }
}