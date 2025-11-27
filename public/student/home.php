<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['student']);

$student_id = $_SESSION['user']['id'];
$classes = $pdo->prepare("
  SELECT c.id,c.name
  FROM class_students cs
  JOIN classes c ON c.id = cs.class_id
  WHERE cs.student_id = ?
");
$classes->execute([$student_id]);
$classes = $classes->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
<title>Student Home</title>
<link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Welcome <?=htmlspecialchars($_SESSION['user']['name'])?></h1>
<ul>
<?php foreach ($classes as $c): ?>
  <li>
    <?=htmlspecialchars($c['name'])?> -
    <a href="view_attendance.php?class_id=<?= $c['id'] ?>">View Attendance</a>
  </li>
<?php endforeach; ?>
</ul>
<p><a href="../logout.php">Logout</a></p>
</body>
</html>
