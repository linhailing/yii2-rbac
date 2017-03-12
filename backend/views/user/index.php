<?php
use Yii;
use backend\services\UrlService;
?>
<div>
	<div class="row">
		<div class="col-sm-9 col-md-9 col-lg-9">
			<h5>用户列表</h5>
		</div>
		<div class="col-sm-3 col-md-3 col-lg-3">
			<a href="<?=UrlService::buildUrl("/user/set");?>" class="pull-right btn btn-link">添加用户</a>
		</div>
	</div>
	<hr>
	<div class="table-responsive">
		<table class="table table-condensed">
			<thead>
				<tr>
					<td>用户id</td>
					<td>用户姓名</td>
					<td>邮箱</td>
					<td>是否管理员</td>
					<td>创建时间</td>
					<td>操作</td>
				</tr>
			</thead>
			<tbody>
				<?php if ($list):?>
					<?php foreach($list as $_items):?>
						<tr>
							<td><?=$_items?$_items['id']:"";?></td>
							<td><?=$_items?$_items['username']:"";?></td>
							<td><?=$_items?$_items['email']:"";?></td>
							<td><?=($_items?$_items['is_admin']:"")?"管理员":"用户";?></td>
							<td><?=$_items?$_items['created_time']:"";?></td>
							<td><a href="<?=UrlService::buildUrl('/user/set',['id'=>$_items['id']]);?>">编辑</td>
						</tr>
					<?php endforeach;?>
				<?php else:?>
					<tr>
						<td colspan="6">暂无数据~~</td>
					</tr>
				<?php endif;?>
			</tbody>
		</table>	
	</div>
</div>