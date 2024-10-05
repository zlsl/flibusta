<?php
define('ROOT_PATH', '/application/');
define('RECORDS_PAGE', 10);
define('BOOKS_PAGE', 10);
define('AUTHORS_PAGE', 50);
define('SERIES_PAGE', 50);
define('OPDS_FEED_COUNT', 100);
define('COUNT_BOOKS', true);
include(ROOT_PATH . 'functions.php');

$dbname = getenv('FLUBUSTA_DBNAME')?getenv('FLUBUSTA_DBNAME'):'flibusta';
$dbhost = getenv('FLUBUSTA_DBHOST')?getenv('FLUBUSTA_DBHOST'):'postgres';
$dbuser = getenv('FLIBUSTA_DBUSER')?getenv('FLIBUSTA_DBUSER'):'flibusta';
if (getenv('FLIBUSTA_DBPASSWORD_FILE')){
		$dbpasswd = file_get_contents(getenv('FLIBUSTA_DBPASSWORD_FILE'));
		if ($dbpasswd) $dbpasswd = trim($dbpasswd);
}
if (!$dbpasswd)
	$dbpasswd = getenv('FLIBUSTA_DBPASSWORD')?getenv('FLIBUSTA_DBPASSWORD'):'flibusta';

$dbtype = getenv('FLIBUSTA_DB_TYPE')?trim(strtolower(getenv('FLIBUSTA_DB_TYPE'))):'postgres';
if ($dbtype != 'postgress') { // check for valid type, currently only postgress is supported, but in the future others e.g. mysql will be added
	error_log('unsupported db type '.$dbtype.', reverting to postgress');
	$dbtype = 'postgres';
}
$dsn = match($dbtype) {
	'postgress' => "pgsql:host=".$dbhost."postgres;dbname=".$dbname.";options='--client_encoding=UTF8'",
	// dsn for supported db types should be added here
	default => "pgsql:host=".$dbhost.";dbname=".$dbname.";options='--client_encoding=UTF8'"
};

try {
	$dbh = new PDO($dsn, $dbuser, $dbpasswd);
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

