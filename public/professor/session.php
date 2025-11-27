<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['professor']);

$class_id = (int)($_GET['class_id'] ?? 0);
if (!$class_id) { echo "Class required"; exit; }
$stmt = $pdo->prepare("SELECT * FROM classes WHERE id=? AND professor_id=?");
$stmt->execute([$class_id, $_SESSION['user']['id']]);
$class = $stmt->fetch();
if (!$class) { echo "Class not found or access denied"; exit; }

$date = $_GET['date'] ?? date('Y-m-d');
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8"><title>Session</title> 
<link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Class: <?=htmlspecialchars($class['name'])?></h1>
<form method="get" action="take_attendance.php">
  <input type="hidden" name="class_id" value="<?= $class_id ?>">
  <label>Date: <input type="date" name="date" value="<?=htmlspecialchars($date)?>"></label>
  <button>Start</button>
</form>
<p><a href="home.php">Back</a></p>
</body>
</html>
