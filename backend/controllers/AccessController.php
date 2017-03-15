<?php
namespace backend\controllers;

use Yii;
use backend\controllers\common\BaseController;
use common\models\Access;

/**
 * accesscontroller
 */
class AccessController extends BaseController{
	// list
	public function actionIndex(){
		$access_list = Access::find()->where( [ 'status' => 1])->all();
		return $this->render("index", [
			'list' => $access_list
		]);
	}
	public function actionSet(){
		if(Yii::$app->request->isGet){
			$id = $this->get('id', 0);
			$info = [];
			$access_info = Access::find()->where( ['id' => $id] )->one();
			if ($access_info){
				$info = $access_info;
			}
			return $this->render('set', [
				'info' => $info
			]);
		}
		$id = intval($this->post('id', 0));
		$title = trim($this->post('title', ""));
		$urls = trim($this->post('urls', ""));
		$date_now = date('Y-m-d H:i:s');
		if (mb_strlen($title,"utf-8") < 1 || mb_strlen($title, "utf-8") > 20){
			return $this->renderJSON( [], "请输入合法的权限标题~~", -1);
		}
		if(!$urls){
			return $this->renderJSON( [], "请输入合法的Urls~~", -1);
		}
		$urls = explode("\n", $urls);
		if(!$urls){
			return $this->renderJSON( [], "请输入合法的Urls~~", -1);
		}
		//查询同一标题的是否存在
		$has_in = Access::find()->where( ['title' => $title])->andWhere( [ '!=', 'id', $id])->count();
		if( $has_in){
			return $this->renderJSON( [], "该权限标题已存在~~", -1);
		}
		//查询指定id的权限
		$info = Access::find()->where( ['id' => $id] )->one();
		if($info){
			$access_model = $info;
		}else{
			$access_model = new Access();
			$access_model->status = 1;
			$access_model->created_time = $date_now;
		}
		$access_model->title = $title;
		$access_model->urls = json_encode( $urls );//json格式保存的
		$access_model->updated_time = $date_now;
		$access_model->save(0);
		return $this->renderJSON([],'操作成功~~');
	}
}