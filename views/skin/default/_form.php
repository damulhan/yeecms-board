<?php
/**
 * Created with love.
 * User: BenasPaulikas
 * Date: 2016-03-30
 * Time: 21:59
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use modules\board\widgets\daumeditor\DaumEditor;
use modules\board\widgets\MediaModal;
#use yeesoft\media\widgets\TinyMce;

/* @var $model benaspaulikas\forum\models\Post */

$form = ActiveForm::begin();

echo $form->errorSummary($model);

#print_r($urls);

if (!isset($comment)) 
	echo $form->field($model, 'title')->textInput(['maxlength' => 100])->label('제목');

#if ($model->category) {
#	echo $form->field($model, 'category')->textInput(['maxlength' => 100]);
#}

if($category_arr && count($category_arr) > 0) {
	echo '<div class="form-group field-bbsdata-subject required">';
	echo '<label class="control-label" for="bbsdata-subject">분류</label>';
	$cat_com_arr = array_combine($category_arr, $category_arr);
	$cat_com_arr = array(''=>'** 분류 선택 **') + $cat_com_arr;
	echo Html::activeDropDownList($model, 'category', $cat_com_arr, ['class' => 'form-control']);	
	echo '<div class="help-block"></div>';
	echo '</div>';
}

echo Html::hiddenInput('upload_json', $upload_json, ['id'=>'upload_json', 'class'=>'']); 

echo $form->field($model, 'content')->widget(DaumEditor::className(), [
	'options' => ['rows' => 6],
])->label(false);

#echo $form->field($model, 'content')->widget(TinyMce::className())->label(false);

echo Html::submitButton(
	$model->isNewRecord ? 
		Yii::t('app', isset($comment) ? 'Reply' : '저장') : 
		Yii::t('app', '저장'), 
	['class' => $model->isNewRecord ? 'btn btn-u' : 'btn btn-u']
);

echo "&nbsp;";
$bid = $model->isNewRecord ? Yii::$app->request->get('bid') : $model->bid;
echo Html::a('목록으로', $urls['list'], ['class' => 'btn btn-u'] ); 

ActiveForm::end();

