<!-- Players -->
<div class="players">
	<!-- <div class="card"><img class="avatar" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/7d/7dca36a27f002495dbab01ae1559345b27de2d53_full.jpg" /></div>
	-->
	<canvas id="canvas" width="1155" height="80">   </canvas>
	<div class="pointer"></div>

	<script>
	init();
	
		socket.on('startLottery', function(msg){
			console.log('zaczynac! winner:'+msg.winner);
			setWinner(msg.winner);
		});
	
		socket.on('addUser', function(msg){
			console.log('addUser!'+msg.avatar+" : "+msg.id);
			addUser(msg.avatar, msg.id);
		});
	
	</script>
	
</div>
<!-- Progress -->
<div class="progress">

<!-- Progress Bar -->
<div class="progressbar">
</div>

<div class="skinsinfo">
		<script>
			function updateSkinsInfo(){
				var div = document.getElementsByClassName("skinsinfo")[0];
				
			$.getJSON( "http://csgoeasypot.com/getSkins.php", {} )
				.done(function( json ) {
					
					var amount = json.length;
					var value = 0;
					
					for(var i=0 ; i < amount ; i++){
						value += Number(json[i].price);
					}
					
					if(value != 0){
						value = value.toFixed(2);
						div.innerHTML = "Skinów: " + amount + "/13 <span class='cash'>"+value+"€</span>";
					}
					else{
						div.innerHTML = "Skinów: " + amount + "/13";
					}
				})
				.fail(function( jqxhr, textStatus, error ) {
					var err = textStatus + ", " + error;
					console.log( "Request Failed: " + err );
					div.innerHTML = "Skinów: 0/13";
				});
				
				setTimeout(updateSkinsInfo, 1000);
			}
		updateSkinsInfo();
	</script>
</div>
<div class="percent">
</div>
<div class="time">
	<script>
		var div = document.getElementsByClassName("time")[0];
		socket.on('coutDown', function(data){
			div.innerHTML = data.time+"s";
			if(data.time == 1){
				setTimeout(function(){
					div.innerHTML = "";
				}, 1000);
			}
		});
		
	</script>
</div>
<div class="playersinfo">
		<script>
			function updatePlayersInfo(){
				var div = document.getElementsByClassName("playersinfo")[0];
				var perc = document.getElementsByClassName("percent")[0];
				var bar = document.getElementsByClassName("progressbar")[0];
				var list = [];
				
				$.getJSON( "http://csgoeasypot.com/getSkins.php", {} )
				.done(function( json ) {
					
					for(var i = 0; i < json.length ; i++){
						var id = json[i].userid;
						
						var contains = false;
						for(var w = 0; w < list.length ; w++){
							if(list[w] == id){
								contains = true;
							}
						}
						
						if(!contains){
							list.push(id);
						}
					}
					
					var value1 = (json.length)/13;
					var value2 = (list.length)/3;
					
					var per = ((value1 + value2)/2)*100;
					
					if(per > 100){
						per = 100;
					}
					
					//update % tez tutaj
					perc.innerHTML = parseInt(per)+"%";
					var widthstr = String(parseInt(per))+"%";
					bar.style.width = widthstr;
					div.innerHTML = "Graczy: " + list.length + "/3";
				})
				.fail(function( jqxhr, textStatus, error ) {
					var err = textStatus + ", " + error;
					console.log( "Request Failed: " + err );
					div.innerHTML = "Graczy: 0/3";
				});
				
				setTimeout(updatePlayersInfo, 1000);
			}
		updatePlayersInfo();
	</script>
</div>

</div>

<!-- Skins -->
<div class="skins">
	<div class="container">
		
		
		<script>
		
		function hexToRgb(hex) {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function(m, r, g, b) {
        return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}
		
		function updateSkins(){
			$.getJSON( "http://csgoeasypot.com/getSkins.php", {} )
				.done(function( json ) {
					
					var div = document.getElementsByClassName("container")[0];
					var str = "";
					
					for(var i = 0; i < json.length ; i++){
						var name = json[i].name;
						var color = json[i].color;
						var url = json[i].url;
						
						var rgb = hexToRgb(color);
						
						str = str + "<div class='item'>	<img src="+url+"/> <hr style='background-image: linear-gradient(to right, rgba("+rgb.r+", "+rgb.g+", "+rgb.b+", 0), rgba("+rgb.r+", "+rgb.g+", "+rgb.b+", 0.75), rgba("+rgb.r+", "+rgb.g+", "+rgb.b+", 0))'> <div class='name'>"+name+"</div></div> ";
						
					}
					div.innerHTML = str;
				})
				.fail(function( jqxhr, textStatus, error ) {
					var err = textStatus + ", " + error;
					console.log( "Request Failed: " + err );
				});
			
			
			
			setTimeout(updateSkins, 1000);
		}
		updateSkins();
		
		</script>
		
	</div>
		
</div>

<!-- logs -->
<div class="logwrapper">
	<div class="deposit">
		
		<div class="depositmsg">Postaw Skiny!</div>
	</div>
	<div class="log">
	
	</div>
	
	
			<script>
		
		function updateLogs(){
			$.getJSON( "http://csgoeasypot.com/getSkins.php", {} )
				.done(function( json ) {
					
					var div = document.getElementsByClassName("log")[0];
					var str = "";
					
					var list = [];
					
					for(var i = 0; i < json.length ; i++){
						var avatar = json[i].avatar;
						var price = +json[i].price;
						var id = json[i].userid;
						var username = json[i].username;
						
						
						var contains = false;
						var cprice;
						var camount;
						for(var w = 0; w < list.length ; w++){
							if(list[w].id == id){
								contains = true;
								cprice = list[w].price;
								camount = list[w].amount;
								list.splice(w, 1);
							}
						}
						
						if(contains){
							cprice += price;
							camount += 1;
							list.push({
								id: id, avatar: avatar, price: cprice, amount: camount, username: username
							});
						}
						else{
							list.push({
								id: id, avatar: avatar, price: price, amount: 1, username: username
							});
						}
						
					}
					
					for(var i=0; i < list.length ; i++){
						var avatar = list[i].avatar;
						var price1 = list[i].price;
						var price = price1.toFixed(2);
						var amount = list[i].amount;
						var username = list[i].username;
						
						str = str +	"<div class='singlelog'><img src='"+avatar+"' /><span class='norm'><span class='imp'>"+username+"</span> postawił<span class='imp'>"+amount+"</span> skin(ów) o wartości<span class='imp'>"+price+"€</span></span></div>";
					}
					
					div.innerHTML = str;
					
				})
				.fail(function( jqxhr, textStatus, error ) {
					var err = textStatus + ", " + error;
					console.log( "Request Failed: " + err );
				});
			
			setTimeout(updateLogs, 1000);
		}
		updateLogs();
		
		</script>
	
</div>

<!-- additional content -->
<div class="ad">

</div>