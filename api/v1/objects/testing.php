<?php

class Testing
{
    private $conn;
    private $table_name = "testing";

    public $id;


    public function __construct($db) { $this->conn = $db; }

    function read($limit = 5, $offset = 0)
    {
        $query = "SELECT
            e.id, e.title, e.start_date, e.end_date, e.start_time, e.status,
            e.description, e.venue, e.images, c.id AS category_id, c.category_name AS category_name,
            d.id AS district_id, d.district_name AS district_name,
            e.last_name, e.first_name, e.middle_name, e.post, e.phone_number,
            e.organization_address, e.social_links, e.experience, e.currency, e.user_rated
        FROM
            " . $this->table_name . " e
            LEFT JOIN
                category_events c ON e.category_id = c.id
            LEFT JOIN
                district_events d ON e.district_id = d.id
        LIMIT
            :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

}

?>