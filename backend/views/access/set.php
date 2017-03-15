<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use backend\services\StaticService;
use backend\services\UrlService;
use backend\assets\AppAsset;
StaticService::includeAppJsStatic('/js/access/set.js', AppAsset::className());
?>
<div class="row">
	<div class="col-xs-9 col-sm-9 col-md-9  col-lg-9">
		<h5>新增角色</h5>
	</div>
</div>
<hr/>
<div class="row">
	<div class="form-horizontal access_set_wrap" role="form">
		<div class="form-group">
			<label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">权限名称</label>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<input type="text" class="form-control" name="title" placeholder="请输入权限名称" value="<?=$info?$info['title']:"";?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">权限Urls</label>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<?php
				$tmp_urls = $info?@json_decode($info['urls']):[];
				?>
				<textarea class="form-control" rows="5" placeholder="一行一个url" name="urls"><?=implode("\n\r", $tmp_urls);?></textarea>
			</div>
		</div>
		<div class="col-xs-6 col-xs-offset-2 col-sm-6 col-sm-offset-2 col-md-6  col-md-offset-2 col-lg-6 col-sm-lg-2 ">
            <input type="hidden" name="id" value="<?=$info?$info['id']:"";?>"/>
			<button type="button" class="btn btn-primary pull-right  save">确定</button>
		</div>
	</div>
</div>