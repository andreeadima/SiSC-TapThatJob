<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "ttj";
	session_start();

	// Connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
		die("Connection failed" . mysqli_connect_error()); 
	}
	//echo "Connected successfully";
	
	$email = $_POST["email"];
	$password = $_POST["password"];
	$_SESSION["email"] = $email;
	
	$sql = "SELECT password FROM participant WHERE email=".$email;
	$result = $con->query($sql);
	

	if ( $result->num_rows == 0 )
	{
		http_response_code(400);
		exit("Email invalid!");
	}
	
	$new = $result->fetch_assoc();
	$pass = $new["password"];
	$_SESSION["course"] = $new["course"];
	$_SESSION["name"] = $new["name"];
	
	if ( hash_equals($pass, crypt($password, $pass)))
	{
		http_response_code(200);
		exit ("Autentificare reusita!");
	}
	else
	{
		http_response_code(400);
		exit ("Parola introdusa nu este corecta!");
	}
	
	mysqli_close($con);
?>