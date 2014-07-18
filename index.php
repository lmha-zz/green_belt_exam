<?php
require('process.php');

unset($_SESSION['first_name']);
unset($_SESSION['user_id']);
$_SESSION['logged_in'] = false;

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>The CodingDojo Crime Watch</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="jumbotron">
					<h1>Welcome to the CodingDojo Crime Watch!</h1>
					<p>Hide your bikes, hide your flipflips, and eat some bacon</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<h3>Sign Up</h3>
				<?php
				if(isset($_SESSION['register_errors'])) {
					foreach ($_SESSION['register_errors'] as $error) {
						?>
						<p class='error'><?= $error ?></p>
						<?php
					}
					unset($_SESSION['register_errors']);
				}
				if(isset($_SESSION['register_success'])) {
						?>
						<p class='success'><?= $_SESSION['register_success'] ?></p>
						<?php
					unset($_SESSION['register_success']);
				}
				?>
				<form class="form-horizontal" role="form" action="process.php" method="post">
					<div class="form-group col-sm-12">
						<div class="row">
							<div class="col-sm-4">
								<label for="first_name">First Name:</label>
							</div>
							<div class="col-sm-8">
								<input class="form-control" type="text" id="first_name" name="first_name">
							</div>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="row">
							<div class="col-sm-4">
								<label for="last_name">Last Name:</label>
							</div>
							<div class="col-sm-8">
								<input class="form-control" type="text" id="last_name" name="last_name">
							</div>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="row">
							<div class="col-sm-4">
								<label for="email">Email:</label>
							</div>
							<div class="col-sm-8">
								<input class="form-control" type="text" id="email" name="email">
							</div>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="row">
							<div class="col-sm-4">
								<label for="password">Password:</label>
							</div>
							<div class="col-sm-8">
								<input class="form-control" type="password" id="password" name="password">
							</div>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="row">
							<div class="col-sm-4">
								<label for="password_confirmation">Confirm Password:</label>
							</div>
							<div class="col-sm-8">
								<input class="form-control" type="password" id="password_confirmation" name="password_confirmation">
							</div>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="row">
							<div class="col-sm-4 pull-right">
								<input class="form-control" type="submit" name="register" value="Register">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="col-sm-6">
				<h3>Sign In</h3>
				<?php
				if(isset($_SESSION['login_errors'])) {
					foreach ($_SESSION['login_errors'] as $error) {
						?>
						<p class='error'><?= $error ?></p>
						<?php
					}
					unset($_SESSION['login_errors']);
				}
				?>
				<form class="form-horizontal" role="form" action="process.php" method="post">
					<div class="form-group col-sm-12">
						<div class="row">
							<div class="col-sm-4">
								<label for="email">Email:</label>
							</div>
							<div class="col-sm-8">
								<input class="form-control" type="text" id="email" name="email">
							</div>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="row">
							<div class="col-sm-4">
								<label for="password">Password:</label>
							</div>
							<div class="col-sm-8">
								<input class="form-control" type="password" id="password" name="password">
							</div>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="row">
							<div class="col-sm-4 pull-right">
								<input class="form-control" type="submit" name="login" value="Login">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>