<?php

class Database
{
    private $host = "localhost";
    private $db_name = "sferaschool"; 
    private $username = "root"; 
    private $password = "";
    public $conn;
    
    public $api_key = '';
    public $admin_token = '';
    public $admin_login = '';
    public $admin_password = '';

    public function getConnection()
    {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8mb4");
        } catch (PDOException $exception) {
            echo "Ошибка подключения: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

?>