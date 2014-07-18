<?php
require('process.php');

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
	$_SESSION['login_errors'][] = "Please log in.";
	header('Location: /');
	die;
}

$incidents = read_incidents();
$name = explode(' ',$_SESSION['name']);

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
			<div class="page-header col-sm-12">
				<h1>Welcome, <?= ucwords($name[0]) ?><small><a class="pull-right" href="process.php?action=logoff">Log Off</a></small></h1>
			</div>
		</div>
		<?php
		if(isset($_SESSION['witness_log'])) {
			?>
			<div class="row">
				<div class="col-sm-12">
					<p class="success"><?= $_SESSION['witness_log'] ?></p>
				</div>
			</div>
			<?php
			unset($_SESSION['witness_log']);
		}
		?>

		<div class="row">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Incident</th>
						<th class="text-right">Date</th>
						<th>Reported By</th>
						<th class="text-center">Did you see it?</th>
						<th class="text-center">Link</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(!empty($incidents)) {
						foreach ($incidents as $index => $incident) {
							?>
							<tr>
								<td><?= ucwords($incident['name']) ?></td>
								<td class="text-right"><?= date('F jS, Y',strtotime($incident['date'])) ?></td>
								<td><?= ucwords($incident['author']) ?></td>
								<td>
									<form class="form-inline" role="form" action="process.php" method="post">
										<div class="form-group col-sm-12 text-center">
											<input type="hidden" name="incident_id" value="<?= $incident['id'] ?>">
											<input type="submit" name="witnessed" value="Yes">
										</div>
									</form>
								</td>
								<td class="text-center">
									<a href="view.php?incident_id=<?= $incident['id'] ?>">GO</a>
								</td>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>

			<?php
			if(empty($incidents)) {
				?>
				<div class="row">
					<div class="col-sm-12">
						<p>No incidents have been reported.</p>
					</div>
				</div>
				<?
			}
			?>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<h1>Add a new Incident...</h1>
				<?php
				if(isset($_SESSION['incident_errors'])) {
					foreach ($_SESSION['incident_errors'] as $error) {
						?>
						<p class='error'><?= $error ?></p>
						<?php
					}
					unset($_SESSION['incident_errors']);
				}
				?>
			</div>
		</div>
		<div class="row">
			<form class="form-inline" role="form" action="process.php" method="post">
				<div class="form-group col-sm-5">
					<label for="name">Incident Name:</label>
					<input type="text" id="name" name="name">
				</div>
				<div class="form-group col-sm-4">
					<label for="date">Incident Date:</label>
					<input type="date" id="date" name="date">
				</div>
				<div class="form-group col-sm-3">
					<input type="submit" class="btn btn-primary" name="create" value="Create">
				</div>
			</form>
		</div>
	</div>
</body>
</html>