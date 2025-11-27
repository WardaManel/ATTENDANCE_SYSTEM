<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['admin']);


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'student'");
    $stmt->execute([$id]);
    header('Location: student.php');
    exit;
}

$students = $pdo->query("SELECT id,name,email FROM users WHERE role='student' ORDER BY id DESC")->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
<title>Manage Students</title>
 <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Students</h1>
<p><a href="student_add.php">Add Student</a> | <a href="home.php">Back</a></p>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr>
<?php foreach ($students as $s): ?>
<tr>
  <td><?= $s['id'] ?></td>
  <td><?= htmlspecialchars($s['name']) ?></td>
  <td><?= htmlspecialchars($s['email']) ?></td>
  <td>
    <a href="student_edit.php?id=<?= $s['id'] ?>">Edit</a> |
    <a href="student.php?delete=<?= $s['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
  </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
