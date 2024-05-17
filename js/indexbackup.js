$(document).ready(function() {
    loadMessages(); // メッセージを読み込む

    $(document).on('click', '.stamp-image', function() {
        const isConfirmed = confirm('このスタンプを送信しますか？');
        if (isConfirmed) {
            // 画像URLから画像名を取得してcontentにセット
            const imageUrl = this.src;
            const imageName = imageUrl.substring(imageUrl.lastIndexOf('/') + 1);
            $.ajax({
                url: 'message_send.php',
                type: 'POST',
                data: {
                    user_id: $('input[name="user_id"]').val(),
                    user_name: $('input[name="user_name"]').val(),
                    message_type: 'stamp',
                    content: 'stdata/' + imageName // stdata/ を含めた画像名を送信する
                },
                success: function(response) {
                    loadMessages(); // メッセージを再読込
                    document.getElementById("mySound").play(); // 音声を再生
                },
                error: function(xhr, status, error) {
                    console.error('エラーが発生しました: ' + error);
                }
            });
        } else {
            console.log('送信がキャンセルされました。');
        }
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
});

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
    
    images.forEach(img => {
        img.addEventListener('click', function() {
            const isConfirmed = confirm('このスタンプを送信しますか？');
            if (isConfirmed) {
                const imageName = this.src.split('/').pop(); // 画像URLから画像名を取得する
                $.ajax({
                    url: 'message_send.php',
                    type: 'POST',
                    data: {
                        user_id: $('input[name="user_id"]').val(),
                        user_name: $('input[name="user_name"]').val(),
                        message_type: 'stamp',
                        content: imageName // 画像名のみを送信する
                        
                    },
                    success: function(response) {
                        console.log('メッセージが送信されました。');
                        // 必要に応じて更なるアクション
                    },
                    error: function(xhr, status, error) {
                        console.error('エラーが発生しました: ' + error);
                    }
                });
            } else {
                console.log('送信がキャンセルされました。');
            }
        });
    });
});

});


