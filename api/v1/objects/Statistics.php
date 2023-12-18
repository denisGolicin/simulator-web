<?php

class Statistics
{
    private $conn;
    private $table_name = "statistics";

    public $id;


    public function __construct($db) { $this->conn = $db; }

    function read($limit = 5, $offset = 0)
    {
        $query = "SELECT
           s.name, s.subjects, s.classes, s.classes_letter, 
           s.answers_correct, s.dialogs, s.precent, s.id, s.modified
        FROM
            " . $this->table_name . " s
        LIMIT
            :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    function add($name, $subjects, $classes, $classes_letter, $answers_correct, $dialogs, $precent) {

        $query = "INSERT INTO " . $this->table_name . "
            SET
                name = :name,
                subjects = :subjects,
                classes = :classes,
                classes_letter = :classes_letter,
                answers_correct = :answers_correct,
                dialogs = :dialogs,
                precent = :precent";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":subjects", $subjects);
        $stmt->bindParam(":classes", $classes);
        $stmt->bindParam(":classes_letter", $classes_letter);
        $stmt->bindParam(":answers_correct", $answers_correct);
        $stmt->bindParam(":dialogs", $dialogs);
        $stmt->bindParam(":precent", $precent);

        $stmt->execute();
        return $stmt;
    }

}



?>