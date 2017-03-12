<?php
namespace backend\services;

use Yii;
use yii\helpers\Url;
/**
 * manage url rules
 */
class UrlService{
	/**
	 * back url
	 * @param  [type] $url    [description]
	 * @param  array  $params [description]
	 * @return [string]       [url]
	 */
	public static function buildUrl($url, $params = []){
		return Url::toRoute( array_merge( [ $url], $params ));
	}

	/**
	 * back null url
	 * @return [""] [description]
	 */
	public static function buildNullUrl(){
		return "javascript:void(0);";
	}
}