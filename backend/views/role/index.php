<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
$this->title = 'add role';
use backend\services\UrlService;
?>
<div>
	<div class="row">
		<div class="col-sm-9 col-md-9 col-lg-9">
			<h5>角色列表</h5>
		</div>
		<div class="col-sm-3 col-md-3 col-lg-3">
			<a href="<?=UrlService::buildUrl("/role/set");?>" class="pull-right btn btn-link">添加角色</a>
		</div>
	</div>
	<hr>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<td>角色</td>
					<td>操作</td>
				</tr>
			</thead>
			<tbody>
				<?php if($list):?>
					<?php foreach ($list as $item):?> 
						<tr>
							<td><?=$item['name'];?></td>
							<td>
								<a href="<?=UrlService::buildUrl("/role/set",[ 'id' => $item['id'] ]);?>">编辑</a>
                            <a href="<?=UrlService::buildUrl("/role/access",[ 'id' => $item['id'] ]);?>">设置权限</a>
							</td>
						</tr>
					<?php endforeach;?>
				<?php else:?>
					<tr>
						<td colspan="2">not find data;</td>
					</tr>
				<?php endif;?>
			</tbody>
		</table>	
	</div>
</div>