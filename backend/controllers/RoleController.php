<?php
namespace backend\controllers;

use Yii;
use backend\controllers\common\BaseController;
use backend\services\UrlService;
use common\models\Role;
use common\models\Access;
use common\models\RoleAccess;

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

	//给角色设置权限
	public function actionAccess(){
		//http get 请求 展示页面
		if( Yii::$app->request->isGet){
			$id = $this->get('id', 0);
			$back_url = UrlService::buildUrl('/role/index');
			if(!$id){
				return $this->redirect($back_url);
			}
			$info = Role::find()->where( ['id' => $id] )->one();
			if(!$info){
				return $this->redirect($back_url);
			}
			$access_list = Access::find()->select(['id', 'title'])->all();
			//取出所有已分配的权限
			$role_access_list = RoleAccess::find()->where( ['role_id'=>$id])->all();
			$access_ids = array_column($role_access_list, 'access_id');
			return $this->render('access', [
				'info' => $info,
				'access_list' => $access_list,
				'access_ids' =>$access_ids
			]);
		}
		// post
		$id = $this->post("id", 0);
		$access_ids = $this->post('access_ids', []);
		if(!$id){
			return $this->renderJSON([],"您指定的角色不存在",-1);
		}
		$info = Role::find()->where( ['id' => $id] )->one();
		if(!$info){
			return $this->renderJSON([],"您指定的角色不存在",-1);
		}
		//取出所有已分配给指定角色的权限
		$role_access_list = RoleAccess::find()->where( ['role_id' => $id] )->asArray()->all();
		$assign_access_ids = array_column($role_access_list, 'access_id');
		/**
		 * 找出删除的权限
		 * 假如已有的权限集合是A，界面传递过得权限集合是B
		 * 权限集合A当中的某个权限不在权限集合B当中，就应该删除
		 * 使用 array_diff() 计算补集
		 */
		$delete_access_ids = array_diff($assign_access_ids, $access_ids);
		if($delete_access_ids){
			RoleAccess::deleteAll(['role_id'=>$id, "access_id"=>$delete_access_ids]);
		}
		/**
		 * 找出添加的权限
		 * 假如已有的权限集合是A，界面传递过得权限集合是B
		 * 权限集合B当中的某个权限不在权限集合A当中，就应该添加
		 * 使用 array_diff() 计算补集
		 */
		$new_access_ids = array_diff($access_ids, $delete_access_ids);
		if($new_access_ids){
			foreach ($new_access_ids as $_access_id) {
				$role_access_model = new RoleAccess();
				$role_access_model->role_id = $id;
				$role_access_model->access_id = $_access_id;
				$role_access_model->created_time = date('Y-m-d H:i:s');
				$role_access_model->save(0);
			}
		}
		return $this->renderJSON([],"操作成功~~",200 );
	}
}