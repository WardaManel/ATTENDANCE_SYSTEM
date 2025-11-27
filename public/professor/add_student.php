<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['professor']);

$class_id = (int)($_GET['class_id'] ?? 0);
if (!$class_id) { echo "class_id required"; exit; }

$stmt = $pdo->prepare("SELECT * FROM classes WHERE id=? AND professor_id=?");
$stmt->execute([$class_id, $_SESSION['user']['id']]);
if (!$stmt->fetch()) { echo "Access denied"; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int)$_POST['student_id'];
    $pdo->prepare("INSERT IGNORE INTO class_students (class_id, student_id) VALUES (?,?)")->execute([$class_id,$student_id]);
    header("Location: manage_class_students.php?class_id=$class_id");
    exit;
}


$stmt = $pdo->prepare("SELECT id,name,email FROM users WHERE role='student' AND id NOT IN (SELECT student_id FROM class_students WHERE class_id=?)");
$stmt->execute([$class_id]);
$students = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
<title>Add Student</title>
 <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Add student to class</h1>
<?php if (empty($students)): ?><p>No students available</p><?php else: ?>
<form method="post">
  <select name="student_id">
    <?php foreach ($students as $s): ?>
      <option value="<?=$s['id']?>"><?=htmlspecialchars($s['name'])?> (<?=htmlspecialchars($s['email'])?>)</option>
    <?php endforeach; ?>
  </select>
  <button>Add</button>
</form>
<?php endif; ?>
<p><a href="manage_class_students.php?class_id=<?= $class_id ?>">Back</a></p>
</body>
</html>
