<?php

class Synthesis
{
    private $conn;

    public function __construct($db) { $this->conn = $db; }

    function add($mode, $id)
    {
        $answer_0 = '';
        $select_query = "SELECT answer_0 FROM " . $mode . "_dialogs WHERE id = :id";
        $select_stmt = $this->conn->prepare($select_query);
        $select_stmt->bindParam(':id', $id);
        $select_stmt->execute();

        if ($select_stmt->rowCount() > 0) {
            
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
            $answer_0 = $row['answer_0'];
        } else {
            return false;
        }

        $curl = curl_init();
 
        $json = json_encode(array('data' => array(array(
            'lang' =>'ru-RU',
            'speaker'=> '1051', //Samokhv
            'emotion' =>'neutral',
            'text' => $answer_0, 
            'rate' => '1.0',
            'pitch' => '1.0',
            'type' => 'wav',
            'pause' => '0'
        ))));
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apihost.ru/api/v1/synthesize',
            CURLOPT_RETURNTRANSFER  =>  1,
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer <TOKEN>',
                'Content-Type: application/json',
            ),
        
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response);

        if ($data && isset($data->status) && $data->status == 205) {
            $gid = $data->process;

            $update_query = "UPDATE " . $mode . "_dialogs SET gid = :gid, status = 'generated' WHERE id = :id";
            $update_stmt = $this->conn->prepare($update_query);
            $update_stmt->bindParam(':id', $id);
            $update_stmt->bindParam(':gid', $gid);
            $update_stmt->execute();

            return $update_stmt;
        }
        return false;
    }

    function check($mode, $id){
        $curl = curl_init();
     
        $gid_query = "SELECT gid FROM " . $mode . "_dialogs WHERE id = :id";
        $gid_stmt = $this->conn->prepare($gid_query);
        $gid_stmt->bindParam(':id', $id);
        $gid_stmt->execute();
    
        if ($gid_stmt->rowCount() > 0) {
            $row = $gid_stmt->fetch(PDO::FETCH_ASSOC);
            $gid = $row['gid'];
    
            $json = json_encode(array('process' => $gid));
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://apihost.ru/api/v1/process',
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer <TOKEN>',
                    'Content-Type: application/json',
                ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            $data = json_decode($response);
    
            if ($data && isset($data->status) && $data->status == 200){
                
                $audioUrl = $data->message;
                $audioContent = file_get_contents($audioUrl);

                $fileName = time() . '.wav';

                $filePath = '../../../src/audio/' . $fileName;

                if(!file_put_contents($filePath, $audioContent)) return false;

                $update_query = "UPDATE " . $mode . "_dialogs SET gid = NULL, audio_url = :audio, status = 'active' WHERE id = :id";
                $update_stmt = $this->conn->prepare($update_query);
                $update_stmt->bindParam(':id', $id);
                $update_stmt->bindParam(':audio', $fileName); 
                $update_stmt->execute();

                return (bool)$update_stmt;
            }
        }
    
        return false;
    }
    
}