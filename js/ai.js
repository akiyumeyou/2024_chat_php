document.addEventListener('DOMContentLoaded', function() {
    var button = document.getElementById("toggleButton");
    button.addEventListener("click", function() {
        var currentState = button.classList.contains("on") ? 0 : 1;  // 現在の状態を取得してトグル

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "ai_ck.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = xhr.responseText.trim(); // 応答をトリムして余計な空白を除去
                if (response === "1") {
                    button.classList.remove("off");
                    button.classList.add("on");
                    button.textContent = "ON";
                } else {
                    button.classList.remove("on");
                    button.classList.add("off");
                    button.textContent = "OFF";
                }
            } else {
                alert('Request failed.  Returned status of ' + xhr.status);
            }
        };
        xhr.send("state=" + currentState);
    });
});

