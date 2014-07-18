<?php
require('process.php');

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
	$_SESSION['login_errors'][] = "Please log in.";
	header('Location: /');
	die;
}

$incidentInfo = read_incident($_GET['incident_id']);
$witnesses = read_witnesses($_GET['incident_id']);

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
				<div class="page-header">
					<h1>Incident Name: <?= ucwords($incidentInfo['name']) ?></h1>
					<h1>Incidnt Date:  <?= date('F jS, Y',strtotime($incidentInfo['date'])) ?></h1>
					<?php
					if(empty($witnesses)) {
						?>
							<h1>No witnesses recorded.</h1>
						<?php
					} elseif(count($witnesses) == 1) {
						?>
							<h1>Seen by: <?= count($witnesses) ?> Person</h1>
						<?php
					} else {
						?>
							<h1>Seen by: <?= count($witnesses) ?> People</h1>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php
		if(count($witnesses) > 0) {
			foreach ($witnesses as $index => $person) {
				?>
				<div class="row">
					<div class="col-sm-12">
						<p><?= ucwords($person['name']) ?></p>
					</div>
				</div>
				<?php
			}
		}
		?>
		<div class="row">
			<div class="col-sm-6 text-center">
				<a class="btn btn-success" href="home.php">Back to Home</a>
			</div>
			<div class="col-sm-6 text-center">
				<form role="form" action="process.php" method="post">
					<input type="hidden" name="incident_id" value="<?= $incidentInfo['id'] ?>">
					<input class="btn btn-danger" type="submit" name="delete_incident" value="DELETE THIS INCIDENT">
				</form>
			</div>
		</div>
	</div>
</body>
</html>