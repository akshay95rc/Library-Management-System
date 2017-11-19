<!DOCTYPE html>
<html>
<head>
	<title>Check In</title>
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
		  <li class="active"><a href="checkin.php">Check In</a></li>
		  <li><a href="fine.php">Pay Fines</a></li>
		  <li><a href="add_borrower.php">Add Borrower</a></li>
		</ul>
	  </div>
	</nav>
<div class="container">
	<h1>Check In</h1>
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
	xmlhttp.open("GET","php/check_in_search.php?search="+document.getElementById("key").value,false);
	xmlhttp.send(null);
	document.getElementById("tbl").innerHTML=xmlhttp.responseText;
}
function checkin(data){
	var d = data.childNodes;
	var a=d[0];
	var isbn=d[1];
	var ci=d[2];
	if (confirm("Are you sure you want to check in.") == true) {
        xmlhttp = new XMLHttpRequest();
		xmlhttp.open("GET","http://localhost/library/php/chech_in_book.php?loanid="+parseInt(a.innerHTML)+"&isbn="+isbn.innerHTML+"&cardid="+parseInt(ci.innerHTML),false);
		xmlhttp.send(null);
		alert(xmlhttp.responseText);
	}
}
</script>

</body>
</html>
