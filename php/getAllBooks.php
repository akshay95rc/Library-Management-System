<?php

include_once 'db_handler.php';

header('Access-Control-Allow-Origin: *'); 
 $db = new DbHandler();
 $search=$_GET["search"];
 //echo $search;
 $result = $db->getAllBooks($search);
 //die(json_encode($result));
 
 if(count($result)>0){
	 echo "<table class='table table-striped'><thead><tr><th>ISBN</th><th>Title</th><th>Author Name</th><th>Available</th></tr></thead><tbody>";
	 foreach($result as $row){
		 
		 echo "<tr id='data' onclick='checkout(this)'>";
		 echo "<td id='isbn'>";echo $row['isbn'];echo "</td>";
		 echo "<td name='title'>";echo $row['title'];echo "</td>";
		 echo "<td name='aname'>";echo $row['a_name'];echo "</td>";
		 if($row['availability']==0){
			 echo "<td name='available'>";echo "False";echo "</td>";
		 }
		 else{
			echo "<td name='available'>";echo "True";echo "</td>";
		 }
		 //echo "<td><input type='button' value='chk' "
		 echo "</tr>";
		
	 }
	 echo "</tbody></table>";
 }
 else{
	 echo "No Results Found!!!";
 }
?>