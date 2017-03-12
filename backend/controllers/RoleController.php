<?php
namespace backend\controllers;

use Yii;
use backend\controllers\common\BaseController;
use backend\services\UrlService;
use common\models\Role;
//use common\models\User;

class RoleController extends BaseController{

	/**
	 * [actionIndex description]
	 * @return [type] [description]
	 */
	public function actionIndex(){
		$list = Role::find()->all();
		return $this->render("index", [
			'list' => $list
		]);
	}

	public function actionSet(){
		if (Yii::$app->request->isGet){
			$uid = $this->get("id", 0);
			$info = [];
			if ($uid){
				$info = Role::find()->where( ["id" => $uid])->one();
			}
			return $this->render("set", [
				"info" => $info
			]);
		}
		$name = $this->post('name', "");
		$id = $this->post('id', 0);
		$date_now = date("Y-m-d H:i:s");
		if(!$name){
			return $this->renderJSON( [], "请输入合法的角色名称~~", -1);
		}
		//查询是否存在角色名相等的记录
		$user_info = Role::find()->where(["name"=>$name])->andWhere(['!=', 'id', $id])->one();
		if($user_info){
			return $this->renderJSON([], "该角色名称已存在，请输入其他的角色名称~~", -1);
		}
		$info = Role::find()->where([ 'id' => $id])->one();
		if ($info){ //edit
			$role_model = $info;
		}else{ //add
			$role_model = new Role();
			$role_model->created_time = $date_now;
		}
		
		$role_model->name = $name;
		$role_model->updated_time = $date_now;
		$role_model->save(0);
		return $this->renderJSON([], "操作成功~~", 200);
	}
}