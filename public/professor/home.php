<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['professor']);

$prof_id = $_SESSION['user']['id'];
$classes = $pdo->prepare("SELECT * FROM classes WHERE professor_id = ?");
$classes->execute([$prof_id]);
$classes = $classes->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
<title>Professor Home</title>
 <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Welcome, <?=htmlspecialchars($_SESSION['user']['name'])?></h1>
<p><a href="../logout.php">Logout</a></p>
<h2>Your Classes</h2>
<ul>
<?php foreach ($classes as $c): ?>
  <li>
    <?=htmlspecialchars($c['name'])?> -
    <a href="session.php?class_id=<?= $c['id'] ?>">Sessions / Take Attendance</a> |
    <a href="summary.php?class_id=<?= $c['id'] ?>">Summary</a> |
    <a href="manage_class_students.php?class_id=<?= $c['id'] ?>">Manage Students</a>
  </li>
<?php endforeach; ?>
</ul>
</body>
</html>
