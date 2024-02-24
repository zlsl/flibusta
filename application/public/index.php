<?php

include("../init.php");
session_start();
decode_gurl();

$user_name = 'Книжные полки';
if (isset($_GET['login_uuid'])) {
	$_SESSION['user_uuid'] = $_GET['login_uuid'];
}

if (isset($_GET['delete_uuid'])) {
	$uu = $_GET['delete_uuid'];
	$stmt = $dbh->prepare("DELETE FROM fav_users WHERE user_uuid=:uuid");
	$stmt->bindParam(":uuid", $uu);
	$stmt->execute();
	$st = $dbh->prepare("DELETE FROM fav WHERE user_uuid=:uuid");
	$st->bindParam(":uuid", $uu);
	$st->execute();
}

if (isset($_GET['new_uuid'])) {
	$nname = trim($_GET['new_uuid']);
	if ($nname !== '') {
		$stmt = $dbh->prepare("INSERT INTO fav_users (user_uuid, name) VALUES (uuid_generate_v1(), :name)");
		$stmt->bindParam(":name", $nname);
		$stmt->execute();

		$stmt = $dbh->prepare("SELECT user_uuid FROM fav_users WHERE name=:name LIMIT 1");
		$stmt->bindParam(":name", $nname);
		$stmt->execute();
		$r = $stmt->fetch();
		$user_uuid = $r->user_uuid;
		$user_name = $nname;
		$_SESSION['user_uuid'] = $user_uuid;
	}
}

if (isset($_SESSION['user_uuid'])) {
	$user_uuid = $_SESSION['user_uuid'];
	$stmt = $dbh->prepare("SELECT * FROM fav_users WHERE user_uuid=:uuid");
	$stmt->bindParam(":uuid", $user_uuid);
	$stmt->execute();
	$user = $stmt->fetch();
	if (isset($user->name)) {
		$user_name = $user->name;

		if (isset($_GET['fav_book'])) {
			$id = intval($_GET['fav_book']);
			$st = $dbh->prepare("INSERT INTO fav (user_uuid, bookid) VALUES(:uuid, :id) ON CONFLICT DO NOTHING");
			$st->bindParam(":uuid", $user_uuid);
			$st->bindParam(":id", $id);
			$st->execute();
		}
		if (isset($_GET['fav_author'])) {
			$id = intval($_GET['fav_author']);
			$st = $dbh->prepare("INSERT INTO fav (user_uuid, avtorid) VALUES(:uuid, :id) ON CONFLICT DO NOTHING");
			$st->bindParam(":uuid", $user_uuid);
			$st->bindParam(":id", $id);
			$st->execute();
		}
		if (isset($_GET['fav_seq'])) {
			$id = intval($_GET['fav_seq']);
			$st = $dbh->prepare("DELETE FROM fav WHERE user_uuid=:uuid AND seqid=:id");
			$st->bindParam(":uuid", $user_uuid);
			$st->bindParam(":id", $id);
			$st->execute();
			$st = $dbh->prepare("INSERT INTO fav (user_uuid, seqid) VALUES(:uuid, :id) ON CONFLICT DO NOTHING");
			$st->bindParam(":uuid", $user_uuid);
			$st->bindParam(":id", $id);
			$st->execute();
		}
	
		if (isset($_GET['unfav_book'])) {
			$id = intval($_GET['unfav_book']);
			$st = $dbh->prepare("DELETE FROM fav WHERE user_uuid=:uuid AND bookid=:id");
			$st->bindParam(":uuid", $user_uuid);
			$st->bindParam(":id", $id);
			$st->execute();
		}
		if (isset($_GET['unfav_author'])) {
			$id = intval($_GET['unfav_author']);
			$st = $dbh->prepare("DELETE FROM fav WHERE user_uuid=:uuid AND avtorid=:id");
			$st->bindParam(":uuid", $user_uuid);
			$st->bindParam(":id", $id);
			$st->execute();
		}
		if (isset($_GET['unfav_seq'])) {
			$id = intval($_GET['unfav_seq']);
			$st = $dbh->prepare("DELETE FROM fav WHERE user_uuid=:uuid AND seqid=:id");
			$st->bindParam(":uuid", $user_uuid);
			$st->bindParam(":id", $id);
			$st->execute();
		}
	} else {
		unset($_SESSION['user_uuid']);
		$user_name = 'Книжные полки';
	}
} else {
	$user_uuid = '';
}

if (isset($_GET['sort'])) {
	$sort_mode = $_GET['sort'];
} else {
	$sort_mode = 'abc';
	if ($url->action == '') {
		$sort_mode = 'date';
	}
}

if (isset($_GET['page'])) {
	$page = intval($_GET['page']);
} else {
	$page = 0;
}

$start = $page * RECORDS_PAGE;
$lang = 'ru';
$filter = "";

switch ($sort_mode) {
	case 'abc':
		$order = 'b.Title';
		break;

	case 'author':
		$order = 'b.Title';
		break;

	case 'date':
		$order = 'b.Time DESC';
		break;

	case 'rating':
		$order = 'b.Title';
		break;
}

if ($url->mod == 'opds') {
	include(ROOT_PATH . "/opds/index.php");
} else {
	include(ROOT_PATH . "renderer.php");
}

