<?php
//SIDEBAR
echo "
<div class='leftSidebar'>
	<div class='leftSidebarTitle'><a href='builder.php'>WebUI Builder</a></div>
	<div class='loginInfo'>Hello ".$loggedInUser['username']."! | <a href='logout.php'>Logout</a></div>
	<div class='builderSiderbarSections'><div class='sidebarSectionHeader'>List of WebUIs</div>";
$sql="SELECT id, name FROM web_uis";
$result = $db->query($sql);
if($result){
	echo "<ul class='builderSiderbarLinksList'>";
	for($i=0;$i<$result->rowCount();$i++){
		$addClass='';
		$allUIs=$result->fetch(PDO::FETCH_ASSOC);
		if(isset($selectedUI)){
			if($allUIs['id']==$selectedUI){
				$addClass=' highlit';
				//echo "<li class='builderSiderbarLinks highlit'><a href='builder.php?id=".$allUIs['id']."'>".$allUIs['name']."</a></li>";
			}
		}
		echo "<li class='builderSiderbarLinks".$addClass."'><a href='builder.php?id=".$allUIs['id']."'>".$allUIs['name']."</a></li>";
	}
	echo "</ul>";
}
echo 
"<ul class='builderSiderbarButtons'>
	<li class='builderSidebarButton'><a href='builder.php?id=-1'><i>+ Create new UI</i></a></li>
    
</ul>
</div>";
echo "<div class='builderSiderbarSections'><div class='sidebarSectionHeader'>List of Users</div>";
$sql="SELECT * FROM web_users";
$result = $db->query($sql);
if($result){
	echo "<ul class='builderSiderbarLinksList'>";
	for($i=0;$i<$result->rowCount();$i++){
		$addClass='';
		$allUsers=$result->fetch(PDO::FETCH_ASSOC);
		if(isset($selectedUser)){
			if($allUsers['id']==$selectedUser){
				$addClass=' highlit';
				//echo "<li class='builderSiderbarLinks highlit'><a href='users.php?id=".$allUsers['id']."'>".$allUsers['username']."</a></li>";
			}
		}
		echo "<li class='builderSiderbarLinks".$addClass."'><a href='users.php?id=".$allUsers['id']."'>".$allUsers['username']."</a></li>";

	}
	echo "</ul>";
}
echo 
"<ul class='builderSiderbarButtons'>
	<li class='builderSidebarButton'><a href='users.php?id=-1'><i>+ Create new User</i></a></li>
</ul>
</div>
<div class='builderSiderbarSections'>
	<ul class='builderSiderbarButtons'>
		<li class='builderSidebarButton'><a href='index.php'><i>Go to front end</i></a></li>
		<li class='builderSidebarButton'><a href='logout.php'><i>Logout</i></a></li>
	</ul>
</div>
<div id='lightActLogoBuilder'><img src='assets/lightAct-iconJustText.png'></div>
</div>";

?>