<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use backend\services\StaticService;
use backend\services\UrlService;
use backend\assets\AppAsset;
StaticService::includeAppJsStatic('/js/user/set.js', AppAsset::className());
?>
<div class="row">
	<div class="col-xs-9 col-sm-9 col-md-9  col-lg-9">
		<h5>新增用户</h5>
	</div>
</div>
<hr/>
<div class="row">
	<div class="form-horizontal user_set_wrap" role="form">
		<div class="form-group">
			<label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">用户名</label>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<input type="text" class="form-control" name="name" placeholder="请输入用户名" value="<?=$user?$user['username']:"";?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">邮箱</label>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<input type="text" class="form-control" name="email" placeholder="请输入邮箱" value="<?=$user?$user['email']:"";?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">密码</label>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<input type="text" class="form-control" name="password" placeholder="请输入密码" value="">
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">所属角色</label>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<?php if( $role_list ):?>
					<?php foreach ($role_list as $_items):?>
						<div class="checkbox">
							<label>
								<input type="checkbox" name="role_ids[]" value="<?=$_items?$_items['id']:0;?>" 
								<?php if($related_role_ids && in_array($_items['id'],$related_role_ids)):?> checked<?php endif;?>><?=$_items?$_items['name']:"";?>
							</label>
						</div>
					<?php endforeach;?>
				<?php endif;?>
			</div>
		</div>
		<div class="col-xs-6 col-xs-offset-2 col-sm-6 col-sm-offset-2 col-md-6  col-md-offset-2 col-lg-6 col-sm-lg-2 ">
            <input type="hidden" name="id" value="<?=$user?$user['id']:0;?>"/>
			<button type="button" class="btn btn-primary pull-right  save">确定</button>
		</div>
	</div>
</div>