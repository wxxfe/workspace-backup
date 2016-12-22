<?php
require_once BASEPATH . '/../config/'.ENVIRONMENT.'/database.php';
/**
 * Class CronMysqlPdo
 * 数据库连接类
 */
class CronMysqlPdo extends PDO{
    public function __construct($dbname){
        global $db;
        if(isset($db[$dbname])){
            $config = $db[$dbname];
            if(isset($config['dsn'])){
                $dsn = $config['dsn'];
            }else{
                $dbname = isset($config['database']) ? $config['database'] : '';
                $host = isset($config['hostname']) ? $config['hostname'] : '';
                $port = isset($config['port']) ? $config['port'] : '3306';
                $dsn = "mysql:dbname={$dbname};host={$host};port={$port}";
            }
            parent::__construct($dsn, $config['username'], $config['password']);
        }else{
            return false;
        }
    }
}
?>