<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['admin']);

$msg='';
if (isset($_POST['import']) && isset($_FILES['csv_file'])) {
    $f = $_FILES['csv_file']['tmp_name'];
    if (($handle = fopen($f,'r')) !== false) {
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
          
            if (count($data) < 3) continue;
            $name = trim($data[0]); $email = trim($data[1]); $pass = trim($data[2]);
            if ($name=='' || $email=='' || $pass=='') continue;
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT IGNORE INTO users (name,email,password_hash,role) VALUES (?,?,?,'student')");
            $stmt->execute([$name,$email,$hash]);
        }
        fclose($handle);
        $msg = 'Import finished';
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Import Students</title>
<link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Import Students (CSV: name,email,password)</h1>
<?php if ($msg): ?><p><?=htmlspecialchars($msg)?></p><?php endif; ?>
<form method="post" enctype="multipart/form-data">
  <input type="file" name="csv_file" accept=".csv" required>
  <button name="import">Upload</button>
</form>
<p><a href="student.php">Back</a></p>
</body>
</html>
