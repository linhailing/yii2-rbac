;
var user_set_opt = {
	init:function(){
		this.eventBind();
	},
	eventBind:function(){
		$('.user_set_wrap .save').click(function () {
			var btn_target = $(this);
			if(btn_target.hasClass('disabled')){
				alert("正在处理，请不要重复提交~~");
                return false;
			}
			var name = $('.user_set_wrap input[name="name"]').val();
			var email = $('.user_set_wrap input[name="email"]').val();
			var password = $('.user_set_wrap input[name="password"]').val();
			id = $('.user_set_wrap input[name="id"]').val();
			if(name.length < 1){
				alert('请输入合法的姓名~~');
				return false;
			}
			if (email.length < 1){
				alert("请输入合法的邮箱~~");
                return false;
			}
			var role_ids = [];
			$('.user_set_wrap input[name="role_ids[]"]').each(function() {
				if($(this).prop("checked")){
					role_ids.push($(this).val());
				}
			});
			btn_target.addClass("disabled");
			$.ajax({
				url: '/user/set',
				type: 'POST',
				data: {
					id: id,
					name: name,
					email: email,
					password: password,
					role_ids: role_ids
				},
				dataType: 'json',
				success:function(res){
					btn_target.removeClass("disabled");
                    alert( res.msg );
                    if( res.code == 200 ){
                        window.location.href = "/user/index";
                    }
				}
			});
		});
	}
};

$(document).ready(function (){
	user_set_opt.init();
});