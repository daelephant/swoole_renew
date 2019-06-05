<?php
namespace app\index\controller;
class Index
{
    public function index()
    {
//        return  '1234';
        echo time();
    }

    public function singwa() {
        echo time();
    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 'hessdggsg' . $name.time();
    }

}
