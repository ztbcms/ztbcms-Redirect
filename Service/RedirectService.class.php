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
     * @param string $redirect 指定访问的短链接
     * @return string
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
        $db->where(['url'=>$data[0]['url']])->setInc('frequency',1,0);
        return $data[0]['url'];
    }

    /**
     * @param string $id 指定id
     * @return string
     */
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
     * 根据传入的：类别关键字，起始日期，结束日期，指定页码，指定记录数获取数据
     * @param string $start_date 起始日期 ，格式：2018-01-01
     * @param string $end_date 结束日期 ，格式：2018-01-01
     * @param int $page 指定的分页页码
     * @param int $limit 指定显示的记录条数
     * @param string $actualurl
     * @param string $sort 排序方式
     * @return array
     */
    public function getUrls($start_date = '', $end_date = '', $page = 1, $limit = 20, $actualurl = '',$sort='+id')
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
        $tmp = $db->where($where)->page($page)->limit($limit);
        if($sort=="-id"){
            $tmp->order(['id'=>'DESC']);
        }
        $Urls=$tmp->select();
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
     * @return array
     */
    public function addUrl($url = '')
    {
        $db = D('Redirect/Redirect');
        $result=$db->where('url="'.$url.'"')->count();
        if($result>0){
            return self::createReturn(false,'','链接已存在');
        }
        $short_id = md5($url);
        $time = time();
        $num = $db->add(['url' => $url, 'short_id' => $short_id, 'input_time' => $time]);
        if(!$num){
            return self::createReturn(false,'','操作失败');
        }
        return self::createReturn(true,'','操作成功');
    }

    /**
     * @param string $url 更新地址
     * @param string $id 更新id
     * @return array
     */
    public function updateUrl($url = '', $id = '')
    {
        $data=['status'=>false,'info'=>'修改失败'];
        if (empty($url) || empty($id))
            return $data;
        $db = D('Redirect/Redirect');
        $result=$db->where('url="'.$url.'"')->count();
        if($result>0){
            return self::createReturn(false,'','链接已存在');
        }
        $short_id = md5($url);
        $time = time();
        $num = $db->where('id=' . $id)->save(['url' => $url, 'short_id' => $short_id, 'input_time' => $time,'frequency'=>0]);
        if ($num){
            return self::createReturn($num,'','操作成功');
        }
        return self::createReturn(false,'','操作失败');
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
            return self::createReturn(true,'','操作成功');
        return self::createReturn(true,'','操作失败');
    }
}