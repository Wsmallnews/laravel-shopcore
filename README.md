<h1 align="center"> smallnews/laravel-shopcore </h1>

<p align="center"> shop core composer.</p>

## Explain

Shop core code package is being developed. Please look forward to it

## Installing

```shell
$ composer require /smallnews/laravel-shopcore -vvv
```

## register routes
```
在 AppServiceProvider 的 boot 方法加入 Shopcore::routes(); 注册 shopcore 路由

Shopcore::routes();
```


## publish


```
数据迁移
php artisan migrate

发布配置文件
php artisan vendor:publish --tag=shopcore-config

发布视图
php artisan vendor:publish --tag=shopcore-views

发布组件
php artisan vendor:publish --tag=shopcore-components


所有操作都触发了 Wsmallnews\Shopcore\Events\OperateLogEvent,您可以监听该事件完成操作日志的记录，可以用下面方法将监听器示例发布到应用中作为完成日志记录的参考
发布事件监听
php artisan vendor:publish --tag=shopcore-operloglistener
```

## Usage

TODO

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com//Wsmallnews/laravel-shopcore/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com//Wsmallnews/laravel-shopcore/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
