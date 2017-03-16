<?php
/**
 * henry
 */
namespace backend\controllers\common;

use Yii;
use yii\web\Controller;
use common\models\User;
use common\models\UserRole;
use common\models\RoleAccess;
use common\models\Access;
use backend\services\UrlService;
/**
 *  BaseController 
 *  base class
 */
class BaseController extends Controller{
	protected $auth_cookie_name = 'henry_888';
	protected $current_user = null;

	protected $allowAllAction = [
		'user/login'
	];
	protected $ignore_url =[
		'error/forbidden',
		'user/login'
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
		/**
		 * 判断权限的逻辑是
		 * 取出当前登录用户的所属角色，
		 * 在通过角色 取出 所属 权限关系
		 * 在权限表中取出所有的权限链接
		 * 判断当前访问的链接 是否在 所拥有的权限列表中
		 */
		//判断当前访问的链接 是否在 所拥有的权限列表中
		if(!$this->checkPrivilege($action->getUniqueId())){
			$this->redirect(UrlService::buildUrl('error/forbidden'));
			return false;
		}
		
		return true;

	}

	//检查是否有访问指定链接的权限
	protected function checkPrivilege($url){
		//如果是超级管理员 也不需要权限判断
		if( $this->current_user && $this->current_user['is_admin']){
			return true;
		}
		//echo $url;
		//exit();
		//有一些页面是不需要进行权限判断的
		if(in_array($url, $this->ignore_url)){
			return true;
		}
		return in_array($url, $this->getRolePrivilege());
	}

	/*
	* 获取某用户的所有权限
	* 取出指定用户的所属角色，
	* 在通过角色 取出 所属 权限关系
	* 在权限表中取出所有的权限链接
	*/
	protected function getRolePrivilege( $uid =0){
		if(!$uid && $this->current_user){
			$uid = $this->current_user->id;
		}
		$privilege_urls = [];
		//取出指定用户的所属角色
		$role_ids = UserRole::find()->where( ['uid' => $uid])->select('role_id')->asArray()->column();
		if($role_ids){
			//在通过角色 取出 所属 权限关系
			$access_ids = RoleAccess::find()->where( ['role_id' =>$role_ids ] )->select('access_id')->asArray()->column();
			//在权限表中取出所有的权限链接
			$list = Access::find()->where( ['id' => $access_ids ] )->all();
			// urls json
			if($list){
				foreach ($list as $_items) {
					$tmp_urls = @json_decode($_items['urls'], true);
					$privilege_urls = array_merge($privilege_urls, $tmp_urls);
				}
			}
		}
		return $privilege_urls;

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