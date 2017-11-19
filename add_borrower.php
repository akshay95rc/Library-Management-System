<!DOCTYPE html>
<html>
	<head>
		<title>Add Borrower</title>
		<meta charset="utf-8"> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

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
		  <li><a href="fine.php">Pay Fines</a></li>
		  <li class="active"><a href="add_borrower.php">Add Borrower</a></li>
		</ul>
	  </div>
	</nav>
		<div style="width:50%;margin:auto;">
			<h1>Add Borrower</h1>
			<form action="php/addBorrower.php" method="post">
				<input type="text" class="form-control" name="ssn" placeholder="Enter SSN" required /><br/>
				<input type="text" class="form-control" name="fname" placeholder="Enter First Name" required /><br/>
				<input type="text" class="form-control" name="lname" placeholder="Enter Last Name" required /><br/>
				<input type="text" class="form-control" name="address" placeholder="Address" required /><br/>
				<input type="text" class="form-control" name="city" placeholder="City" required /><br/>
				<input type="text" class="form-control" name="state" placeholder="State" required /><br/>
				<input type="text" class="form-control" name="phone" placeholder="Phone" required /><br/>
				<input type="submit" class="btn btn-primary btn-block" value="submit" required />
			</form>
		</div>
	</body>
</html>