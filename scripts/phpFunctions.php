<?php

function checkCredentials($level){
	//$level 0 - front end, $level 1-backend
	global $db;
	global $loggedInUser;

	if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
		$sql="SELECT * FROM web_users WHERE username='".$_COOKIE['username']."'";
		$result = $db->query($sql);
		if($result){
			$loggedInUser=$result->fetch(PDO::FETCH_ASSOC);
		}
		if($loggedInUser){    
			if ($_COOKIE['password'] != password_hash($loggedInUser['password'],PASSWORD_DEFAULT)) {    
			}
			if ($level==1 && $loggedInUser['role']=='1'){
				header('Location: login.php?message=You don\'t have access to this content.');
			}
				
		}
    
	} else {
	    header('Location: login.php');
	}
}

function generateRandomString($length = 10) {
//Create unique random string that gets appended to element id.
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

function insertVariableValues($shortcodeText){
//Insert variable values read from DB into the shortcodes
	global $db;
	preg_match_all('/varname="(.*?)"/',$shortcodeText,$matches,PREG_OFFSET_CAPTURE);
	$insertedStringLength=0;
	for($i=0;$i<count($matches[0]);$i++){
		$sql="SELECT value FROM web_variables WHERE name='".$matches[1][$i][0]."'";
		$result = $db->query($sql);
		if($result){
			$varValue=$result->fetch(PDO::FETCH_ASSOC);
		}
		$insertedString="value=\"".$varValue['value']."\" ";
		$shortcodeText=substr_replace($shortcodeText,$insertedString,$matches[0][$i][1]+$insertedStringLength,0);
		$insertedStringLength+=strlen($insertedString);
	}
	return $shortcodeText;
}

function buildButtonJS(){
//Create JS string that reads the {parameters} from shortcodes
$jsString='
<script>
if({Hide} != 1){
	document.getElementById("{id}-button").innerHTML = `<div class="elementWrapper">
	<button id="{id}" onClick="sendCommand(\'{command}\')">{label}</button>
</div>`;
}
$( "#{id}" ).button();
</script>';

return $jsString;
}

function buildSliderJS(){
//Create JS string that reads the {parameters} from shortcodes
$jsString='
<script>
if({Hide} != 1){
	document.getElementById("{id}-slider").innerHTML = `<div class="elementWrapper"><div class="slider-wrapper"><div id="{id}"class="slider-with-handle"><div id="{id}-handle"class="ui-slider-handle"></div></div></div></div>`;
}
$( function() {
	var handle = $("#{id}-handle" );
    $( "#{id}" ).slider({
		max: {max},
		min: {min},
		value: {value},
		create: function() {
        	handle.text( $( this ).slider( "value" ) );
      	},
      	slide: function( event, ui ) {
        	handle.text( ui.value );			
      	},
		change: function(event,ui){
			setVariable("{varname}",+$( this ).slider( "option", "value" ));
		}
    });
} );
</script>
';

return $jsString;
}

function buildColorJS(){
//Create JS string that reads the {parameters} from shortcodes
	$jsString='$( function() {
	function hexFromRGBA(r, g, b, a) {
      var hex = [
        r.toString( 16 ),
        g.toString( 16 ),
        b.toString( 16 ),
		a.toString( 16 )
      ];
      $.each( hex, function( nr, val ) {
        if ( val.length === 1 ) {
          hex[ nr ] = "0" + val;
        }
      });
      return hex.join( "" ).toUpperCase();
    }
    function hexFromRGB(r, g, b) {
      var hex = [
        r.toString( 16 ),
        g.toString( 16 ),
        b.toString( 16 )
      ];
      $.each( hex, function( nr, val ) {
        if ( val.length === 1 ) {
          hex[ nr ] = "0" + val;
        }
      });
      return hex.join( "" ).toUpperCase();
    }
	
	function RGBAFromHex(hex){
		var r= parseInt(hex.substring(0,2),16);
		if(isNaN(r)){
			r=0;
		}
		var g= parseInt(hex.substring(2,4),16);
		if(isNaN(g)){
			g=0;
		}
		var b= parseInt(hex.substring(4,6),16);
		if(isNaN(b)){
			b=0;
		}
		var a= parseInt(hex.substring(6,8),16);
		if(isNaN(a)){
			a=0;
		}
		return [r, g, b, a];
		
	}
	function RGBFromHex(hex){
		if (hex.lastIndexOf("#") > -1) {
        	hex = hex.replace(/#/, "0x");
    	} else {
        	hex = "0x" + hex;
    	}
		var r = hex >> 16;
		var g = (hex & 0x00FF00) >> 8;
		var b = hex & 0x0000FF;
		return [r, g, b];
	}
	
    function refreshSwatch() {
		var red = $( "#{id}-red" ).slider( "value" ),
		green = $( "#{id}-green" ).slider( "value" ),
		blue = $( "#{id}-blue" ).slider( "value" ),
		alpha = $( "#{id}-alpha" ).slider( "value" ),
		hex = hexFromRGBA( red, green, blue, alpha );
		$( "#{id}-swatch" ).css( "background-color", "#" + hex );
		$( "#{id}-swatch" ).css( "opacity", alpha/255 );
		document.getElementById("{id}-swatch-label").innerHTML="#"+hex+"("+red+","+green+","+blue+","+alpha+")";
		return hex;
    }
	rgba=RGBAFromHex("{value}");

	$("#{id}-red").slider({value: rgba[0]});
	$("#{id}-green").slider({value: rgba[1]});
	$("#{id}-blue").slider({value: rgba[2]});
	$("#{id}-alpha").slider({
		value: rgba[3],
		create: function(event,ui){
			$( "#{id}-swatch" ).css( "background-color", "#{value}" );
			$( "#{id}-swatch" ).css( "opacity", rgba[3]/255 );
			document.getElementById("{id}-swatch-label").innerHTML="#{value} ("+rgba[0]+","+rgba[1]+","+rgba[2]+","+rgba[3]+")";
	  	}
		
	});
 
    $( "#{id}-red, #{id}-green, #{id}-blue, #{id}-alpha" ).slider({
      orientation: "horizontal",
      range: "min",
      max: 255,
      slide: refreshSwatch,
      change: function(event,ui){
		  hex=refreshSwatch();
		  setVariable("{varname}",hex);
	  },
    });
  } );';
  return $jsString;
}

function buildSpinnerJS(){
//Create JS string that reads the {parameters} from shortcodes
$jsString='
<script>
if({Hide} != 1){
	document.getElementById("{id}-spinner").innerHTML = `<div class="elementWrapper"><input id="{id}" value="{value}"></div>`;
}
$( "#{id}" ).spinner({
	change: function(event,ui){
		setVariable(\'{varname}\',$( this ).spinner( "value" ));
	}
});
</script>
';
return $jsString;
}

?>