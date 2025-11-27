<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['student']);

$class_id = (int)($_GET['class_id'] ?? 0);
$student_id = $_SESSION['user']['id'];

if (!$class_id) { echo "class required"; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['just_file'])) {

    if (!is_dir(__DIR__ . '/../../uploads')) mkdir(__DIR__ . '/../../uploads', 0755, true);
    $f = $_FILES['just_file'];
    if ($f['error'] === 0) {
        $fn = time() . '_' . basename($f['name']);
        $dest = __DIR__ . '/../../uploads/' . $fn;
        move_uploaded_file($f['tmp_name'],$dest);

        $msg = "File uploaded";
    } else $msg = "Upload error";
}


$stmt = $pdo->prepare("SELECT date,status,justification FROM attendance WHERE class_id=? AND student_id=? ORDER BY date DESC");
$stmt->execute([$class_id,$student_id]);
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance</title>
 <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Your Attendance</h1>
<?php if (!empty($msg)): ?><p><?=htmlspecialchars($msg)?></p><?php endif; ?>
<table border="1" cellpadding="6">
<tr><th>Date</th><th>Status</th><th>Justification</th></tr>
<?php if (!$rows) echo "<tr><td colspan=3>No records</td></tr>"; else foreach ($rows as $r): ?>
<tr>
  <td><?=htmlspecialchars($r['date'])?></td>
  <td><?=htmlspecialchars($r['status'])?></td>
  <td><?=htmlspecialchars($r['justification'] ?: '-')?></td>
</tr>
<?php endforeach; ?>
</table>

<h3>Upload justification (if absent)</h3>
<form method="post" enctype="multipart/form-data">
  <input type="file" name="just_file" accept=".pdf,.jpg,.png">
  <button>Upload</button>
</form>

<p><a href="home.php">Back</a></p>
</body>
</html>
