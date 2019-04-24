<?php
include("scripts/laConfig.php");
include("scripts/phpFunctions.php");
checkCredentials(1);

if(isset($_GET['id'])){
	$operationResult='0';
	if($_GET['id']==-1){ //if id=-1 we create a new user and reload the page with the id of the new UI
		$sql = "INSERT INTO web_users (username, password,email) VALUES ('New user','".password_hash('YouaresoLA',PASSWORD_DEFAULT)."','support@lightact-systems.com') ";
	  	$result = $db->query($sql);
		$sql ='SELECT MAX(id) FROM web_users';
		$result = $db->query($sql);
		if($result){
			$maxID=$result->fetch(PDO::FETCH_ASSOC);
		}
		$sql="UPDATE web_users SET username='New user-".$maxID['MAX(id)']."' WHERE id='".$maxID['MAX(id)']."'";
		$result = $db->query($sql);
		$url=strtok($_SERVER["HTTP_REFERER"],'?');
		header("Location: users.php?id=".$maxID['MAX(id)']); 
	}elseif(isset($_GET['delete'])){ //if 'delete' is set we are deleting the user with _GET[id] and go back to referrer
		$sql="SELECT id FROM web_users WHERE username='".$loggedInUser['username']."'";
		$result = $db->query($sql);
		$result=$result->fetch(PDO::FETCH_ASSOC);
		if($result['id']==$_GET['id']){
			$operationResult='You cannot delete the currently logged-in user.';
		}else{
			$sql="DELETE FROM web_users WHERE id='".$_GET['id']."'";
		  	$result = $db->query($sql);
			$url=strtok($_SERVER["HTTP_REFERER"],'?');
			header('Location: users.php');
		}
	}
	$selectedUser=$_GET['id']; //if ID is valid and delete is not set, we open that user
	if(isset($_POST['role'])){ //First we check if we have to save new User data first
		$sql="SELECT username FROM web_users WHERE username='".$_POST['username']."' AND id!='".$selectedUser."'";
		$result = $db->query($sql);
		if($result->rowCount()>0){//If username already exists we exit
			$operationResult="This username already exists.";
		}else{
			$sql="UPDATE web_users SET username='".$_POST['username']."', first_name='".$_POST['first_name']."', last_name='".$_POST['last_name']."', email='".$_POST['email']."', role='".$_POST['role']."' WHERE id='".$selectedUser."'";
			$result = $db->query($sql);
			$operationResult='1';
			if (!empty($_POST['newpassword'])&&!empty($_POST['retypepassword'])) {//This means the user wants to change the password. We do this separately.
				if($_POST['newpassword']==''){
					$operationResult="New password can't be empty";
				}else{
					if($_POST['newpassword']==$_POST['retypepassword']){
						$sql="UPDATE web_users SET password='".password_hash($_POST['newpassword'],PASSWORD_DEFAULT)."' WHERE id='".$selectedUser."'";
						$result = $db->query($sql);
					}else{
						$operationResult="Passwords don't match";
					}
				}
			}
		}
	}
	
	//we open the user with $selectedUser ID
	$sql="SELECT * FROM web_users WHERE id='".$selectedUser."'";
	$result = $db->query($sql);
	if($result){
		$laUser=$result->fetch(PDO::FETCH_ASSOC);
	}
}else{
	$selectedUser=0; //if ID is not set we don't load any user
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WebUI Users</title>
	<link rel="icon" href="assets/lightact-16.png">
    <link rel="stylesheet" type="text/css" href="css/webui-builder.css">
    <link type="text/css" rel="stylesheet" href="css/jquery-te-1.4.0.css">
    <script src="jquery/jquery-3.2.1.min.js"></script>
    <script src="jquery/jquery-ui.js"></script>
    <script src="scripts/webUIJavascript.js"></script>
</head>

<body>
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

include("scripts/builderSidebar.php");

if($selectedUser!=0){?>
<div class="mainWrapper">
  <div class="builderHeader">Edit user:
  <!--<div class="pageName"><input id="usernameInput" placeholder="Type in the username..." type="text" value="<?php //echo $laUser['username']; ?>"></div>-->
  </div>


<div class='userForm'>
<form action="users.php?id=<?php echo $selectedUser;?>" method="post" name="Change password">
    Username: <br><input type="text" name="username" value="<?php echo $laUser['username']; ?>"><br>
    First name: <br><input type="text" name="first_name" value="<?php echo $laUser['first_name']; ?>"><br>
    Last name: <br><input type="text" name="last_name" value="<?php echo $laUser['last_name']; ?>"><br>
    E-mail: <br><input type="text" name="email" value="<?php echo $laUser['email']; ?>"><br>
    Role: <br>
    <select name="role" <?php if($loggedInUser['id']==$laUser['id']):?> disabled <?php endif ?>>
    	<option value="0" <?php if($laUser['role']=='0'): ?>selected <?php endif ?>>Administrator</option>
        <option value="1" <?php if($laUser['role']=='1'): ?>selected <?php endif ?>>User</option>
    </select><br>
<!--    Old password: <br><input type="password" name="oldpassword" value="<?php //echo $laUser['password']; ?>" placeholder="Leave empty if you don't want to change password..."><br>-->
    New password: <br><input type="password" name="newpassword" placeholder="Leave empty if you don't want to change your password..."><br>
    Re-type new password: <br><input type="password" name="retypepassword" placeholder="Leave empty if you don't want to change your password..."><br>
    <input type="submit" name="submit" value="Save">
</form>
<div class="deleteUser"><a href="users.php?id=<?php echo $selectedUser?>&delete=yes">Delete this User</a></div>
</div>

<?php
}else{
	//echo "<div class='pickUIBuilder'>Pick a user on the left or create a new one.</div>";
}?>
</body>
</html>