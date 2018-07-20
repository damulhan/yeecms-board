<?php
/**
 * @var View $this
 */
?>
<?php
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\ArrayHelper;

$actions = ArrayHelper::map($this->context->actions, 'action', 'desc');
$targets = ArrayHelper::map($this->context->actions, 'action', 'target');

?>
<div class="<?= $this->context->wrapperClass ?>">

    <?= Html::dropDownList(
        'grid-bulk-actions',
        null,
        $actions,
        [
            'class' => $this->context->dropDownClass,
            'id' => "{$this->context->gridId}-bulk-actions",
            'data-ok-button' => "#{$this->context->gridId}-ok-button",
            'prompt' => $this->context->promptText,
        ]
    ) ?>

    <?= Html::tag('span', Yii::t('yee', 'OK'), [
        'class' => "grid-bulk-ok-button {$this->context->okButtonClass} disabled",
        'id' => "{$this->context->gridId}-ok-button",
        'data-list' => "#{$this->context->gridId}-bulk-actions",
        'data-pjax' => "#{$this->context->pjaxId}",
        'data-grid' => "#{$this->context->gridId}",
    ]) ?>
	
	<?php 
	foreach($targets as $action => $target) {
		if($target != null) {
	?>
	<div id="target-select-<?=$action?>" class="bulk-actions-target-select collapse grid-nav " style="margin-top:10px; ">
		<div class="row">
			<div class="col-sm-3">
				<?= Html::dropDownList(
					'target-'.$action,
					null,
					$target,
					['id'=> 'target-'.$action, 'class' => 'target form-control input-sm', 'prompt' => Yii::t('yee', '====== Select Target ======')]
				); ?>
			</div>
		</div>
	</div>	
	<?php }
	}
	?>
	
</div>

