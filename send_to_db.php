<?php
session_start();

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit();
	
}
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'test';
$DATABASE_PASS = 'test';
$DATABASE_NAME = 'hmu_db';
// Try and connect using the info above.
$conn = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$professor_id = $_SESSION['name']; //get professor id from PHP Session variables as currently logged user
$student_id = htmlspecialchars($_GET['student_id']); //parse Student ID from URL argument
$lesson_id = htmlspecialchars($_GET['lesson_id']);   //parse Lesson ID from URL argument
$device_id = htmlspecialchars($_GET['device_id']); 	 //parse Device ID from URL argument

date_default_timezone_set('Europe/Athens');  //set correct timezone
$cur_datetime = date('d-m-Y H:i:s');	     //get currect date and time for the request

//Note: Getting datetime in PHP is more secure because it is executed server-side, also user has no control through URL parameter in case of attack

$sql = "INSERT INTO data (professor_id, student_id, lesson_id, datetime, log_device) VALUES ('$professor_id', '$student_id', '$lesson_id', '$cur_datetime', '$device_id')";

if ($conn->query($sql) === TRUE) {
   echo "New record created successfully";
   echo "\n";
   echo "Date and Time is: " . $cur_datetime;
   echo "\n";
   echo "Currently logged user: " . $professor_id;
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
 
?>
