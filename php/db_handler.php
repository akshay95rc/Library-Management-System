<?php

class DbHandler {

	
	private $conn;

	function __construct() {
		require_once 'db_connect.php';
		date_default_timezone_set("America/Chicago");
		
		$db = new DbConnect();
		$this->conn = $db->connect();
	}

	function getAllBooks($search) {
        //echo $search;
 		//$stmt = $this->conn->prepare("SELECT book.isbn,title,GROUP_CONCAT(name) as a_name, availability FROM book,book_authors,authors WHERE book.isbn=book_authors.isbn and book_authors.author_id=authors.author_id and CONCAT(book.isbn, title, name) LIKE '%$search%' group by book.isbn");
        $words = explode(",", $search);
		$stf="";
		for($i=0;$i<count($words);$i++){
			$like=" LIKE '%".$words[$i]."%'";
			if($stf!=""){
				$stf=$stf." AND ";
			}
			$stf=$stf."(book.isbn".$like." or title".$like." or name".$like.")"; 
		}
		//echo $stf;
		$stmt = $this->conn->prepare("SELECT book.isbn,title,GROUP_CONCAT(name) as a_name, availability FROM book,book_authors,authors WHERE book.isbn=book_authors.isbn and book_authors.author_id=authors.author_id and $stf group by book.isbn");
	    if ($stmt->execute()) {
            
	     	$stmt->bind_result($isbn, $title,$a_name,$availability);
	     	$i=0;
            $book=[];
	     	while ($stmt->fetch()) {
	     		$book[$i]["isbn"] = $isbn;
	     		$book[$i]["title"] =$title;
                $book[$i]["a_name"] =$a_name;
				$book[$i]["availability"] =$availability;
	     		$i = $i + 1;
	     	}
	     	$stmt->close();
            if($book){
				return $book;
            }
			return null;
	    } 
	    else {
	     	echo "Unable to Execute.";
	    }
    }
	
	function addBorrower() {
		$ssn=mysqli_real_escape_string($this->conn, $_REQUEST['ssn']);
		$f_name=mysqli_real_escape_string($this->conn, $_REQUEST['fname']);
		$l_name=mysqli_real_escape_string($this->conn, $_REQUEST['lname']);
		$address=mysqli_real_escape_string($this->conn, $_REQUEST['address']);
		$city=mysqli_real_escape_string($this->conn, $_REQUEST['city']);
		$state=mysqli_real_escape_string($this->conn, $_REQUEST['state']);
		$phone=mysqli_real_escape_string($this->conn, $_REQUEST['phone']);
		//echo $f_name,$l_name;
		$stmt = $this->conn->prepare("INSERT INTO `borrower`(`ssn`, `fname`, `lname`, `address`, `city`, `state`, `phone`) VALUES ('$ssn','$f_name','$l_name','$address','$city','$state','$phone')");
		if($stmt->execute()){
			$stmt->close();
			echo "Data added Successfully.";
		}
		else{
			echo "Data entry failed. " . mysqli_error($this->conn);
		}
	}
	
	function checkOut($card_id,$isbn){
		$response = array();
		$valid = $this->validCardId($card_id);
		//Current check Out Count
		$current_count =  $this->currentCount($card_id);
		
		if($valid){
			if($current_count<3){
				$stmt = $this->conn->prepare("INSERT INTO `book_loans`(`isbn`, `card_id`, `date_out`, `due_date`) VALUES ($isbn,$card_id,CURDATE(),DATE_ADD(CURDATE(), INTERVAL 14 DAY))");
				$stmt->execute();
				$stmt->close();
				$this->updateQuantity($isbn);
				$this->updateCountIssued($current_count,$card_id);
				$response["error"] = false;
				$response["message"] = "Book issued!";
			}
			else {
				$response["error"] = true;

				$response["message"] = "The user has already checked out 3 books.";
			}
		} 
		else {

			$response["error"] = true;

			$response["message"] = "Card Id does not exist.";
		}

		return $response;

	}
		
	
	function updateCountIssued($current_count,$card_id){
		$count=$current_count+1;
		$stmt = $this->conn->prepare("UPDATE borrower SET act_co = $count WHERE card_id = $card_id");
		$stmt->execute();
		$stmt->close();
	}
	function updateQuantity($isbn){
		$count=
		$stmt = $this->conn->prepare("UPDATE book SET availability = 0 WHERE isbn = $isbn");
		$stmt->execute();
		$stmt->close();

	}
	function validCardId($card_id){
		$stmt = $this->conn->prepare("SELECT card_id FROM borrower WHERE card_id = ?");
		$stmt->bind_param("s", $card_id);
		$stmt->execute();
		$stmt->store_result();
		$num_rows = $stmt->num_rows;
		$stmt->close();
		return ($num_rows == 1);
	}
	function currentCount($card_id){

		$stmt = $this->conn->prepare("SELECT act_co FROM borrower WHERE card_id = ?");

		$stmt->bind_param("s", $card_id);
		
		if($stmt->execute()){

			$stmt->bind_result($act_co);
			
			$stmt->fetch();
			
			$current = $act_co;
			
			$stmt->close();

			return $current;

		}	

	}
	
	
	function getBookLoans($search){
		$card_id=$this->getCardId($search);
		if(count($card_id)>0){
			$str="";
			foreach($card_id as $row){
				$str=$str.(string)$row.",";
			}
			$st=substr($str,0,-1);
			//echo $st;
			$stmt = $this->conn->prepare("SELECT * from book_loans where card_id in ($st) and date_in is null");
			if($stmt->execute()){

				$stmt->bind_result($loan_id,$isbn,$card_id,$date_out,$due_date,$date_in);
				$i=0;
				$loan=[];
				while ($stmt->fetch()) {
					$loan[$i]["loan_id"] = $loan_id;
					$loan[$i]["isbn"] = $isbn;
					$loan[$i]["card_id"] = $card_id;
					$loan[$i]["date_out"] = $date_out;
					$loan[$i]["due_date"] = $due_date;
					$i=$i+1;
				}
				$stmt->close();
			
				return $loan;
			}
			else {
				return "Unable to Execute.";
			}
		}
		else{
			
			return null;
		}
	}
	function getCardId($search){
		$stmt = $this->conn->prepare("SELECT distinct book_loans.card_id FROM book,borrower,book_loans WHERE book.isbn=book_loans.isbn and borrower.card_id=book_loans.card_id and CONCAT(book.isbn, borrower.fname,borrower.lname,book_loans.card_id) LIKE '%$search%' and book_loans.date_in is null");
		if($stmt->execute()){

			$stmt->bind_result($card_id);
			$i=0;
            $cardid=[];
	     	while ($stmt->fetch()) {
	     		$cardid[$i] = $card_id;
				$i=$i+1;
			}
			$stmt->close();

			return $cardid;

		}
	}
	
	function check_in($loan_id,$isbn,$card_id){
		$stmt = $this->conn->prepare("UPDATE book_loans SET date_in = CURDATE() WHERE loan_id = $loan_id");
		$stmt->execute();
		$stmt->close();
		$this->updateBookQuantity($isbn);
		$this->decCountIssued($card_id);
		$response["error"] = false;

		$response["message"] = "Book check in Successfull.";	
	
		return $response;
	}
	
	function updateBookQuantity($isbn){
		$stmt = $this->conn->prepare("UPDATE book SET availability = 1 WHERE isbn = $isbn");
		$stmt->execute();
						
		$stmt->close();
	}
	
	function decCountIssued($card_id){
		$cur=$this->currentCount($card_id);
		$cur=$cur-1;
		$stmt = $this->conn->prepare("UPDATE borrower SET act_co = $cur WHERE card_id = $card_id");
		$stmt->execute();
						
		$stmt->close();
	}
	
	//fines
	
	function getFines(){
		$records=$this->getRecords();
		foreach($records as $row){
			//echo $row['loan_id'];
			$chk=$this->checkInFines($row["loan_id"]);
			//echo $chk;
			if(!$chk){
				//echo $row["loan_id"];
				$this->insertFines($row["loan_id"],$row["due_date"],$row["date_in"]);
			}else{
				//echo $row["loan_id"];
				$this->updateFines($row["loan_id"],$row["due_date"],$row["date_in"]);
			}
		}
		
		$stmt = $this->conn->prepare("SELECT card_id,sum(fine_amt) from fines,book_loans where fines.loan_id=book_loans.loan_id and fines.loan_id in (select loan_id from book_loans c where c.date_in is not null) and paid=0 group by card_id");
		if($stmt->execute()){

			$stmt->bind_result($card_id,$fine_amt);
			$i=0;
            $res=[];
	     	while ($stmt->fetch()) {
				$res[$i]['card_id'] = $card_id;
	     		//$res[$i]['loan_id'] = $loan_id;
				$res[$i]['fine_amt'] = $fine_amt;
				//$res[$i]['paid'] = $paid;
				$i=$i+1;
			}
			$stmt->close();

			return $res;
		}
		else{
			echo "Could not Execute";
		}
	}
	
	function getRecords(){
		$stmt = $this->conn->prepare("SELECT * from book_loans where DATEDIFF(date_in,due_date)>0 OR loan_id in (SELECT loan_id from book_loans b where DATEDIFF(CURDATE(),b.due_date)>0 AND b.date_in is null)");
		if($stmt->execute()){

			$stmt->bind_result($loan_id,$isbn,$card_id,$date_out,$due_date,$date_in);
			$i=0;
            $res=[];
	     	while ($stmt->fetch()) {
	     		$res[$i]['loan_id'] = $loan_id;
				$res[$i]['isbn'] = $isbn;
				$res[$i]['card_id'] = $card_id;
				$res[$i]['date_out'] = $date_out;
				$res[$i]['due_date'] = $due_date;
				$res[$i]['date_in'] = $date_in;
				$i=$i+1;
			}
			$stmt->close();

			return $res;
		}
	}
	
	function checkInFines($loan_id){
		$stmt = $this->conn->prepare("SELECT loan_id from fines where loan_id=$loan_id");
			if($stmt->execute()){

				$stmt->bind_result($loan_id);
				$stmt->fetch();
				$exist=$loan_id;
				$stmt->close();
				if(count($exist)>0){
					return true;
				}
				else{
					return false;
				}
			}
				
	}
	function insertFines($loan_id,$due_date,$date_in){
		//echo $date_in;
		if($date_in==null){
			//echo "here1";
			$this->dateInNull($loan_id,$due_date);
		}
		else{
			//echo "here2";
			$stmt = $this->conn->prepare("INSERT INTO `fines`(`loan_id`, `fine_amt`, `paid`) values (?,0.25*DATEDIFF(?,?),0)");
			$stmt->bind_param("sss",$loan_id,$date_in,$due_date);
			$stmt->execute();
						
			$stmt->close();
		}
	
	}
	function dateInNull($loan_id,$due_date){
		//echo $loan_id;
		$stmt = $this->conn->prepare("INSERT INTO `fines`(`loan_id`, `fine_amt`, `paid`)  values (?,0.25*DATEDIFF(CURDATE(),?),0)");
		$stmt->bind_param("ss",$loan_id,$due_date);
		$stmt->execute();				
		$stmt->close();
	}
		
	
	
	function updateFines($loan_id,$due_date,$date_in){
		if($date_in==null){
			$stmt = $this->conn->prepare("UPDATE fines SET fine_amt = 0.25*DATEDIFF(CURDATE(),?) WHERE loan_id = ? and paid=0");
			$stmt->bind_param("ss",$loan_id,$due_date);
			$stmt->execute();
							
			$stmt->close();
		}
	}
	
	
	//Pay Fine
	function payFine($card_id){
		$response=array();
		$stmt = $this->conn->prepare("UPDATE fines SET paid = 1 WHERE loan_id in (select loan_id from book_loans b where b.card_id=$card_id and date_in is not null) and paid=0");
		$stmt->execute();
							
		$stmt->close();
		$response["error"] = false;
		$response["message"] = "Fine Paid For the returned books.";
		return $response;
	}

}

?>