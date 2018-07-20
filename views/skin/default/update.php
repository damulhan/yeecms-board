<?php
/**
 * Created with love.
 * User: BenasPaulikas
 * Date: 2016-03-30
 * Time: 22:01
 */

include_once __DIR__."/_common.php";

/* @var $this yii\web\View */
/* @var $model benaspaulikas\forum\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?= $this->render('_form', [
		'model' => $model,
		'bbsdata_id' => $bbsdata_id, 
		'upload_json' => $upload_json, 
		#'bbslist_model' => $bbslist_model, 
		'category_arr' => $category_arr,
		'bbsskin_list' => $bbsskin_list,
		'urls' => $urls,
	]) ?>

</div>
