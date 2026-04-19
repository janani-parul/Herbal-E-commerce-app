<?php
require_once 'config.php';
header('Content-Type: application/json');

$result = $conn->query("SELECT * FROM menu ORDER BY id ASC");
$products = [];
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $products[] = $row;
  }
}
echo json_encode($products);
?>
