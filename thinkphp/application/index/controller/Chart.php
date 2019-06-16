<?php
namespace app\index\controller;
use app\common\lib\Util;
class Chart
{
    public function index()
    {
        // 判断登录 cookie或token
        if(empty($_POST['game_id'])) {
            return Util::show(config('code.error'), 'error');
        }
        if(empty($_POST['content'])) {
            return Util::show(config('code.error'), 'error');
        }

        $data = [
            'user' => "用户".rand(0, 2000),//此处为测试、获取用户名
            'content' => $_POST['content'],
        ];
        //  todo
        //遍历客户端的fd，比连接到redis效率更高
        foreach($_POST['http_server']->ports[1]->connections as $fd) {
            $_POST['http_server']->push($fd, json_encode($data));
        }

        return Util::show(config('code.success'), 'ok', $data);
    }


}
