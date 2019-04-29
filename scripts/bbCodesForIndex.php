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
$builder = new JBBCode\CodeDefinitionBuilder('la-button', '<div class="elementWrapper"><button id="{id}" onClick="sendCommand(\'{command}\')">{label}</button></div><script>$( "#{id}" ).button();</script>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//SLIDER
$builder = new JBBCode\CodeDefinitionBuilder('la-slider', '<div id="{id}-slider"></div>'.buildSliderJS());
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//COLOR
$builder = new JBBCode\CodeDefinitionBuilder('la-color', '<div class="elementWrapper"><div class="color-sliders-wrapper"><div id="{id}-red"></div><div id="{id}-green"></div><div id="{id}-blue"></div><div id="{id}-alpha"></div></div><div class="swatch-wrapper"><div class="swatch-bg"><div id="{id}-swatch" class="ui-color-swatch ui-widget-content ui-corner-all"></div></div><div id="{id}-swatch-label" class="ui-color-label"></div></div></div><script>'.buildColorJS().'</script>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//PADDING
$builder = new JBBCode\CodeDefinitionBuilder('la-padding', '<div style="height:{height}px; clear:both;"></div>');
$builder->setUseOption(true);
$parser->addCodeDefinition($builder->build());

//SPINNER
$builder = new JBBCode\CodeDefinitionBuilder('la-spinner', '<div class="elementWrapper"><input id="{id}" value="{value}"></div><script>'.buildSpinnerJS().'</script>');
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