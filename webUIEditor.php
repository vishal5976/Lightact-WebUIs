<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="us">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Lightact WebUI Builder</title>
	<link href="jquery/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/webui-builder.css">
    <script src="jquery/jquery-3.2.1.min.js"></script>
    <script src="jquery/external/jquery/jquery.js"></script>
	<script src="jquery/jquery-ui.js"></script>
    <script src="scripts/webUIJavascript.js"></script>
    <?php 
	include("scripts/phpFunctions.php");
	include("scripts/laConfig.php");
	//require __DIR__ . '/vendor/autoload.php';
	//open database connection to sqlite database
	//$db = new PDO('sqlite:laTestDatabase.s3db');
	
	//open connection to MySQL database 'lightact'
	try {
    	$db = new PDO("mysql:host=$servername;dbname=lightact", $username, $password);
	    // set the PDO error mode to exception
    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "Connected successfully"; 
	  	}
 	 catch(PDOException $e)
		  {
		  echo "Connection failed: " . $e->getMessage();
		  }
	  //****	

	if(isset($_POST['uiName'])){
		$sql="UPDATE webuis SET name = '".$_POST['uiName']."', header = '".$_POST['uiHeader']."', page = '".$_POST['uiPage']."', sidebar = '".$_POST['uiSidebar']."' WHERE id='".$_POST['uiID']."'";
		$result = $db->query($sql);
		//print_r($_POST);
	}

	if(isset($_GET['ui'])){
		$uiID=$_GET['ui'];
	}else{
		$uiID=1;
	}
	$sql="SELECT * FROM webuis WHERE id='".$uiID."'";
	$result = $db->query($sql);
	if($result){
		$laUI=$result->fetch(PDO::FETCH_ASSOC);
	}
	?>
</head>

<body>
<?php
echo "<div id=\"builderWrapper\"><h2>".$laUI['name']."</h2>";
?>
<form action="webUIEditor.php" method="post" >
	Name:<br /> 
    <input type="text" size="50" maxlength="50" name="uiName" value="<?php echo $laUI['name'];?>" /><br /><br />
    Header:<br /> 
    <textarea rows="5" cols="100" name="uiHeader"><?php echo $laUI['header'];?></textarea><br/>
    Page:<br /> 
    <textarea rows="30" cols="100" name="uiPage"><?php echo $laUI['page'];?></textarea><br/>
    Sidebar:<br /> 
    <textarea rows="5" cols="100" name="uiSidebar"><?php echo $laUI['sidebar'];?></textarea><br/>
    <input type="hidden" name="uiID" value="<?php echo $uiID;?>" /><br /><br />
    <input type="submit" value="Submit"/>
</form>
</body>
</html>