<?php

include_once 'db_handler.php';

header('Access-Control-Allow-Origin: *'); 
 $db = new DbHandler();
 $loan_id=$_GET["loanid"];
 $isbn=$_GET["isbn"];
 $card_id=$_GET["cardid"];
 $result=$db->check_in($loan_id,$isbn,$card_id);
 echo $result["message"];
 ?>