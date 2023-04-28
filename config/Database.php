<?php

class Database {
    // DB Params
    private $host = 'localhost';
    private $db_name = 'php_dev';
    private $username = 'root';
    private $password = '1234';
    private $conn;

    // DB Connect
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOExceptio $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}