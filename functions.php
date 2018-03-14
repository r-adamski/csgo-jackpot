<?php

class item{
	public $name;
	public $price;
	public $color;
	public $url;
	public $username;
	public $avatar;
	public $userid;
	
	public function __construct($name, $price, $color, $url, $username, $avatar, $userid) {
              $this->name = $name;
              $this->price = $price;
              $this->color = $color;
			  $this->url = $url;
			  $this->username = $username;
			  $this->avatar = $avatar;
			  $this->userid = $userid;
            }
			
}

function getMessage(){
	
	include_once "mysql.php";
	$conn = new mysqli($host, $username, $password, $dbname);
	
	if ($conn->connect_errno!=0){
		echo "Error: ".$conn->connect_errno;
	}
	else{
			$sql = "SELECT * FROM messages WHERE `steamid`='".$_SESSION['steamid']."' AND `status`='1'";
			$result = $conn->query($sql);

	if ($result->num_rows > 0) {

		$message = null;
		while($row = $result->fetch_assoc()) {
			$message = $row['message'];
			$id = $row['id'];
			$update = "UPDATE messages SET `status`='0' WHERE `id`='".$id."'";
			$rupdate = $conn->query($update);
			break;
		}
		return $message;
		mysqli_close($conn);
	}
	mysqli_close($conn);
	}
}


function getItems(){
	
	include_once "mysql.php";
	$conn = new mysqli($host, $username, $password, $dbname);
	
	if ($conn->connect_errno!=0){
		echo "Error: ".$conn->connect_errno;
	}
	else{
			$sql = "SELECT * FROM currgame";
			$result = $conn->query($sql);

	if ($result->num_rows > 0) {

		$list = array();
		while($row = $result->fetch_assoc()) {
			
			$it = new item($row['item'], $row['price'], $row['color'], $row['itemimg'], $row['username'], $row['avatar'], $row['userid']);
			array_push($list, $it);
		}
		return $list;
		mysqli_close($conn);
	}
	mysqli_close($conn);
	}
}

function getLogs(){
	
	include_once "mysql.php";
	$conn = new mysqli($host, $username, $password, $dbname);
	
	if ($conn->connect_errno!=0){
		echo "Error: ".$conn->connect_errno;
	}
	else{
			$sql = "SELECT * FROM currgame";
			$result = $conn->query($sql);

	if ($result->num_rows > 0) {

		$list = array();
		while($row = $result->fetch_assoc()) {
			
			$it = new item($row['item'], $row['price'], $row['color'], $row['itemimg']);
			array_push($list, $it);
		}
		return $list;
		mysqli_close($conn);
	}
		mysqli_close($conn);
	}
	
}

function logIn(){
	
	include_once "mysql.php";
	$conn = new mysqli($host, $username, $password, $dbname);
	
	if ($conn->connect_errno!=0){
		echo "Error: ".$conn->connect_errno;
	}
	else{
			$sql = "SELECT * FROM users WHERE `steamid`='".$_SESSION['steamid']."'";
			$result = $conn->query($sql);

	if ($result->num_rows == 0){
		
			$insert = "INSERT INTO users (`nick`, `steamid`, `token`, `partnerid`) VALUES ('".$_SESSION['username']."', '".$_SESSION['steamid']."', 'notset', 'notset')";
			$rinsert = $conn->query($insert);
	}
	else{
		while($row = $result->fetch_assoc()) {
			$username = $row['nick'];
			
			if($row['token'] != "noset" && $row['partnerid'] != "noset"){
				$_SESSION['tradeUrl'] = true;
			}
			else{
				$_SESSION['tradeUrl'] = false;
			}
		}
		if($_SESSION['username'] != $username){
			$update = "UPDATE users SET `nick`='".$_SESSION['username']."' WHERE `steamid`='".$_SESSION['steamid']."'";
			$rupdate = $conn->query($update);
		}
	}
			mysqli_close($conn);
	}
}

function hasTradeUrlSet(){
	
	include_once "mysql.php";
	$conn = new mysqli($host, $username, $password, $dbname);
	
	if ($conn->connect_errno!=0){
		return "Error: ".$conn->connect_errno;
	}
	else{
			$sql = "SELECT * FROM users WHERE `steamid`='".$_SESSION['steamid']."'";
			$result = $conn->query($sql);

	if ($result->num_rows == 1){
		
	while($row = $result->fetch_assoc()) {
		if($row['token'] != "noset" && $row['partnerid'] != "noset"){
			$_SESSION['tradeUrl'] = true;
		}
		else{
			$_SESSION['tradeUrl'] = false;
		}
	}
		
		mysqli_close($conn);
	}
	else{
		return "Error: User not found in db(or too many)";
		mysqli_close($conn);
	}
	mysqli_close($conn);
	}
	
	
}

function setTradeUrl($url){
	
	include_once "mysql.php";
	$conn = new mysqli($host, $username, $password, $dbname);
	
	if ($conn->connect_errno!=0){
		return "Error: ".$conn->connect_errno;
	}
	else{
			$sql = "SELECT * FROM users WHERE `steamid`='".$_SESSION['steamid']."'";
			$result = $conn->query($sql);

	if ($result->num_rows == 1){
		
		$parts = parse_url($url);
		$output = [];
		if(!isset($parts['query'])){
			return "Niepoprawny tradeUrl";
				mysqli_close($conn);

		}
		
		parse_str($parts['query'], $output);

		if(!isset($output['token'])){
			return "Niepoprawny tradeUrl";
				mysqli_close($conn);

		}
		if(!isset($output['partner'])){
			return "Niepoprawny tradeUrl";
				mysqli_close($conn);

		}
		
		$token = $output['token'];
		$partnerId = $output['partner'];
		
		$update = "UPDATE users SET `token`='".$token."', `partnerid`='".$partnerId."' WHERE `steamid`='".$_SESSION['steamid']."'";
		$rupdate = $conn->query($update);
		
		return "Update succesfull";
		mysqli_close($conn);
		$_SESSION['tradeUrl'] = true;
	}
	else{
		return "Error: User not found in db(or too many)";
			mysqli_close($conn);

	}
	mysqli_close($conn);
	
	}
	
}

/*function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}*/

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

?>