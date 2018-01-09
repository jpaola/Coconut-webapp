<?php 

function sanitizeFormPassword($inputText) {
	$inputText = strip_tags($inputText);
	return $inputText;
}

function sanitizeFormUsername($inputText) {
	$inputText = strip_tags($inputText);
	$inputText = str_replace(" ", "", $inputText);
	return $inputText;
}

function sanitizeFormString($inputText) {
	$inputText = strip_tags($inputText);
	$inputText = str_replace(" ", "", $inputText);
	$inputText = ucfirst(strtolower($inputText));
	return $inputText;
}


if(isset($_POST['registerButton'])) {
	//Register button was pressed
	$username = sanitizeFormUsername($_POST['username']);
	$firstName = sanitizeFormString($_POST['firstName']);
	$lastName = sanitizeFormString($_POST['lastName']);
	$email = sanitizeFormString($_POST['email']);
	$email2 = sanitizeFormString($_POST['email2']);
	$password = sanitizeFormPassword($_POST['password']);
	$password2 = sanitizeFormPassword($_POST['password2']);
	$gender = $_POST['gender'];

	// register details in the order they are shown on the UI
	$wasSuccessful = $account->register($username, $firstName, $lastName, $gender, $email, $email2, $password, $password2);

	// User registered successfully and is redirected to index page
	if($wasSuccessful == true) {
		$_SESSION['userLoggedIn'] = $username;	// session successfully established
		header("Location: index.php");
	}

}


?>