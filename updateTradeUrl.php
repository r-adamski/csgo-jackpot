<?php
session_start();
include "functions.php";

if(isset($_SESSION['logged'])){
	if($_SESSION['logged'] == true){
		$tradeUrl = $_GET['url'];
		
		if($tradeUrl != null){
		
		$msg = setTradeUrl($tradeUrl);
		
		echo $msg;
		}
		else{
			echo "Podaj swój tradeUrl!";
		}
		
	}
	else{
		echo "Błąd: Nie jesteś zalogowany.";
	}
}
else{
	echo "Błąd sesji.";
}
?>
