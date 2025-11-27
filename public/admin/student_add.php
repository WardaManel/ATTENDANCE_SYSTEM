<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['admin']);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if ($name == '' || $email == '' || $pass == '') $error = 'All fields required';
    else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,'student')");
        $stmt->execute([$name,$email,$hash]);
        header('Location: student.php');
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Student</title>
<link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Add Student</h1>
<?php if ($error): ?><p style="color:red"><?=htmlspecialchars($error)?></p><?php endif; ?>
<form method="post">
  <label>Name<br><input name="name" required></label><br><br>
  <label>Email<br><input type="email" name="email" required></label><br><br>
  <label>Password<br><input type="password" name="password" required></label><br><br>
  <button>Add</button>
</form>
<p><a href="student.php">Back</a></p>
</body>
</html>
