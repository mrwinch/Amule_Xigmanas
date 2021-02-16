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
$Amule_Group_ID = 802;
$config_file = "/cf/conf/config.xml";
Presentation();
//--------------------------------------------------------------------------
function Presentation(){
	global $CYellow;
	global $CCyan;
	global $CRed;
	global $CWhite;
	Color_Output($CYellow,"###################################");
	Color_Output($CCyan,"This script will install Amule");
	Color_Output($CYellow,"###################################");
	Color_Output($CRed,"This script may change Xigmanas configuration!!!\nPlease, close Xigmanas WebGUI before continue");
	echo($CWhite."Are you ready to start [y/n]?$CReset");
	$Answer = InputKeyboard();
	if(strtoupper($Answer) == "Y"){
		BuildDirectory();
		Pkg_Installer();
		Conf_Amule();
		Check_Group_User();
		Start_Conf();
		Amule_Complete();
	}
	else
		Color_Output($CCyan,"Script termited by user");
}
function BuildDirectory(){
	global $CCyan;	
	exec("cd /");
	Color_Output($CCyan,"Creating aMule directory...");
	if(file_exists("/home")==false)
		mkdir("home");
	if(file_exists("/home/aMule")==false)	
		mkdir("/home/aMule");
	if(file_exists("/home/aMule/.aMule")==false)	
		mkdir("/home/aMule/.aMule");	
	Color_Output($CCyan,"...done");
}
function Pkg_Installer(){
	global $CCyan;	
	global $CGreen;
	$pkg_list = array ("atk","avahi-app","bash","bash-completion","ca_root_nss","cairo","cryptopp","cups","dbus",
		"dbus-glib","dejavu","desktop-file-utils","encodings","expat","font-bh-ttf","font-misc-ethiopic",
		"font-misc-meltho","fontconfig","freetype2","fribidi","gdbm","gdk-pixbuf2","gettext-runtime",
		"giflib","glib","gmp","gnome_subr","gnutls","graphite2","gtk-update-icon-cache","gtk2","harfbuzz",
		"hicolor-icon-theme","indexinfo","jbigkit","jpeg-turbo","libGLU","libICE","libSM","libX11","libXau",
		"libXcomposite","libXcursor","libXdamage","libXdmcp","libXext","libXfixes","libXft","libXi",
		"libXinerama","libXrandr","libXrender","libXxf86vm","libdaemon","libdrm","libepoll-shim",
		"libevent","libffi","libfontenc","libgd","libiconv","libidn2","liblz4","libmspack","libpaper",
		"libpciaccess","libpthread-stubs","libtasn1","libunistring","libunwind","libxcb","libxml2","libxshmfence",
		"mesa-libs","mkfontscale","nettle","p11-kit","pango","pangox-compat","pciids","pcre","pixman","png",
		"python37","readline","shared-mime-info","tiff","tpm-emulator","trousers","wayland","webp","wx28-gtk2",
		"wx28-gtk2-common","xorg-fonts-truetype","xorgproto","zstd","amule");	
	$cnt = 1;
	$Res = exec("pkg info amule");
	if(strpos($Res,"amule")>-1){
		Color_Output($CCyan,"Amule package already installed");
	}
	else
	{
		Color_Output($CCyan,"Installing packages: this may takes few minutes...");
		foreach($pkg_list as $Pkg){
			Color_Output($CCyan,"Installing ".$Pkg."(".$cnt."/".count($pkg_list).")...");
			exec("pkg install -y $Pkg");
			$cnt = $cnt + 1;
		}
		Color_Output($CCyan,"Packages installation complete");
	}
}
function Conf_Amule(){
	global $CCyan;
	global $CGreen;
	Color_Output($CCyan,"Starting post install configuration...");
	if(file_exists("/etc/rc.d/amuled") == false)
		exec("ln /usr/local/etc/rc.d/amuled /etc/rc.d/amuled");
	else
		Color_Output($CGreen,"Amuled alreay linked");
	$RC_Conf = parse_ini_file("/etc/rc.conf",true);
	if(is_array($RC_Conf)){
		if(array_key_exists("amuled_enable",$RC_Conf) == false){
			exec('echo amuled_enable=\"YES\" >> /etc/rc.conf');
		}
		else
			Color_Output("$CGreen","Amuled already enabled in /etc/rc.conf...");
	}
	else
		Color_Output("$CGreen","Invalid /etc/rc.conf");	
	Color_Output($CCyan,"...done");
}
function Check_Group_User(){
	global $CCyan;
	global $CGreen;
	global $CRed;
	global $Amule_Group_ID;
	global $config_file;
	$group_file = "/etc/group";
	$user_file = "/etc/passwd";
	$Config_File = "/cf/conf/config.xml";	
	Color_Output($CCyan,"Checking user and group...");
	//Checking for group existance...
	if(file_exists($group_file) <> false){
		$group = file_get_contents($group_file,true);
		if(is_string($group)){
			if(strpos($group,"aMule") == false){
				exec("pw groupadd aMule -g".strval($Amule_Group_ID));				
			}
			else
				Color_Output($CGreen,"Amule group already exist: now checking for user...\n");
		}
		else
			Color_Output($CRed,"Bad content in ".$group_file);
	}
	else
		Color_Output($CRed,"Unable to find ".$group_file);
	//Checking if user exist...
	if(file_exists($user_file) <> false){
		$group = file_get_contents($user_file,true);
		if(is_string($group)){
			if(strpos($group,"aMule") == false){
				exec("pw useradd aMule -g aMule -s /bin/sh -c \"aMule Daemon\" -d /home/aMule");				
			}
			else
				Color_Output($CGreen,"Amule user already exist...");
		}
		else
			Color_Output($CRed,"Bad content in ".$user_file);
	}
	else
		Color_Output($CRed,"Unable to find ".$user_file);	
	if(file_exists($config_file)){
		Color_Output($CCyan,"Found config.xml: check for user and group...");
		CheckAmuleGroup();
	}
	exec("chown -R aMule:wheel /home/aMule");
	exec("chmod -R 0777 /home/aMule/.aMule");
	Color_Output($CCyan,"...done");
}
function Start_Conf(){
	global $CCyan;
	global $CGreen;
	global $CRed;
	$ConfFile = "/home/aMule/.aMule/amule.conf";
	Color_Output($CCyan,"Creating and editing amule.conf...");
	if(file_exists($ConfFile) == false){
		$AmuleConf = fopen($ConfFile, "w");
		fclose($AmuleConf);	
		chmod($ConfFile,octdec("666"));		
	}
	exec("/etc/rc.d/amuled onestart");
	sleep(1);
	exec("/etc/rc.d/amuled stop");
	exec("chmod -R 0777 /home/aMule/.aMule");
	$Size = filesize($ConfFile);
	if(($Size <> false) and ($Size > 0)){
		$pass = InputKeyboard("Insert amule password:");
		$md_pass = md5($pass);
		$dir = InputKeyboard("Would you change download directories [y/n]?");
		if(strtoupper($dir) == "Y"){
			$downdir = InputKeyboard("Set directory for complete file: ");
			$incdir = InputKeyboard("Set directory for incomplete file: ");
		}
		//echo("Pass -> ".$pass."-".md5($pass)."..\n");
		$RC_Conf = parse_ini_file($ConfFile, true,INI_SCANNER_RAW);
		if(is_array($RC_Conf)){
			$RC_Conf["ExternalConnect"]["AcceptExternalConnections"] = 1;
			$RC_Conf["ExternalConnect"]["ECPassword"] = $md_pass;
			$RC_Conf["WebServer"]["Enabled"] = 1;
			$RC_Conf["WebServer"]["Password"] = $md_pass;
			$RC_Conf["eMule"]["TempDir"] = $incdir;
			$RC_Conf["eMule"]["IncomingDir"] = $downdir;
			write_php_ini($RC_Conf,$ConfFile);
			
		}
		else
			Color_Output($CRed,"Invalid ".$ConfFile);
	}
	else
		Color_Output($CRed,"Invalid amule.conf file (size 0 byte)");			
	Color_Output($CCyan,"...creation and modification complete");
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
function CheckAmuleGroup(){
	//$Amule_Group_ID = 803;
	global $config_file;
	global $Amule_Group_ID;
	global $CCyan;
	global $CGreen;	
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
													//echo("Gruppo:".$groupitem->textContent."\n");
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
													//echo("Utente:".$groupitem->textContent."\n");
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
								Color_Output($CGreen,"Amule group doesn't exist in config.xml...");
								//echo("Amule group doesn't exist...\n");
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
								//echo("Amule user doesn't exist...\n");
								Color_Output($CGreen,"Amule user doesn't exist in config.xml...");
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
function write_php_ini($array, $file)
{
	$res = array();
	foreach($array as $key => $val)
	{
		if(is_array($val))
		{
			$res[] = "[$key]";
			foreach($val as $skey => $sval) {
				//$res[] = "$skey=".(is_numeric($sval) ? $sval : '"'.$sval.'"');
				$res[] = "$skey=".$sval;
			}
		}  
		else{ 
			//$res[] = "$key=".(is_numeric($val) ? $val : '"'.$val.'"');
			$res[] = "$key=".$val;
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
function Amule_Complete(){
	global $CCyan;	
	global $CGreen;
	exec("/etc/rc.d/amuled start");
	Color_Output($CCyan,"To start aMule: /etc/rc.d/amuled start");
	Color_Output($CCyan,"To stop aMule: /etc/rc.d/amuled stop");
	Color_Output($CCyan,"Amule installation complete: enjoy aMule :)");	
}
?>
