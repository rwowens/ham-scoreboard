<?php
require('consts.php');

header('Content-type: application/json');

$dbException = null;
try {
	$db = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USER, DB_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $db->prepare('SELECT contest FROM scores_current WHERE lst_upd > DATE_SUB(NOW(), INTERVAL 330 DAY) GROUP BY contest ORDER BY MAX(lst_upd) DESC');
	$dataRows = array();
	if ($stmt->execute() == TRUE) {
		while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$contests[] = $rec['contest'];
		}
	}
} catch (Exception $e) {
	$dbException = $e;
}

echo json_encode($contests);
