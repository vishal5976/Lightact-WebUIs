<?php
include("laConfig.php");

//echo "to je test";

/*
Cron types:
 - file: downloada vsebino fajla in ga spravi v value column. Predpostavka je, da gre za text file
 - openweather: connecta se na openweather in downloada podatke in jih v jsonu spravi v value column
 - twitter: connecta se na twitter in podatke v jsonu spravi v value column
 */

$sql="SELECT id,type,params FROM web_crons";
$result = $db->query($sql);
if($result){
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		if($row['type']=='file'){
			$sql="UPDATE web_crons SET value='".addslashes(file_get_contents(urldecode($row['params'])))."' WHERE id=".$row['id'];
			//echo $sql;
			$updateCron=$db->query($sql);
		}

	}

}

?>