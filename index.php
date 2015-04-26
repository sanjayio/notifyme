<?php
	if(isset($_POST['tag']) && $_POST['tag'] != '') {
		$tag = $_POST['tag'];
		//include database handler
		require_once 'include/DB_Functions.php';
  		$db = new DB_Functions();
  		//create a response array
  		$response = array("tag" => $tag, "success" => 0, "error" => 0);

  		//register tag
  		if($tag == 'register') {
  			//get all POST variables.
  			$fname = $_POST['fname'];
      		$lname = $_POST['lname'];
      		$email = $_POST['email'];
      		$uname = $_POST['uname'];
      		$password = $_POST['password'];
      		//store the user details
      		$user = $db->addUser($fname, $lname, $email, $uname, $password);
      		//check is user is stored successfull?
      		if($user) {
      			//if success, return the values.
      			$response["success"] = 1;
        		$response["user"]["fname"] = $user["fname"];
        		$response["user"]["lname"] = $user["lname"];
        		$response["user"]["email"] = $user["email"];
        		$response["user"]["uname"] = $user["username"];
        		$response["user"]["uid"] = $user["id"];
        		$response["user"]["created_at"] = $user["created_at"];
        		//encode in JSON
        		echo json_encode($response);
      		} else {
      			$response["error"] = 1;
        		$response["error_msg"] = "JSON Error occured in Registration";
        		echo json_encode($response);
      		}
  		}
	} else {
		echo "notifyme API";
	}
?>