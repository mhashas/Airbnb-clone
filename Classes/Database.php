<?php

class Database
{
    private static $db = null;

    static function get()
    {
         $servername = "localhost";
         $username = "root";
         $password = "";
         $dbName = "test";

        if(!self::$db){
            self::$db = new mysqli($servername, $username, $password, $dbName);
        }

        return self::$db;
    }

    static function close(){
        if(!self::$db) return;

        self::$db->close();
    }

}