<?php

class TestingClasses
{
    private $conn;
    private $table_name = "testing_classes";

    public $id;


    public function __construct($db) { $this->conn = $db; }

    function read()
    {
        $query = "SELECT
            tc.id, tc.classes_name, tc.status, tc.modified
        FROM
            " . $this->table_name . " tc";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    function add($subjects_name, $class_name) {

        $subject_query = "SELECT id FROM testing_subject WHERE subjects_name = :subjects_name LIMIT 1";
        $subject_stmt = $this->conn->prepare($subject_query);
        $subject_stmt->bindParam(':subjects_name', $subjects_name);
        $subject_stmt->execute();
        
        $class_query = "SELECT id FROM " . $this->table_name . " WHERE classes_name = :class_name LIMIT 1";
        $class_stmt = $this->conn->prepare($class_query);
        $class_stmt->bindParam(':class_name', $class_name);
        $class_stmt->execute();
        
        if ($subject_stmt->rowCount() > 0) {

            $query = "INSERT INTO testing_dialogs (answer_correct, answer_0, audio_url, image_url, subjects, classes, status)
            VALUES (0, '', 'default.wav', 'default.svg', :subjects, :classes, 'active')";
  
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':subjects', $subjects_name);
            $stmt->bindParam(':classes', $class_name);
            $stmt->execute();

            if ($class_stmt->rowCount() > 0) {
                return false;
            } else {

                $query = "INSERT INTO " . $this->table_name . " (classes_name, status) VALUES (:class_name, 'active')";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':class_name', $class_name);
                $stmt->execute();
            
                return (bool)$stmt;
            }
        } else {
            return false;
        }
    }

    function edit($currentSubjects, $currentClass, $newClass) {

        $update_dialog_query = "UPDATE testing_dialogs SET classes = :new_class WHERE subjects = :current_subjects AND classes = :current_class";
        $update_dialog_stmt = $this->conn->prepare($update_dialog_query);
        $update_dialog_stmt->bindParam(':new_class', $newClass);
        $update_dialog_stmt->bindParam(':current_subjects', $currentSubjects);
        $update_dialog_stmt->bindParam(':current_class', $currentClass);
        $update_dialog_stmt->execute();

        return $update_dialog_stmt;
    
    }

    function delete($subjects_name, $class_name) {

        $class_query = "SELECT id FROM " . $this->table_name . " WHERE classes_name = :class_name LIMIT 1";
        $class_stmt = $this->conn->prepare($class_query);
        $class_stmt->bindParam(':class_name', $class_name);
        $class_stmt->execute();
        
        if ($class_stmt->rowCount() > 0) {

            $dialog_query = "DELETE FROM testing_dialogs WHERE subjects = :subjects_name AND classes = :class_name";
            $dialog_stmt = $this->conn->prepare($dialog_query);
            $dialog_stmt->bindParam(':subjects_name', $subjects_name);
            $dialog_stmt->bindParam(':class_name', $class_name);
            $dialog_stmt->execute();
    
            $delete_class_query = "DELETE FROM " . $this->table_name . " WHERE classes_name = :class_name";
            $delete_class_stmt = $this->conn->prepare($delete_class_query);
            $delete_class_stmt->bindParam(':class_name', $class_name);
    
            if ($delete_class_stmt->execute()) {
                return true;
            }
        }
        return false;
    }

}



?>