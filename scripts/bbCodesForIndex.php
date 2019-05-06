<?php
//JBBCode setup
$parser = new JBBCode\Parser();
$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

//ROW
$builder = new JBBCode\CodeDefinitionBuilder('la-row', '<div class="la-row">{param}</div>');
$parser->addCodeDefinition($builder->build());

//TEXT
$builder = new JBBCode\CodeDefinitionBuilder('la-column', '<div class="la-column {class}">{param}</div>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

/*$builder = new JBBCode\CodeDefinitionBuilder('la-text', '<div class="la-text"><{format}>{param}</{format}></div>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());*/

$builder = new JBBCode\CodeDefinitionBuilder('la-text', '<div class="elementWrapper"><div class="la-text">{param}</div></div>');
$parser->addCodeDefinition($builder->build());

//BUTTON
$builder = new JBBCode\CodeDefinitionBuilder('la-button', '<div id="{id}-button"></div>'.buildButtonJS());
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//SLIDER
$builder = new JBBCode\CodeDefinitionBuilder('la-slider', '<div id="{id}-slider"></div>'.buildSliderJS());
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//COLOR
$builder = new JBBCode\CodeDefinitionBuilder('la-color', '<div class="elementWrapper" id="{id}-colorPicker"></div>'.buildColorJS());
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//PADDING
$builder = new JBBCode\CodeDefinitionBuilder('la-padding', '<div style="height:{height}px; clear:both;"></div>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//SPINNER
$builder = new JBBCode\CodeDefinitionBuilder('la-spinner', '<div id="{id}-spinner"></div>'.buildSpinnerJS());
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//TEXTBOX
$builder = new JBBCode\CodeDefinitionBuilder('la-textbox', '<div class="elementWrapper"><input id="{id}" class="ui-textbox" placeholder="Type the command here..." value="{value}"><button id="{id}-button" onclick="setVariable(\'{varname}\',document.getElementById(\'{id}\').value)">Send</button></div><script>$( "#{id}-button" ).button();</script>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//FILEREADER
$builder = new JBBCode\CodeDefinitionBuilder('la-filereader', '<div class="elementWrapper">This is a placeholder for <b>{name}</b> Scheduled File Reader.<br><span style="font-size:0.7em;"><b>File URL:</b> <i>{url}<br>The frequency of updates is set in Windows Task Scheduler</i></span></div>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

?>