# Amule_Xigmanas
A script written in php that install aMule in Xigmanas system or in a jail.


## Installation
To install aMule, you have to access to console with root privileges and execute the following code:
```
fetch https://raw.githubusercontent.com/mrwinch/Amule_Xigmanas/main/Amule.php
chmod a+x Amule.php
./Amule.php
```
If you want to install aMule in a jail, you have to check if php is already install in the jail otherwise, you have to do that: the following code will install php (version 7.4) in the jail
```
pkg install php74
```
after this code, you can execute the code showed above and install correctly aMule.
With this script, you can set password for webGUI and change directories for temporary and complete files

## Notes
Before installing aMule with this script, **you must read the following informations**
* it's strongly recommended to backup your system  before executing this script: this script will install some packages and change Xigmanas configuration
* at any moment, you can start or stop amule with following code
```
/etc/rc.d/amuled start  //start amule
/etc/rc.d/amuled stop   //stop amule
```
* amule configuration files, will be placed in /home/aMule/.aMule directory: configuration data, are stored in amule.conf. Stop amule before editing this file
* default directories for incoming and incomplete file
```
/home/aMule/.aMule/Incoming     //for complete downloaded files
/home/aMule/.aMule/Temp         //during downloading, for incomplete files
```
* to access webGUI, with your favorite browser go to address xxx.yyy.zzz.ttt:4711 and insert password you set in script

