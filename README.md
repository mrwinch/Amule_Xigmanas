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

## Notes
Before installing aMule with this script, **you must read the following informations**
* it's strongly recommended to backup your system  before executing this script: this script will install same packages and change Xigmanas configuration
