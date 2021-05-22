<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/Utils/utils.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/Modules/sqlfill/sqlfill.php';

    global $APP_SECRET;
    $APP_SECRET = "EnterAppSecretHere"; // APP secret should be quite long and hard example: 19234ufbhdsybeyasgd738209hdue7d39287hgd7382<3spuqe
    
    global $sql;
    $host = "localhost";
    $user = "root";
    $pass = "SecureFromHackersPassword1234";
    $dbname = "admin";
    $sql = new SQLFill($host, $user, $pass, $dbname);
   
?>
