<?php
	// inject all variables, configuration, and handlers
	include("includes/config.php");
    include("includes/classes/Account.php");
    include("includes/classes/Constants.php");

	$account = new Account($con);	// pass on the 'connection'

	include("includes/handlers/register-handler.php");
	include("includes/handlers/login-handler.php");

	// (Session controller) Remember form values
	function getInputValue($name) {
		if(isset($_POST[$name])){
			echo $_POST[$name];
		}
	}
?>

<html>
<head>
	<title>Welcome to Coconut!</title>

	<!-- Stylesheets -->
	<link rel="stylesheet" type="text/css" href="assets/css/register.css">

	<!-- Scripts -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="assets/js/register.js"></script>

</head>
<body id="background">
	<!-- Show only relevant data -->
<?php
	if(isset($_POST['registerButton'])){
		echo '<script>
			$(document).ready(function() {
				$("#loginForm").hide();
				$("#registerForm").show();
			});
		</script>';
	}else{
		echo '<script>
			$(document).ready(function() {
				$("#loginForm").show();
				$("#registerForm").hide();
			});
		</script>';
	}
		
?>
		<div id="loginContainer">
			<div id="inputContainer">
				<form id="loginForm" action="register.php" method="POST">
					<h2>Login to your account</h2>
					<p>
						<?php echo $account->getError(Constants::$loginFailed); ?>
						<label for="loginUsername">Username</label>
						<input id="loginUsername" name="loginUsername" type="text" placeholder="username" value="<?php getInputValue('loginUsername') ?>" required>
					</p>
					<p>
						<label for="loginPassword">Password</label>
						<input id="loginPassword" name="loginPassword" type="password" placeholder="password" required>
					</p>

					<button class="button" type="submit" name="loginButton">LOG IN</button>

					<div class="hasAccountText">
						<span id="hideLogin">Don't have an account yet? Signup here.</span>
					</div>
				</form>



				<form id="registerForm" action="register.php" method="POST">
					<h2>Create an account</h2>
					<p>
						<?php echo $account->getError(Constants::$usernameCharacters); ?>
						<?php echo $account->getError(Constants::$usernameTaken); ?>
						<label for="username">Username</label>
						<input id="username" name="username" type="text" placeholder="username" value="<?php getInputValue('username') ?>" required>
					</p>

					<p>
						<?php echo $account->getError(Constants::$firstNameCharacters); ?>
						<label for="firstName">First name</label>
						<input id="firstName" name="firstName" type="text" placeholder="first name" value="<?php getInputValue('firstName') ?>" required>
					</p>

					<p>
						<?php echo $account->getError(Constants::$lastNameCharacters); ?>
						<label for="lastName">Last name</label>
						<input id="lastName" name="lastName" type="text" placeholder="last name" value="<?php getInputValue('lastName') ?>" required>
					</p>

					<p>
						<label for="gender">Gender</label>
						<select name= "gender">
							<option value="male">male</option>
							<option value="female">female</option>
						</select>
					</p>

					<p>
						<?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
						<?php echo $account->getError(Constants::$emailInvalid); ?>
						<?php echo $account->getError(Constants::$emailTaken); ?>
						<label for="email">Email</label>
						<input id="email" name="email" type="email" placeholder="ex. mpao89@gmail.com" value="<?php getInputValue('email') ?>" required>
					</p>

					<p>
						<label for="email2">Confirm email</label>
						<input id="email2" name="email2" type="email" placeholder="ex. mpao89@gmail.com" value="<?php getInputValue('email2') ?>" required>
					</p>

					<p>
						<?php echo $account->getError(Constants::$passwordsDoNotMatch); ?>
						<?php echo $account->getError(Constants::$passwordNotAlphanumeric); ?>
						<?php echo $account->getError(Constants::$passwordCharacters); ?>
						<label for="password">Password</label>
						<input id="password" name="password" type="password" placeholder="password" required>
					</p>

					<p>
						<label for="password2">Confirm password</label>
						<input id="password2" name="password2" type="password" placeholder="password" required>
					</p>

					<button class="button" type="submit" name="registerButton">SIGN UP</button>
					
					<div class="hasAccountText">
						<span id="hideRegister">Already have an account? Login here.</span>
					</div>
				</form>
			</div>

			<div id="loginText">
				<h1>Share your music</h1>
				<h2>Queue up as many songs as you like</h2>
				<ul>
					<li>Invite friends and family to your lobby</li>
					<li>Let everyone queue the songs they like</li>
					<li>No more plugging and unplugging devices</li>
				</ul>
			</div>
		</div>
</body>
</html>