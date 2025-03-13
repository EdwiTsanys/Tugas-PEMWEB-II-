<?php
include_once '../config/Database.php';
include_once '../models/Grade.php';

$database = new Database();
$db = $database->getConnection();

$grade = new Grade($db);
$grades = $grade->read();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Student Grades</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Student Grades (Student Panel)</h2>
        <table id="gradesTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Course Name</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $grades->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['student_name']); ?></td>
                        <td><?= htmlspecialchars($row['course_name']); ?></td>
                        <td><?= htmlspecialchars($row['grade']); ?></td>
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
