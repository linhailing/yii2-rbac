<?php
namespace backend\controllers;

use Yii;
use backend\controllers\common\BaseController;
use backend\services\UrlService;
use common\models\User;
use common\models\Role;
use common\models\UserRole;
use backend\models\LoginForm;

/**
 * usercontroller
 */
class UserController extends BaseController{

	// index
	public function actionIndex(){
		$user_info = User::find()->orderBy( ['id' => SORT_DESC] )->all();
		return $this->render("index", [
			"list" => $user_info
		]);
	}
	//login
	public function actionVlogin(){
		$uid = $this->get('uid', 0);
		$reback_url = UrlService::buildUrl("/");
		if( !$uid ){
			return $this->redirect( $reback_url );
		}
		$user_info = User::find()->where( ["id" => $uid] )->one();
		if( !$user_info ){
			return $this->redirect( $reback_url );
		}
		// save cookie
		// role: user_auth_token + "#" + uid
		$this->createLoginStatus($user_info);
		return $this->redirect( $reback_url );
	}
	public function actionLogin(){
		$this->layout = "login";
		$model = new LoginForm();
		if( $model->load(Yii::$app->request->post()) && $model->login()){
			$userInfo = $model->getUser();
			if (!$userInfo){
				return;
			}
			$this->createLoginStatus($userInfo);
			return $this->redirect(UrlService::buildUrl("/"));
		}
		return $this->render("login", [ "model" => $model]);
	}

	/**
	 * [add and edit user]
	 * @return [type] [description]
	 */
	public function actionSet(){
		if (Yii::$app->request->isGet) {
			$uid = $this->get('id', 0);
			$info = [];
			$user_list = User::find()->where( ['id' => $uid, 'status' => 1])->one();
			$user_role_list = UserRole::find()->where( ['uid'=> $uid])->asArray()->all();
			$related_role_ids = array_column($user_role_list, "role_id");
			if ($user_list){
				$info = $user_list;
			}
			$role_list = Role::find()->where( ['status' => 1] )->all();
			return $this->render('set', [
				"role_list" => $role_list,
				"user" => $info,
				"related_role_ids"=> $related_role_ids
			]);
		}
		// post
		$uid = intval($this->post('id', 0));
		$name = trim($this->post("name", ""));
		$email = trim($this->post("email", ""));
		$password = trim($this->post("password", ""));
		$role_ids = $this->post('role_ids', []);
		$date_now = date('Y-m-d H:i:s');
		if (mb_strlen($name, "utf-8") < 1 || mb_strlen($name, "utf-8") > 20){
			return $this->renderJSON([], "请输入合法的姓名~~", -1);
		}
		if( !filter_var( $email , FILTER_VALIDATE_EMAIL) ){
			return $this->renderJSON([],'请输入合法的邮箱~~',-1);
		}
		$has_in = User::find()->where( ['email' => $email])->andWhere( ['!=', 'id',$uid] )->one();
		if ($has_in){
			return $this->renderJSON([], '该邮箱已存在~~', -1);
		}
		$info = User::find()->where( ['id'=> $uid] )->one();
		if ($info){
			$model_user = $info;
		}else{
			$model_user = new User();
			$model_user->status = 1;
			$model_user->created_time = $date_now;
		}
		$model_user->username = $name;
		$model_user->email = $email;
		if($password){
			$model_user->password = md5( $password );
		}
		$model_user->updated_time = $date_now;
		if ($model_user->save(0)){//如果用户信息保存成功，接下来保存用户和角色之间的关系
			/**
			 * 找出删除的角色
			 * 假如已有的角色集合是A，界面传递过得角色集合是B
			 * 角色集合A当中的某个角色不在角色集合B当中，就应该删除
			 * array_diff();计算补集
			 */
			$user_role_list = UserRole::find()->where([ 'uid' => $model_user->id ])->all();
			$related_role_ids = [];
			if( $user_role_list ){
				foreach( $user_role_list as $_item ){
					$related_role_ids[] = $_item['id'];
					if( !in_array( $_item['id'],$role_ids ) ){
						$_item->delete();
					}
				}
			}
			/**
			 * 找出添加的角色
			 * 假如已有的角色集合是A，界面传递过得角色集合是B
			 * 角色集合B当中的某个角色不在角色集合A当中，就应该添加
			 */
			if ( $role_ids ){
				foreach( $role_ids as $_role_id ){
					if( !in_array( $_role_id ,$related_role_ids ) ){
						$model_user_role = new UserRole();
						$model_user_role->uid = $model_user->id;
						$model_user_role->role_id = $_role_id;
						$model_user_role->created_time = $date_now;
						$model_user_role->save(0);
					}
				}
			}
		}
		return $this->renderJSON([], '操作成功~~', 200);

	}
}