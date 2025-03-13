<?php
include_once '../config/Database.php';
include_once '../models/Student.php';
include_once '../models/Course.php';
include_once '../models/Grade.php';

$database = new Database();
$db = $database->getConnection();

$student = new Student($db);
$course = new Course($db);
$grade = new Grade($db);

if ($_POST) {
    // Check or insert student
    $student_name = trim($_POST['student_name']);
    $student_stmt = $db->prepare("SELECT id FROM students WHERE name = :name");
    $student_stmt->bindParam(':name', $student_name);
    $student_stmt->execute();
    $student_data = $student_stmt->fetch(PDO::FETCH_ASSOC);

    if ($student_data) {
        $student_id = $student_data['id'];
    } else {
        $insert_student = $db->prepare("INSERT INTO students (name) VALUES (:name)");
        $insert_student->bindParam(':name', $student_name);
        $insert_student->execute();
        $student_id = $db->lastInsertId();
    }

    // Check or insert course
    $course_name = trim($_POST['course_name']);
    $course_stmt = $db->prepare("SELECT id FROM courses WHERE course_name = :course_name");
    $course_stmt->bindParam(':course_name', $course_name);
    $course_stmt->execute();
    $course_data = $course_stmt->fetch(PDO::FETCH_ASSOC);

    if ($course_data) {
        $course_id = $course_data['id'];
    } else {
        $insert_course = $db->prepare("INSERT INTO courses (course_name) VALUES (:course_name)");
        $insert_course->bindParam(':course_name', $course_name);
        $insert_course->execute();
        $course_id = $db->lastInsertId();
    }

    // Insert grade
    $grade->student_id = $student_id;
    $grade->course_id = $course_id;
    $grade->grade = $_POST['grade'];

    if ($grade->create()) {
        echo "<div class='alert alert-success'>Grade added successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Unable to add grade.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Grade</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Grade</h2>
        <form action="add_grade.php" method="post">
            <div class="mb-3">
                <label for="student_name" class="form-label">Student Name</label>
                <input type="text" name="student_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="course_name" class="form-label">Course Name</label>
                <input type="text" name="course_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="grade" class="form-label">Grade</label>
                <input type="text" name="grade" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
            <a href="index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>
