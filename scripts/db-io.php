<?php 
include("laConfig.php");

if(isset($_POST['uiid'])){ //if post is set we are saving the data first
	  if($_FILES["headerimage"]["name"]!=='') {//if user selected a file, we start with the upload process
		  include("fileUploader.php");
	  }else{ //if not, we reuse whatever is written in headerfilename
		  $target_file=$_POST['headerfilename'];
	  }
	  $sidebar=0;
	  if(isset($_POST['sidebar'])){
		  $sidebar=1;
	  }
	  
	  //Delete old variables and create new ones in web_variables table
	  $newVariables=json_decode($_POST['newVariables']);
	  $oldVariables=json_decode($_POST['oldVariables']);
	  if(count($oldVariables)>0){
	  	$sql="DELETE FROM web_variables WHERE name=";
		for($i=0;$i<count($oldVariables);$i++){
			 $sql.="'".$oldVariables[$i]."' ";
			 if($i<count($oldVariables)-1){
				 $sql.="OR name=";
			 }
			 
		}
		$db->query($sql);
	  }
	  if(count($newVariables)>0){
		//Add new variables
		$sql="INSERT INTO web_variables (name, value) VALUES ";
		for($i=0;$i<count($newVariables);$i++){
		  $sql.="('".$newVariables[$i]."','0')";
		  if($i<count($newVariables)-1){
				 $sql.=", 
				 ";
		  }
		}
		$db->query($sql);
	  }
	  
	  //Delete old crons and create new ones in web_crons table
	  $newCrons=json_decode($_POST['newCrons']);
	  $oldCrons=json_decode($_POST['oldCrons']);
/*	  print_r($newCrons);
	  echo "<br><br>";
	  echo "Old crons: ";
	  print_r($oldCrons);*/
	  if(count($oldCrons)>0){
	  	$sql="DELETE FROM web_crons WHERE params=";
		for($i=0;$i<count($oldCrons);$i++){
			 $sql.="'".htmlspecialchars_decode($oldCrons[$i])."' ";
			 if($i<count($oldCrons)-1){
				 $sql.="OR params=";
			 }
			 
		}
		$db->query($sql);
	  }
	  if(count($newCrons)>0){
		//Add new crons
		$sql="INSERT INTO web_crons (type, params,name) VALUES ";
		for($i=0;$i<count($newCrons);$i++){
		  	$sql.="('".$newCrons[$i][0]."','".htmlspecialchars_decode($newCrons[$i][1])."','".$newCrons[$i][2]."')";
			  if($i<count($newCrons)-1){
				  $sql.=",
				  ";
			  }
		  
		}
		//echo $sql."<br>";
		$db->query($sql);
	  }

	  $userJson='';
	  if(isset($_POST['users'])){
		  $userJson=json_encode($_POST['users']);
	  }
	  $stylingJson=json_encode(array('pageBackground'=>$_POST['pageBackground'], 'pagemrgtop'=>$_POST['pagemrgtop'], 'pagemrglft'=>$_POST['pagemrglft'], 'bodybg'=>$_POST['bodybg'], 'bodytext'=>$_POST['bodytext'], 'sidebar'=>$sidebar, 'headerimage'=>$target_file));

	  $sql = "UPDATE web_uis SET name = '".$_POST['uiNameHidden']."', page = '".urlencode($_POST['pageInShortcodes'])."',  users='".$userJson."', styling='".$stylingJson."' WHERE id = ".$_POST['uiid'];
	  $result = $db->query($sql);
	  	if($result || $uploadOk==1){
		  $responseString="1";
	  	}else{
		  $responseString=$uploadResult;
		  if($uploadOk==2){ //this happens when no file was attached
			  $responseString="1";
		  }
		}
	//redirect back to builder...
	$url=strtok($_SERVER["HTTP_REFERER"],'?');
	header('Location: ' . $url."?id=".$_POST['uiid']."&message=".$responseString);
	die();

}else{ //IF POST IS NOT SET WE TRY WITH GET

  if(isset($_GET['e'])){
	if($_GET['e']=="emptyCommands"){ //We are emptying Command stack
		$sql = "DELETE FROM web_commands";
		$result = $db->query($sql);
		$responseString="Command stack emptied.";
	}elseif($_GET['e']=="getAll"){ //We are retrieving Variables & Commands
		$responseString=Array('','');
		$responseString[0]="<table><tr><th style='width:33%;'>Timestamp</th><th style='width:33%;'>Name</th><th style='width:33%;'>Value</th></tr>";
		$sql = "SELECT name,value,timestamp FROM web_variables ORDER BY name";
		$result = $db->query($sql);
		if($result){
			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$responseString[0].="<tr><td>".$row['timestamp']."</td><td>".$row['name']."</td><td>".$row['value']."</td></tr>";
			}
			$responseString[0].="</table>";
		}
		$responseString[1]='<table><tr><th style="width:50%;">Timestamp</th><th style="width:50%;">Command</th></tr>';
		$sql = "SELECT command,timestamp FROM web_commands ORDER BY timestamp DESC";
		$result = $db->query($sql);
		if($result){
			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$responseString[1].="<tr><td>".$row['timestamp']."</td><td>".$row['command']."</td></tr>";
			}
			$responseString[1].="</table>";
		}
		$responseString=json_encode($responseString);
	}
  }elseif(isset($_GET['c'])) {
	  //Inserts command to web_commands table
	  $sql = "INSERT INTO web_commands (command) VALUES ('".$_GET['c']."') ";
	  $result = $db->query($sql);
	  $responseString="Command '<strong>".$_GET['c']."</strong>' has been sent.";
  
  }elseif(isset($_GET['varname'])){
	  if(isset($_GET['varvalue'])){
		//Set variable to varvalue 
		$sql="UPDATE web_variables SET value='".$_GET['varvalue']."' WHERE name='".$_GET['varname']."'";
		$result = $db->query($sql);
		if($result){
			$responseString="Variable <strong>".$_GET['varname']."</strong> updated to <strong>".$_GET['varvalue']."</strong>.";
		}else{
			$responseString="Variable ".$_GET['varname']." not updated!";
		}
	  }
  }
  
  	if(isset($_GET['getvar'])){
		//Get current value of the variable  
	  	$sql="SELECT value FROM web_variables WHERE name='".$_GET['getvar']."'";
		//echo $sql;
		$result = $db->query($sql);
		if($result){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$responseString=$row['value'];
		}
	}

  
  echo $responseString;
}


?>