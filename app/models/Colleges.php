<?php

require_once __DIR__ . '/Database.php';

class Colleges {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT id, name, points FROM colleges ORDER BY points DESC, name ASC";
        $result = $this->db->query($sql);
        $colleges = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $colleges[] = $row;
            }
        }
        return $colleges;
    }

    public function updatePoints($id, $points) {
        $stmt = $this->db->prepare("UPDATE colleges SET points = points + ? WHERE id = ?");
        $stmt->bind_param("ii", $points, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}

?>