<?php
$q = trim($_GET['q'] ?? $_GET['searchTerm'] ?? $_GET['searchTerms'] ?? '');
$_GET['q'] = $q;

$search = $_GET['by'] ?? $_GET['searchType'] ?? '';
switch ($search) {
	case 'author':
	case 'authors':
		include('search_author.php');
		break;

	default:
		include('search_book.php');
}
?>
