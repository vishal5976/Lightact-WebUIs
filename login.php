<?php
include("scripts/laConfig.php");

if (isset($_POST['username']) && isset($_POST['password'])) {
	$sql="SELECT * FROM web_users WHERE username='".$_POST['username']."'";
	$result = $db->query($sql);
	if($result){
		$laUsers=$result->fetch(PDO::FETCH_ASSOC);
	}
	if($laUsers){
		if (($_POST['username'] == $laUsers['username']) && password_verify($_POST['password'],$laUsers['password'])) {    
			if (isset($_POST['rememberme'])) {
				/* Set cookie to last 1 year */
				setcookie('username', $_POST['username'], time()+60*60*24*365);
				//setcookie('password', md5($_POST['password']), time()+60*60*24*365);
				setcookie('password', $laUsers['password'], time()+60*60*24*365); //NOT SAFE VERSION
			} else {
				/* Cookie expires when browser closes */
				setcookie('username', $_POST['username'], false);
				//setcookie('password', md5($_POST['password']), false);//NOT SAFE VERSION
				setcookie('password', $laUsers['password'], false);
			}
			header('Location: index.php');
		} else {
			$operationResult= 'Username and password invalid.';
		}
	}else{
		$operationResult= "No user found with this username.";
	}
	
} else {
	//header('Location: login.php');
	isset($_GET['message']) ? $operationResult=$_GET['message'] : $operationResult='';
}	
?>
<html>
<head>
    <meta charset="utf-8">
    <title>WebUI Login</title>
    <link rel="icon" href="assets/lightact-16.png">
    <link rel="stylesheet" type="text/css" href="css/webui-index.css">
    <link type="text/css" rel="stylesheet" href="css/jquery-te-1.4.0.css">
    <script src="jquery/jquery-3.2.1.min.js"></script>
    <script src="jquery/jquery-ui.js"></script>
    <script src="scripts/webUIJavascript.js"></script>
</head>
<body style="background-color:#151619;">
<div id="notificationDiv">
Various notifications will appear here.
</div>
<?php 
if(!empty($operationResult)){ 
	if ($operationResult=='1'){
	  echo "<script> $(function(){
		  document.getElementById(\"notificationDiv\").innerHTML = \"User saved.\";
		  newNotification(1);
	  });
	  </script>";
	}else{
	  	echo "<script> $(function(){
		  document.getElementById(\"notificationDiv\").innerHTML = \"".$operationResult."\";
		  newNotification(2);
	  });
	  </script>";	
	}
}
?>
<div class='indexLogo'><img src='assets/lightAct-iconJustText.png'></div>
  <div class='indexTitle'><a href="login.php">WebUI Login</a></div>
  <div class='listOfAllUIs'>
  <form name="login" method="post" action="login.php">
   Username:<br><input type="text" name="username"><br>
   Password:<br><input type="password" name="password"><br>
   Remember me: <input type="checkbox" name="rememberme" value="1"><br>
   <input type="submit" name="submit" value="Login!">
  </form>
  </div>
</body>
</html>