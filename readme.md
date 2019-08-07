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
