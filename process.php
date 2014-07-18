<?php
session_start();
require('connection.php');

if(isset($_POST['register'])) {
	foreach ($_POST as $key => $value) {
		if(empty($value)) {
			$_SESSION['register_errors'][] = ucwords(str_replace('_', ' ',$key))." is a required field.";
		} else {
			switch ($key) {
				case 'first_name':
				case 'last_name':
					if(!ctype_alpha($value)) {
						$_SESSION['register_errors'][] = ucwords(str_replace('_', ' ',$key))." can only contain alphabetic characters.";
					}
					break;
				case 'email':
					if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
						$_SESSION['register_errors'][] = ucwords(str_replace('_', ' ',$key))." must be a valid email.";
					} else {
						$emails = read_user_emails();
						if(isset($emails) > 0) {
							foreach ($emails as $index => $email) {
								if($value == $email['email']) {
									$_SESSION['register_errors'][] = "The email, ".$value.", has already been registered to another user.";
									break;
								}
							}
						}
					}
					break;
				case 'password':
					if(strlen($value) < 6) {
						$_SESSION['register_errors'][] = ucwords(str_replace('_', ' ',$key))." must be at least six characters long.";
					}
					break;
				case 'password_confirmation':
					if(strlen($value) < 6) {
						$_SESSION['register_errors'][] = ucwords(str_replace('_', ' ',$key))." must be at least six characters long.";
					} else if($value != $_POST['password']) {
						$_SESSION['register_errors'][] = ucwords(str_replace('_', ' ',$key))." must must match Password.";
					}
					break;
			}
		}
	}
	if(isset($_SESSION['register_errors'])) {
		header("Location: index.php");
		die;
	} else {
		create_user($_POST);
		$_SESSION['register_success'] = "Congratulations, you have successfully registered with us! Please sign in.";
		header("Location: index.php");
		die;
	}
}

if(isset($_POST['login'])) {
	foreach ($_POST as $key => $value) {
		if(empty($value)) {
			$_SESSION['login_errors'][] = ucwords(str_replace('_', ' ',$key))." is a required field.";
		} else {
			switch ($key) {
				case 'email':
					if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
						$_SESSION['login_errors'][] = ucwords(str_replace('_', ' ',$key))." must be a valid email.";
					}
					break;
				case 'password':
					if(strlen($value) < 6) {
						$_SESSION['login_errors'][] = ucwords(str_replace('_', ' ',$key))." must be at least six characters long.";
					}
					break;
			}
		}
	}
	if(isset($_SESSION['login_errors'])) {
		header("Location: index.php");
		die;
	} else {
		$user = read_user($_POST['email']);
		if(count($user) > 0) {
			$encPW = md5(escape_this_string($_POST['password']));
			if($encPW == $user['password']) {
				$_SESSION['name'] = $user['first_name'].' '.$user['last_name'];
				$_SESSION['user_id'] = $user['id'];
				$_SESSION['logged_in'] = true;
				header("Location: home.php");
				die;
			} else {
				$_SESSION['login_errors'][] = "The password you have entered does not match what is on record.";
				header("Location: index.php");
				die;
			}
		} else {
			$_SESSION['login_errors'][] = "A user with those credentials does not exist in our system.";
			header("Location: index.php");
			die;
		}
	}
}

if(isset($_POST['create'])) {
	if($_POST['create'] == 'Create') {
		foreach ($_POST as $key => $value) {
			if(empty($value)) {
				$_SESSION['incident_errors'][] = ucwords(str_replace("_", " ", $key))." is required to file an incident.";
			} else {
				if($key == 'date') {
					$date = explode("-", $value);
					if((checkdate($date[1], $date[2], $date[0]) == false)) {
						$_SESSION['error'][] = ucwords(str_replace('_', ' ', $key))." must be a valid date.";
					}
				}
			}
		}
	}
	if(isset($_SESSION['incident_errors'])) {
		header("location: home.php");
		die;
	} else {
		create_incident($_POST);
		header('location: home.php');
		die;
	}
}

if(isset($_POST['witnessed'])) {
	$alreadyWitness = read_user_witnessed_incidents($_SESSION['user_id'], $_POST['incident_id']);
	$incidentInfo = read_incident($_POST['incident_id']);
	if(empty($alreadyWitness)) {
		create_user_witness_report($_POST['incident_id']);
		$_SESSION['witness_log'] = "Thank you for reporting that you witness the ".ucwords($incidentInfo['name'])." incident.";
		header("location:home.php");
		die;
	} else {
		$_SESSION['witness_log'] = "You already reported that you witnessed the ".ucwords($incidentInfo['name'])." incident.";
		header("location:home.php");
		die;
	}
}

if(isset($_POST['delete_incident'])) {
	$incidentInfo = read_incident($_POST['incident_id']);
	delete_incident($_POST['incident_id']);
	$_SESSION['witness_log'] = "You have successfully deleted the ".ucwords($incidentInfo['name'])." incident.";
	header('Location: home.php');
	die;
}

if(isset($_GET['action']) && $_GET['action'] == 'logoff') {
	session_destroy();
	header('Location: index.php');
	die;
}


function create_user($post) {
	$escFName = escape_this_string($post['first_name']);
	$escLName = escape_this_string($post['last_name']);
	$escEmail = escape_this_string($post['email']);
	$encPW = md5(escape_this_string($post['password']));
	$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) VALUES ('{$escFName}', '{$escLName}', '{$escEmail}', '{$encPW}', NOW(), NOW())";
	run_mysql_query($query);
}

function create_incident($post) {
	$escName = escape_this_string($post['name']);
	$query = "INSERT INTO incidents (author_id, name, date, created_at, updated_at) VALUES ({$_SESSION['user_id']}, '{$escName}', '{$post['date']}', NOW(), NOW())";
	run_mysql_query($query);
}

function create_user_witness_report($incident_id) {
	$query = "INSERT INTO user_witnessed_incident (user_id, incident_id) VALUES ({$_SESSION['user_id']}, {$incident_id})";
	run_mysql_query($query);
}

function read_user($email) {
	$escEmail = escape_this_string($email);
	$query = "SELECT * FROM users WHERE email = '{$escEmail}'";
	return fetch_record($query);
}

function read_user_emails() {
	$query = "SELECT email FROM users";
	return fetch_all($query);
}

function read_incidents() {
	$query = "SELECT incidents.id, incidents.date, incidents.name, CONCAT(users.first_name,' ',users.last_name) AS author FROM incidents JOIN users ON incidents.author_id = users.id";
	return fetch_all($query);
}

function read_witnesses($incident_id) {
	$query = "SELECT user_id, CONCAT(users.first_name,' ',users.last_name) AS name FROM user_witnessed_incident JOIN users ON user_witnessed_incident.user_id = users.id WHERE incident_id = {$incident_id}";
	return fetch_all($query);
}

function read_incident($incident_id) {
	$query = "SELECT * FROM incidents WHERE id = {$incident_id}";
	return fetch_record($query);
}

function read_user_witnessed_incidents($user_id, $incident_id) {
	$query = "SELECT * FROM user_witnessed_incident WHERE user_id = {$user_id} AND incident_id = {$incident_id}";
	return fetch_record($query);
}

function delete_incident($incident_id) {
	$query = "DELETE FROM incidents WHERE id = {$incident_id}";
	run_mysql_query($query);
}

?>