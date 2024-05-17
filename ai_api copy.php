<?php
$input_json = file_get_contents('php://input');
$post = json_decode($input_json, true);
$req_question = $post['prompt'];
ini_set('display_errors', 1);
error_reporting(E_ALL);
// APIキー
$apiKey = '';
$endpoint = 'https://api.openai.com/v1/chat/completions';

$headers = array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
);

// リクエストのペイロード
$data = array(
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ["role" => "system", "content" => "日本語で応答してください"],
        ["role" => "user", "content" => $req_question]
    ]
);

// cURLリクエストを初期化
$ch = curl_init();

// cURLオプションを設定
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// APIにリクエストを送信
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// cURLリクエストを閉じる
curl_close($ch);

if ($response === false || $httpcode >= 400) {
    echo json_encode(["error" => "API request failed with status $httpcode"], JSON_PRETTY_PRINT);
    exit;
}

// 応答を解析
$result = json_decode($response, true);

// 生成されたテキストを取得（応答の形式に注意）
$text = $result['choices'][0]['message']['content'] ?? '応答データが正しくありません。';

echo json_encode($text, JSON_PRETTY_PRINT);
?>
