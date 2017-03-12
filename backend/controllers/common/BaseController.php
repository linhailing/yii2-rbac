<?php
/**
 * henry
 */
namespace backend\controllers\common;

use Yii;
use yii\web\Controller;
use common\models\User;
use backend\services\UrlService;
/**
 *  BaseController 
 *  base class
 */
class BaseController extends Controller{
	protected $auth_cookie_name = 'henry_888';
	protected $current_user = null;

	protected $allowAllAction = [
		'user/login',
		'user/vlogin'
	];

	//init
	public function beforeAction($action){
		$login_status = $this->checkLoginStatus();
		if( !$login_status && !in_array( $action->uniqueId, $this->allowAllAction)){
			if( Yii::$app->request->isAjax){
				$this->renderJSON( [], "not login, back login page.", -302);
			}else{
				$this->redirect( UrlService::buildUrl("/user/login"));
			}
			return false;
		}
		return true;

	}

	// return true or false
	protected function checkLoginStatus(){
		$request = Yii::$app->request;
		$cookies = $request->cookies;
		$auth_cookie = $cookies->get($this->auth_cookie_name);
		if(!$auth_cookie){
			return false;
		}
		list($auth_token, $uid) = explode("#", $auth_cookie);
		if(!$auth_cookie || !$uid){
			return false;
		}
		if( $uid && preg_match("/^\d+$/", $uid)){
			$userinfo = User::findOne( ['id' => $uid] );
			if(!$userinfo){
				return false;
			}
			if( $auth_token != $this->createAuthToken($userinfo['id'], $userinfo['username'], $userinfo['password'], $_SERVER["HTTP_USER_AGENT"]) ){
				return false;
			}
			$this->current_user = $userinfo;
			$view = Yii::$app->view;
			$view->params['current_user'] = $userinfo;
			return true;
		}
		return false;
	}

	// set cookie
	public function createLoginStatus($userinfo){
		$auth_token = $this->createAuthToken($userinfo['id'], $userinfo['username'], $userinfo['password'], $_SERVER['HTTP_USER_AGENT']);
		$cookies = Yii::$app->response->cookies;
		$cookies->add( new \yii\web\Cookie([
			'name' => $this->auth_cookie_name,
			'value' => $auth_token."#".$userinfo['id'],
		]));
	}
	
	// create auth_token
	public function createAuthToken($uid, $name, $password, $user_agent){
		return md5($uid.$name.$password.$user_agent);
	}

	// post
	public function post($key, $default = ""){
		return Yii::$app->request->post($key, $default);
	}
	// get
	public function get($key, $default = ""){
		return Yii::$app->request->get($key, $default);
	}

	/**
	 * [js, ajax...]
	 * @param  array   $data [description]
	 * @param  string  $msg  [description]
	 * @param  integer $code [description]
	 * @return [type]        [description]
	 */
	public function renderJSON($data = [], $msg = "ok", $code = 200){
		header('Content-type: aplication/json'); // setting header config
		echo json_encode([
				"code" => $code,
				"msg" => $msg,
				"data" => $data,
				"req_id" =>uniqid(),
			]);
		return Yii::$app->end();
	}
}