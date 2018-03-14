<?php
session_start();

$hastrade = "false";
$logged = "false";

if(isset($_SESSION['logged'])){
	if($_SESSION['logged'] == true){
		$logged = "true";
	}
}

if(isset($_SESSION['tradeUrl'])){
	if($_SESSION['tradeUrl'] == true){
		$hastrade = "true";
	}
}


$tab = [];
$tab['hasTradeUrl'] = $hastrade;
$tab['isLogged'] = $logged;

header('Content-Type: application/json');
echo json_encode($tab);
?>
