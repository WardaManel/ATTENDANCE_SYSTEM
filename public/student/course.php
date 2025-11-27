<?php
require_once(__DIR__ . "/../../src/auth.php");
require_once(__DIR__ . "/../../src/db_connect.php");

require_login(['student']);

$student_id = $_SESSION['user']['id'];


$stmt = $pdo->prepare("
    SELECT cs.id AS class_student_id, c.name AS course_name
    FROM class_students cs
    JOIN courses c ON cs.class_id = c.id
    WHERE cs.student_id = ?
");
$stmt->execute([$student_id]);
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Courses</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>


<div class="navbar">
    <a href="home.php">Home</a>
    <a href="course.php" class="active">My Courses</a>
    <a href="view_attendance.php">My Attendance</a>
    <a href="../logout.php">Logout</a>
</div>

<div class="content">
    <h1>My Courses</h1>

    <div class="table-wrapper">
        <table>
            <tr>
                <th>#</th>
                <th>Course Name</th>
            </tr>

            <?php if (count($courses) === 0): ?>
                <tr>
                    <td colspan="2">No courses assigned.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($courses as $i => $c): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($c['course_name']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>
