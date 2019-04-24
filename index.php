<?php 
	include("scripts/phpFunctions.php");
	include("scripts/laConfig.php");
	require_once "./JBBCode/Parser.php";
	include ("scripts/bbCodesForIndex.php");

checkCredentials(0);
$jsString='';

//Check which WebUI we want to use.	
if(isset($_GET['id'])){
	$selectedUI=$_GET['id'];
	$sql="SELECT * FROM web_uis WHERE id='".$selectedUI."'";
	$result = $db->query($sql);
	if($result){
		$laUI=$result->fetch(PDO::FETCH_ASSOC);
	}
	$usersWithAccess=json_decode($laUI['users']);
	if($loggedInUser['role']!=='0'){
		if(is_null($usersWithAccess)){
			header('Location: login.php?message=You don\'t have access to this content.');
		}elseif(!in_array($loggedInUser['id'],$usersWithAccess)){
			header('Location: login.php?message=You don\'t have access to this content.');
		}
	}
}
	



?>
<!DOCTYPE html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>Lightact WebUI</title>
    <link rel="icon" href="assets/lightact-16.png">
	<link href="jquery/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/webui-index.css">
    <script src="jquery/jquery-3.2.1.min.js"></script>
    <script src="jquery/external/jquery/jquery.js"></script>
	<script src="jquery/jquery-ui.js"></script>
    <script src="scripts/jquery.ui.touch-punch.min.js"></script>
    <script src="scripts/webUIJavascript.js"></script>
    
<?php

if(isset($_GET['id'])){

if ($laUI['styling']==''){
      $pagebg='F6F6F6';
      $pagemargintop='0';
      $pagemarginleft='0';
      $bodybg='fff';
      $bodytext='000';
      $sidebaryesno='';
	  $headerimage='';
}else{
	  $stylingJson=json_decode($laUI['styling'],true);
	  $pagebg=$stylingJson['pageBackground'];
      $pagemargintop=$stylingJson['pagemrgtop'];
      $pagemarginleft=$stylingJson['pagemrglft'];
      $bodybg=$stylingJson['bodybg'];
      $bodytext=$stylingJson['bodytext'];
      $sidebaryesno=$stylingJson['sidebar'];
  	  $headerimage=$stylingJson['headerimage'];
}
 
$uipassword=''; //for now this is always empty

if ($sidebaryesno=='1'){
	$mainWidth=70;
}else{
	$mainWidth=100;
}

echo "<style>
	.la-main {
		width:".$mainWidth."%; 
		color: ".$bodytext."; 
		background-color: ".$bodybg.";'
	}
	</style>";

echo "</head> <body style='margin:".$pagemargintop."px ".$pagemarginleft."px; background-color: ".$pagebg.";'>
<div id='notificationDiv'>
Notifications of command database entries will appear here.
</div>";



if($headerimage!=''){
	echo "<div id=\"header\"><div id=\"logo\"><img src=\"".$headerimage."\"></div></div>";
}

	
echo "<div class='la-main' id='la-main'>";

$parser->parse(insertVariableValues(urldecode($laUI['page'])));
print $parser->getAsHtml();
echo "</div>";

if ($sidebaryesno=='1'){
echo "<div class='la-sidebar' style='width:".(95-$mainWidth)."%; background-color: ".$bodybg.";  color: ".$bodytext."; '>";

echo "<div id=\"commandStackWrapper\"><h3>Current Command Stack</h3><div id=\"command-stack-contents\"></div><div id=\"empty-command-stack\"><button id=\"empty-commands-button\" onclick=\"getCommandsAndVariables('emptyCommands')\">Empty Command Stack</button></div>
</div><div id='variablesWrapper'><h3>Current Variables</h3><div id='variables-values'></div><span id='updateCountdown' style='font-style:italic; font-size:12px;'>Next refresh in 7s.</span></div>";

echo "</div>";
}

echo "<div class='footer'>Logged in as ".$loggedInUser['username']." | <a href='index.php'>All WebUIs</a> | <a href='builder.php'>WebUI Builder</a>| <a href='logout.php'>Logout</a></div>";


echo '<script>'.$jsString.'
$( "#empty-commands-button" ).button();';

if ($sidebaryesno=='1'){
echo '$(document).ready(function() {
	getCommandsAndVariables(\'getAll\');
	updateCountdown();
});';
}
echo '</script>';

}else{//IF $_GET['id'] IS NOT SET WE DISPLAY A LIST OF ALL WEB-UIS THE USER HAS ACCESS TO
$sql="SELECT id, name, users FROM web_uis";
$result = $db->query($sql);

if($result){
	echo "</head> 
	<body style='background-color: #151619;'>";
	if(isset($_GET['message'])){
		echo "<div id='notificationDiv'>Notifications of command database entries will appear here.</div>";
		echo "<script> $(function(){
		  document.getElementById(\"notificationDiv\").innerHTML = \"".$_GET['message']."\";
		  newNotification(2);
	  });
	  </script>";	
	}
	
	echo "<div class='indexLogo'><img src='assets/lightAct-iconJustText.png'></div>
		<div class='indexTitle'><div class='loginInfo'>Hello ".$loggedInUser['username']."!</div>Your WebUIs</div>
		<div class='listOfAllUIs'>
		<ul>";
		$j=0;
	for($i=0;$i<$result->rowCount();$i++){
		$allUIs=$result->fetch(PDO::FETCH_ASSOC);
		$usersWithAccess=json_decode($allUIs['users']);
		if($loggedInUser['role']=='0'){
			if($j==0){
				echo "<li class='webUILinks' style='border:none;'><a href='index.php?id=".$allUIs['id']."'>".$allUIs['name']."</a></li>";
			}else{
				echo "<li class='webUILinks'><a href='index.php?id=".$allUIs['id']."'>".$allUIs['name']."</a></li>";
			}
			$j++;
		}elseif(!is_null($usersWithAccess)){
			if(in_array($loggedInUser['id'],$usersWithAccess)){	
				if($j==0){
					echo "<li class='webUILinks' style='border:none;'><a href='index.php?id=".$allUIs['id']."'>".$allUIs['name']."</a></li>";
				}else{
					echo "<li class='webUILinks'><a href='index.php?id=".$allUIs['id']."'>".$allUIs['name']."</a></li>";
				}
				$j++;
			}
		}
		
	}
	echo "</ul></div>";
	echo "<div class='builderLink'>";
	if($loggedInUser['role']=='0'){
		echo "<a href='builder.php'><i>WebUI Builder</i></a> | ";
	}
	echo "<a href='logout.php'><i>Logout</i></a></div>";
}
}
?>

</body>
</html>
