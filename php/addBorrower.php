<?php

include_once 'db_handler.php';

header('Access-Control-Allow-Origin: *'); 
 $db = new DbHandler();
 $db->addBorrower();
 //die(json_encode($result));
?>