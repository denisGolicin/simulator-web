<?php

class Version
{
    private $conn;
    private $table_name = "version";

    public $count; 

    public function __construct($db) {
        $this->conn = $db;
    }

    function add($type)
    {
        
        $currentCount = $this->getCount();
        $newCount = $currentCount + 1;

        $update_query = "UPDATE " . $this->table_name . " SET count = :count";
        $update_stmt = $this->conn->prepare($update_query);
        $update_stmt->bindParam(':count', $newCount);
        $update_stmt->execute();

        $this->count = $newCount;

        $this->sendTelegram("🔄 - SferaSchool: " .$_SERVER['REMOTE_ADDR'] ."\n" .$type, "console");

        return $newCount;
    }

    function read()
    {
        $this->count = $this->getCount();
        $this->sendTelegram("🔀 - SferaSchool: " .$_SERVER['REMOTE_ADDR'] ."\nCount: " .$this->count, "console");
        return $this->count;
    }

   
    private function getCount()
    {
        $query = "SELECT count FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['count'];
        }

        return 0; 
    }
    function sendTelegram($message, $console)
    {
        $token = 'TOKEN';
        if($console == 'error'){
            $chatId = 'ID';
        } else {
            $chatId = 'ID';
        }

        $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($message);

        $context = stream_context_create(['socket' => ['bindto' => '0:0']]);
        $response = file_get_contents($url, false, $context);
   
    }
}


?>