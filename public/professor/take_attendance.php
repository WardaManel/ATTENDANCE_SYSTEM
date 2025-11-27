<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['professor']);

$class_id = (int)($_REQUEST['class_id'] ?? 0);
$date = $_REQUEST['date'] ?? date('Y-m-d');

if (!$class_id) { echo "Class required"; exit; }


$stmt = $pdo->prepare("SELECT * FROM classes WHERE id=? AND professor_id=?");
$stmt->execute([$class_id, $_SESSION['user']['id']]);
if (!$stmt->fetch()) { echo "Access denied"; exit; }


$students = $pdo->prepare("
  SELECT u.id, u.name
  FROM class_students cs
  JOIN users u ON u.id = cs.student_id
  WHERE cs.class_id = ?
");
$students->execute([$class_id]);
$students = $students->fetchAll();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $del = $pdo->prepare("DELETE FROM attendance WHERE class_id=? AND date=?");
    $del->execute([$class_id, $date]);

    foreach ($students as $s) {
        $status = isset($_POST['present'][$s['id']]) ? 'present' : 'absent';
        $ins = $pdo->prepare("INSERT INTO attendance (class_id, student_id, date, status) VALUES (?,?,?,?)");
        $ins->execute([$class_id, $s['id'], $date, $status]);
    }
    header("Location: take_attendance.php?class_id=$class_id&date=$date&saved=1");
    exit;
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Take Attendance</title>
 <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Take Attendance - Date: <?=htmlspecialchars($date)?></h1>
<form method="post">
  <input type="hidden" name="class_id" value="<?= $class_id ?>">
  <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
  <table border="1" cellpadding="6">
    <tr><th>Student</th><th>Present</th></tr>
    <?php foreach ($students as $s): ?>
      <tr>
        <td><?=htmlspecialchars($s['name'])?></td>
        <td><input type="checkbox" name="present[<?= $s['id'] ?>]" checked></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <button>Save Attendance</button>
</form>
<p><a href="session.php?class_id=<?= $class_id ?>">Back</a></p>
</body>
</html>
