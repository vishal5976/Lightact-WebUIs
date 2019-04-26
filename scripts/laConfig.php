<?php
//Main database access configuration. Is included in most of the other php files.

//default database access credentials.
$servername = "laweb";
$username = "Lightact";
$password = "YouaresoLA";
$loggedInUser;

//open connection to MySQL database 'lightact'
try {
    $db = new PDO("mysql:host=$servername;dbname=lightact", $username, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
//****	

//check if a password is set and if it's not we set it to 'YouaresoLA'

$sql="SELECT * FROM web_users";

$result = $db->query($sql);
if($result){
	$loggedInUser=$result->fetch(PDO::FETCH_ASSOC);
}
if(!$loggedInUser){
	$sql="INSERT INTO web_users (username,password,first_name,last_name,role,email) VALUES ('Lightact','".password_hash('YouaresoLA',PASSWORD_DEFAULT)."','Lightact','Systems','0','support@lightact-systems.com')";
	$result = $db->query($sql);
}

?>