<?php
require_once("funcs.php"); // DB接続関数の呼び出し

$pdo = db_conn(); // DB接続
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

ini_set('display_errors', 1);
error_reporting(E_ALL);

$state = filter_input(INPUT_POST, 'state', FILTER_VALIDATE_INT);
$state = $state === 1 ? 1 : 0;
$conversation_id = 1; // conversation_idを指定

// 更新操作
$sql = "UPDATE P_conversation_table SET ai_flg = ? WHERE conversation_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$state, $conversation_id]);

// 状態をクライアントに返す
echo $state;
?>

