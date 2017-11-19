<!DOCTYPE html>
<html>
<head>
	<title>Search Books</title>
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
		  <li class="active"><a href="search.php">Search Books</a></li>
		  <li><a href="checkin.php">Check In</a></li>
		  <li><a href="fine.php">Pay Fines</a></li>
		  <li><a href="add_borrower.php">Add Borrower</a></li>
		</ul>
	  </div>
	</nav>
<div class="container">
	<h1>Search Books</h1>
	<input class="form-control" type="text" id="key" name="search" placeholder="search" />
	<br/>
	<center>
	<input type="button" class="btn btn-primary" id="btn" value="Search" onclick="search()"/>
	</center>
	<div id="tbl"></div>
</div>
<script>
function search(){
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET","php/getAllBooks.php?search="+document.getElementById("key").value,false);
	xmlhttp.send(null);
	document.getElementById("tbl").innerHTML=xmlhttp.responseText;
}
function checkout(data){
	var a = data.childNodes;
	if(a[3].innerHTML=="False"){
		alert("Item Not Available.");
	}
	else{
		var card_id = prompt("Please enter Card Id");

		if (card_id != null) {
			xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","php/check_out.php?cardid="+parseInt(card_id)+"&isbn="+a[0].innerHTML,false);
			xmlhttp.send(null);
			alert(xmlhttp.responseText);
		}
	}
}
</script>

</body>
</html>
