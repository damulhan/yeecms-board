<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = Yii::t('yee', 'Create {item}', ['item' => Yii::t('yee/board', 'Board Data')]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yee/board', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">
    <h3 class="lte-hide-title"><?= Html::encode($this->title) ?></h3>
    <?= $this->render('_form', compact('model', 'boards')) ?>
</div>
