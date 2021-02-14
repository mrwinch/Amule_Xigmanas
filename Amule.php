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
	  Color_Output($CRed,"This script may change Xigmanas configuration!!!\nPlease, close Xigmanas WebGUI before continue");
	  echo($CWhite."Are you ready to start [y/n]?$CReset");
	  $Answer = InputKeyboard();
	  if(strtoupper($Answer) == "Y"){
		  BuildDirectory();
	  }
	  else
		Color_Output($CCyan,"Script termited by user");
}
function BuildDirectory(){
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
$pkg_list = new array {"atk","avahi-app","bash","bash-completion","ca_root_nss","cairo","cryptopp","cups","dbus",
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
	"wx28-gtk2-common","xorg-fonts-truetype","xorgproto","zstd","amule"};	
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
