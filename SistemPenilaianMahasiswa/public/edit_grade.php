<?php
include_once '../config/Database.php';
include_once '../models/Grade.php';

$database = new Database();
$db = $database->getConnection();
$grade = new Grade($db);

// Fetch existing grade data
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT grades.id, students.name as student_name, courses.course_name, grades.grade 
                          FROM grades 
                          JOIN students ON grades.student_id = students.id 
                          JOIN courses ON grades.course_id = courses.id 
                          WHERE grades.id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $gradeData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$gradeData) {
        echo "<div class='alert alert-danger'>Grade not found.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>Invalid request.</div>";
    exit;
}

// Update grade data
if ($_POST) {
    $newGrade = $_POST['grade'];
    $stmt = $db->prepare("UPDATE grades SET grade = :grade WHERE id = :id");
    $stmt->bindParam(':grade', $newGrade);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Grade updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Failed to update grade.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Grade</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Grade</h2>
        <form action="edit_grade.php?id=<?= $id; ?>" method="post">
            <div class="mb-3">
                <label class="form-label">Student Name</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($gradeData['student_name']); ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Course Name</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($gradeData['course_name']); ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="grade" class="form-label">Grade</label>
                <input type="text" name="grade" class="form-control" value="<?= htmlspecialchars($gradeData['grade']); ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>