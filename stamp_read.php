<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);



$pdo = db_conn();

$sql = "SELECT image FROM P_stamp_table";  // image_tableは画像パスを保存しているテーブル名
$statement = $pdo->prepare($sql);
$statement->execute();

$images = $statement->fetchAll();
?>

