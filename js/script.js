function loading(){
	$("img.lazy").lazyload({ effect : "fadeIn"});
}

function sendMsg(){
	var msg = $("#insertmsg").val();
	
	if(msg != ""){
		$("#insertmsg").val("");
		
		$.get("getUserInfo.php", {
			
		},
        function(data) {
            var nick = data.nick;
			var steamid = data.id;
			socket.emit('chatMsg', { nick: nick, id: steamid, msg: msg });
        });
		
	}
}

/*  settings */
$(document).ready(function( e ) {
    $(".settings").click(function() {
		$(".shadow").fadeIn(300);
		$(".settingsdiv").fadeIn(300);
      });
    $(".shadow").click(function() {
		$(".shadow").fadeOut(200);
		$(".settingsdiv").fadeOut(200);
      });
	  
	$(".save").click(function() {
        parent = $(this).parent();
        url = $(parent).children("input[name='tradeurl']").val();
        $.get("updateTradeUrl.php", {
            url : url,
        },
        function(data) {
            $(parent).children(".out").html(data);
        });
    });
	
       $("form").submit(function (e) {
            e.preventDefault();
        });
	
	$("#insertmsg").keyup(function(event){
		if(event.keyCode == 13){
			$(".send").click();
			return false;
		}
	});
	
	var toggle = false;
	$(".chat").click(function() {
		if(toggle == false){
			//show
			toggle = true;
			$(".chatwrapper").velocity({ bottom: "0px" }, 300, "easeOutBounce");
		}
		else{
			//hide
			toggle = false;
			$(".chatwrapper").velocity({ bottom: "-270px" }, 400, "easeOutBounce");
		}
    });
	  
	  
	$(".depositmsg").click(function() {
		
			$.getJSON("http://csgoeasypot.com/depositClick.php", {} )
				.done(function(data) {
					    var has_trade_url = data['hasTradeUrl'];
						var is_logged = data['isLogged'];
			
					if(is_logged === "false"){
						window.location="http://csgoeasypot.com/?login";
					}
					else if(has_trade_url === "false"){
						//ustaw trade url
					}
					else{
						var redirectWindow = window.open('https://steamcommunity.com/tradeoffer/new/?partner=228380003&token=oDdlz6am', '_blank');
						redirectWindow.location;
					}
					
				})
				.fail(function( jqxhr, textStatus, error ) {
					var err = textStatus + ", " + error;
					console.log( "Request Failed: " + err );
				});
		
    });
});

/* loterry */

var c, ctx, raf;
var xoffset = 0;
var speed = 8;
var winner = 0;
var t = 3;
var a = 0;
var images = [];

function setWinner(id){
	console.log("setting winner");
	//winner - his steamid
	winner = id;
	speed = speed * 0.8;
}

function init(){
	console.log('init');
	
	c = document.getElementById("canvas");
	ctx = c.getContext("2d");
	
				var list = [];
				
				$.getJSON( "http://csgoeasypot.com/getSkins.php", {} )
				.done(function( json ) {
					
					for(var i = 0; i < json.length ; i++){
						var id = json[i].userid;
						var url = json[i].avatar;
						
						var contains = false;
						for(var w = 0; w < list.length ; w++){
							if(list[w].id == id){
								contains = true;
							}
						}
						
						if(!contains){
							list.push({id: id, avatar: url});
						}
					}
					
					for(var i=0 ; i < list.length ; i++){
						addUser(list[i].avatar, list[i].id);
					}
					
				})
				.fail(function( jqxhr, textStatus, error ) {
					var err = textStatus + ", " + error;
					console.log( "Request Failed: " + err );
				});
	
	
	setTimeout(function(){
	start();
	}, 2000);
}

function addUser(url, steamid){
	var contains = false;
	for(var i=0 ; i < images.length ; i++){
		if(images[i].id == steamid){
			contains = true;
		}
	}
	
	var img = new Image();
	img.src = url;
	img.onload = function(){
		
		var i = {
			obj: img,
			id: steamid
		};
		if(contains == false){
		images.push(i);
		}
	}
}

function start(){
	raf = window.requestAnimationFrame(keyframes);
}

var lastCalledTime;
var fps;
var timeleft = 0;
var speed0 = null;

function keyframes(){
	var stopit = false;
	
  if(lastCalledTime){
	delta = (new Date().getTime() - lastCalledTime)/1000;
	fps = 1/delta;
	if(a != 0){
		var tnow = Date.now();
		timeleft += tnow - lastCalledTime;
	}
  }
    lastCalledTime = Date.now();
	
	if(a != 0){
		if(speed0 == null){
			speed0 = speed;
		}
		
		speed = speed0-a*(timeleft/1000);
		//console.log(speed+" : "+speed0+" : "+timeleft);
		if(speed <= 0){
			a = 0;
			speed = 0;
			stopit = true;
			setTimeout(function(){
				ctx.clearRect(0, 0, c.width, c.height);
				winner = 0;
				images = [];
				xoffset = 0;
				speed = 10;
				timeleft = 0;
				speed0 = null;
				init();
			}, 5000);
		}
	}
	
	ctx.clearRect(0, 0, c.width, c.height);
	
	var dv = images.length * 90;
	if(dv != 0){
		xoffset = -(-(xoffset - speed) % dv);
	}
	draw(xoffset);
	if(!stopit){
		raf = window.requestAnimationFrame(keyframes);
	}
}
	
function draw(x1){
	for(var idx=0 ; idx < images.length ; idx++) {
			var image = images[idx];
			
			if(image.id == winner){
				if(a == 0){//25 65
					if(x1 >= (c.width/2-65) && x1 <= (c.width/2-25)) {
						console.log('zatrzymuje');
						//var s = x1 - (c.width/2) - 30;
						a = 1000;
					}
				}
			}
			
            ctx.drawImage(image.obj, x1, 0, 80, 80);

            x1 += 90;

            if(x1 > c.width) {
                return;
            }
			
		}
		
		if(x1 < c.width && images.length > 0) {
            draw(x1);
        }
	}
	