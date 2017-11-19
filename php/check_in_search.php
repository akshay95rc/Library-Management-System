<?php

include_once 'db_handler.php';

header('Access-Control-Allow-Origin: *'); 
 $db = new DbHandler();
 $search=$_GET["search"];
 $result = $db->getBookLoans($search);
 if(count($result)>0){
	 echo "<table class='table table-striped'><thead><tr><th>Loan Id</th><th>ISBN</th><th>Card Id</th><th>Date Out</th><th>Due Date</th></tr></thead><tbody>";
	 foreach($result as $row){
		 echo "<tr id='data' onclick='checkin(this)'>";
		 echo "<td>";echo $row['loan_id'];echo "</td>";
		 echo "<td>";echo $row['isbn'];echo "</td>";
		 echo "<td>";echo $row['card_id'];echo "</td>";
		 echo "<td>";echo $row['date_out'];echo "</td>";
		 echo "<td>";echo $row['due_date'];echo "</td>";
		 echo "</tr>";
	 }
	 echo "</tbody></table>";
 }
 else{
	 echo "No Results Found!!!";
 }
?>