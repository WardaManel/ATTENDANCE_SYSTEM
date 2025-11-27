<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['admin']);


$students = $pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
$profs = $pdo->query("SELECT COUNT(*) FROM users WHERE role='professor'")->fetchColumn();
$classes = $pdo->query("SELECT COUNT(*) FROM classes")->fetchColumn();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
<link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Welcome, Admin!</h1>
<p>Students: <?= $students ?> | Professors: <?= $profs ?> | Classes: <?= $classes ?></p>
<ul>
    <li><a href="student.php">Manage Students</a></li>
    <li><a href="import.php">Import Students</a></li>
    <li><a href="statistics.php">View Statistics</a></li>
    <li><a href="../logout.php">Logout</a></li>
</ul>
</body>
</html>
