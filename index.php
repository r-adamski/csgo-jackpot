<?php
	session_start();
	include "functions.php";
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Csgoeasypot.com</title>
	<meta name="description" content="" />
	<meta name="keywords"  content="" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="stylesheet" style="text/css" href="style.css">
	<script src="js/jquery-2.1.4.js" type="text/javascript"></script>
	<script src="js/jquery.lazyload.js" type="text/javascript"></script>
	<script src="js/jquery.easing.js" type="text/javascript"></script>
	<script src="js/script.js" type="text/javascript"></script>
	<script src="js/socket.io-client/socket.io.js" type="text/javascript"></script>
	<script src="//cdn.jsdelivr.net/velocity/1.2.3/velocity.min.js"></script>
	<script>
		var socket = io('http://149.202.48.53:3000');
	</script>
  
</head>

<body onload="loading();">

<!-- bg - lazyloading -->
	<div class="background">
		<img class="lazy" data-original="images/bg.jpg">
	</div>

 <!-- Menu -->
<div class="menu">


<!-- logo -->
<a href="home">
<img class="logo" src="images/logo.jpg" alt="Csgoeasypot.pl" />
</a>

<!-- steam login -->
<?php

require 'openid.php';
$_STEAMAPI = "C1C8F66C010C212394F6775C85BE2911";
try 
{
    $openid = new LightOpenID('http://csgoeasypot.com/home');
    if(!$openid->mode)
    {
        if(isset($_GET['login'])) 
        {
            $openid->identity = 'http://steamcommunity.com/openid/?l=english';
            header('Location: ' . $openid->authUrl());
        }
	if(!isset($_SESSION['logged'])){
	?>	
		<a href="?login"><img class="steamlogin" src="images/login.png" /></a>
	<?php
	}
		else if($_SESSION['logged'] == false){
?>

<a href="?login"><img class="steamlogin" src="images/login.png" /></a>

<?php
	}
	else{
		?>
		
		<div class="account">
			<img src="<?php echo $_SESSION['avatar']; ?>" />
			<div class="wrap">
			<div class="settings">
				Ustawienia
			</div>
			<a href="logout.php"><div class="logout">
				Wyloguj
			</div></a>
			</div>
		</div>
		
		<?php
	}
    } 
    elseif($openid->mode == 'cancel') 
    {
        
    } 
    else 
    {
        if($openid->validate()) 
        {
                $id = $openid->identity;
                // identity is something like: http://steamcommunity.com/openid/id/76561197960435530
                // we only care about the unique account ID at the end of the URL.
                $ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
                preg_match($ptn, $id, $matches);
                echo "User is logged in (steamID: $matches[1])\n";

                $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$_STEAMAPI&steamids=$matches[1]";
                $json_object= file_get_contents($url);
                $json_decoded = json_decode($json_object);
				
                foreach ($json_decoded->response->players as $player)
                {
					$_SESSION['logged'] = true;
					$_SESSION['steamid'] = $player->steamid;
					$_SESSION['username'] = $player->personaname;
					$_SESSION['profileurl'] = $player->profileurl;
					$_SESSION['avatar'] = $player->avatarmedium;
                }
				logIn();
				header("Location: home");
        } 
        else 
        {
                echo "User is not logged in.\n";
        }
    }
} 
catch(ErrorException $e) 
{
    echo $e->getMessage();
}

?>
<!-- buttons -->
<ul class="menu">
	<li><a href="home">Home</a></li>
	<li><a href="howtoplay">Jak grać?</a></li>
	<li><a href="rules">Regulamin</a></li>
</ul>

<!-- alerts -->
<div class="alerts">
	<script>
	var shownext = true;
			function updateAlerts(){
				if(shownext == true){
			
				var div = document.getElementsByClassName("alerts")[0];
				
				$.getJSON( "http://csgoeasypot.com/getMessage.php", {} )
				.done(function( json ) {
					
					var msg = json['msg'];
					if(msg != null){
						div.innerHTML = msg;
						$(".alerts").fadeIn(100);
						shownext = false;
					}
					
				})
				.fail(function( jqxhr, textStatus, error ) {
					var err = textStatus + ", " + error;
					console.log( "Request Failed: " + err );
				});
			}
				
			setTimeout(updateAlerts, 1500);
			}
		updateAlerts();
	</script>
</div>

</div>

<!-- settings -->
<div class="shadow">
</div>
<div class="settingsdiv">
	    <form>
			<button type="button" class="save">Zapisz</button>
			<input type="text" placeHolder="Podaj swój tradeUrl" name="tradeurl" autocomplete="off" class="tradeurl"><br>
			<div class="out"></div>
		</form>
</div>

<!-- chat -->
<div class="chatwrapper">
	<div class="chat">
		Chat
	</div>
	<div class="messages">
		<!-- odbieranie socketow i wyswietlanie -->
		<script>
		var divMsg = document.getElementsByClassName("messages")[0];
		
		socket.on('newChatMsg', function(data){
			var before = divMsg.innerHTML;
			divMsg.innerHTML = before + "<div class='singlemsg'><span style='color: #c93838'>"+data.nick+"</span> : "+data.msg+"</div>";
			$(".messages").animate({ scrollTop: $(document).height() }, 0.2);
		});
		
		</script>
	</div>
	<?php
		if(isset($_SESSION['logged']) && $_SESSION['logged'] ==  true){
	?>
	<div class="inputmsg">
		<form>
			<input id="insertmsg" autocomplete="off" type="text" placeHolder="Wiadomość" name="message">
			<button type="button" class="send" onclick="sendMsg()">	-> </button>
		</form>
	</div>
	<?php
		}
		else{
			?>
			<div class="needlogin">
				Musisz być zalogowany!
			</div>
			<?php
		}
	?>
</div>
<!-- wrapper -->
<div class="wrapper">

<?php
if(isset($_GET['id'])) {
  $id = $_GET['id'];
}
else $id=0;

switch($id) {
  case 0:
    include "home.php";
    break;
  case 1:
    include "rules.php";
    break; 
  case 2:
    include "howtoplay.php";
    break;  
}
?>


</div>


</body>
</html>