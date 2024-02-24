<?php
include('../init.php');
session_start();

if (!isset($_GET['user_uuid'])) {
	die();
}

$user_uuid = $_GET['user_uuid'];
if ($user_uuid == "") {
	die();
}
$bookid = intval($_GET['bookid']);
$pos = floatval($_GET['pos']);

if ($pos == 0) {
	$stmt = $dbh->prepare("DELETE FROM progress WHERE user_uuid=:uuid AND bookid=:id");
	$stmt->bindParam(":uuid", $user_uuid);
	$stmt->bindParam(":id", $bookid);
	$stmt->execute();
	die();
}

$stmt = $dbh->prepare("INSERT INTO progress (user_uuid, bookid, pos) VALUES (:uuid, :id, :pos) ON CONFLICT(user_uuid, bookid) DO UPDATE set pos=:pos2");
$stmt->bindParam(":uuid", $user_uuid);
$stmt->bindParam(":id", $bookid);
$stmt->bindParam(":pos", $pos);
$stmt->bindParam(":pos2", $pos);
$stmt->execute();
