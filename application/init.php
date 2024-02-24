<?php
define('ROOT_PATH', '/application/');
define('RECORDS_PAGE', 10);
define('BOOKS_PAGE', 10);
define('AUTHORS_PAGE', 50);
define('SERIES_PAGE', 50);
define('OPDS_FEED_COUNT', 100);
include(ROOT_PATH . 'functions.php');

try {
	$dbh = new PDO("pgsql:host=postgres;dbname=flibusta;options='--client_encoding=UTF8'", 'flibusta', 'flibusta');
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch(Exception $e) {
	print_r($e);
}

session_set_cookie_params(3600 * 24 * 31 * 12,"/");
#session_start();

error_reporting(E_ALL);

$cdt = date('Y-m-d H:i:s');

