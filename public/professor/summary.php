<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['professor']);

$class_id = (int)($_GET['class_id'] ?? 0);
if (!$class_id) { echo "Class required"; exit; }


$stmt = $pdo->prepare("SELECT * FROM classes WHERE id=? AND professor_id=?");
$stmt->execute([$class_id, $_SESSION['user']['id']]);
$class = $stmt->fetch();
if (!$class) { echo "Access denied"; exit; }


$rows = $pdo->prepare("
  SELECT u.id, u.name,
    SUM(att.status='present') AS present_count,
    SUM(att.status='absent') AS absent_count
  FROM class_students cs
  JOIN users u ON u.id = cs.student_id
  LEFT JOIN attendance att ON att.student_id = u.id AND att.class_id = cs.class_id
  WHERE cs.class_id = ?
  GROUP BY u.id
");
$rows->execute([$class_id]);
$rows = $rows->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
<title>Summary</title>
 <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Summary for <?= htmlspecialchars($class['name']) ?></h1>
<table border="1" cellpadding="6">
<tr><th>Student</th><th>Present</th><th>Absent</th><th>Percentage</th></tr>
<?php foreach ($rows as $r):
  $total = $r['present_count'] + $r['absent_count'];
  $perc = $total ? round(($r['present_count'] / $total)*100) : 0;
?>
<tr>
  <td><?= htmlspecialchars($r['name']) ?></td>
  <td><?= $r['present_count'] ?></td>
  <td><?= $r['absent_count'] ?></td>
  <td><?= $perc ?>%</td>
</tr>
<?php endforeach; ?>
</table>
<p><a href="home.php">Back</a></p>
</body>
</html>
