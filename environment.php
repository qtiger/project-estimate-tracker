<?php
/////////////////////////////////////////////////////////////////////////////////////////////////
// Enviroment file holds the settings which enable PET to connect to the MySQL Database
// Database name must match the MySQL database name. The default from in the database_setup.sql
// script is PET, but if you change that you also need to change the databse name here
// The host is 'localhost' if your MySQL server is on the same server as your Apache server
// otherwise it needs to be the local IP address of the MySQL Server
/////////////////////////////////////////////////////////////////////////////////////////////////
$env['database'] = "PET";
$env['host'] ='localhost';
$env['dbUser'] = '***'; // Database Username here
$env['pw'] = '***'; // Database password here
?>