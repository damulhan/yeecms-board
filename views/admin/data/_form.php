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

if(!is_null($boards)) {
	$board_items = [];
	foreach($boards as $board) {
		$board_items[$board->id] = $board->name . ' (group: ' . $board->group->title . ')';
	}
}

?>

    <div class="post-form">

        <?php
        $form = ActiveForm::begin([
            'id' => 'post-form',
            'validateOnBlur' => false,
        ]);
        ?>

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">

						<?php if ($model->isMultilingual()): ?>
						<?= LanguagePills::widget() ?>
						<?php endif; ?>

						<div class="form-group field-board-board_id">
						<label class="control-label" for="board-board_id">Board</label>
						<?php 
						echo Html::dropDownList('Data[board_id]', $model->board_id, $board_items, 
								['id' => 'option-default_role', 'class' => 'form-control']);
						?>
						<div class="help-block"></div>
						</div>
						
						<?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('이름')  ?>
						
						<?= $form->field($model, 'nic')->textInput(['maxlength' => true])->label('닉네임')  ?>

						<?= $form->field($model, 'category')->textInput(['maxlength' => true]); ?>
						
						<?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('제목')  ?>
						
						<?= $form->field($model, 'content')->textarea(['maxlength' => true])->label('본문')  ?>
						
						<?= $form->field($model, 'depth')->textInput(['maxlength' => true])->label('depth')  ?>
						<?= $form->field($model, 'display')->textInput(['maxlength' => true])->label('display')  ?>
						<?= $form->field($model, 'hidden')->textInput(['maxlength' => true])->label('hidden')  ?>
						
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