<?php 

namespace modules\board;

use yii\web\AssetBundle;

/**
 * AppAsset is used to register asset files on frontend application.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class BoardModuleAsset extends AssetBundle
{
    public $sourcePath = '@modules/board/assets/';
	#public $basePath = '@webroot/modules/bbs/';
	#public $baseUrl = '@web/modules/bbs/';
	
    public $depends = [
        #'yii\web\YiiAsset',
        #'yii\bootstrap\BootstrapAsset',
    ];
	
	public $publishOptions = [
		#'forceCopy'=>true,
	];

    public function init()
    {
        #if (YII_DEBUG) {
        #    $this->css = ['css/site.css'];
        #} else {
        #    $this->css = ['css/min/site.css'];
        #}
		
        parent::init();
        $this->css[] = 'main.css';
        $this->js[] = 'main.js'; 
    }
}
