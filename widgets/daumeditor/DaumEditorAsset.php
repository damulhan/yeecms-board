<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace modules\board\widgets\daumeditor;
use Yii;
use yii\web\AssetBundle;

/**
 * @author Alexander Yaremchuk <alwex10@gmail.com>
 * @since 2.0.1
 */
class DaumEditorAsset extends AssetBundle
{
    public $sourcePath = '@modules/board/widgets/assets/daumeditor';
	public $baseUrl = '@web';
	
    public $js = [
        'js/editor_loader.js'
    ];
    public $css = [
        'css/editor.css'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];

	public function init() {
		#$this->publishOptions['forceCopy'] = YII_DEBUG;
	}
	
}


