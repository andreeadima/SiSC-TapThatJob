<html>
<body>
<?php

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "ttj";
	
	// connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
		die("Connection failed" . mysqli_connect_error()); 
	}
	//echo "Connected successfully";

	//get data
	
	$name = $_POST["name"];
	$phone = $_POST["phone"];
	$e_mail = $_POST["e_mail"];
	
	$fileName = $_FILES['cv']['name'];
	$tmpName  = $_FILES['cv']['tmp_name'];
	$fileType = pathinfo($fileName)['extension'];
	$f = array();

	$location = 'uploads/';
	
	//verify data 
	
	function name_v ($data){
		if (!preg_match("/^[a-zA-Z ]*$/",$data)) {
			http_response_code(400);
			exit("Verificati numele introdus"); }
		if (strlen($data)<9 or strlen($data)>255) {
			http_response_code(404);
			exit("Introduceti un nume intre 9 si 255 de caractere"); }
	}
	name_v($name);
		
	function phone_v ($data) {
		if (strlen($data)>14 or strlen($data)<9) {
			http_response_code(400);
			exit("Verificati telefonul introdus");}
	}
	phone_v($phone);
	
	function email_v ($data) {
		if (!filter_var($data,FILTER_VALIDATE_EMAIL)) {
			http_response_code(400);
			exit("Verficati e-mail introdus"); }
	}
	email_v($e_mail);
	
	function cv_v ($data) {
		if ($data != 'doc' and $data != 'docx' and $data != 'pdf') {
			http_response_code(400);
			exit ("Verificati CV atasat");
		}
	}
	cv_v($fileType);

	$ok = 0;
	if (isset($_POST["f1"])){
		$f[1] = 1;
		$ok = 1;
	}
	else { 
		$f[1] = 0;
	}
	if (isset($_POST["f2"])){
		$f[2] = 1;
		$ok = 1;
	}
	else { 
		$f[2] = 0;
	}
	if (isset($_POST["f3"])){
		$f[3] = 1;
		$ok = 1;
	}
	else { 
		$f[3] = 0;
	}
	if (isset($_POST["f4"])){
		$f[4] = 1;
		$ok = 1;
	}
	else { 
		$f[4] = 0;
	}
	if ( $ok != 1) {
	http_response_code(400);
	exit("Nu ati selectat nicio firma");
	}
	
	//verify if already in the database
				
	$sql = "SELECT * FROM participant WHERE e_mail = '".$e_mail."';";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		http_response_code(500);
		exit("E-mail existent in baza de date");
	}
		
	//insert into database
	
	$sql1 = $conn->prepare ("INSERT INTO participant(name,phone,e_mail,firma_1,firma_2,firma_3,firma_4) VALUES (?,?,?,?,?,?,?)");
	$sql1 -> bind_param ("sssiiii", $name, $phone, $e_mail, $f[1], $f[2], $f[3], $f[4]);
	
	$sql1->execute();
	
	//echo $conn->error;
	move_uploaded_file($tmpName,$location.$name.'.'.$fileType);
	http_response_code(200);
	exit("Multumim pentru inscriere!");
	
	
?>
</body>
</html>