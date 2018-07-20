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

if(!is_null($groups)) {
	$groups_items = [];
	foreach($groups as $group) {
		$groups_items[$group->id] = $group->title . ' (' . $group->slug . ')'	;
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

						<div class="form-group field-board-group_id">
						<label class="control-label" for="board-group_id">Group</label>
						<?php 
						echo Html::dropDownList('Group[group_id]', $model->group_id, $groups_items, 
								['id' => 'option-default_role', 'class' => 'form-control']);
						?>
						<div class="help-block"></div>
						</div>
						
						<?= $form->field($model, 'bid')->textInput(['maxlength' => true])
							->label('게시판 ID')  ?>

						<?= $form->field($model, 'name')->textInput(['maxlength' => true])
							->label('게시판 이름')  ?>

						<?= $form->field($model, 'category')->textInput(['maxlength' => true])
							->hint('파이프(\'|\') 로 분리해서 입력해주세요.'); ?>
						
						<?= $form->field($model, 'skin')->dropDownList($this->context->module->skinList)
							->label('스킨'); ?>

						<?= $form->field($model, 'm_skin')->dropDownList($this->context->module->mSkinList)
							->label('모바일 스킨');
						?>						
						
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
						
						<?php 
						$_bbsvar = $model->_bbsvar;
						?>
						
								<?php 
								$levels = range(0, 10); 
								$level_options = array_combine($levels, $levels);
								?>
								<h4>권한</h4>
								
								<div class="form-group">
								<?= Html::label('목록접근 최소 레벨') ?>
								<?php echo Html::dropDownList("_bbsvar[perm_user_list]", getValue($_bbsvar->perm_user_list, 0), $level_options, ['class' => 'form-control']); ?>
								</div>
								
								<div class="form-group">
								<?= Html::label('본문열람 최소 레벨') ?>
								<?php echo Html::dropDownList("_bbsvar[perm_user_view]", getValue($_bbsvar->perm_user_view, 0), $level_options, ['class' => 'form-control']); ?>
								</div>
								
								<div class="form-group">
								<?= Html::label('글쓰기 최소 레벨') ?>
								<?php echo Html::dropDownList("_bbsvar[perm_user_write]", getValue($_bbsvar->perm_user_write, 1), $level_options, ['class' => 'form-control']); ?>
								</div>
								
								<div class="form-group">
								<?= Html::label('다운로드 최소 레벨') ?>
								<?php echo Html::dropDownList("_bbsvar[perm_user_down]", getValue($_bbsvar->perm_user_down, 1), $level_options, ['class' => 'form-control']); ?>
								</div>

                        </div>
                    </div>
                </div> 

				<?# $form->field($model, 'bbsvar')->hiddenInput()->label(false); ?>

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

function make_bbsvar() {
	var bbsvar_str = $("input[name='Board[bbsvar]']").val() || "{}";
	var bbsvar = JSON.parse(bbsvar_str);	

    bbsvar['perm_user_list'] = $('select[name=perm_user_list]').val();
    bbsvar['perm_user_view'] = $('select[name=perm_user_view]').val();
    bbsvar['perm_user_write'] = $('select[name=perm_user_write]').val();
    bbsvar['perm_user_down'] = $('select[name=perm_user_down]').val();
	
	bbsvar_str = JSON.stringify(bbsvar);
	
	$("input[name='Board[bbsvar]']").val(bbsvar_str);	
}

function extract_bbsvar() {
	var bbsvar_str = $("input[name='Board[bbsvar]']").val() || "{}";
	var bbsvar = JSON.parse(bbsvar_str);
	
	$('select[name=perm_user_list]').val( bbsvar['perm_user_list'] );
	$('select[name=perm_user_view]').val( bbsvar['perm_user_view'] );
    $('select[name=perm_user_write]').val( bbsvar['perm_user_write'] );
    $('select[name=perm_user_down]').val( bbsvar['perm_user_down'] );
}

$('#post-form').on('beforeSubmit', function(e) {
	make_bbsvar(); 
    return true;
});

$(document).ready(function() {
	extract_bbsvar();
});
	
JS;

$this->registerCss($css);
$this->registerJs($js, yii\web\View::POS_READY);
?>