<?php

include_once 'db_handler.php';

header('Access-Control-Allow-Origin: *'); 
 $db = new DbHandler();
 $card_id=$_GET["cardid"];
 $isbn=$_GET["isbn"];
 $result = [];
 $result = $db->checkOut($card_id,$isbn);

 echo $result["message"];
 //echo $isbn;
 