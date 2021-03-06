<?php
/**
 * 代表的是  swoole里面 后续 所有  task异步 任务 都放这里来
 * Date: 18/3/27
 * Time: 上午1:20
 */
namespace app\common\lib\task;
//use app\common\lib\ali\Sms;
use app\common\lib\qcloud\Sms;
use app\common\lib\redis\Predis;
use app\common\lib\Redis;
class Task {

    /**
     * 异步发送 验证码
     * @param $data
     * @param $serv swoole server对象 服务对象
     */
    public function sendSms($data) {
        try {
            $Sms = new Sms();
            $response = $Sms->sendSms($data['phone'], $data['code'], $data['expire']);
        }catch (\Exception $e) {
            // todo
            return false;
        }

        // 如果发送成功 把验证码记录到redis里面
//        if($response->errmsg === "OK") {
        $responseArray = json_decode($response,true);
        if($responseArray['errmsg'] === 'OK') {
            Predis::getInstance()->set(Redis::smsKey($data['phone']), $data['code'], config('redis.out_time'));
        }else {
            return false;
        }
        return true;
    }

    /**
     * 通过task机制发送赛况实时数据给客户端
     * @param $data
     * @param $serv swoole server对象
     */
    public function pushLive($data, $serv) {
        $clients = Predis::getInstance()->sMembers(config("redis.live_game_key"));//获取所有用户

        foreach($clients as $fd) {
            $serv->push($fd, json_encode($data));
        }
    }
}