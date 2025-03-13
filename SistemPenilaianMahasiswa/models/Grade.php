<?php
class Grade {
    private $conn;
    private $table = "grades";

    public $id;
    public $student_id;
    public $course_id;
    public $grade;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT g.id, s.name as student_name, c.course_name, g.grade 
                  FROM " . $this->table . " g 
                  JOIN students s ON g.student_id = s.id 
                  JOIN courses c ON g.course_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET student_id=:student_id, course_id=:course_id, grade=:grade";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":student_id", $this->student_id);
        $stmt->bindParam(":course_id", $this->course_id);
        $stmt->bindParam(":grade", $this->grade);

        return $stmt->execute();
    }
}
?>