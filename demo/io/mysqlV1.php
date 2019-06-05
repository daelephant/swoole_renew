<?php
/**
 * Created by PhpStorm.
 * User: yin
 * Date: 2019/5/28
 * Time: 15:16
 */

class AsyncMySql {
    /**
     * @var string
     */
    public $dbSource = "";
    /**
     * mysql的配置
     * @var array
     */
    public $dbConfig = [];
    public function __construct() {
        //new swoole_mysql;
        $this->dbSource = new Swoole\Mysql;

        $this->dbConfig = [
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'password' => '',
            'database' => 'swoole',
            'charset' => 'utf8',
        ];
    }
    public function update() {}
    public function add() {}
    /**
     * mysql 执行逻辑
     * @param $id
     * @param $username
     * @return bool
     */
    public function execute($id, $username) {
        $this->dbSource->connect($this->dbConfig, function($db, $result) use($id, $username)  {
            echo "mysql-connect".PHP_EOL;
            if($result === false) {
                var_dump($db->connect_error);
                // todo
            }
            $sql = "select * from test where id=1";
            //$sql = "update test set `username` = '".$username."' where id=".$id;
            // insert into
            // query (add select update delete)
            $db->query($sql, function($db, $result){
                // select => result返回的是 查询的结果内容
                if($result === false) {
                    // todo
                    var_dump($db->error);
                }elseif($result === true) {// add update delete
                    // todo
                    var_dump($db->affected_rows);
                }else {
                    print_r($result);
                }
                $db->close();
            });

        });
        return true;
    }
}
$obj = new AsyncMySql();
$flag = $obj->execute(1, 'singwa-111112');
var_dump($flag).PHP_EOL;
echo "start".PHP_EOL;