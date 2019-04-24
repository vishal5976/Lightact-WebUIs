// JavaScript Document
var scriptUrl="scripts/db-io.php";
var newVariables=new Array();
var oldVariables=new Array();
var newCrons=new Array();
var oldCrons=new Array();

function sendCommand(command) {
	//this function sends commands to php scripts which writes them to db
	$.ajax({
		url: scriptUrl+"?c="+command,
		success: function(data){
			document.getElementById("notificationDiv").innerHTML = data;
			newNotification(1);
		}
	});
}

function setVariable(variableName,variableValue){
	//this function sends commands to php scripts which writes them to db
	$.ajax({
		url: scriptUrl+"?varname="+variableName+"&varvalue="+variableValue,
		success: function(data){
			document.getElementById("notificationDiv").innerHTML = data;
			newNotification(1);
		}
	});
}
$secondsToUpdate=7;

function getCommandsAndVariables(command) {
	$secondsToUpdate=7;
	//this function gets current command stack  	
	$.ajax({
    url: scriptUrl+"?e="+command,
	dataType:"json",
	success: function(data) {
      // we write it to the appropriate div
	  if(command=='getAll'){
		  document.getElementById("command-stack-contents").innerHTML = data[1];
		  document.getElementById("variables-values").innerHTML = data[0];
	  }
    }
  });
	setTimeout(function(){getCommandsAndVariables('getAll');}, 7000);
}

function updateCountdown(){
	$dots='.......';
/*	for (var i = $secondsToUpdate; i > 1; i--){
		$dots=$dots	
	}*/
	document.getElementById("updateCountdown").innerHTML="Next sidebar refresh in "+$secondsToUpdate+"s"+$dots.substring(0,$secondsToUpdate);
	$secondsToUpdate=$secondsToUpdate-1;
	setTimeout(function(){updateCountdown();}, 1000);
}

function newNotification(type){
	if(type==1){
		//1 means regular notication
		$("#notificationDiv").fadeIn(300).delay(1500).fadeOut(400);
	}else if(type==2){
		//2 means error
		$("#notificationDiv").fadeIn(300).delay(2500).fadeOut(400);
		$("#notificationDiv").css('background-color','#d5a6a6');
		$("#notificationDiv").css('border-color','#da2e2e');
		$("#notificationDiv").css('color','#da2e2e');
	}
}
function makeID(charNum){
	var text = "";
  	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	for (var i = 0; i < charNum; i++)
	  text += possible.charAt(Math.floor(Math.random() * possible.length));
  
	return text;
}

function removeVarElement(element){
	oldVariables.push(element.find('.oldNameValue').html());
	element.remove()
}

function removeCronElement(element){
	oldCrons.push(element.find('.oldCronParam').html());
	element.remove()
}


//ACTIONS ON SAVE UI
function saveUI() {
	var parsedHTML="";
	var rowContents="";
	var columnContents="";
	$("#rows").children().each(function(index) {
		var rowContents="";
		$(this).find('li.column').each(function(index){
			columnContents="";
			$(this).find('li.la-element').each(function(index){
				if($(this).hasClass('la-text')){ // TEXT BLOCK
					columnContents+="[la-text]"+$(this).find('.draggableContent').html()+"[/la-text]";
					
				}else if($(this).hasClass('la-button')){ // BUTTON
					columnContents+="[la-button id=\"button-"+makeID(5)+"\" label=\""+$(this).find('.labelValue').html()+"\" command=\""+$(this).find('.commandValue').html()+"\"][/la-button]";
					
				}else if($(this).hasClass('la-slider')){ // SLIDER
					columnContents+="[la-slider id=\"slider-"+makeID(5)+"\" min=\""+$(this).find('.minValue').html()+"\" max=\""+$(this).find('.maxValue').html()+"\" varname=\""+$(this).find('.sliderNewNameValue').html()+"\"][/la-slider]";
					if($(this).find('.sliderNewNameValue').html()!=$(this).find('.oldNameValue').html()){
						newVariables.push($(this).find('.sliderNewNameValue').html());
						if($(this).find('.oldNameValue').html()!=''){
							oldVariables.push($(this).find('.oldNameValue').html());
						}
					}
					
				}else if($(this).hasClass('la-color')){ // COLOR
					columnContents+="[la-color id=\"color-"+makeID(5)+"\" varname=\""+$(this).find('.colorNewNameValue').html()+"\"][/la-color]";
					if($(this).find('.colorNewNameValue').html()!=$(this).find('.oldNameValue').html()){
						newVariables.push($(this).find('.colorNewNameValue').html());
						if($(this).find('.oldNameValue').html()!=''){
							oldVariables.push($(this).find('.oldNameValue').html());
						}
					}
				}else if($(this).hasClass('la-spinner')){ // SPINNER
					columnContents+="[la-spinner id=\"spinner-"+makeID(5)+"\" varname=\""+$(this).find('.spinnerNewNameValue').html()+"\"][/la-spinner]";
					if($(this).find('.spinnerNewNameValue').html()!=$(this).find('.oldNameValue').html()){
						newVariables.push($(this).find('.spinnerNewNameValue').html());
						if($(this).find('.oldNameValue').html()!=''){
							oldVariables.push($(this).find('.oldNameValue').html());
						}
					}
				}else if($(this).hasClass('la-textbox')){ // TEXTBOX
					columnContents+="[la-textbox id=\"textbox-"+makeID(5)+"\" varname=\""+$(this).find('.textBoxNewNameValue').html()+"\"][/la-textbox]";
					if($(this).find('.textBoxNewNameValue').html()!=$(this).find('.oldNameValue').html()){
						newVariables.push($(this).find('.textBoxNewNameValue').html());
						if($(this).find('.oldNameValue').html()!=''){
							oldVariables.push($(this).find('.oldNameValue').html());
						}
					}
				}else if($(this).hasClass('la-padding')){ // PADDING
					columnContents+="[la-padding height=\""+$(this).find('.heightValue').html()+"\"][/la-padding]";
					
				}else if($(this).hasClass('la-fileReader')){ // FILE READER
					columnContents+="[la-filereader name=\""+$(this).find('.cronNewNameValue').html()+"\" url=\""+$(this).find('.fileNewURLValue').html()+"\"][/la-filereader]";
					if($(this).find('.fileNewURLValue').html()!=$(this).find('.fileOldURLValue').html()){
						//We package cron data to a multidimensional array
						newCrons.push(['file',$(this).find('.fileNewURLValue').html(),$(this).find('.cronNewNameValue').html()]); 
						if($(this).find('.oldCronParam').html()!=''){
							oldCrons.push($(this).find('.oldCronParam').html());
						}
					}
				}
			});
			if ($(this).hasClass('col1')){$colClass='col1';}
			else if ($(this).hasClass('col2')){$colClass='col2';}
			else if ($(this).hasClass('col3')){$colClass='col3';}
			else if ($(this).hasClass('col4')){$colClass='col4';}
			rowContents+="[la-column class=\""+$colClass+"\"]"+columnContents+"[/la-column]";
		});;
		
        parsedHTML+="[la-row]"+rowContents+"[/la-row]";
	
    });;
	$("#uiNameHidden").val($("#uiNameInput").val());
	$("#pageInShortcodes").val(parsedHTML);
	$("#newVariables").val(JSON.stringify(newVariables));
	$("#oldVariables").val(JSON.stringify(oldVariables));
	$("#newCrons").val(JSON.stringify(newCrons));
	$("#oldCrons").val(JSON.stringify(oldCrons));
}

function openDialogWindow(elementReference,elementType){
	$("#dialog").children('div').each(function(){
		$(this).css('display','none');
	});
	if(elementType==0){//Text box
		$( "#dialog" )
			.data('contentReference',elementReference)
			.data('elementType',0)
			.dialog("open")
			.find(".jqte_editor").html(elementReference.html());
		
		$( "#dialog" ).find('.textBlockDialog').css('display','block');		
	}
	if(elementType==1){//Button
		$( "#dialog" )
			.data('contentReference',elementReference)
			.data('elementType',1)
			.dialog("open");
		$( "#dialog" ).find('.buttonDialog').css('display','block');
		$( "#dialog" ).find('.buttonDialog').find('#buttonLabel').val(elementReference.find('.labelValue').html());	
		$( "#dialog" ).find('.buttonDialog').find('#buttonCommand').val(elementReference.find('.commandValue').html());		
	}
	if(elementType==2){//Slider
		$( "#dialog" )
			.data('contentReference',elementReference)
			.data('elementType',2)
			.dialog("open");
		$( "#dialog" ).find('.sliderDialog').css('display','block');
		$( "#dialog" ).find('.sliderDialog').find('#sliderMin').val(elementReference.find('.minValue').html());	
		$( "#dialog" ).find('.sliderDialog').find('#sliderMax').val(elementReference.find('.maxValue').html());	
		$( "#dialog" ).find('.sliderDialog').find('#sliderNewName').val(elementReference.find('.sliderNewNameValue').html());		
	}
	if(elementType==3){//Color picker
		$( "#dialog" )
			.data('contentReference',elementReference)
			.data('elementType',3)
			.dialog("open");
		$( "#dialog" ).find('.colorDialog').css('display','block');
		$( "#dialog" ).find('.colorDialog').find('#colorNewName').val(elementReference.find('.colorNewNameValue').html());		
	}
	if(elementType==4){//Spinner
		$( "#dialog" )
			.data('contentReference',elementReference)
			.data('elementType',4)
			.dialog("open");
		$( "#dialog" ).find('.spinnerDialog').css('display','block');
		$( "#dialog" ).find('.spinnerDialog').find('#spinnerNewName').val(elementReference.find('.spinnerNewNameValue').html());		
	}
	if(elementType==5){//Padding
		$( "#dialog" )
			.data('contentReference',elementReference)
			.data('elementType',5)
			.dialog("open");
		$( "#dialog" ).find('.paddingDialog').css('display','block');
		$( "#dialog" ).find('.paddingDialog').find('#paddingHeight').val(elementReference.find('.heightValue').html());		
	}
	if(elementType==6){//Text box
		$( "#dialog" )
			.data('contentReference',elementReference)
			.data('elementType',6)
			.dialog("open");
		$( "#dialog" ).find('.textBoxDialog').css('display','block');
		$( "#dialog" ).find('.textBoxDialog').find('#textBoxNewName').val(elementReference.find('.textBoxNewNameValue').html());		
	}
	if(elementType==7){//File reader
		$( "#dialog" )
			.data('contentReference',elementReference)
			.data('elementType',7)
			.dialog("open");
		$( "#dialog" ).find('.fileReaderDialog').css('display','block');
		$( "#dialog" ).find('.fileReaderDialog').find('#cronNewName').val(elementReference.find('.cronNewNameValue').html());	
		$( "#dialog" ).find('.fileReaderDialog').find('#fileNewURL').val(elementReference.find('.fileNewURLValue').html());		
	}
}

function resetColumns(rowObject,newColumnNumber){
	var $i=0;
	var $width=100/newColumnNumber-1;
	rowObject.find('.columns').children('li').each(function(){
		for($j=1;$j<5;$j++){
			$(this).removeClass('col'+$j);
		}
		$(this).addClass('col'+newColumnNumber);
		if($i>=newColumnNumber){
			$(this).remove();
		}
		$i++;
	});
	if($i<newColumnNumber){
		for($j=0;$j<newColumnNumber-$i;$j++){
			rowObject.find('.columns').append("<li class='droppable ui-widget-header column col"+newColumnNumber+"'><div class='columnControls'><div class='columnReorder sorthandle'><i class='fa fa-arrows'></i></div><div class='columnName'>Column</div></div><ul class='columnDropArea'></ul></li>");
		}
		columnSortable();
	}
}

function addRow(operation){
	$emptyRow="<li class=\"ui-state-default la-row\"><div class=\"rowControls\" onMouseOver=\"$(this).find('.columnNumber').css('visibility','visible')\" onMouseOut=\"$(this).find('.columnNumber').css('visibility','hidden')\"><div class=\"rowReorder sorthandle\" title=\"Drag to reorder rows\"><i class=\"fa fa-reorder\"></i></div><div class=\"columnNumber\"><a href=\"#\" onClick=\"resetColumns($(this).closest('li'),1)\" title=\"1 column row\"><img src=\"assets/1-col.png\" width=\"16\" height=\"16\" alt=\"1 column\"></a> <a href=\"#\" onClick=\"resetColumns($(this).closest('li'),2)\" title=\"2 column row\"><img src=\"assets/2-col.png\" width=\"16\" height=\"16\" alt=\"2 column\"></a> <a href=\"#\" onClick=\"resetColumns($(this).closest('li'),3)\" title=\"3 column row\"><img src=\"assets/3-col.png\" width=\"16\" height=\"16\" alt=\"3 column\"></a> <a href=\"#\" onClick=\"resetColumns($(this).closest('li'),4)\" title=\"4 column row\"><img src=\"assets/4-col.png\" width=\"16\" height=\"16\" alt=\"4 column\"></a></div><div class=\"rowName\">Row</div><div class=\"rowDelete\"><a href='#' onClick=\"$(this).closest(\'.la-row\').remove()\" title=\"Delete this row\"><i class='fa fa-remove'></i></a></div></div><ul class=\"columns\"><li class=\"droppable ui-widget-header column col1\"><div class=\"columnControls\"><div class=\"columnReorder sorthandle\"><i class=\"fa fa-arrows\"></i></div><div class=\"columnName\">Column</div></div><ul class=\"columnDropArea\"></ul></li></ul></li></ul></li>";
	if(operation==0){
		$('#rows').prepend($emptyRow);
	}else if(operation==1){
		$('#rows').append($emptyRow);
	}
	rowSortable();
	columnSortable();
}


/*JQUERY SET UP FROM HERE BELOW*/
function columnSortable(){
	$( ".columns" ).sortable({
		handle: '.sorthandle'
	});
	$( ".columns" ).disableSelection();
	$( ".columnDropArea" ).sortable({
		handle: '.elementIcon'
	});
	$( ".draggable" ).draggable({ 
	revert: "invalid",
	scope: "la-elements",
	helper: "clone",
	cursor: "move",
	
	containment: "document",
	});
 
    $( ".droppable" ).droppable({
		
		scope: "la-elements",
      	classes: {
        	"ui-droppable-active": "ui-state-active",
        	"ui-droppable-hover": "ui-state-hover"
      	},
	    drop: function( event, ui ) {
			$( this ).find(".columnDropArea").append($(ui.draggable).clone())
			var $droppedElement=$(this).find( ".la-element" ).last();
			$droppedElement.draggable();
			$droppedElement.draggable('destroy');
			$droppedElement.hover(
			  function(){
				  $(this).find(".elementControls").css('visibility','visible');
			  },
			  function(){
				  $(this).find(".elementControls").css('visibility','hidden');
			});
			
			/*We open editing window and adapt it according to the type of element we are editing*/
			if($droppedElement.hasClass('la-text')){ //Text block
				$droppedElement.find('.draggableContent').html("");
			  	openDialogWindow($droppedElement.find('.draggableContent'),0);
			}
			if($droppedElement.hasClass('la-button')){ //Button
			  	openDialogWindow($droppedElement.find('.draggableContent'),1);
			}
			if($droppedElement.hasClass('la-slider')){ //Slider
			  	openDialogWindow($droppedElement.find('.draggableContent'),2);
			}
			if($droppedElement.hasClass('la-color')){ //Color picker
			  	openDialogWindow($droppedElement.find('.draggableContent'),3);
			}
			if($droppedElement.hasClass('la-spinner')){ //Spinner
			  	openDialogWindow($droppedElement.find('.draggableContent'),4);
			}
			if($droppedElement.hasClass('la-padding')){ //Padding
			  	openDialogWindow($droppedElement.find('.draggableContent'),5);
			}
			if($droppedElement.hasClass('la-textbox')){ //Textbox
			  	openDialogWindow($droppedElement.find('.draggableContent'),6);
			}
			if($droppedElement.hasClass('la-fileReader')){ //File reader
			  	openDialogWindow($droppedElement.find('.draggableContent'),7);
			}
		}
	});
}

function rowSortable(){
	$( ".rows" ).sortable({
		handle: '.sorthandle'	
	});
    $( ".rows" ).disableSelection();
}

function dialogDialog(){
	 $( "#dialog" ).dialog({
		autoOpen:false,	
		modal:true,
		height: "auto",
		width: 600,
		buttons:[
		  {
			  text:"Save",
			  click: function(){
				  if($(this).data('elementType')==0){ //Text box
					  $(this).data('contentReference').html($(this).find(".jqte_editor").html());
					  
				  }else if($(this).data('elementType')==1){ //Button
					   $(this).data('contentReference').find('.labelValue').html($(this).find("#buttonLabel").val());
					   $(this).data('contentReference').find('.commandValue').html($(this).find("#buttonCommand").val());
					   
				  }else if($(this).data('elementType')==2){ //Slider
					  $(this).data('contentReference').find('.minValue').html($(this).find("#sliderMin").val());
					  $(this).data('contentReference').find('.maxValue').html($(this).find("#sliderMax").val());
					  $(this).data('contentReference').find('.sliderNewNameValue').html($(this).find("#sliderNewName").val());
		  
				  }else if($(this).data('elementType')==3){ //Color picker
					   $(this).data('contentReference').find('.colorNewNameValue').html($(this).find("#colorNewName").val());
					   
				  }else if($(this).data('elementType')==4){ //Spinner
					   $(this).data('contentReference').find('.spinnerNewNameValue').html($(this).find("#spinnerNewName").val());
				  
				  }else if($(this).data('elementType')==5){ //Padding
					   $(this).data('contentReference').find('.heightValue').html($(this).find("#paddingHeight").val());
				  
				  }else if($(this).data('elementType')==6){ //Textbox
					   $(this).data('contentReference').find('.textBoxNewNameValue').html($(this).find("#textBoxNewName").val());
					   
				  }else if($(this).data('elementType')==7){ //File reader
					   $(this).data('contentReference').find('.cronNewNameValue').html($(this).find("#cronNewName").val());
					   $(this).data('contentReference').find('.fileNewURLValue').html($(this).find("#fileNewURL").val());
				  }
				  $(this).dialog("close");
			  }
		  },
		  {
			  text:"Cancel",
			  click: function(){
				  $(this).dialog("close");
			  }
		  }
		]
	});	
}
