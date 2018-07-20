<?php
/**
 * Created with love.
 * User: BenasPaulikas
 * Date: 2016-03-30
 * Time: 21:59
 */

use yii\helpers\Html;
use yii\imperavi\Widget;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

// use yii\imperavi\Widget as EditorWidget;
use yii\widgets\ListView;
	
$is_logged_in = (Yii::$app->user->id != ''); 	

#$form = ActiveForm::begin(['action' => Url::toRoute(['/bbs/action/comment_create'])]);
$form = ActiveForm::begin();

	echo $form->errorSummary($model);

	#$form->field($model, 'bid')->hiddenInput()->label(false);	

	echo Html::hiddenInput('bid', $bid); 
	echo Html::hiddenInput('bbsdata_id', $bbsdata_id); 

	#echo $form->field($model, 'bbsdata_id')->hiddenInput()->label(false);
	
	$buttons = ['formatting', 'bold', 'italic', 'deleted', 'link', 'orderedlist', 'paragraph'];

	// echo EditorWidget::widget([
		// 'model' => $model,
		// 'attribute' => 'content',
		// #'lang' => 'ko',
		// 'options' => [
			// 'imageUpload' => true,
			// 'buttons' => $buttons,
			// 'formatting' => ['p', 'blockquote', 'pre', 'h3', 'h4', 'h5'],
		// ],
		// 'plugins' => [
			// 'fullscreen',
			// 'clips',
			// 'fontsize',
			// 'fontfamily',			
			// 'filemanager',
			// 'imagemanager',
			// 'table',
		// ]
	// ]); 
	
	echo $form->field($model, 'content', ['enableClientValidation'=>$is_logged_in])->textarea(['rows' => '3'])->label(false);

	#echo Html::submitButton($model->isNewRecord ? Yii::t('app', isset($comment) ? 'Reply' : 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-u' : 'btn btn-u']);
	#echo '<input type="submit" value=" 검색 " class="btngray" />'; 
	echo "<div class='bottom' style='text-align:right'>";
	#echo '<span class="btn00"><a href="/index.php?r=bbs/update&amp;id=1812&amp;bid=34">수정</a></span>';
	echo Html::submitInput( $model->isNewRecord ? Yii::t('app', isset($comment) ? 'Reply' : '댓글 입력') : Yii::t('app', '수정'), ['class' => 'btn00'] );
	echo "</div>";	

ActiveForm::end();

$comment_initjs = <<<HERE
	$('#bbscomment-content').prop('readonly', true)
		.css('background-color', '#fff')
		.on('click', function(e) {
			e.preventDefault();
			alert('로그인해주세요');
		});
HERE;

if(!$is_logged_in) 
	$this->registerJs($comment_initjs, \yii\web\View::POS_END, 'bbscomment');



