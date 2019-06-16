<?php
namespace app\index\controller;
use app\common\lib\qcloud\Sms;
class Index
{
    public function index()
    {
        return  '';
//        echo time();
    }

    public function singwa() {
        echo time();
    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 'hessdggsg' . $name.time();
    }

    public function sms(){
        try{
            $Sms = new Sms();
            $Sms->sendSms(13381101326,1234,15);
        }catch (\Exception $e){
            //todo
        }
    }

}
