<?php
include("laConfig.php");

/*
Cron types:
 - file: downloads the file contents and puts it into the value column. We assume that it is a text file.
 - openweather: Connects to open weather, downloads the data and saves them as JSON into value column
 - twitter: Connects to Twitter and saves the data as JSON into the value column.
 */

$sql="SELECT id,type,params FROM web_crons";
$result = $db->query($sql);
if($result){
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		if($row['type']=='file'){
			$sql="UPDATE web_crons SET value='".addslashes(file_get_contents(urldecode($row['params'])))."' WHERE id=".$row['id'];
			$updateCron=$db->query($sql);
		}
	}
}

?>