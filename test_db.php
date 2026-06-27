<?php
require 'db.php';

echo "Connected successfully!<br>";

$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll();

echo "Tables in ewaste_db:<br>";
foreach ($tables as $t) {
    echo '- ' . $t[array_key_first($t)] . '<br>';
}
