<?php
namespace backend\controllers;

use Yii;
use backend\controllers\common\BaseController;

class DefaultController extends BaseController{
	public function actionIndex(){
		return $this->render("index");
	}
}