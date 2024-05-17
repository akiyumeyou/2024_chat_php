<?php
session_start();

//１．関数群の読み込み
include("funcs.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

//LOGINチェック → funcs.phpへ関数化しましょう！
sschk();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>POTZ チャットアプリ</title>
    <link href="css/chat.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
<header class="header">
    <div><?= htmlspecialchars($_SESSION["user_name"], ENT_QUOTES); ?>さん</div>
</header>
<?php include("inc/menu.php");?>

<div id="phone">
    <div id="screen">
        <div id="output" class="scroll_bar" style="overflow-y: auto; overflow-x: hidden; height: 600px;"></div>
        <audio id="mySound" src="sound/syupon01.mp3" preload="auto"></audio>
        <form method="POST" action="message_send.php" enctype="multipart/form-data">
            <div class="send_wrap">
                <fieldset>
                    <label>投稿：<?= htmlspecialchars($_SESSION["user_name"], ENT_QUOTES); ?></label><br>
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION["user_id"], ENT_QUOTES); ?>">
                    <input type="hidden" name="user_name" value="<?= htmlspecialchars($_SESSION["user_name"], ENT_QUOTES); ?>">
                    <input type="text" name="content" class="chat_input">
                    <button id="send" type="submit"><img src="img/btn_send.png" alt="送信"></button>
                </fieldset>
            </div>
            <?php include("stamp_read.php"); ?>
            <div id="image-gallery" class="gallery">
            <?php foreach ($images as $image): ?>
                <img src="<?php echo htmlspecialchars($image['image']); ?>" alt="Image" class="stamp-image">
            <?php endforeach; ?>    
            </div>
        </form>
    </div>
    <!-- <?php include("ai_flg.php"); ?>  -->
    <button id="toggleButton" class="button <?= $ai_flg == 1 ? 'on' : 'off' ?>"><?= $ai_flg == 1 ? 'ON' : 'OFF' ?></button>
    <script src="js/ai.js"></script>
    <a href="stamp.php">スタンプ作成</a>
</div>
<?php include("inc/foot.html"); ?>
<script>
$(document).ready(function() {
    loadMessages(); // メッセージを読み込む
    $(document).on('click', '.stamp-image', function() {
    const isConfirmed = confirm('このスタンプを送信しますか？');
    if (isConfirmed) {
        const imageName = this.src.split('/').pop(); // この行を追加して画像名を取得
        $.ajax({
            url: 'message_send.php',
            type: 'POST',
            data: {
                user_id: $('input[name="user_id"]').val(),
                user_name: $('input[name="user_name"]').val(),
                message_type: 'stamp',
                content: imageName // 修正されたデータ
            },
            success: function(response) {
                loadMessages(); // メッセージを再読込
                document.getElementById("mySound").play(); // 音声を再生
            },
            error: function(xhr, status, error) {
                console.error('エラーが発生しました: ' + error);
                console.log('サーバーからのレスポンス: ', xhr.responseText);
            }
        });
    } else {
        console.log('送信がキャンセルされました。');
    }
});

    



});


// メッセージを読み込む関数をグローバルスコープに移動
var currentUserId = <?= json_encode($_SESSION['user_id']); ?>; // PHPセッションからユーザーIDを取得

function loadMessages() {
    $.ajax({
        url: 'message_load.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const output = $('#output');
            output.empty(); // 古いメッセージをクリア

            if (data.length > 0) {
                $.each(data, function(i, message) {
                    var date = new Date(message.timestamp);
                    var formattedTime = (date.getMonth() + 1) + '/' + date.getDate() + ' ' +
                                        date.getHours() + ':' + 
                                        (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes());

                    var alignmentClass = (message.user_id == currentUserId) ? 'right' : 'left';
                    var userClass = (message.user_id == currentUserId) ? 'its_me' : 'not_me'; 
                    var timeAlignmentClass = (message.user_id == currentUserId) ? 'time-right' : 'time-left';
                    var messageHtml;

                    if (message.message_type === 'stamp') {
                        // スタンプの場合
                        messageHtml = '<div class="message-wrapper ' + alignmentClass + '">' + 
                          '<div class="user-name">♡: ' + $('<div/>').text(message.user_name).html() + '</div>' +
                          '<img src="' + message.content + '" style="max-width: 380px;">' +
                          '<div class="message-time ' + timeAlignmentClass + '">' + formattedTime + '</div>' +
                          '</div>';
                     } else {
            // テキストの場合
                         messageHtml = '<div class="message-wrapper ' + alignmentClass + '">' + 
                          '<div class="user-name">♡: ' + $('<div/>').text(message.user_name).html() + '</div>' +
                          '<div class="message-content ' + userClass + '">' + $('<div/>').text(message.content).html() + '</div>' + // userClass
                          '<div class="message-time ' + timeAlignmentClass + '">' + formattedTime + '</div>' +
                          '</div>';
        }
                    output.append(messageHtml);
                });
            }
        },
        error: function() {
            alert('メッセージの読み込みに失敗しました。');
        }
    });
}


$(document).ready(function() {
    loadMessages(); // ページ読み込み時にメッセージを読み込む

    $('#send').click(function(e) {
        e.preventDefault();
        var content = $('input[name="content"]').val();
        if (!content) {
            alert('メッセージを入力してください。');
            return;
        }
        $.ajax({
            url: 'message_send.php',
            type: 'POST',
            data: {
                user_id: $('input[name="user_id"]').val(),
                user_name: $('input[name="user_name"]').val(),
                message_type: 'text', // message_typeの修正
                content: content
            },
            success: function(response) {
                $('input[name="content"]').val(''); // 入力フィールドをクリア
                loadMessages(); // メッセージを再読込
                document.getElementById("mySound").play(); // ここで音声を再生
            },
            error: function(xhr, status, error) {
                alert('メッセージの送信に失敗しました。エラー: ' + xhr.responseText);
            }
        });
    });

  

    document.addEventListener('DOMContentLoaded', function() {
    // ギャラリー内のすべてのスタンプ画像を取得
    const images = document.querySelectorAll('.stamp-image');
    
    
});

});

</script>

</body>
</html>