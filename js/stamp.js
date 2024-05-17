
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const canvas = document.getElementById('preview-canvas');
    const ctx = canvas.getContext('2d');

    // 画像ファイルが選択された時の処理
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image();
                img.onload = function() {
                    // canvasのサイズを画像のサイズに合わせて調整
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0, img.width, img.height);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // テキスト反映ボタンが押された時の処理
    document.getElementById('apply-text').addEventListener('click', function() {
        const text = document.getElementById('text-overlay').value;
        const color = document.getElementById('text-color').value;
        const position = document.getElementById('text-position').value;
        const size = document.getElementById('text-size').value;

        // テキストを描画する前に画像を再度描画（テキストをリフレッシュ）
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

        ctx.font = size + 'px Arial';
        ctx.fillStyle = color;
        ctx.textAlign = 'center';

        let yPos = 0;
        if (position === 'top') {
            yPos = parseInt(size, 10);
        } else if (position === 'center') {
            yPos = canvas.height / 2;
        } else if (position === 'bottom') {
            yPos = canvas.height - parseInt(size, 10);
        }

        ctx.fillText(text, canvas.width / 2, yPos);
    });
});


