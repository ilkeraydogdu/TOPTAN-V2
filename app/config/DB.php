<?php

define("URL", "http://localhost/ilkeraydogdu.site");
define("DB_HOST", "localhost:3306");
define("DB_DATABASE", "kabal");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");

function getDbConnection() {
	try {
		$db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_DATABASE.";charset=utf8", DB_USERNAME, DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $db;
	} catch (PDOException $e) {
		die("Database connection failed: " . $e->getMessage());
	}
}
global $db;
$db = getDbConnection();
?>
