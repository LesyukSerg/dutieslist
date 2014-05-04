<?
	$bdconn = array(
		'host'		=>	'localhost',
		'dbname'	=>	'list_of_duties',
		'user'		=>	'root',
		'pass'		=>	'',
	);
	mysql_connect($bdconn['host'], $bdconn['user'], $bdconn['pass'], $bdconn['dbname']) or die(mysql_error());
	mysql_select_db($bdconn['dbname']) or die(mysql_error());
	unset($bdconn);
?>