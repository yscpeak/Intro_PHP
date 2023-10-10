 <?php

/*******w********

Name: Yi Siang Chang
Date: 2023-10-06
Description: To authenticate to posting and editing.

 ****************/

  define('ADMIN_LOGIN','wally'); /*define('ADMIN_LOGIN','sa');*/

  define('ADMIN_PASSWORD','mypass'); /*define('ADMIN_PASSWORD','sa');*/

  if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])

      || ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN)

      || ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD)) {

    header('HTTP/1.1 401 Unauthorized');

    header('WWW-Authenticate: Basic realm="Our Blog"');

    exit("Access Denied: Username and password required.");

  }

/*This script will prompt for a username and password. If the incorrect user/pass is
provided it will exit and display an error message. Feel free to change the
username and password by modifying the ADMIN_LOGIN and ADMIN_PASSWORD
constants.*/

?>