<?php
require_once(__DIR__ . "/../../src/auth.php");
require_once(__DIR__ . "/../../src/db_connect.php");

require_login(['admin']);

if (!isset($_GET['id'])) {
    die("Missing ID");
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("DELETE FROM users WHERE id=? AND role='student'");
$stmt->execute([$id]);

header("Location: student.php");
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Student</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<div class="content">
    <h1>Deleting student...</h1>
    <p>If you are not redirected automatically, click below:</p>

    <a href="student.php">
        <button>Go Back</button>
    </a>
</div>

</body>
</html>
