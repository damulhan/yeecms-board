<?php
/**
 * Created with love.
 * User: BenasPaulikas
 * Date: 2016-03-30
 * Time: 22:00
 */
include_once __DIR__."/_common.php";

/* @var $category benaspaulikas\forum\models\Category */
/* @var $model benaspaulikas\forum\models\Post */

$this->title = Yii::t('app', 'Create Topic');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Forum'), 'url' => ['/forum']];
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['/forum/category', 'id' => $category->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="post-form">

    <?= $this->render('_form', [
		'model' => $model, 
		'bbsdata_id' => null, 
		'bbsskin_list' => $bbsskin_list,
	]); ?>

</div>

