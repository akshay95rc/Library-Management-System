<?php

include_once 'db_handler.php';

header('Access-Control-Allow-Origin: *'); 
 $db = new DbHandler();
 $result=$db->getFines();
 if(count($result)>0){
	 echo "<table class='table table-striped'><thead><tr><th>Card Id</th><th>Amount</th></tr></thead><tbody>";
	 foreach($result as $row){
		 echo "<tr id='data' onclick='payFine(this)'>";
		 echo "<td>";echo $row['card_id'];echo "</td>";
		 echo "<td>";echo $row['fine_amt'];echo "</td>";
		 
		 echo "</tr>";
	 }
	 echo "</tbody></table>";
 }
 else{
	 echo "No Results Found!!!";
 }
 ?>