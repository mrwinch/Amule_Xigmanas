#!/usr/local/bin/php -q
<?php

//Color of characters
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

//Main script...
/*echo("##################################\n");
echo("$CCyan  This script will install Amule $CReset\n");
echo("##################################\n");
echo("$CCyan"."Creating installation directory...$CReset\n");*/

//CheckAmuleGroup();
//MyXMLManager("/cf/conf/config.xml");
/*Build_Directory();
echo("$CCyan"."Installing packages: it may require few minutes...$CReset\n");
Pkg_Install();
echo("$CCyan"."Checking for user and group...$CReset\n");
$Res = Check_User_Group();
Color_Output("$CCyan","Configuring Amule...");
Amule_Conf();*/
echo("World...\n");
Color_Output($CMagenta,"Ciao mondo...");
Color_Output($BYellow,"Ciao mondo...");
echo("Hello\n");
function Color_Output($color,string $Output){
	echo($color.$Output."\033[m\n");
}
function Color_Out($color,string $Output){
	$Reset = "\033[m";
	$Bright = "\033[1m";
	$Red = "\033[31m";
	$Green = "\033[32m";
	echo("$Red".$Output."$Reset\n");
	echo("$Bright".$Output."$Reset\n");

}
function Build_Directory(){
	exec("cd /");
	if(file_exists("/home")==false)
		mkdir("home");
	if(file_exists("/home/aMule")==false)	
		mkdir("/home/aMule");
	if(file_exists("/home/aMule/.aMule")==false)	
		mkdir("/home/aMule/.aMule");
}
function Pkg_Install(){
	$pkg = "amule";
	exec("pkg install -y ".$pkg);
	if(file_exists("/etc/rc.d/amuled") == false)
		exec("ln /usr/local/etc/rc.d/amuled /etc/rc.d/amuled");
	else
		echo("$CGreen"."Amuled already linked...$CReset\n");
	$RC_Conf = parse_ini_file("/etc/rc.conf");
	if(is_array($RC_Conf)){
		if(array_key_exists("amuled_enable",$RC_Conf) == false){
			exec('echo amuled_enable="YES" >> /etc/rc.conf');
		}
		else
			echo("$CGreen"."Amuled already enabled in /etc/rc.conf...$CReset\n");
	}
	else
		echo("$CGreen"."Invalid /etc/rc.conf...$CReset\n");
}
function Check_User_Group(){
	$group_file = "/etc/group";
	$user_file = "/etc/passwd";
	$Config_File = "/cf/conf/config.xml";
	$config = null;
	if(file_exists($Config_File)==true){
		$config = parse_XML_file($Config_File);
		if(is_array($config)==false)
			echo("$CRed"."Invalid config file $CReset\n");
	}
	if(file_exists($group_file) <> false){
		$group = file_get_contents($group_file,true);
		if(is_string($group)){
			if(strpos($group,"aMule") == false){
				exec("pw groupadd aMule -u".strval($Amule_Group_ID));				
			}
			else
				echo("Amule group already exist: now checking for user...\n");
		}
		else
			echo("$CRed"."Bad content in ".$group_file."$CReset\n");
	}
	else
		echo("$CRed"."Unable to find ".$group_file."$CReset\n");
	
	if(file_exists($user_file) <> false){
		$group = file_get_contents($user_file,true);
		if(is_string($group)){
			if(strpos($group,"aMule") == false){
				exec("pw useradd aMule -g aMule -s /bin/sh -c \"aMule Daemon\" -d /home/aMule");				
			}
			else
				echo("$CGreen"."Amule user already exist...\n");
		}
		else
			echo("$CRed"."Bad content in ".$user_file."$CReset\n");
	}
	else
		echo("$CRed"."Unable to find ".$user_file."$CReset\n");
	//exec("chown -R aMule:wheel /home/aMule");
	//Xigmanas distribution, not a jail...
	if(is_array($config)){
		echo("$CCyan"."Checking config.xml...\n");
		$Amule_Group_Exist = false;
		foreach($config['system']['usermanagement']['group'] as $Key=>$group_array){
			if(is_array($group_array)){
				if($group_array['name'] == "aMule"){//Amule group exist in config.xml...
					$Amule_Group_Exist = true;
				}
			}
		}
		if($Amule_Group_Exist == false){	//Amule group not exist in config.xml...
			echo("$CCyan"."No Amule group in config.xml: creating...\n");
			$group_amule = array();
			$group_amule['uuid'] = "245a1033-f9ee-443c-8c55-6be964d51345";
			$group_amule['id'] = $Amule_Group_ID;
			$group_amule['name'] = "aMule";
			$config['system']['usermanagement']['group'][] = $group_amule;			
		}
		$Amule_User_Exist = false;
		foreach($config['system']['usermanagement']['user'] as $Key=>$group_array){
			if(is_array($group_array)){
				if($group_array['name'] == "aMule"){//Amule group exist in config.xml...
					$Amule_User_Exist = true;
				}
			}
		}
		if($Amule_User_Exist == false){	//Amule group not exist in config.xml...
			echo("$CCyan"."No Amule user in config.xml: creating...\n");
			$user_amule = array();
			$user_amule['uuid'] = "71589751-b2fb-495d-86af-c7d217998a45";
			$user_amule['id'] = $Amule_Group_ID;
			$user_amule['name'] = "aMule";
			$user_amule['primarygroup'] = $Amule_Group_ID;
			$user_amule['extraoptions'] = "-c \"aMule Daemon\" -d /home/aMule -s /bin/sh";
			$config['system']['usermanagement']['user'][] = $user_amule;			
		}
		array_to_XMLFile($config,"/mnt/Check.xml");
	}
	return 0;
}
//-------------------------------------------------
function write_php_ini($array, $file)
{
	$res = array();
	foreach($array as $key => $val)
	{
		if(is_array($val))
		{
			$res[] = "[$key]";
			foreach($val as $skey => $sval) 
			$res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
		}  
		else{ 
			$res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
		}
	}
	safefilerewrite($file, implode("\r\n", $res));
}
function safefilerewrite($fileName, $dataToSave)
{
	if ($fp = fopen($fileName, 'w'))
	{
		$startTime = microtime(TRUE);
		do
        {            
			$canWrite = flock($fp, LOCK_EX);
			if(!$canWrite) 
				usleep(round(rand(0, 100)*1000));
		} while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));
		if ($canWrite)
		{
			fwrite($fp, $dataToSave);
			flock($fp, LOCK_UN);
		}
		fclose($fp);
	}
}
function parse_XML_file($fileName){
	$data = file_get_contents($fileName,true);
	if(is_string($data)){	
		$Tmp = simplexml_load_string($data);
		if($Tmp <> false){
			$Tmp1 = json_encode($Tmp);
			if(is_string($Tmp1)){
				$config = json_decode($Tmp1, true);
				if($config <> null)
					return $config;
				else{
					echo("Invalid json_decode:".$filename."\n");
					return 3;
				}
			}
			else{
				echo("Invalid json_encode:".$filename."\n");
				return 2;
			}
		}
		else{
			echo("Invalid XML:".$filename."\n");
			return 1;
		}
	}
	else
		echo("Invalid file:".$fileName."\n");
	return 0;
}

function array_to_XMLFile(array $array,string $filename){
	$xml_Object = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xigmanas/>');
	array_to_xml($array, $xml_Object);

	// TO PRETTY PRINT OUTPUT
	$domxml = new DOMDocument("1.0", "ISO-8859-15");
	$domxml->preserveWhiteSpace = false;
	$domxml->formatOutput = true;
	$domxml->loadXML($xml_Object->asXML());
	$domxml->save($filename);	
}
function array_to_xml($array, SimpleXMLElement &$xml) {        
    foreach($array as $key => $value) {               
        if(is_array($value)) {            
            if(!is_numeric($key)){
                $subnode = $xml->addChild($key);
                array_to_xml($value, $subnode);
            } else {
				$subnode = $xml->addChild("item$key");
                array_to_xml($value, $subnode);
            }
        } else {
            $xml->addChild($key, $value);
        }
    }        
}
/*function array_to_XMLFile(array $array,string $filename){
	$xml_Object = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xigmanas/>');
	array_to_XML($array,$xml_Object);
	//saving generated xml file
	
	$xml_Object->asXML("/mnt/Checkme.xml");	
	$dom = new DOMDocument('1.0');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($xml_Object->asXML());
	//$dom->load("/mnt/Checkme.xml");	
	$dom->save($filename);
}
function  array_to_XML(array $array, SimpleXMLElement &$xml_Object){
    foreach($array as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_Object->addChild("$key");
                array_to_xml($value, $subnode);
            }else{
                $subnode = $xml_Object->addChild("item$key");
                array_to_xml($value, $subnode);
            }
        }else {
            $xml_Object->addChild("$key",htmlspecialchars("$value"));
        }
    }	
}*/
/*function Color_Output($color,$Output){
	echo($color.$Output.$CReset."\n");
}*/
function Amule_Conf(){
	$ConfFile = "/home/aMule/.aMule/amule.conf";
	if(file_exists($ConfFile) == false){
		exec("chmod -R 0777 /home/aMule/.aMule");
		$AmuleConf = fopen($ConfFile, "w");
		fclose($AmuleConf);	
		chmod($ConfFile,octdec("666"));
		
	}
	else
		Color_Output("$CGreen","Amule.conf already exist...");
	exec("/etc/rc.d/amuled onestart");
	sleep(1);
	exec("/etc/rc.d/amuled stop");
	$Size = filesize($ConfFile);
	if(($Size <> false) and ($Size > 0)){
		//readline_clear_history();
		//$pass = readline("Insert amule password:");
		//readline_add_history($pass);
		//echo("Pass -> ".readline_list_history()."-".$pass."-".md5($pass)."..\n");
		$pass = InputKeyboard("Insert amule password:");
		echo("Pass -> ".$pass."-".md5($pass)."..\n");
	}
	else
		Color_Output("$CRed","Invalid amule.conf file");
}
/*function read_password($prompt=null, $hide=false)
{
    if($prompt) print $prompt;
    $s = ($hide) ? '-s' : '';
    $f=popen("read $s; echo \$REPLY","r");
    $input=fgets($f,100);
    pclose($f);
    if($hide) print "\n";
    return $input;
}*/
function InputKeyboard($prompt=null){
	if($prompt <> null)
		echo($prompt);
	fscanf(STDIN,"%s",$pass);
	return $pass;
}
function MyXMLManager($filename){
	$config = new DOMDocument("1.0");
	$config->load($filename);
	$x = $config->documentElement;
	foreach ($x->childNodes AS $item) {
		$name = $item->nodeName;
		if(strrpos($name,"#text") === false)
			echo($name . " = " . "\n");
	}	
}
function CheckAmuleGroup(){
	$Amule_Group_ID = 803;
	$config_file = "/cf/conf/config.xml";
	$config = new DOMDocument("1.0");
	$config->preserveWhiteSpace = false;
	$config->formatOutput = true;			
	$config->load($config_file);
	$main = $config->documentElement;
	//['system']['usermanagement']['group']
	$GroupExist = false;
	$UserExist = false;
	foreach($main->childNodes as $item){
		$name = $item->nodeName;
		if(strrpos($name,"#text") === false){//node has a valid name...
			if($name == "system"){	//system node...
				foreach($item->childNodes as $subitem){
					$subname = $subitem->nodeName;
					if(strrpos($subname,"#text") === false){	//node has a valid name
						if($subname == "usermanagement"){
							foreach($subitem->childNodes as $childitem){
								$childname = $childitem->nodeName;
								if(strrpos($childname,"#text") === false){
									if($childname == "group"){	//ok: now we are in group leaf
										foreach($childitem->childNodes as $groupitem){
											$groupname = $groupitem->nodeName;
											if(strrpos($groupname,"#text") === false){
												if($groupname == "name"){
													echo("Gruppo:".$groupitem->textContent."\n");
													if($groupitem->textContent == "aMule")
														$GroupExist = true;
												}
											}
										}
									}
									if($childname == "user"){	//ok: now we are in user leaf
										foreach($childitem->childNodes as $groupitem){
											$groupname = $groupitem->nodeName;
											if(strrpos($groupname,"#text") === false){
												if($groupname == "name"){
													echo("Utente:".$groupitem->textContent."\n");
													if($groupitem->textContent == "aMule")
														$UserExist = true;													
												}
											}
										}
									}
									
								}
							}
							//Now we can add user...
							if($GroupExist == false){
								echo("Amule group doesn't exist...\n");
								$g = $config->createElement("group","");
								$g1 = $config->createElement("uuid","a504481c-cf3c-43a7-97a4-74352fbb406a");
								$g2 = $config->createElement("id",$Amule_Group_ID);
								$g3 = $config->createElement("name","aMule");
								$g->appendchild($g1);
								$g->appendchild($g2);
								$g->appendchild($g3);
								$subitem->appendchild($g);
								$config->save($config_file);
							}
							if($UserExist == false){
								echo("Amule user doesn't exist...\n");
								$g = $config->createElement("user","");
								$g1 = $config->createElement("uuid","1ecbc68e-fede-66b3-074f-9afddcd34aef");
								$g2 = $config->createElement("id",$Amule_Group_ID);
								$g3 = $config->createElement("name","aMule");
								$g4 = $config->createElement("primarygroup",$Amule_Group_ID);
								$g5 = $config->createElement("extraoptions","-c \"aMule Daemon\" -d /home/aMule -s /bin/sh");
								$g->appendchild($g1);
								$g->appendchild($g3);
								$g->appendchild($g2);
								$g->appendchild($g4);
								$g->appendchild($g5);
								$subitem->appendchild($g);		
								$config->save($config_file);								
							}
							
						}
					}
				}
			}
		}
	}
}
?>
