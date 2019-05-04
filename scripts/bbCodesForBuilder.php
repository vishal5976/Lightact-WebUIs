<?php
$parser = new JBBCode\Parser();
$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

//ROW
$builder = new JBBCode\CodeDefinitionBuilder('la-row', '<li class="ui-state-default la-row"><div class="rowControls" onMouseOver="$(this).find(\'.columnNumber\').css(\'visibility\',\'visible\')" onMouseOut="$(this).find(\'.columnNumber\').css(\'visibility\',\'hidden\')"><div class="rowReorder sorthandle" title="Drag to reorder rows"><i class="fa fa-reorder"></i></div><div class="columnNumber">
<button class="iconButtons" onClick="resetColumns($(this).closest(\'li\'),1)" title="1 column row"><img src="assets/1-col.png" width="16" height="16" alt="1 column"></button>
<button class="iconButtons" onClick="resetColumns($(this).closest(\'li\'),2)" title="2 column row"><img src="assets/2-col.png" width="16" height="16" alt="2 column"></button>
<button class="iconButtons" onClick="resetColumns($(this).closest(\'li\'),3)" title="3 column row"><img src="assets/3-col.png" width="16" height="16" alt="3 column"></button>
<button class="iconButtons" onClick="resetColumns($(this).closest(\'li\'),4)" title="4 column row"><img src="assets/4-col.png" width="16" height="16" alt="4 column"></button>
</div><div class="rowName">Row</div><div class="rowDelete"><button class="iconButtons" onClick="$(this).closest(\'.la-row\').remove()" title="Delete this row"><i class="fa fa-remove"></i></button></div></div>
    <ul class="columns">{param}</ul>');
$parser->addCodeDefinition($builder->build());

//COLUMN
$builder = new JBBCode\CodeDefinitionBuilder('la-column', '<li class="droppable ui-widget-header column {class}"><div class="columnControls"><div class="columnReorder sorthandle"><i class="fa fa-arrows"></i></div><div class="columnName">Column</div></div><ul class="columnDropArea">{param}</ul></li>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//TEXT
$builder = new JBBCode\CodeDefinitionBuilder('la-text', '<li class="la-element la-text ui-widget-content" onMouseOver="$(this).find(\'.elementControls\').css(\'visibility\',\'visible\');" onMouseOut="$(this).find(\'.elementControls\').css(\'visibility\',\'hidden\');">
      <div class="elementIcon"><i class="fa fa-font" aria-hidden="true"></i></div>
      <div class="elementControls"><button class="iconButtons"><i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings(\'.draggableContent\'),0)"></i></button><button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="$(this).closest(\'.la-element\').remove()"></i></button></div>
      <div class="draggableContent">{param}</div></li>');
$parser->addCodeDefinition($builder->build());

//BUTTON
$builder = new JBBCode\CodeDefinitionBuilder('la-button', '<li class="la-element la-button ui-widget-content" onMouseOver="$(this).find(\'.elementControls\').css(\'visibility\',\'visible\');" onMouseOut="$(this).find(\'.elementControls\').css(\'visibility\',\'hidden\');">
    <div class="elementIcon"><i class="fa fa-minus-square-o" aria-hidden="true"></i></div>
    <div class="elementControls"><button class="iconButtons"><i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings(\'.draggableContent\'),1)"></i></button><button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="$(this).closest(\'.la-element\').remove()"></i></button></div>
    <div class="draggableContent"><div class="elementName">Button</div><div class="buttonLabel elementData"><span style="font-weight:bold">Label: </span><span class="labelValue">{label}</span></div><div class="buttonCommand elementData"><span style="font-weight:bold">Command: </span><span class="commandValue">{command}</span></div><div class="buttonHide elementData"><span style="font-weight:bold">Hide: </span><span class="hideButtonWidget">{hide}</span></div></div></li>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//SLIDER
$builder = new JBBCode\CodeDefinitionBuilder('la-slider', '<li class="la-element la-slider draggable ui-widget-content" onMouseOver="$(this).find(\'.elementControls\').css(\'visibility\',\'visible\');" onMouseOut="$(this).find(\'.elementControls\').css(\'visibility\',\'hidden\');">
    <div class="elementIcon"><i class="fa fa-arrows-h" aria-hidden="true"></i></div>
    <div class="elementControls"><button class="iconButtons"><i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings(\'.draggableContent\'),2)"></i></button><button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="removeVarElement($(this).closest(\'.la-element\'))"></i></button></div>
    <div class="draggableContent"><div class="elementName">Slider</div>
      <div class="sliderMin elementData"><span style="font-weight:bold">Min: </span><span class="minValue">{min}</span></div>
      <div class="sliderMax elementData"><span style="font-weight:bold">Max: </span><span class="maxValue">{max}</span></div>
      <div class="sliderNewName elementData"><span style="font-weight:bold">Variable: </span><span class="sliderNewNameValue">{varname}</span></div><div class="oldNameValue elementHiddenData">{varname}</div>
      <div class="sliderHide elementData"><span style="font-weight:bold">Hide: </span><span class="hideSliderWidget">{hide}</span></div>
    </div>
  </li>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//COLOR
$builder = new JBBCode\CodeDefinitionBuilder('la-color', '<li class="la-element la-color ui-widget-content" onMouseOver="$(this).find(\'.elementControls\').css(\'visibility\',\'visible\');" onMouseOut="$(this).find(\'.elementControls\').css(\'visibility\',\'hidden\');">
    <div class="elementIcon"><i class="fa fa-sliders" aria-hidden="true"></i></div>
    <div class="elementControls"><button class="iconButtons"><i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings(\'.draggableContent\'),3)"></i></button><button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="removeVarElement($(this).closest(\'.la-element\'))"></i></button></div>
    <div class="draggableContent">
		<div class="elementName">Color picker</div>
		<div class="colorNewName elementData"><span style="font-weight:bold">Variable: </span><span class="colorNewNameValue">{varname}</span></div>
		<div class="oldNameValue elementHiddenData">{varname}</div></div>
  </li>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//SPINNER
$builder = new JBBCode\CodeDefinitionBuilder('la-spinner', '<li class="la-element la-spinner ui-widget-content" onMouseOver="$(this).find(\'.elementControls\').css(\'visibility\',\'visible\');" onMouseOut="$(this).find(\'.elementControls\').css(\'visibility\',\'hidden\');">
    <div class="elementIcon"><i class="fa fa-circle-o-notch" aria-hidden="true"></i></div>
    <div class="elementControls"><button class="iconButtons"><i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings(\'.draggableContent\'),4)"></i></button><button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="removeVarElement($(this).closest(\'.la-element\'))"></i></button></div>
    <div class="draggableContent">
    	<div class="elementName">Spinner</div>
        <div class="spinnerNewName elementData"><span style="font-weight:bold">Variable: </span><span class="spinnerNewNameValue">{varname}</span></div>
		<div class="oldNameValue elementHiddenData">{varname}</div>
    <div class="spinnerHide elementData"><span style="font-weight:bold">Hide: </span><span class="hideSpinnerWidget">{hide}</span>
    </div>
  </li>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//PADDING
$builder = new JBBCode\CodeDefinitionBuilder('la-padding', '<li class="la-element la-padding ui-widget-content" onMouseOver="$(this).find(\'.elementControls\').css(\'visibility\',\'visible\');" onMouseOut="$(this).find(\'.elementControls\').css(\'visibility\',\'hidden\');">
    <div class="elementIcon"><i class="fa fa-arrows-v" aria-hidden="true"></i></div>
    <div class="elementControls"><button class="iconButtons"><i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings(\'.draggableContent\'),5)"></i></button><button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="$(this).closest(\'.la-element\').remove()"></i></button></div>
    <div class="draggableContent">
    	<div class="elementName">Padding</div>
        <div class="paddingHeight elementData"><span style="font-weight:bold">Height: </span><span class="heightValue">{height}</span></div>
    
    </div>
  </li>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//TEXTBOX
$builder = new JBBCode\CodeDefinitionBuilder('la-textbox', '<li class="la-element la-textbox ui-widget-content" onMouseOver="$(this).find(\'.elementControls\').css(\'visibility\',\'visible\');" onMouseOut="$(this).find(\'.elementControls\').css(\'visibility\',\'hidden\');">
    <div class="elementIcon"><i class="fa fa-text-width" aria-hidden="true"></i></div>
    <div class="elementControls">
	<button class="iconButtons"><i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings(\'.draggableContent\'),6)"></i></button>
	<button class="iconButtons"><i class="fa fa-remove" aria-hidden="true" onClick="removeVarElement($(this).closest(\'.la-element\'))"></i></button></div>
    <div class="draggableContent">
		<div class="elementName">Textbox</div>
		<div class="textBoxNewName elementData"><span style="font-weight:bold">Variable: </span><span class="textBoxNewNameValue">{varname}</span></div>
        <div class="oldNameValue elementHiddenData">{varname}</div>
	</div>
  </li>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//FILEREADER
$builder = new JBBCode\CodeDefinitionBuilder('la-filereader','<li class="la-element la-fileReader ui-widget-content" onMouseOver="$(this).find(\'.elementControls\').css(\'visibility\',\'visible\');" onMouseOut="$(this).find(\'.elementControls\').css(\'visibility\',\'hidden\');">
            	<div class="elementIcon">
                	<i class="fa fa-file-text" aria-hidden="true"></i>
                </div>
                <div class="elementControls">
                    <button class="iconButtons">
                        <i class="fa fa-pencil" aria-hidden="true" onClick="openDialogWindow($(this).parent().parent().siblings(\'.draggableContent\'),7)"></i>
                    </button>
                	<button class="iconButtons">
                    	<i class="fa fa-remove" aria-hidden="true" onClick="removeCronElement($(this).closest(\'.la-element\'))"></i>
                    </button>
                </div>
                <div class="draggableContent">
                    <div class="elementName">File reader</div>
					<div class="cronName elementData"><span style="font-weight:bold">Name: </span><span class="cronNewNameValue">{name}</span></div>
                    <div class="fileNewURL elementData"><span style="font-weight:bold">URL: </span><span class="fileNewURLValue">{url}</span></div>
					<div class="oldCronName elementHiddenData">{name}</div>
                    <div class="oldCronParam elementHiddenData">{url}</div>
                </div>
        	</li>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());
?>