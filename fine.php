<!DOCTYPE html>
<html>
<head>
	<title>Pay Fines</title>
	<meta charset="utf-8"> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<!-- Latest compiled JavaScript -->
		<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
		
</head>
<body>
<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <a class="navbar-brand" href="#">Library</a>
		</div>
		<ul class="nav navbar-nav">
		  <li><a href="index.php">Home</a></li>
		  <li><a href="search.php">Search Books</a></li>
		  <li><a href="checkin.php">Check In</a></li>
		  <li class="active"><a href="fine.php">Pay Fines</a></li>
		  <li><a href="add_borrower.php">Add Borrower</a></li>
		</ul>
	  </div>
	</nav>
<div class="container" style="width:50%;">
	<h1>Fines</h1>
	<center>
	<input type="button" class="btn btn-primary" id="btn" value="Refresh" onclick="getFines()"/>
	</center>
	<div id="tbl"></div>
</div>
<script>
function getFines(){
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET","php/getFines.php",false);
	xmlhttp.send(null);
	document.getElementById("tbl").innerHTML=xmlhttp.responseText;
}
function payFine(data){
	var d = data.childNodes;
	var a=d[0];
	if (confirm("Are you sure you want to Pay Fine.") == true) {
        xmlhttp = new XMLHttpRequest();
		xmlhttp.open("GET","http://localhost/library/php/payFine.php?cardId="+parseInt(a.innerHTML),false);
		xmlhttp.send(null);
		alert(xmlhttp.responseText);
	}
}
</script>