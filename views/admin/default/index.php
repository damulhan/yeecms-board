<?php

use yeesoft\grid\GridPageSize;
use yeesoft\grid\GridQuickLinks;
use yeesoft\grid\GridView;
use yeesoft\helpers\Html;
use yeesoft\models\User;
use modules\board\models\Board;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel yeesoft\board\models\search\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yee/board', 'Boards');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index">

    <div class="row">
        <div class="col-sm-12">
            <h3 class="lte-hide-title page-title"><?= Html::encode($this->title) ?></h3>
            <?= Html::a(Yii::t('yee', 'Add New'), ['create'], ['class' => 'btn btn-sm btn-primary']) ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row">
                <div class="col-sm-6">
                  <? #GridQuickLinks::widget([
                        // 'model' => Board::className(),
                        // 'searchModel' => $searchModel,
                        // 'labels' => [
                            // 'all' => Yii::t('yee', 'All'),
                            // 'active' => Yii::t('yee', 'Published'),
                            // 'inactive' => Yii::t('yee', 'Pending'),
                        // ]
						// 'options' => [
							// 'all' => ['label' => Yii::t('yee', 'All'), 'filterWhere' => []],
							// 'display' => ['label' => Yii::t('yee', 'Display'), 'filterWhere' => ['display' => 1]],
							// 'not_display' => ['label' => Yii::t('yee', 'Not displaying'), 'filterWhere' => ['display' => 0]],
						// ],
                    // ]) ?>
                </div>

                <div class="col-sm-6 text-right">
                    <?= GridPageSize::widget(['pjaxId' => 'board-grid-pjax']) ?>
                </div>
            </div>

            <?php
            Pjax::begin([
                'id' => 'board-grid-pjax',
            ])
            ?>

            <?=
            GridView::widget([
                'id' => 'board-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'bulkActionOptions' => [
                    'gridId' => 'board-grid',
                    'actions' => [
                        Url::to(['bulk-activate']) => Yii::t('yee', 'Publish'),
                        Url::to(['bulk-deactivate']) => Yii::t('yee', 'Unpublish'),
                        Url::to(['bulk-delete']) => Yii::t('yii', 'Delete'),
                    ]
                ],
                'columns' => [
                    ['class' => 'yeesoft\grid\CheckboxColumn', 'options' => ['style' => 'width:10px']],
                    [
                        'class' => 'yeesoft\grid\columns\TitleActionColumn',
                        'controller' => '/board/default',
                        'title' => function (Board $model) {
                            return Html::a($model->name, ['view', 'id' => $model->id], ['data-pjax' => 0]);
                        },
                    ],
                    [
                        'attribute' => 'created_by',
                        'filter' => yeesoft\models\User::getUsersList(),
                        'value' => function (Board $model) {
                            return Html::a($model->author->username,
                                ['/board/default/update', 'id' => $model->created_by],
                                ['data-pjax' => 0]);
                        },
                        'format' => 'raw',
                        'visible' => User::hasPermission('viewBoards'),
                        'options' => ['style' => 'width:180px'],
                    ],
                    [
                        'class' => 'yeesoft\grid\columns\DateFilterColumn',
                        'attribute' => 'created_at',
                        'value' => function (Board $model) {
                            return '<span style="font-size:85%;" class="label label-'
                            . ((time() >= $model->created_at) ? 'primary' : 'default') . '">'
                            . $model->createdDateTime . '</span>';
                        },
                        'format' => 'raw',
                        'options' => ['style' => 'width:150px'],
                    ],
                ],
            ]);
            ?>

            <?php Pjax::end() ?>
        </div>
    </div>
</div>


