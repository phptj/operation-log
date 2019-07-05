# operation-log
Laravel 5 操作日志自动记录


## 安装

可以通过 [Composer](http://getcomposer.org) 安装
`tangjun/sys-audit-log`， 在`composer.json`require部分引入，然后执行 ```composer install```或```composer update```（注意 ：composer update会更新你其他没有固定本部的组件）.

```json
{
    "require": {
       
        "tangjun/operation-log": "~1.0"
        
    }
   
}
```

或者

项目根目录执行:
```
composer require tangjun/operation-log
```


## 使用

要使用sys-audit-log服务提供程序，在引导Laravel应用程序时必须注册该提供程序。有
基本上有两种方法。

Find the `providers` key in `config/app.php` and register the ActionLog Service Provider.

Laravel 5.1+
```php
    'providers' => [
        // ...
        TangJun\SysAuditLog\SysAuditLogServiceProvider::class,
    ]
```

Find the `aliases` key in `config/app.php`.

Laravel 5.1+
```php
    'aliases' => [
        // ...
        'SysAuditLog' => TangJun\SysAuditLog\Facades\SysAuditLogFacade::class,
    ]
```



## 配置

移动配置文件到根目录config下面.

```$ php artisan vendor:publish```

`config/sysauditlog.php`

```php
	//填写要记录的日志的模型名称
	return [
	    'entities'=>
            [
		        '\App\Models\Users',
	        ]
    ];

```
## 创建记录表
run:
```$ php artisan migrate```

## Demo
自动记录操作日志，数据库操作需按如下:
```php

update

$users = Users::find(1);
$users->name = "myname";
$users->save();

add

$users = new Users();
$users->name = "myname";
$users->save()

delete

Users:destroy(1);

```

主动记录操作日志

```php

use SysAuditLog

SysAuditLog::createActionLog(Array $data,$action);

```




