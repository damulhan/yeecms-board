<?php

$theme_info = [
	'name' => basename(__DIR__),
];


##########

use modules\bbs\frontend\ModuleAsset;

$asset = ModuleAsset::register(Yii::$app->view); 
$asset->css[] = 'bbs/themes/'.$theme_info['name'].'/main.css';

