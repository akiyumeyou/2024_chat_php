
<?php
function get_chat_response($input) {
    $apiKey = ''; // 実際のAPIキーに置き換えてください
    $endpoint = 'https://api.openai.com/v1/chat/completions';

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ];

    $data = [
        'model' => 'gpt-3.5-turbo', // 使用しているモデルを確認してください
        'messages' => [
            ["role" => "system", "content" => "日本語で応答してください"],
            ["role" => "user", "content" => $input]
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        return 'API request failed: ' . curl_error($ch);
    }

    if ($httpcode >= 400) {
        return "API Error: Received HTTP status code " . $httpcode;
    }

    $result = json_decode($response, true);
    if (is_null($result) || !isset($result['choices'][0]['message']['content'])) {
        return 'Failed to get a valid response or invalid API response structure.';
    }

    return $result['choices'][0]['message']['content'];
}
?>
