# Introduction #

The following steps take you through how to install PET on your server.

# Requirements #
In order to run PET you will need a server or PC with
  * Apache
  * MySQL
  * PHP 5.2 or later
  * A fixed IP address (at least locally)
  * Smarty v3.1.7 (see section on Smarty below)

You will also need access to a MySQL administrator account on the server. If you are using a standalone PC to act as the PET Server, a packaged installation of Apache, MySQL and PHP (for example XAMPP) may be used.

**IMPORTANT** In the current version, PET has no user security. If you intend to use it, it is strongly recommended that you install it on a LAN and do not expose it to the public internet

# Getting hold of PET #
The downloads area contains the current recommended version of PET in a zip file.
  * PET\_Vx\_x\_x\_rNNN.zip

This file contains the PET sourcecode, templates and database setup script.

PET files should be placed in a directory under the web-root of the Apache server (eg /var/html/pet or htdocs/pet in XAMPP). The templates need to be in a directory below this called /templates (eg /var/html/pet/templates).

# Setting up the database #
  * From the MySQL administrator account open and run the script **database\_setup.sql**. This will create all of the required tables and the basic lookup data
  * Create a user account for the 'PET' database, and grant the user select, insert, update and delete privileges on the PET database
  * Modify the **environment.php** file with the username and password of the database user

**Note**. If you want to install multiple instances of PET, or change the database name you will need to search and replace all instances of PET in the [database\_setup.sql](http://code.google.com/p/project-estimate-tracker/source/browse/trunk/sql/database_setup.sql) file, and change the database name in **environment.php**

# Setting up Smarty #
PET uses a PHP Template Engine called [Smarty](http://www.smarty.net/).  This can be downloaded from http://www.smarty.net/download. The latest version of PET has been built to use Smarty v3.1.7

Smarty's library files need to be installed under the web-root of your Apache server, and the $smartyPath variable in the **smarty\_init.php** needs to point to the installation location of Smarty.

Smarty also needs several directories inside the PET folder. By default these are
  * templates
  * templates/templates\_c
  * templates/cache
  * templates/configs

# Setting up the browser to connect to PET #
In order for the browser to connect to PET it needs a URL. The simplest form of this is the IP address of the server followed by the directory name of PET (eg 192.0.0.10/PET). However, to make things tidier a full URL should be specified. If you are using a conventional Apache installation you can specify this in the Apache configuration files.

If you are using a local installation (for example on XAMPP) you can specify this on the client machines, by altering the **hosts** file. On a windows machine this is stored under

c:\windows\system32\drivers\etc\hosts

Add in a line with your servers IP address and point it to a url. eg

192.0.0.10  PET

from the browser you can then got to http:\\pet\

# Logging in for the first time #
Type in the URL you set  up in the previous stage into the URL bar of your browser. If the set-up worked successfully you should see the PET login window.

For the first time you may login with the username **zzz**. This is the general user - and will allow you to set-up all of the other users needed.