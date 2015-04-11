<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "ttj";
	session_start();
	
	$sql = $sql1 = "";
	
	// Connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
		die("Connection failed" . mysqli_connect_error()); 
	}
	//echo "Connected successfully";
	
	$email = $_SESSION["email"];
	$password = $_POST["password"];
	
	//encrypt password
	
	$cost = 10;
	$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
	$salt = sprintf("$2a$%02d$", $cost) . $salt;
	$hash = crypt($password, $salt);
	
	$sql = "SELECT * FROM participant WHERE email=".$email;	
	$result = $con->query($sql);
	$new = $result->fetch_assoc();
	$pass = $new["password"];

	
	// verify if new password
	
	if ( hash_equals($pass, crypt($password, $pass)))
	{
		http_response_code(400);
		exit("Parola noua trebuie sa fie diferita de cea existenta!");
	}
	else
	// change existent password
	{
		$sql1 = $con->prepare ("UPDATE participant SET password=? WHERE email=?");
		$sql1 -> bind_param ("ss", $hash, $email);
		$sql1->execute();
		http_response_code(200);
		exit("Parola a fost modificata");
	}
	
	mysqli_close($con);
?>