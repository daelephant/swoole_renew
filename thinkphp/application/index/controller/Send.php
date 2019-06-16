<?php
namespace app\index\controller;
//use app\common\lib\ali\Sms;
use app\common\lib\qcloud\Sms;
use app\common\lib\Util;
use app\common\lib\Redis;
class Send
{
    /**
     * 发送验证码
     */
    public function index() {
        $Sms = new Sms();
        // tp  input
        //$phoneNum = request()->get('phone_num', 0, 'intval');
        $phoneNum = intval($_GET['phone_num']);
        if(empty($phoneNum)) {
            // status 0 1  message data
            return Util::show(config('code.error'), 'error');
        }

        //tood
        // 生成一个随机数
        $code = rand(1000, 9999);
        $expire = 10;//有效时长10分钟

//        $taskData = [
//            'method' => 'sendSms',
//            'data' => [
//                'phone' => $phoneNum,
//                'code' => $code,
//                'expire' => $expire
//            ]
//        ];
//        $_POST['http_server']->task($taskData);
//        return Util::show(config('code.success'), 'ok');
        try {
            $response = $Sms->sendSms($phoneNum, $code ,$expire);
//            echo $response;

        }catch (\Exception $e) {
            // todo
            return Util::show(config('code.error'), '短信第三方内部异常');
        }
        if(empty($response)){
            echo 'empty';exit;
        }else{
            var_dump($response);exit;
        }
//        var_dump($response);
//        if($response['errmsg'] === "OK") {
        if($response) {

//            $keys = key($responseo);
//            swoole_async_writefile(__DIR__."/runTime.log",$keys, function($filename){
//                // todo
//                echo "success".PHP_EOL;
//            }, FILE_APPEND);
            // redis
            $redis = new \Swoole\Coroutine\Redis();
            $redis->connect(config('redis.host'), config('redis.port'));
            $redis->set(Redis::smsKey($phoneNum), $code, config('redis.out_time'));

            // 异步redis

            return Util::show(config('code.success'), 'success');
        } else {
            return Util::show(config('code.error'), '验证码发送失败');
        }

    }
}
