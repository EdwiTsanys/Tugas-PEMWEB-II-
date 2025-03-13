<?php
include_once '../config/Database.php';
include_once '../models/Grade.php';

$database = new Database();
$db = $database->getConnection();

$grade = new Grade($db);


if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM grades WHERE id = :id");
    $stmt->bindParam(':id', $delete_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Grade deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Failed to delete grade.</div>";
    }
}

$grades = $grade->read();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Grades</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Student Grades (Dosen Panel)</h2>
        <a href="add_grade.php" class="btn btn-primary mb-3">Tambahkan Nilai</a>
        <a href="view_grades.php" class="btn btn-info mb-3">Lihat Sebagai Mahasiswa</a>
        <table id="gradesTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Course Name</th>
                    <th>Grade</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $grades->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['student_name']); ?></td>
                        <td><?= htmlspecialchars($row['course_name']); ?></td>
                        <td><?= htmlspecialchars($row['grade']); ?></td>
                        <td>
                            <a href="edit_grade.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="index.php?delete_id=<?= $row['id']; ?>" 
                               onclick="return confirm('Are you sure to delete this grade?');" 
                               class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#gradesTable').DataTable();
        });
    </script>
</body>
</html>