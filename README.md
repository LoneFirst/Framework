LoneFirstFramework
======

> Author : Wang Jie <i@i8e.net>

### 安装

```bash
#使用composer安装
composer create-project lonefirst/framework project

#从github安装
git clone https://github.com/cnwangjie/LoneFirstFramework.git project
cd project
composer install
```

### 目录结构
    ├─app
    │  ├─controllers (用来存放控制器文件)
    │  ├─core (框架文件)
    │  └─models (用来存放模型文件)
    ├─cache (用来存放缓存文件)
    ├─public (用来存放外部文件,请将该目录设置为网站的根目录或将请求内容重定向至此)
    └─resources (用来存放各种前端资源)
       └─view(用来存放视图文件)


### 设置路由规则
 - 你可以在 `app/routes.php` 里设置路由规则
 - 使用 `$this->reg()` 函数来注册一条路由规则,下面是该函数的使用方法
     - 第一个参数必须为 `string` 类型且不能包含 `/` ,如果需要传入包含该符号的参数请自行进行处理.你可以使某一段以`:`开头来作为参数,路径中的这部分会自动依序成为指定控制器方法的参数
     - 第二个参数可以为 `string` 类型或者 `callback` 类型,用来指定该路由进行的操作,如果要指定一个控制器的某一函数请用`@`来链接控制器名和函数名,如果是一个回掉函数则会直接执行
     - 第三个参数为 `string` 或 `array` 类型,该参数可选,用来指定允许的HTTP方法

实例:

```php
$this->reg('name/:id', 'name@getNameById', ['get', 'head']);
// 当访问 yourdomain/name/:id 时将会调用 nameController 中的 getNameById(:id) 函数

$this->reg('say/:hi', function($hi) {echo $hi;});
// 当访问 yourdomain/say/hi 时会显示 'hi'
```

### 使用模型
 - 你可以将模型类放在 `app/models/` 目录中
 - 框架具有一个模型父类,只要你继承 `core\model` 就可以使用一些通用的函数.你可以阅读 `core/model.php` 中的代码来了解具体它是如何使用的

### 全局函数
框架具有以下全局函数,可以在任何地方使用

#### view()
 - 可以显示 `resources/views/` 中的视图
 - 第一个必选参数为视图的名称
 - 第二个可选参数为要传入的数据
 - 返回一个视图类的实例
 - 之后如果要传入数据可以使用视图类的 `push()` 函数
 - 使用视图类的 `render()` 函数展示视图

#### config()
 - 可以返回 `app/configs.php` 中的设置
 - 唯一一个参数为所需要的设置,只要在每级之间用`:`来连接就可以了

#### redirect()
 - 重定向
 - 唯一一个参数为要重定向至的URL

#### response()
 - 返回一个响应类的实例

### 非常简单的连接到数据库
框架自带基于PDO的数据库类,可以在任何一个地方使用 `core\database::get()` 都可以返回一个数据库类型的对象
