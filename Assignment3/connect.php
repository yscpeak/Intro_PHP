 <?php

 /*******w********

 Name: Yi Siang Chang
 Date: 2023-10-06
 Description: To connect to the database.

  ****************/

    /* 要對應phpmyadmin中的db名、User accounts overview 中的所有資料都要match，
       還有C:\xampp\phpMyAdmin\config.inc.php中的一些資料，都要match。*/
     define('DB_DSN','mysql:host=localhost;dbname=serverside;charset=utf8');
     define('DB_USER','serveruser');
     define('DB_PASS','gorgonzola7!');
     /*define('DB_USER','root');
     define('DB_PASS','');*/
     
    //  PDO is PHP Data Objects
    //  mysqli <-- BAD. 
    //  PDO <-- GOOD.
     try {
         // Try creating new PDO connection to MySQL.
         $db = new PDO(DB_DSN, DB_USER, DB_PASS);
         //,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
     } catch (PDOException $e) {
         print "Error: " . $e->getMessage();
         die(); // Force execution to stop on errors.
         // When deploying to production you should handle this
         // situation more gracefully.
     }
 ?>