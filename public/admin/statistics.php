<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/db_connect.php';
require_login(['admin']);


$rows = $pdo->query("
  SELECT c.name, COUNT(cs.student_id) AS cnt
  FROM classes c
  LEFT JOIN class_students cs ON cs.class_id = c.id
  GROUP BY c.id
")->fetchAll();

$labels = []; $data = [];
foreach ($rows as $r) { $labels[] = $r['name']; $data[] = (int)$r['cnt']; }
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
<title>Statistics</title>
<link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<h1>Statistics</h1>
<canvas id="chart" width="600" height="300"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chart');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets: [{ label: 'Students per class', data: <?= json_encode($data) ?> }]
  }
});
</script>
<p><a href="home.php">Back</a></p>
</body>
</html>
