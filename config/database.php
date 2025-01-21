<?php
require_once 'server.config.php';

$dsn = "mysql:host:$host;port=3306;dbname=$dbName;charset=utf8";

try {

    $PDOConn = new PDO($dsn, $user, $pass);
    $PDOConn->setAttribute(attribute: PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $err) {
    echo "Connection error :" . $err->getMessage();
}
?>