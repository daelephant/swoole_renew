<?php
/**
 * Created by PhpStorm.
 */
$swoole_mysql = new Swoole\Coroutine\MySQL();
$swoole_mysql->connect([
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'password' => '',
    'database' => 'swoole',
]);
$res = $swoole_mysql->query('select * from `test`');
if($res === false) {
    return;
}
foreach ($res as $value) {
    echo $value['f_filed_name'];
}