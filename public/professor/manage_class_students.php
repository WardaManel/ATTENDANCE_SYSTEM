<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['professor']);

$class_id = (int)($_GET['class_id'] ?? 0);


$prof_id = $_SESSION['user']['id'];
$classes = $pdo->prepare("SELECT * FROM classes WHERE professor_id = ?");
$classes->execute([$prof_id]);
$classes = $classes->fetchAll();


if ($class_id && isset($_GET['remove'])) {
    $sid = (int)$_GET['remove'];
    $pdo->prepare("DELETE FROM class_students WHERE class_id=? AND student_id=?")->execute([$class_id,$sid]);
    header("Location: manage_class_students.php?class_id=$class_id");
    exit;
}


$students = [];
if ($class_id) {
    $stmt = $pdo->prepare("
      SELECT u.id, u.name, u.email
      FROM class_students cs
      JOIN users u ON u.id = cs.student_id
      WHERE cs.class_id = ?
    ");
    $stmt->execute([$class_id]);
    $students = $stmt->fetchAll();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
<title>Manage Students</title> 
<link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Manage Class Students</h1>
<form method="get">
  <label>Select class:
    <select name="class_id" onchange="this.form.submit()">
      <option value="">--choose--</option>
      <?php foreach ($classes as $c): ?>
      <option value="<?= $c['id'] ?>" <?= $class_id==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </label>
</form>

<?php if ($class_id): ?>
  <h2>Students</h2>
  <table border="1" cellpadding="6">
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Remove</th></tr>
    <?php if (!$students): ?>
      <tr><td colspan="4">No students</td></tr>
    <?php else: foreach ($students as $s): ?>
      <tr>
        <td><?= $s['id'] ?></td>
        <td><?= htmlspecialchars($s['name']) ?></td>
        <td><?= htmlspecialchars($s['email']) ?></td>
        <td><a href="?class_id=<?= $class_id ?>&remove=<?= $s['id'] ?>" onclick="return confirm('Remove?')">Remove</a></td>
      </tr>
    <?php endforeach; endif; ?>
  </table>
  <p><a href="add_student.php?class_id=<?= $class_id ?>">Add Student to Class</a></p>
<?php endif; ?>

<p><a href="home.php">Back</a></p>
</body>
</html>
