<?php
session_start();
include "functions.php";

$message = getMessage();
$list = [];
$list['msg'] = $message;
header('Content-Type: application/json');
echo json_encode($list);

?>
