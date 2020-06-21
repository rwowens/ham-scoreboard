<?php
require('consts.php');
$auth = $_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW'];
if (UPLOAD_PASSWORD != $_SERVER['PHP_AUTH_PW']) {
	die('Invalid username or password.');
}

if ($debug == true) {
	$entityBody = file_get_contents('php://input');
	var_dump($entityBody);
	$xml = simplexml_load_string($entityBody) or die('Unable to parse XML');
} else {
	$xml = simplexml_load_file('php://input') or die('Unable to parse XML');
}

if ($xml !== false && strcasecmp($xml->call, $_SERVER['PHP_AUTH_USER']) == 0) {
	$callsign = filter_var($xml->call, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$club = filter_var($xml->club, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$arrlSection = filter_var($xml->qth->arrlsection, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$score = filter_var($xml->score, FILTER_SANITIZE_NUMBER_INT);
	$mult = filter_var($xml->breakdown->mult, FILTER_SANITIZE_NUMBER_INT);
	$qso = filter_var($xml->breakdown->qso, FILTER_SANITIZE_NUMBER_INT);
	$cqZone = filter_var($xml->qth->cqzone, FILTER_SANITIZE_NUMBER_INT);
	$mode = filter_var($xml->class['mode'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$bands = filter_var($xml->class['bands'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$ops = filter_var($xml->class['ops'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$xmtrs = filter_var($xml->class['transmitter'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$power = filter_var($xml->class['power'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$contest = filter_var($xml->contest, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	if (!($callsign && $club && $arrlSection && $contest)) {
		http_response_code(400);
		if (!$callsign) echo('Callsign is missing; ');
		if (!$club) echo('Club name is missing; ');
		if (!$arrlSection) echo('ARRL Section is missing; ');
		if (!$contest) echo('Contest value is missing; ');
		die('Invalid input provided');
	}
	
	try {
		$db = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USER, DB_PASS);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$db->beginTransaction();

		$sql_scores = 'INSERT INTO scores (callsign, lst_upd, score, mult, qso, arrl_section, cq_zone, club, mode, bands, ops, xmtrs, power, contest) VALUES (:callsign, :lstUpd, :score, :mult, :qso, :arrlSection, :cqZone, :club, :mode, :bands, :ops, :xmtrs, :power, :contest)';
		$stmt = $db->prepare($sql_scores);
		$stmt->bindParam('callsign', $callsign);
		$stmt->bindParam('lstUpd', $xml->timestamp);
		$stmt->bindParam('score', $score);
		$stmt->bindParam('mult', $mult);
		$stmt->bindParam('qso', $qso);
		$stmt->bindParam('arrlSection', $arrlSection);
		$stmt->bindParam('cqZone', $cqZone);
		$stmt->bindParam('club', $club);
		$stmt->bindParam('mode', $mode);
		$stmt->bindParam('bands', $bands);
		$stmt->bindParam('ops', $ops);
		$stmt->bindParam('xmtrs', $xmtrs);
		$stmt->bindParam('power', $power);
		$stmt->bindParam('contest', $contest);
		if ($stmt->execute() != TRUE) {
			http_response_code(500);
		}

		$sql_scores = 'INSERT INTO scores_current (callsign, lst_upd, score, mult, qso, arrl_section, cq_zone, club, mode, bands, ops, xmtrs, power, contest) VALUES (:callsign, :lstUpd, :score, :mult, :qso, :arrlSection, :cqZone, :club, :mode, :bands, :ops, :xmtrs, :power, :contest) ON DUPLICATE KEY UPDATE lst_upd=VALUES(lst_upd), score=VALUES(score), mult=VALUES(mult), qso=VALUES(qso), arrl_section=VALUES(arrl_section), cq_zone=VALUES(cq_zone), club=VALUES(club), mode=VALUES(mode), bands=VALUES(bands), ops=VALUES(ops), xmtrs=VALUES(xmtrs), power=VALUES(power), contest=VALUES(contest)';
		$stmt = $db->prepare($sql_scores);
		$stmt->bindParam('callsign', $callsign);
		$stmt->bindParam('lstUpd', $xml->timestamp);
		$stmt->bindParam('score', $score);
		$stmt->bindParam('mult', $mult);
		$stmt->bindParam('qso', $qso);
		$stmt->bindParam('arrlSection', $arrlSection);
		$stmt->bindParam('cqZone', $cqZone);
		$stmt->bindParam('club', $club);
		$stmt->bindParam('mode', $mode);
		$stmt->bindParam('bands', $bands);
		$stmt->bindParam('ops', $ops);
		$stmt->bindParam('xmtrs', $xmtrs);
		$stmt->bindParam('power', $power);
		$stmt->bindParam('contest', $contest);

		if ($stmt->execute() != TRUE) {
			http_response_code(500);
		}
		$db->commit();
	} catch (Exception $e) {
		$db->rollBack();
		http_response_code(500);
		var_dump($e->getMessage());
		die('Failed to save update.');
	}

?>
<html>
<body>
Data successfully uploaded for <?php echo $callsign; ?><br/>

Call: <?php echo $callsign;?><br/>
Power: <?php echo $power;?><br/>
Transmitters: <?php echo $xmtrs;?><br/>
Ops: <?php echo $ops;?><br/>
Bands: <?php echo $bands;?><br/>
Mode: <?php echo $mode;?><br/>
Club: <?php echo $club;?><br/>
CQ Zone: <?php echo $cqZone;?><br/>
ARRL Section: <?php echo $arrlSection;?><br/>
QSO Total: <?php echo $qso;?><br/>
Multiplier: <?php echo $mult;?><br/>
Score: <?php echo $score;?><br/>
Last Update: <?php echo $xml->timestamp;?><br/>
Contest: <?php echo $contest;?><br/>
</body>
</html>
<?php
} else {
	http_response_code(401);
	?>Invalid data received or invalid username or password.<?php
}
