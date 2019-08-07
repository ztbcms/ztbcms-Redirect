<?php


namespace Redirect\Controller;


use Redirect\Service\RedirectService;
use Think\Controller;

class RedirectController extends Controller
{
    public function link()
    {
        $redirect = I('redirect');
        $result = RedirectService::getUrl($redirect);
        if (!$result['status'] || empty($result['data'])) {
            echo '找不到链接';
            return;
        }
        redirect($result['data']);
    }
}