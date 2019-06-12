<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 18/2/28
 * Time: 上午1:39
 */
use Swoole\Coroutine as co;
$http = new swoole_http_server("0.0.0.0", 8811);


//添加测试一：获取参数并打印出来
//$http->on('request', function ($request, $response) {
//    $response->cookie("vipElephant",'xsssss', time() + 1800);
//    $response->end('len'.json_encode($request->get));
//});
/**
 * https://wiki.swoole.com/wiki/page/783.html
 * 配置静态文件根目录，与enable_static_handler配合使用。
 * 设置document_root并设置enable_static_handler为true后，
 * 底层收到Http请求会先判断document_root路径下是否存在此文件，
 * 如果存在会直接发送文件内容给客户端，不再触发onRequest回调。
 */

$http->set(
    [
        'enable_static_handler' => true,
        'document_root' => "/home/work/swoole_renew/data",
    ]
);
$http->on('request', function($request, $response) {
    //print_r($request->get);
    $content = [
        'date:' => date("Ymd H:i:s"),
        'get:' => $request->get,
        'post:' => $request->post,
        'header:' => $request->header,
    ];
//    异步回调方式写文件：swoole低版本用法
    swoole_async_writefile(__DIR__."/access.log", json_encode($content).PHP_EOL, function($filename){
        // todo
    }, FILE_APPEND);

//    swoole高版本用法异步回调  swoole>4.0.0  协程方式
//    $filename = __DIR__ . "/access.log";
//    co::create(function () use ($filename,$content)
//    {
//        $r =  co::writeFile($filename,json_encode($content).PHP_EOL,FILE_APPEND);
////        var_dump($r);
//    });

    $response->cookie("singwa", "xsssss", time() + 1800);
    $response->end("elephant". json_encode($request->get));
});

$http->start();