#!/usr/local/bin/php -q
<?php
$CBlack = "\033[30m";
$CRed = "\033[31m";
$CGreen = "\033[32m";
$CYellow = "\033[33m";
$CBlue = "\033[34m";
$CMagenta = "\033[35m";
$CCyan = "\033[36m";
$CWhite = "\033[37m";
//Color of background
$BBlack = "\033[40m";
$BRed = "\033[41m";
$BGreen = "\033[42m";
$BYellow = "\033[43m";
$BBlue = "\033[44m";
$BMagenta = "\033[45m";
$BCyan = "\033[46m";
$BWhite = "\033[47m";
//Reset of colors...
$CReset = "\033[m";
Presentation();
//--------------------------------------------------------------------------
function Presentation(){
  Color_Output($CYellow,"###################################");
  Color_Output($CCyan,"This script will install Amule");
  Color_Output($CYellow,"###################################");
  Color_Output($CRed,"This script may change Xigmanas configuration!!!");
  Color_Output($CRed,"Please, close Xigmanas WebGUI before continue");
  echo($CWhite."Are you ready to start [y/n]?$CReset");
  $Answer = InputKeyboard();
  if(strtoupper($Answer) == "Y"){
    
  }
  else
    Color_Output($CCyan,"Script termited by user");
}
//--------------------------------------------------------------------------
function Color_Output($color,string $Output){
	echo($color.$Output."\033[m\n");
}
function InputKeyboard($prompt=null){
	if($prompt <> null)
		echo($prompt);
	fscanf(STDIN,"%s",$pass);
	return $pass;
}
?>
