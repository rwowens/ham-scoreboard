<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	<title>OH-KY-IN Club Scores</title>
	<style>
.op-details {
	font-size: 60%;
}
	</style>
</head>
<body>
<div class="container">
	<div class="row"><div class="col-12"><div class="h1 text-center">OH-KY-IN Club Scores</div></div></div>

	<table class="display" id="scoretable" style="width:100%">
		<thead>
			<tr>
				<th scope="col">Call</th>
				<th scope="col" class="text-right">Score</th>
				<th scope="col" class="text-right">QSO's</th>
				<th scope="col" class="text-right">Multiplier</th>
				<th scope="col" class="text-center">Last Update (UTC)</th>
			</tr>
		</thead>
<?php
	require('consts.php');
	$dbException = null;
	try {
		$db = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USER, DB_PASS);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<?php
		$stmt = $db->prepare('SELECT callsign, lst_upd, score, mult, qso, arrl_section, cq_zone, club, mode, bands, ops, xmtrs, power FROM scores_current ORDER BY callsign');
		if ($stmt->execute() == TRUE) {
?>
		<tbody>
<?php
			while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
			<tr>
				<td data-toggle="tooltip" data-placement="left" title="<?php echo $rec['club']; ?> / <?php echo $rec['arrl_section']; ?> / CQ Zone <?php echo $rec['cq_zone'];?> / <?php echo $rec['power']; ?> Power / <?php echo $rec['xmtrs']; ?> Transmitter(s) / <?php echo $rec['ops']; ?>"><?php echo $rec['callsign']; ?></td>
				<td class="text-right"><?php echo number_format($rec['score']); ?></td>
				<td class="text-right"><?php echo number_format($rec['qso']); ?></td>
				<td class="text-right"><?php echo number_format($rec['mult']); ?></td>
				<td class="text-center"><?php echo $rec['lst_upd']; ?></td>
			</tr>
<?php
		}
	}
?>
		</tbody>
<?php
		$stmt = $db->prepare('SELECT SUM(score) AS score, SUM(qso) AS qso FROM scores_current');
		if ($stmt->execute() == TRUE) {
?>
		<tfoot>
<?php
			while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
			<tr>
				<th scope="col">Totals</th>
				<th scope="col" class="text-right"><?php echo number_format($rec['score']); ?></th>
				<th scope="col" class="text-right"><?php echo number_format($rec['qso']); ?></th>
				<th scope="col" class="text-right"></th>
				<th scope="col" class="text-center"></th>
			</tr>
<?php
			}
		}
?>
		</tfoot>
<?php
	} catch (Exception $e) {
		$dbException = $e;
	}
?>
	</table>
<?php
	if ($dbException != null) {
		?><div class="alert alert-danger">An error occurred</div><?php
	}
?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
	$('#scoretable').DataTable({
	"paging": false
	});
});

$(function () {
	$('[data-toggle="tooltip"]').tooltip()
})
</script>
</body>
</html>

