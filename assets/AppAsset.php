<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [	
        //'css/site.css',
		'css/custom.css',
		'src/jssocials.css',
		'src/jssocials-theme-minima.css',
		'src/font-awesome.min.css',
		
    ];
    public $jsOptions = [
		//'async' => 'async',
		'position' => \yii\web\view::POS_HEAD,
    ];
    public $js = [
		'src/jssocials.min.js',
		'src/bootstrap.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
