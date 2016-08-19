LoneFirstFramework
======

> Author : Wang Jie <i@i8e.net>

### Directory structure

### Set route rule
 - You can set route rule on `app/routes.php`.
 - You must use function `$this->reg()`.
 - The function `$this->reg(string $format, string $function, string $httpMethod = null)` could be afferent 3 string parameters.
 - The first parameter $format define the route format.Can not exist `/` in front of this string.And if a section you can add a `:` in front of that section and the content of this section in URL will be a parameter of the function in controller.
 - The second parameter must be a string or a callback function $function include the controller name and the function name.Connect these with `@`
 - The third parameter is an optional parameter. As the name suggests this is the http method.

 example:

 ```php
 $this->reg('name/:id', 'name@getNameById', 'get');
 // this will use getNameById(:id); in name controller
 ```

### Use model
 - You can create an model class in  `app/model`.
 - We have had a model farther class you just need to make your model class extend core\model and it include 3 function : create() delete() update(). You can read the source code of core\model.php to learn to use them.Of course you can write a new function.

### Global function
We have 2 global function now : view() config()

#### view()
 - It will export a view in `resources/views/`
 - The first parameter is the name of view
 - The second parameter is optional and it set variables by array

#### config()
 - It will return the element of the `app/configs.php`
 - It just need a string parameter use `:` to connect two levels

### Easy to connect to database
