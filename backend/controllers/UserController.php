<?php
namespace backend\controllers;

use Yii;
use backend\controllers\common\BaseController;
use backend\services\UrlService;
use common\models\User;
use backend\models\LoginForm;

/**
 * usercontroller
 */
class UserController extends BaseController{

	// index
	public function actionIndex(){
		return $this->render("index");
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
}