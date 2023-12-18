<?php

class TeachingDialogs
{
    private $conn;
    private $table_name = "teaching_dialogs";

    public $id;

    public function __construct($db) { $this->conn = $db; }

    function readAll()
    {
        $query = "SELECT
            t.id, t.question, t.answer_correct, t.answer_0, t.answer_1, t.answer_2, t.answer_3, t.audio_url, t.image_url,
            t.subjects, t.classes, t.status, t.modified
        FROM
            " . $this->table_name . " t";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function readOne($id) {
        $query = "SELECT
            t.id, t.question, t.answer_correct, t.answer_0, t.answer_1, t.answer_2, t.answer_3, t.audio_url, t.image_url,
            t.subjects, t.classes, t.status, t.modified
        FROM
            " . $this->table_name . " t
        WHERE
            t.id = :id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt;
    }

    function read($subject, $class)
    {
        $query = "SELECT
            t.id, t.question, t.answer_correct, t.answer_0, t.answer_1, t.answer_2, t.answer_3, t.audio_url, t.image_url,
            t.subjects, t.classes, t.status, t.modified
        FROM
            " . $this->table_name . " t
        WHERE
            t.subjects = :subjects
        AND
            t.classes = :classes";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':subjects', $subject);
        $stmt->bindParam(':classes', $class);

        $stmt->execute();
        return $stmt;
    }

    function addExample($subjects, $classes) {

        $question = "";
        $answer_correct = 0;
        $answer_0 = "";
        $answer_1 = "";
        $answer_2 = "";
        $answer_3 = "";
        $audio_url = "default.wav";
        $image_url = "default.svg";
        $status = "active"; 

        $query = "INSERT INTO " . $this->table_name . "
            SET
                question = :question,
                answer_correct = :answer_correct,
                answer_0 = :answer_0,
                answer_1 = :answer_1,
                answer_2 = :answer_2,
                answer_3 = :answer_3,
                audio_url = :audio_url,
                image_url = :image_url,
                subjects = :subjects,
                classes = :classes,
                status = :status";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":question", $question);
        $stmt->bindParam(":answer_correct", $answer_correct);
        $stmt->bindParam(":answer_0", $answer_0);
        $stmt->bindParam(":answer_1", $answer_1);
        $stmt->bindParam(":answer_2", $answer_2);
        $stmt->bindParam(":answer_3", $answer_3);
        $stmt->bindParam(":audio_url", $audio_url);
        $stmt->bindParam(":image_url", $image_url);
        $stmt->bindParam(":subjects", $subjects);
        $stmt->bindParam(":classes", $classes);
        $stmt->bindParam(":status", $status);

        $stmt->execute();
        return $stmt;
    }
    function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }
    function edit($id, $question, $answer_correct, $answer_0, $answer_1, $answer_2, $answer_3) {

        $check_query = "SELECT id FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(":id", $id);
        $check_stmt->execute();
    
        if ($check_stmt->rowCount() > 0) {
            $query = "UPDATE " . $this->table_name . "
                SET
                    question = :question,
                    answer_0 = :answer_0,
                    answer_correct = :answer_correct,
                    answer_1 = :answer_1,
                    answer_2 = :answer_2,
                    answer_3 = :answer_3
                WHERE
                    id = :id";
    
            $stmt = $this->conn->prepare($query);
    
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":question", $question);
            $stmt->bindParam(":answer_0", $answer_0);
            $stmt->bindParam(":answer_correct", $answer_correct);
            $stmt->bindParam(":answer_1", $answer_1);
            $stmt->bindParam(":answer_2", $answer_2);
            $stmt->bindParam(":answer_3", $answer_3);
    
            return $stmt->execute();
        }
    
        return false;
    }

    function upload($id, $file) {
        
        $check_query = "SELECT id FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(":id", $id);
        $check_stmt->execute();
    
        if ($check_stmt->rowCount() > 0) {
        
            $file_extension = pathinfo($file["name"], PATHINFO_EXTENSION);
    
            
            $new_filename = uniqid() . "." . $file_extension;
    
            
            $upload_directory = "../../../src/image/"; 
    
            if (move_uploaded_file($file["tmp_name"], $upload_directory . $new_filename)) {
                
                $update_query = "UPDATE " . $this->table_name . " SET image_url = :image_url WHERE id = :id";
                $update_stmt = $this->conn->prepare($update_query);
                $update_stmt->bindParam(":image_url", $new_filename);
                $update_stmt->bindParam(":id", $id);
    
                return (bool)$update_stmt->execute();
            } else {
                return false;
            }
        }
    
        return false;
    }  
    
}

?>