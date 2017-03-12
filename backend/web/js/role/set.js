;
var role_set_opt = {
	init:function(){
		this.eventBind();
	},
	eventBind:function(){
		$('.role_set_wrap .save').click(function () {
			var name = $('.role_set_wrap input[name="name"]').val();
			if (name.length < 1){
				alert("请输入合法的角色名称~~");
				return false;
			}

			$.ajax({
				url: '/role/set',
				type: 'POST',
				data: {
					name: name,
					id: $('.role_set_wrap input[name="id"]').val()
				},
				dataType: 'json',
				success: function(res){
					alert(res.msg);
					if (res.code == 200){
						window.location.href = "/role/index";
					}
				},
			});
		});
	}
};

// page load 
$(document).ready(function(){
	role_set_opt.init();
});