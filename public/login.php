<?php

require_once __DIR__ . '/../src/db_connect.php';
require_once __DIR__ . '/../src/auth.php';

if (is_logged_in()) {
    
    if (is_admin()) header('Location: admin/home.php');
    elseif (is_professor()) header('Location: professor/home.php');
    else header('Location: student/home.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';

    if (login($email, $pass, $pdo)) {
        if (is_admin()) header('Location: admin/home.php');
        elseif (is_professor()) header('Location: professor/home.php');
        else header('Location: student/home.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
<title>Login</title>
 <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Login</h1>
<?php if ($error): ?><p style="color:red"><?=htmlspecialchars($error)?></p><?php endif; ?>
<form method="post">
    <label>Email<br><input type="email" name="email" required></label><br><br>
    <label>Password<br><input type="password" name="password" required></label><br><br>
    <button>Log in</button>
</form>
</body>
</html>
