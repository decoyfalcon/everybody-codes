<?php
global $db;
require 'init_db.php'; #verbinding met database

header('Content-type: application/json');

$cmd = $db->pdo->query("SELECT * FROM cameras"); //haalt alles uit de tabel op
$cameras = $cmd->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($cameras);