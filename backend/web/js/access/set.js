;
var access_set_opt = {
	init: function (){
		this.eventBind();
	},
	eventBind: function(){
		$('.access_set_wrap .save').click(function () {
			var btn_target = $(this);
			if(btn_target.hasClass('disabled')){
				alert("正在处理，请不要重复提交~~");
                return false;
			}
			var title = $('.access_set_wrap input[name="title"]').val();
			var urls = $('.access_set_wrap textarea[name="urls"]').val();
			var id = $('.access_set_wrap input[name="id"]').val();
			if(title.length < 1){
				alert("权限标题不能为空～～");
				return false;
			}
			if (urls.length < 1) {
				alert("权限urls不能为空～～");
				return false;
			}
			btn_target.addClass('disabled');
			$.ajax({
				url: '/access/set',
				type: 'POST',
				data: {
					title: title,
					urls: urls,
					id: id
				},
				dataType: 'json',
				success: function(reg){
					btn_target.removeClass('disabled');
					alert(reg.msg);
					if(reg.code == 200){
						window.location.href = "/access/index";
					}
				}
			})
		});
	}
};

$(document).ready(function() {
	access_set_opt.init();
});