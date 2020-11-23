<?php
namespace zihaolinqianhuifan\hw3\models;
require_once('src/config/Config.php');


use zihaolinqianhuifan\hw3\Config as conf;
class Model{
    protected static $conn;
    function __construct(){

        if(empty($conn)){
            self::$conn=new \mysqli(conf::host, conf::user, conf::password, conf::db);
        }

        #$tables=self::fetch('show tables;');
        
    }
    function fetch($sql){
        return self::$conn->query($sql)->fetch_all();
    }
    function exe($sql){
        return self::$conn->query($sql);
    }
    
}
