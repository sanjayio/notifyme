<?php

class DB_Functions {

    // constructor
    function __construct() {
        
    }

    // destructor
    function __destruct() {
        
    }

    public function connectdb() {
    	require_once 'DB_Connect.php';
        // connecting to database
        $db = new DB_Connect();
        $con = $db->connect();
        return $con;
    }

    public function addUser($fname, $lname, $email, $uname, $password) {
    	$con = $this->connectdb();

        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $result = mysqli_query($con, "INSERT INTO users(fname, lname, email, username, encrypted_password, salt, created_at) VALUES('$fname', '$lname', '$email', '$uname', '$encrypted_password', '$salt', NOW())");
    	// check for successful store
        if ($result) {
            // get user details 
            $uid = mysqli_insert_id($con); // last inserted id
            $res = mysqli_query($con, "SELECT * FROM users WHERE id = $uid");
            // return user details
            return mysqli_fetch_array($res, MYSQLI_ASSOC);
        } else {
            return false;
        }
    }


    //Check if user exists or not
    public function userExist($email) {
    	$con = $this->connectdb();
        $result = mysqli_query($con, "SELECT email from users WHERE email = '$email'");
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            // user exists 
            return true;
        } else {
            // user doesn't exist
            return false;
        }
    }

    //get user by email and password
    public function getUserByEmailAndPassword($email, $password) {
        $con = $this->connectdb();
        $result = mysqli_query($con, "SELECT * FROM users WHERE email = '$email'") or die(mysql_error());
        // check for result 
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $salt = $result['salt'];
            $encrypted_password = $result['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
            return false;
        }
    }


    //returns salt and encrypted password.
    public function hashSSHA($password) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    //get the hash from salt and password.
    public function checkhashSSHA($salt, $password) {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }

}

?>
