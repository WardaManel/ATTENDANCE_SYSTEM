<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['admin']);

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role='student'");
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user) { header('Location: student.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $sql = "UPDATE users SET name=?, email=? WHERE id=?";
    $pdo->prepare($sql)->execute([$name,$email,$id]);
    header('Location: student.php'); exit;
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
<title>Edit Student</title> 
<link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Edit Student</h1>
<form method="post">
  <label>Name<br><input name="name" value="<?=htmlspecialchars($user['name'])?>" required></label><br><br>
  <label>Email<br><input name="email" value="<?=htmlspecialchars($user['email'])?>" required></label><br><br>
  <button>Save</button>
</form>
<p><a href="student.php">Back</a></p>
</body>
</html>
