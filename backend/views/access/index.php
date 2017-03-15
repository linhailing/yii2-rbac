<?php
use Yii;
use backend\services\UrlService;
?>
<div>
	<div class="row">
		<div class="col-sm-9 col-md-9 col-lg-9">
			<h5>权限列表</h5>
		</div>
		<div class="col-sm-3 col-md-3 col-lg-3">
			<a href="<?=UrlService::buildUrl("/access/set");?>" class="pull-right btn btn-link">添加权限</a>
		</div>
	</div>
	<hr>
	<div class="table-responsive">
		<table class="table table-condensed">
			<thead>
				<tr>
					<td>Title</td>
					<td>Urls</td>
					<td>创建时间</td>
					<td>操作</td>
				</tr>
			</thead>
			<tbody>
				<?php if ($list):?>
					<?php foreach($list as $_items):?>
						<?php if($_items['urls']): ?>
							<?php $urls_tmp = json_decode($_items['urls'], true);?>
							<?php $urls_tmp = $urls_tmp?$urls_tmp:[];?>
						<?php endif;?>
						<tr>
							<td><?=$_items?$_items['title']:"";?></td>
							<td><?=implode("<br>", $urls_tmp);?></td>
							<td><?=$_items?$_items['created_time']:"";?></td>
							<td><a href="<?=UrlService::buildUrl("/access/set", ['id' => $_items?$_items['id']:0 ]);?>">edit</a></td>
						</tr>
					<?php endforeach;?>
				<?php else:?>
					<tr>
						<td colspan="4">暂无数据~~</td>
					</tr>
				<?php endif;?>
			</tbody>
		</table>	
	</div>
</div>