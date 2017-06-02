<?php

class Connection {
    
    static public $host;
    static public $username;
    static public $password;
    static public $db;
    
    static public function getConnectionData(array $connData) {
        
        Connection::$host       = $connData['host'];
        Connection::$username   = $connData['username'];
        Connection::$password   = $connData['password'];
        Connection::$db         = $connData['db'];
        
    }
    
    static public function connect($query) {
        
        $connection = new mysqli(Connection::$host, Connection::$username, Connection::$password, Connection::$db);
        $result = $connection->query($query);
        
        $connection->close();
        $connection = NULL;
        
        return $result;
        
    }
    
}

