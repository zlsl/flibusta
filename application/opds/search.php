<?php

switch ($url->action) {
	case 'author':
		include('search_author.php');
		break;

	default:
		include('search_book.php');
}