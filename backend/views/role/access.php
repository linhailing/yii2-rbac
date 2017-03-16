<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
$this->title = 'add role';
use backend\services\UrlService;
use backend\assets\AppAsset;
use backend\services\StaticService;
StaticService::includeAppJsStatic('/js/role/access.js', AppAsset::className());
?>
<div class="row">
	<div class="col-lg-9 col-md-9 col-sm-9">
		<h5>为 <?=$info?$info['name']:"";?> 设置权限</h5>
	</div>
</div>
<hr/>
<div class="row">
	<div class="form-horizontal role_access_set_wrap" role="form">
		<div class="form-group">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<?php if ($access_list): ?>
					<?php foreach ($access_list as $_item):?>
						<div class="checkbox">
							<label>
								<input type="checkbox" name="access_ids[]" value="<?=$_item['id']?>"
								<?php if($access_ids && in_array($_item['id'], $access_ids)):?>checked<?php endif;?>
								><?=$_item?$_item['title']:"";?>
							</label>
						</div>
					<?php endforeach;?>
				<?php else:?>
					<h1>没有设置权限在，先去设置权限。谢谢！</h1>
				<?php endif;?>
			</div>
		</div>
		<div class="col-xs-6 col-xs-offset-2 col-sm-6 col-sm-offset-2 col-md-6  col-md-offset-2 col-lg-6 col-sm-lg-2 ">
			<input type="hidden" name="id" value="<?=$info?$info['id']:0;?>">
			<button type="button" class="btn btn-primary pull-right  save">确定</button>
		</div>
	</div>
</div>