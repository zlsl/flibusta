<?php
switch ($url->action) {
	case 'list':
		include('list.php');
		break;
	case 'authorsindex':
		include('authorsindex.php');
		break;
	case 'sequencesindex':
		include('sequencesindex.php');
		break;
	case 'genres':
		include('genres.php');
		break;
	case 'listgenres':
		include('listgenres.php');
		break;
	case 'fav':
		include('fav.php');
		break;
	case 'favs':
		include('favs.php');
		break;
	case 'search':
		include('search.php');
		break;

	default:
		include('main.php');
}
