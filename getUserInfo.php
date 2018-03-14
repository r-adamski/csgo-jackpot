<?php
session_start();
include "functions.php";

$nick = $_SESSION['username'];
$id = $_SESSION['steamid'];

$tab = [];
$tab['nick'] = $nick;
$tab['id'] = $id;

header('Content-Type: application/json');
echo json_encode(utf8ize($tab));
?>
