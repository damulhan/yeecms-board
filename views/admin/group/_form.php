<?php

use yeesoft\helpers\Html;
use yeesoft\media\widgets\TinyMce;
use yeesoft\models\User;
use yeesoft\post\models\Category;
use yeesoft\post\models\Post;
use yeesoft\widgets\ActiveForm;
use yeesoft\widgets\LanguagePills;
use yii\jui\DatePicker;
use yeesoft\post\widgets\MagicSuggest;
use yeesoft\post\models\Tag;

/* @var $this yii\web\View */
/* @var $model yeesoft\post\models\Post */
/* @var $form yeesoft\widgets\ActiveForm */
?>

    <div class="post-form">

        <?php
        $form = ActiveForm::begin([
            'id' => 'post-form',
            'validateOnBlur' => false,
        ]);
		
		#$bbsvar = json_decode($model->bbsvar);
		#$val1 = $bbsvar->val1; 
		
        ?>

        <div class="row">
            <div class="col-md-9">

                <div class="panel panel-default">
                    <div class="panel-body">

						<?php if ($model->isMultilingual()): ?>
						<?= LanguagePills::widget() ?>
						<?php endif; ?>

						<?= $form->field($model, 'slug')->textInput(['maxlength' => true])
							->label('그룹 ID')  ?>

						<?= $form->field($model, 'title')->textInput(['maxlength' => true])
							->label('그룹 이름')  ?>
							
	
						<!--
	<div class="form-group field-bbslist-category">
	<label class="control-label" for="bbslist-category">스킨</label>
	<?php 
	#echo Html::dropDownList('BbsList[skin]', $model->skin, array_combine($bbsskin_list, $bbsskin_list), 
	#		['id' => 'option-default_role', 'class' => 'form-control']);
	?>
	<div class="help-block"></div>
	</div>
	-->
	
						<?# $form->field($model, 'addinfo')->hiddenInput()->label('') ?>
						
						<?# $form->field($model, 'bbsvar')->hiddenInput()->label('') ?>
						
						<!--
						<div class="form-group field-board-bbsvar">
							<label class="control-label" for="board-bbsvar">이름</label>
							<?# Html::input('text', 'val1', $val1, ['class' => 'form-control']); ?>
							<p class="help-block help-block-error"></p>
						</div>
						-->
						
                    </div>
                </div>
            </div>

            <div class="col-md-3">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="record-info">
                            <?php if (!$model->isNewRecord): ?>

                                <div class="form-group clearfix">
                                    <label class="control-label" style="float: left; padding-right: 5px;">
                                        <?= $model->attributeLabels()['created_at'] ?> :
                                    </label>
                                    <span><?= $model->createdDatetime ?></span>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="control-label" style="float: left; padding-right: 5px;">
                                        <?= $model->attributeLabels()['updated_at'] ?> :
                                    </label>
                                    <span><?= $model->updatedDatetime ?></span>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="control-label" style="float: left; padding-right: 5px;">
                                        <?= $model->attributeLabels()['updated_by'] ?> :
                                    </label>
                                    <span><?= $model->updatedBy->username ?></span>
                                </div>

                            <?php endif; ?>

                            <div class="form-group">
                                <?php if ($model->isNewRecord): ?>
                                    <?= Html::submitButton(Yii::t('yee', 'Create'), ['class' => 'btn btn-primary']) ?>
                                    <?= Html::a(Yii::t('yee', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                                <?php else: ?>
                                    <?= Html::submitButton(Yii::t('yee', 'Save'), ['class' => 'btn btn-primary']) ?>
                                    <?= Html::a(Yii::t('yee', 'Delete'), ['delete', 'id' => $model->id], [
                                        'class' => 'btn btn-default',
                                        'data' => [
                                            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="record-info">
                            <?# $form->field($model, 'category_id')->dropDownList(Category::getCategories(), ['prompt' => '', 'encodeSpaces' => true]) ?>

                            <?php if (!$model->isNewRecord): ?>
                                <?= $form->field($model, 'created_by')->dropDownList(User::getUsersList()) ?>
                            <?php endif; ?>

                            <?#= $form->field($model, 'skin')->dropDownList($this->context->module->skinList) ?>

                            <?#= $form->field($model, 'm_skin')->dropDownList($this->context->module->mSkinList) ?>

                        </div>
                    </div>
                </div>

                <div class="panel panel-default">

                </div>

            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
$css = <<<CSS
.ms-ctn .ms-sel-ctn {
    margin-left: -6px;
    margin-top: -2px;
}
.ms-ctn .ms-sel-item {
    color: #666;
    font-size: 14px;
    cursor: default;
    border: 1px solid #ccc;
}
CSS;

$js = <<<JS
    var thumbnail = $("#post-thumbnail").val();
    if(thumbnail.length == 0){
        $('.post-thumbnail').hide();
    } else {
        $('.post-thumbnail').html('<img src="' + thumbnail + '" />');
    }
JS;

$this->registerCss($css);
$this->registerJs($js, yii\web\View::POS_READY);
?>