<?php

class TeachingSubjects
{
    private $conn;
    private $table_name = "teaching_subject";

    public $id;
    public $subjects_name;
    public $status;
    public $modified;


    public function __construct($db) { $this->conn = $db; }

    function read()
    {
        $query = "SELECT
            ts.id, ts.subjects_name, ts.status, ts.modified
        FROM
            " . $this->table_name . " ts";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    } 
    
    function add($subjects_name) {
        
        $query = "SELECT id FROM " . $this->table_name . " WHERE subjects_name = :subjects_name LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':subjects_name', $subjects_name);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id'];
        }
    
        $query = "INSERT INTO " . $this->table_name . " (subjects_name, status) VALUES (:subjects_name, 'active')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':subjects_name', $subjects_name);
    
        if ($stmt->execute()) {
            return true; 
        }
    
        return false;
    }

    function delete($subjects_name) {

        $query = "SELECT id FROM " . $this->table_name . " WHERE subjects_name = :subjects_name LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':subjects_name', $subjects_name);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $subject_id = $row['id'];
    
            $dialog_query = "DELETE FROM teaching_dialogs WHERE subjects = :subjects_name";
            $dialog_stmt = $this->conn->prepare($dialog_query);
            $dialog_stmt->bindParam(':subjects_name', $subjects_name);
            $dialog_stmt->execute();
    
            $delete_query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $delete_stmt = $this->conn->prepare($delete_query);
            $delete_stmt->bindParam(':id', $subject_id);
    
            if ($delete_stmt->execute()) {
                return true;
            }
        }
    
        return false;
    }

    function edit($currentName, $newName) {

        $check_query = "SELECT id FROM " . $this->table_name . " WHERE subjects_name = :new_name LIMIT 1";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(':new_name', $newName);
        $check_stmt->execute();
    
        if ($check_stmt->rowCount() > 0) {
            return false; 
        }
    
        $query = "SELECT id FROM " . $this->table_name . " WHERE subjects_name = :current_name LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':current_name', $currentName);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $subject_id = $row['id'];
    
            $update_dialog_query = "UPDATE teaching_dialogs SET subjects = :new_name WHERE subjects = :current_name";
            $update_dialog_stmt = $this->conn->prepare($update_dialog_query);
            $update_dialog_stmt->bindParam(':new_name', $newName);
            $update_dialog_stmt->bindParam(':current_name', $currentName);
            $update_dialog_stmt->execute();

            $update_subject_query = "UPDATE " . $this->table_name . " SET subjects_name = :new_name WHERE subjects_name = :current_name";
            $update_subject_stmt = $this->conn->prepare($update_subject_query);
            $update_subject_stmt->bindParam(':new_name', $newName);
            $update_subject_stmt->bindParam(':current_name', $currentName);
    
            if ($update_subject_stmt->execute()) {
                return true;
            }
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