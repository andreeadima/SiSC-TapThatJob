<html>
<body>
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
	
	$group = $_SESSION["course"];
	
	$sql = "SELECT * FROM news WHERE news.group='general' OR news.group='".$group."'";
	$result = $con->query($sql);
	$list = [];
	
	while ($row = $result->fetch_assoc())
	{	
		$new_object = array ( "title" => $row["title"], "text" => $row["text"], "date" => $row["date"]);
		array_push($list, $new_object);
	}
	var_dump(json_encode($list));
	$con->close();
?>
</body>
</html>