## 跳转

![图片](https://coding-net-production-pp-ci.codehub.cn/2342aa2c-69a8-47f0-9e15-f7b59dd3ddba.png)

## 使用说明

如何使短链接?

修改文件 `Common/Conf/config.php`
```php
return array(
    'URL_ROUTER_ON'   => true,
    'URL_ROUTE_RULES' => [
        //设置路由
        'redirect/:redirect$' => 'Redirect/Redirect/link'
    ]
);

```
