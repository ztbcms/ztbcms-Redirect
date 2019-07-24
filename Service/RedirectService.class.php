<?php

// +----------------------------------------------------------------------
// | 地址转换系统
// +----------------------------------------------------------------------

namespace Redirect\Service;

use System\Service\BaseService;

/**
 * 地址转换服务
 */
class RedirectService extends BaseService
{
    /**
     * @param string $url 指定短链接
     * @return mixed|void
     */
    public function url($redirect = '')
    {
        if (empty($redirect)) {
            return;
        }
        $db = D('redirect_redirect');
        $where = array();
        $where['short_id'] = $redirect;
        $data = $db->where($where)->select();
        $db->where(['url'=>$data[0]['url']])->setInc('frequency');
        return $data[0]['url'];
    }

    public function urlFromId($id = '')
    {
        if (empty($id)) {
            return;
        }
        $db = D('Redirect/Redirect');
        $where = array();
        $where['id'] = $id;
        $data = $db->where($where)->select();
        return $data[0]['url'];
    }

    /**
     *
     */
    public function link($url = '', $param = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'mypost=' . $param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * 根据传入的：类别关键字，起始日期，结束日期，指定页码，指定记录数获取数据
     * @param string $start_date 起始日期 ，格式：2018-01-01
     * @param string $end_date 结束日期 ，格式：2018-01-01
     * @param int $page 指定的分页页码
     * @param int $limit 指定显示的记录条数
     * @param string $actualurl 指定实际地址匹配
     * @return array
     */
    public function getUrls($start_date = '', $end_date = '', $page = 1, $limit = 20, $actualurl = '')
    {
        $db = D('Redirect/Redirect');
        //初始化条件数组
        $where = array();
        //
        if (!empty($start_date)) {
            //将输入的起始和结束时间转换成时间戳
            $start_date = strtotime($start_date);
            $where['input_time'] = array('EGT', $start_date);
        }

        if (!empty($end_date)) {
            //这里是下面的计算是因为单单转换"结束日期"为时间戳的话，并不会包括"结束日期"的那一天
            $end_date = strtotime($end_date) + 24 * 60 * 60 - 1;
            if (isset($where['input_time'])) {
                $where['input_time'] = array($where['input_time'], array('ELT', $end_date), 'AND');
            } else {
                $where['input_time'] = array('ELT', $end_date);
            }

        }

        if (!empty($actualurl)) {
            $where['url'] = array('LIKE', '%' . $actualurl . '%');
        }

        //获取总记录数
        $count = $db->where($where)->count();
        //总页数
        $total_page = ceil($count / $limit);
        //获取到的分页数据
        $Urls = $db->where($where)->page($page)->limit($limit)->order(array("id" => "desc"))->select();
        for ($i = 0; $i < count($Urls); $i++) {
            $Urls[$i]['short_id'] = "http://ztbcms.biz/index.php/Redirect/Index/link/" . $Urls[$i]['short_id'];
        }
        $data = [
            'items' => $Urls,
            'page' => $page,
            'limit' => $limit,
            'total_page' => $total_page,
        ];

        return $data;
    }

    /**
     * @param string $url 指定新增地址
     * @return bool|void  返回增加结果
     */
    public function addUrl($url = '')
    {
        $data=['status'=>true,'info'=>''];
//        if (empty($url))
//            return;
        $db = D('Redirect/Redirect');
        $result=$db->where('url="'.$url.'"')->count();
        if($result>0){
            $data['status']=false;
            $data['info']="链接已存在";
            return $data;
        }
        $short_id = md5($url);
        $time = time();
        $num = $db->add(['url' => $url, 'short_id' => $short_id, 'input_time' => $time]);
        if(!$num){
            $data['status']=false;
            $data['info']="添加失败";
        }
        return $data;
    }

    /**
     * @param string $url 更新地址
     * @param string $id 更新id
     * @return bool|void  更新结果
     */
    public function updateUrl($url = '', $id = '')
    {
        if (empty($url) || empty($id))
            return;
        $db = D('Redirect/Redirect');
        $short_id = md5($url);
        $time = time();
        $num = $db->where('id=' . $id)->save(['url' => $url, 'short_id' => $short_id, 'input_time' => $time]);
        if ($num)
            return true;
        return false;
    }

    /**
     * @param string $id 删除数据的id
     * @return bool|void  返回删除结果
     */
    public function deleteUrl($id = '')
    {
        if (empty($id))
            return false;
        $db = D('Redirect/Redirect');
        $num = $db->where('id=' . $id)->delete();
        if ($num)
            return true;
        return false;
    }
}