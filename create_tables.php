<?php
include_once("include/DB_Connect.php");
$db = new DB_Connect();
$con = $db->connect();


$tbl_users = "CREATE TABLE IF NOT EXISTS users (
              id INT(11) NOT NULL AUTO_INCREMENT,
              fname VARCHAR(16) NOT NULL,
              lname VARCHAR(16) NOT NULL,
              email VARCHAR(255) NOT NULL,
			  username VARCHAR(16) NOT NULL,
			  encrypted_password VARCHAR(255) NOT NULL,
			  salt VARCHAR(255) NOT NULL,
			  created_at DATETIME NOT NULL,
              PRIMARY KEY (id),
			  UNIQUE KEY username (username,email)
             )";
$query = mysqli_query($con, $tbl_users);
if ($query === TRUE) {
	echo "<h3>user table created OK :) </h3>"; 
} else {
	echo "<h3>user table NOT created :( </h3>"; 
}

?>