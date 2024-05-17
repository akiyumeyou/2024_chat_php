$(function() {
	// 表示する
	if($('.eye-slash-solid').on('click', function() {
		$('.eye-slash-solid').css('display', 'none');
		$('.eye-solid').show();
		$('.password-input').attr('type','text');
	}));
	
    // 非常時にする
	if($('.eye-solid').on('click', function() {
		$('.eye-solid').css('display', 'none');
		$('.eye-slash-solid').show();
		$('.password-input').attr('type','password');
	}));
 });



 
