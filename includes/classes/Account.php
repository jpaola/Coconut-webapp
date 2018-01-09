<?php
	class Account {

		private $con;
		private $errorArray;

		// pass on the 'connection' to the construct
		public function __construct($con) {
			$this->con = $con;
			$this->errorArray = array();
		}

		public function login($un, $pw){
			$pwd = md5($pw);

			$query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password='$pwd'");

			if(mysqli_num_rows($query) == 1){
				return true;
			}else{
				array_push($this->errorArray, Constants::$loginFailed);
				return false;
			}
		}

		public function register($un, $fn, $ln, $gen, $em, $em2, $pw, $pw2) {
			$this->validateUsername($un);
			$this->validateFirstName($fn);
			$this->validateLastName($ln);
			$this->validateEmails($em, $em2);
			$this->validatePasswords($pw, $pw2);

			if(empty($this->errorArray) == true) {
				//Insert into db
				return $this->insertUserDetails($un, $fn, $ln, $gen, $em, $pw);
			}
			else {
				return false;
			}
		}

		public function getError($error) {
			if(!in_array($error, $this->errorArray)) {
				$error = "";
			}
			return "<span class='errorMessage'>$error</span>";
		}

		private function insertUserDetails($un, $fn, $ln, $gen, $em, $pw) {
			// encrypt password using md5() algorithm
			$encryptedPwd = md5($pw);
			$date = date("Y-m-d");

			if($gen == "female"){
				$profilePic = "assets/images/profile-pics/female-profile.png";
			}else{
				$profilePic = "assets/images/profile-pics/male-profile.png";
			}
			// insert data in the same order it is held in the db
			$result = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$un', '$fn', '$ln', '$em', '$encryptedPwd', '$date', '$profilePic', '$gen')");
			
			return $result;
		}

		private function validateUsername($un) {

			if(strlen($un) > 25 || strlen($un) < 5) {
				array_push($this->errorArray, Constants::$usernameCharacters);
				return;
			}

			// check if username exists
			$checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");
			if(mysqli_num_rows($checkUsernameQuery) != 0) {
				array_push($this->errorArray, Constants::$usernameTaken);
			}
		}

		private function validateFirstName($fn) {
			if(strlen($fn) > 25 || strlen($fn) < 2) {
				array_push($this->errorArray, Constants::$firstNameCharacters);
				return;
			}
		}

		private function validateLastName($ln) {
			if(strlen($ln) > 25 || strlen($ln) < 2) {
				array_push($this->errorArray, Constants::$lastNameCharacters);
				return;
			}
		}

		private function validateEmails($em, $em2) {
			if($em != $em2) {
				array_push($this->errorArray, Constants::$emailsDoNotMatch);
				return;
			}

			if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
				array_push($this->errorArray, Constants::$emailInvalid);
				return;
			}

			// check if user is not yet registered with this email
			$checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$em'");
			if(mysqli_num_rows($checkEmailQuery) != 0) {
				array_push($this->errorArray, Constants::$emailTaken);
			}

		}

		private function validatePasswords($pw, $pw2) {
			
			if($pw != $pw2) {
				array_push($this->errorArray, Constants::$passwordsDoNotMatch);
				return;
			}

			if(preg_match('/[^A-Za-z0-9]/', $pw)) {
				array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
				return;
			}

			if(strlen($pw) > 30 || strlen($pw) < 5) {
				array_push($this->errorArray, Constants::$passwordCharacters);
				return;
			}
		}
	}
?>