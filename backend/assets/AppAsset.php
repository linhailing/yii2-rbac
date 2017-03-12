<?php

namespace backend\assets;

use yii\web\AssetBundle;
use backend\services\UrlService;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    // public $css = [
    //     'css/site.css',
    // ];
    // public $js = [
    // ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public function registerAssetFiles( $view ){
        $v = '20170311';
        $this->css = [
            UrlService::buildUrl( 'bootstrap/css/bootstrap.min.css?', [ 'v' => $v ]),
            UrlService::buildUrl( 'bootstrap/css/app.css', [ 'v' => $v ])
        ];
        $this->js = [
            UrlService::buildUrl( 'jquery/jquery.min.js', [ 'v' => $v ]),
            UrlService::buildUrl( 'bootstrap/js/bootstrap.min.js', [ 'v' => $v ]),
        ];
        parent::registerAssetFiles( $view );
    }
}
