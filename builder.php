<?php
//main file for the back-end - aka WebUI Builder

	include("scripts/phpFunctions.php");
	include("scripts/laConfig.php");
	require_once "./JBBCode/Parser.php";
	include ("scripts/bbCodesForBuilder.php");
  
checkCredentials(1);

//Check if GET [ID] is set
if(isset($_GET['id'])){
	if($_GET['id']==-1){ //if id=-1 we create a new WebUI and reload the page with the id of the new UI
		$sql = "INSERT INTO web_uis (name, page) VALUES ('New WebUI','[la-row][la-column class=\"col1\"][/la-column][/la-row]') ";
	  	$result = $db->query($sql);
		$sql ='SELECT MAX(id) FROM web_uis';
		$result = $db->query($sql);
		if($result){
			$maxID=$result->fetch(PDO::FETCH_ASSOC);
		}
		$url=strtok($_SERVER["HTTP_REFERER"],'?');
		header('Location: ' . $url."?id=".$maxID['MAX(id)']); 
	}elseif(isset($_GET['delete'])){ //if 'delete' is set we are deleting the WebUI with _GET[id] and go back to builder.php page
		$sql="DELETE FROM web_uis WHERE id='".$_GET['id']."'";
	  	$result = $db->query($sql);

		$url=strtok($_SERVER["HTTP_REFERER"],'?');
		header('Location: ' . $url);
	}
	$selectedUI=$_GET['id']; //if ID is valid and delete is not set, we open that WebUI
	
	//we open the WebUI with $selectedUI ID
	$sql="SELECT * FROM web_uis WHERE id='".$selectedUI."'";
	$result = $db->query($sql);
	if($result){
		$laUI=$result->fetch(PDO::FETCH_ASSOC);
	}
}else{
	$selectedUI=0; //if ID is not set we don't load any UIs
}

$pagebg='';
$pagemargintop='';
$pagemarginleft='';
$bodybg='';
$bodytext='';
$sidebaryesno='';
$headerimage='';
//****	

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>WebUI Builder</title>
  <link rel="icon" href="assets/lightact-16.png">
  <link href="jquery/jquery-ui.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/webui-builder.css">
  <link type="text/css" rel="stylesheet" href="css/jquery-te-1.4.0.css">
  <link type="text/css" rel="stylesheet" href="css/spectrum.css">
  <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
  <script src="jquery/jquery-3.2.1.min.js"></script>
  <script src="jquery/jquery-ui.js"></script>
  <script src="scripts/webUIJavascript.js"></script>
  <script src="scripts/spectrum.js"></script>
  <script type="text/javascript" src="scripts/jquery-te-1.4.0.min.js" charset="utf-8"></script>
<!--<script src="https://use.fontawesome.com/50c13653cc.js"></script>-->
<!--<script defer src="assets/js/all.js"></script>-->
  <script>
   $( function() {
 	rowSortable();
	columnSortable();
	dialogDialog();

	});
  </script>

</head>
<body>
<div id="notificationDiv">
Various notifications will appear here.
</div>
<?php 
if(isset($_GET['message'])){
	if ($_GET['message']=='1'){
	  $message="UI saved.";
	  echo "<script> $(function(){
		  document.getElementById(\"notificationDiv\").innerHTML = \"".$message."\";
		  newNotification(1);
	  });
	  </script>";
	}else{
		$message=$_GET['message'];
	  	echo "<script> $(function(){
		  document.getElementById(\"notificationDiv\").innerHTML = \"".$message."\";
		  newNotification(2);
	  });
	  </script>";	
	}
}

include("scripts/builderSidebar.php");

if($selectedUI!=0){?>
    <div class="mainWrapper">
      <div class="builderHeader">Edit WebUI:
        <div class="pageName"><input id="uiNameInput" placeholder="Type in the name..." type="text" value="<?php echo $laUI['name']; ?>"></div>
        <div class="viewUILink"><a href="index.php?id=<?php echo $selectedUI;?>" target="_blank">View this WebUI</a></div>
      </div>
      
      <div class="la-main" style='width:70%'> 
      <div class="rowAdd"><button class="iconButtons" title="Prepend a row" onClick="addRow(0)"><i class="fa fa-plus" aria-hidden="true"></i></button></div>
      <ul class="rows" id="rows">
      <?php
        $parser->parse(urldecode($laUI['page']));
        print $parser->getAsHtml();
      ?>
      
      </ul>
      <div class="rowAdd"><button class="iconButtons" title="Append a row" onClick="addRow(1)"><i class="fa fa-plus" aria-hidden="true"></i></button></div>
      </div>
      
      <div id="dialog" title="Edit the widget">
          <div class="textBlockDialog">
              <textarea name="textarea" class="jqte-test"><b>My contents are from <u><span style="color:rgb(0, 148, 133);">TEXTAREA</span></u></b></textarea>
          </div>
          <div class="buttonDialog">
              <div class="stylingProperty">Label: <br><input name="buttonLabel" id="buttonLabel" type="text" value=""></div>
              <div class="stylingProperty">Command: </br><input name="buttonCommand" id="buttonCommand" type="text" value=""></div>
          </div>
          <div class="sliderDialog">
            <div class="stylingProperty">Min: <br><input name="sliderMin" id="sliderMin" type="number" value=""></div>
            <div class="stylingProperty">Max: <br><input name="sliderMax" id="sliderMax" type="number" value=""></div>
            <div class="stylingProperty">Variable name: <br><input name="sliderNewName" id="sliderNewName" type="text" value=""></div>
          </div>
          <div class="colorDialog">
              Variable name: <br><input name="colorNewName" id="colorNewName" type="text" value="">
          </div>
          <div class="spinnerDialog">
               Variable name: <br><input name="spinnerNewName" id="spinnerNewName" type="text" value="">
          </div>
          <div class="textBoxDialog">
              Variable name: <br><input name="textBoxNewName" id="textBoxNewName" type="text" value="">
          </div>
          <div class="paddingDialog">
              Height (just a number in pixels): <input name="paddingHeight" id="paddingHeight" type="number" size="4">
          </div>
          <div class="fileReaderDialog">
          <div class="stylingProperty">Name: <br><input name="cronNewName" id="cronNewName" type="text" value=""></div>
          <div class="stylingProperty">File URL: <br><input name="fileNewURL" id="fileNewURL" type="text" value=""></div>
          </div>
      </div>
                  
      
      
      <div class="la-sidebar">
      <h3>Widgets</h3>
      <h4>Static</h4>
      <ul class="la-elements">
        <!--TEXT BLOCK = 0-->
        <li class="la-element la-text draggable ui-widget-content">
            <div class="elementIcon"><i class="fa fa-font" aria-hidden="true"></i></div>
            <div class="elementControls">
                <button class="iconButtons">
                    <i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings('.draggableContent'),0)"></i></button>
                <button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="$(this).closest('.la-element').remove()"></i></button>
            </div>
            <div class="draggableContent">Text block</div>    
        </li>
        
        <!--PADDING = 5-->
         <li class="la-element la-padding draggable ui-widget-content">
          <div class="elementIcon"><i class="fa fa-arrows-v" aria-hidden="true"></i></div>
          <div class="elementControls">
          <button class="iconButtons">
            <i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings('.draggableContent'),5)"></i></button>
        <button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="$(this).closest('.la-element').remove()"></i></button></div>
          <div class="draggableContent">
              <div class="elementName">Padding</div>
              <div class="paddingHeight elementData"><span style="font-weight:bold">Height: </span><span class="heightValue"></span></div>
          </div>
        </li>
      </ul>
      <h4>Interactive</h4>
      <ul class="la-elements">
        <!--BUTTON = 1-->
        <li class="la-element la-button draggable ui-widget-content">
          <div class="elementIcon"><i class="fa fa-minus-square-o" aria-hidden="true"></i></div>
          <div class="elementControls">
            <button class="iconButtons">
                <i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings('.draggableContent'),1)"></i></button>
        <button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="$(this).closest('.la-element').remove()"></i></button></div>
          <div class="draggableContent">
            <div class="elementName">Button</div>
            <div class="buttonLabel elementData"><span style="font-weight:bold">Label: </span><span class="labelValue"></span></div>
            <div class="buttonCommand elementData"><span style="font-weight:bold">Command: </span><span class="commandValue"></span></div>
          </div>
        </li>
        
        <!--SLIDER = 2-->
        <li class="la-element la-slider draggable ui-widget-content">
          <div class="elementIcon"><i class="fa fa-arrows-h" aria-hidden="true"></i></div>
          <div class="elementControls">
            <button class="iconButtons">
                <i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings('.draggableContent'),2)"></i></button>
            <button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="removeElement($(this).closest('.la-element'))"></i></button></div>
          <div class="draggableContent"><div class="elementName">Slider</div>
            <div class="sliderMin elementData"><span style="font-weight:bold">Min: </span><span class="minValue"></span></div>
            <div class="sliderMax elementData"><span style="font-weight:bold">Max: </span><span class="maxValue"></span></div>
            <div class="sliderNewName elementData"><span style="font-weight:bold">Variable: </span><span class="sliderNewNameValue"></span></div>
            <div class="oldNameValue elementHiddenData"></div>
          </div>
        </li>
        
        <!--COLOR PICKER = 3-->
        <li class="la-element la-color draggable ui-widget-content">
          <div class="elementIcon"><i class="fa fa-sliders" aria-hidden="true"></i></div>
          <div class="elementControls">
            <button class="iconButtons">
                <i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings('.draggableContent'),3)"></i></button>
            <button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="removeElement($(this).closest('.la-element'))"></i></button></div>
          <div class="draggableContent">
            <div class="elementName">Color picker</div>
            <div class="colorNewName elementData"><span style="font-weight:bold">Variable: </span><span class="colorNewNameValue"></span></div>
            <div class="oldNameValue elementHiddenData"></div>
            </div>
        </li>
        
        <!--SPINNER = 4-->
        <li class="la-element la-spinner draggable ui-widget-content">
          <div class="elementIcon"><i class="fa fa-circle-o-notch" aria-hidden="true"></i></div>
          <div class="elementControls">
            <button class="iconButtons">
                <i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings('.draggableContent'),4)"></i></button>
            <button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="removeElement($(this).closest('.la-element'))"></i></button>
        </div>
          <div class="draggableContent">
              <div class="elementName">Spinner</div>
              <div class="spinnerNewName elementData"><span style="font-weight:bold">Variable: </span><span class="spinnerNewNameValue"></span></div>
              <div class="oldNameValue elementHiddenData"></div>
              
          </div>
        </li>
        
               
        <!--TEXTBOX = 6-->
        <li class="la-element la-textbox draggable ui-widget-content">
          <div class="elementIcon"><i class="fa fa-text-width" aria-hidden="true"></i></div>
          <div class="elementControls">
            <button class="iconButtons">
                <i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings('.draggableContent'),6)"></i></button>
        <button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="removeElement($(this).closest('.la-element'))"></i></button>
          </div>
          <div class="draggableContent">
            <div class="elementName">Textbox</div>
            <div class="textBoxNewName elementData"><span style="font-weight:bold">Variable: </span><span class="textBoxNewNameValue"></span></div>
            <div class="oldNameValue elementHiddenData"></div>
          </div>
        </li>
      </ul>
      
      <h4>Scheduled</h4>
      	<ul class="la-elements">
        	<!--FILE READER = 7-->
        	<li class="la-element la-fileReader draggable ui-widget-content">
            	<div class="elementIcon">
                	<i class="fa fa-file-text" aria-hidden="true"></i>
                </div>
                <div class="elementControls">
                    <button class="iconButtons">
                        <i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings('.draggableContent'),7)"></i>
                    </button>
                	<button class="iconButtons">
                    	<i class="fa fa-remove" aria-hidden="true" onClick="removeCronElement($(this).closest('.la-element'))"></i>
                    </button>
                </div>
                <div class="draggableContent">
                    <div class="elementName">File reader</div>
                    <div class="cronName elementData"><span style="font-weight:bold">Name: </span><span class="cronNewNameValue"></span></div>
                    <div class="fileNewURL elementData"><span style="font-weight:bold">URL: </span><span class="fileNewURLValue"></span></div>
                    <div class="oldCronName elementHiddenData"></div>
                    <div class="oldCronParam elementHiddenData"></div>                    
                </div>
        	</li>
      </ul>
            	
      
      
      </div>
      
      <?php
    if ($laUI['styling']!=''){
          $stylingJson=json_decode($laUI['styling'],true);
          $pagebg=$stylingJson['pageBackground'];
          $pagemargintop=$stylingJson['pagemrgtop'];
          $pagemarginleft=$stylingJson['pagemrglft'];
          $bodybg=$stylingJson['bodybg'];
          $bodytext=$stylingJson['bodytext'];
          $sidebaryesno=$stylingJson['sidebar'];
          $headerimage=$stylingJson['headerimage'];
     }
     
     $uipassword=''; //for now this is always blank
          
      
      ?>
      <div class="la-sidebar">
      <h3>Styling</h3>
      <div class="styling">
      <form action="scripts/db-io.php" id="uiData" method="post" enctype="multipart/form-data">
    
      <div class="stylingProperty">
      <label for="pagebg">Page background color:</label><br>
      <input type="text" id="pagebg" name="pageBackground" placeholder="Hex color code..." value="<?php echo $pagebg;?>"><em id="pagebgLabel" class="colorLabels"><?php echo $pagebg;?></em>
      </div>
      
      <div class="stylingProperty">
        <label for="pagemargins">Page margins:</label><br>
        <div class="pageMarginsWrapper">
        Top & bottom:<br>
        <input type="number" id="pagemrgtop" name="pagemrgtop" placeholder="Top/bottom margin in px..." value="<?php echo $pagemargintop;?>">px
        </div>
        <div class="pageMarginsWrapper">
        Left & right:<br>
        <input type="number" id="pagemrglft" name="pagemrglft" placeholder="Type in the left/right margin in px..." value="<?php echo $pagemarginleft;?>">px
        </div>
      </div>
      
      <div class="stylingProperty">
      <label for="bodybg">Body background color:</label><br>
      <input type="text" id="bodybg" name="bodybg" placeholder="Hex color code..." value="<?php echo $bodybg;?>"><em id="bodybgLabel" class="colorLabels"><?php echo $bodybg;?></em></div>
      
      <div class="stylingProperty">
      <label for="bodytext">Body text color:</label><br>
      <input type="text" id="bodytext" name="bodytext" placeholder="Hex color code..." value="<?php echo $bodytext;?>"><em id="bodytextLabel" class="colorLabels"><?php echo $bodytext;?></em></div>
    
      <div class="stylingProperty">
      <label for="headerfilename">Header image:</label>
      <input type="text" id="headerfilename" name="headerfilename" placeholder="Select a file below..." value="<?php echo $headerimage?>">
      <input type="file" id="headerimage" name="headerimage" ></div>
      
      <label for="sidebar">Show default sidebar?</label><br>
      <input type="checkbox" id="sidebar" name="sidebar" value=1 <?php if($sidebaryesno=='1'){echo "checked=\"checked\"";}?>></div>
      
      <input type="hidden" id="uiNameHidden" name="uiNameHidden" placeholder="Type in the name..." value="<?php echo $laUI['name'];?>">
      <input type="hidden" id="uiPassword" name="uiPassword" placeholder="If left empty, no password..." value="<?php echo $uipassword;?>">
      <input type="hidden" id="pageInShortcodes" name="pageInShortcodes">
      <input type="hidden" id="newVariables" name="newVariables" value="Default vrednost new Variables">
      <input type="hidden" id="oldVariables" name="oldVariables">
      <input type="hidden" id="newCrons" name="newCrons">
      <input type="hidden" id="oldCrons" name="oldCrons">
      <input type="hidden" id="uiid" name="uiid" value="<?php echo $selectedUI;?>">
      <h3>Users</h3>
      Select which users should have access to this UI.
      <select name="users[]" multiple>
      <?php 
	  $sql="SELECT id,username FROM web_users WHERE role='1'";
	  $result = $db->query($sql);
	  for($i=0;$i<$result->rowCount();$i++){
		  $user=$result->fetch(PDO::FETCH_ASSOC);
		  echo "<option value='".$user['id']."' ";
		  $usersWithAccess=json_decode($laUI['users']);
		  if(!is_null($usersWithAccess)){
			  if(in_array($user['id'],$usersWithAccess)){
				  echo "selected";
			  }
		  }
		  
		  
		  echo ">".$user['username']."</option>";
	  }
	  ?>
      </select>
      <input type="submit" value="Save">
      </form>
      <div class="deleteUI"><a href="builder.php?id=<?php echo $selectedUI?>&delete=yes">Delete this WebUI</a></div>
      </div>  
      </div>
    </div>
<?php
}else{
	//echo "<div class='pickUIBuilder'>Pick a WebUI on the left or create a new one.</div>";
}?>
<script>

//Color pickers
$("#pagebg").spectrum({
    color: "<?php echo $pagebg?>",
    change: function(color) {
        $("#pagebgLabel").text(color.toHexString());
    },
	showInput: true,
	preferredFormat: "hex"
});
$("#bodybg").spectrum({
    color: "<?php echo $bodybg?>",
    change: function(color) {
        $("#bodybgLabel").text(color.toHexString());
    },
	showInput: true,
	preferredFormat: "hex"
});
$("#bodytext").spectrum({
    color: "<?php echo $bodytext?>",
    change: function(color) {
        $("#bodytextLabel").text(color.toHexString());
    },
	showInput: true,
	preferredFormat: "hex"
});

//Actions on Save button
$( "#uiData" ).submit(function( event ) {
//  event.preventDefault();
  saveUI();
//  return false;
});

/*WYSIWYG Text Editor*/
$('.jqte-test').jqte();

// settings of status
var jqteStatus = true;
$(".status").click(function()
{
  jqteStatus = jqteStatus ? false : true;
  $('.jqte-test').jqte({"status" : jqteStatus})
});

</script>
</body>
</html>