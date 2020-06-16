<?php
require('consts.php');

header('Content-type: application/json');

$dbException = null;
try {
	$db = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USER, DB_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $db->prepare('SELECT callsign, lst_upd, score, mult, qso, arrl_section, cq_zone, club, mode, bands, ops, xmtrs, power FROM scores_current ORDER BY callsign');
	$dataRows = array();
	if ($stmt->execute() == TRUE) {
		while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$dataRows[] = array('call' => $rec['callsign'],
					'score' => $rec['score'],
					'qso' => $rec['qso'],
					'mult' => $rec['mult'],
					'lstUpd' => $rec['lst_upd'],
					'tooltip' => $rec['club'] . ' / ' . $rec['arrl_section'] . ' / CQ Zone ' . $rec['cq_zone'] . ' / ' . $rec['power'] . ' Power / ' . $rec['xmtrs'] . ' Transmitter(s) / ' . $rec['ops']
					);
		}
	}

	$stmt = $db->prepare('SELECT SUM(score) AS score, SUM(qso) AS qso FROM scores_current');
	if ($stmt->execute() == TRUE) {
		while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$totalRow = array('score' => number_format($rec['score']), 'qso' => number_format($rec['qso']));
		}
	}
} catch (Exception $e) {
	$dbException = $e;
}

echo json_encode(array('data' => $dataRows, 'totals' => $totalRow));
