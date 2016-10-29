<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
        <meta name="description" content="">
        <meta name="author" content="">

        <title>{{ $title }}</title>
        <style>
        </style>
    </head>

    <body>
        <center>
            <h1 style="color:white;background-color:{{ isset($error) ? 'red' : 'green' }}">{{ $error or 'SUCCESS' }}</h1>
            <h1>Thanks for using LoneFirstFramework!</h1>
        </center>
    </body>
</html>
