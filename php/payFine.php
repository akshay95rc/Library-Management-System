<?php

include_once 'db_handler.php';

header('Access-Control-Allow-Origin: *'); 
 $db = new DbHandler();
 $card_id=$_GET["cardId"];
 $result=$db->payFine($card_id);
 echo $result["message"];
 ?>