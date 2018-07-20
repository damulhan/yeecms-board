<?php

#use yeesoft\grid\GridPageSize;
#use yeesoft\grid\GridQuickLinks;
#use yeesoft\grid\GridView;
use yeesoft\helpers\Html;
use yeesoft\models\User;
use modules\board\models\Board;
use modules\board\models\Data;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;

use modules\board\grid\GridPageSize;
use modules\board\grid\GridQuickLinks;
use modules\board\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel yeesoft\board\models\search\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yee/board', 'Board data');
$this->params['breadcrumbs'][] = $this->title;

$bbslist_items = [];
foreach($boards as $board) {
	$bbslist_items[$board->id] = $board->bid . ' (' . $board->name . ')'	;
}

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
                    <?= GridQuickLinks::widget([
                        'model' => Data::className(),
                        'searchModel' => $searchModel,
                        'labels' => [
                            'all' => Yii::t('yee', 'All'),
                            'display' => Yii::t('yee', 'Display'),
                            'not_display' => Yii::t('yee', 'Not displaying'),
                        ], 
						'options' => [
							'all' => ['label' => Yii::t('yee', 'All'), 'filterWhere' => []],
							'display' => ['label' => Yii::t('yee', 'Display'), 'filterWhere' => ['display' => 1]],
							'not_display' => ['label' => Yii::t('yee', 'Not displaying'), 'filterWhere' => ['display' => 0]],
						],
                    ]) ?>
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
                        ['action'=>'bulk-delete', 'desc'=> Yii::t('yii', 'Delete 삭제'), 'target'=>null],
						['action'=>'bulk-copy', 'desc'=>Yii::t('yii', 'Copy 복사'), 'target'=>$bbslist_items],
						['action'=>'bulk-move', 'desc'=>Yii::t('yii', 'Move 이동'), 'target'=>$bbslist_items],
                    ],
                ],
                'columns' => [
                    ['class' => 'yeesoft\grid\CheckboxColumn', 'options' => ['style' => 'width:10px']],
                    [
						'attribute' => 'title',
                        'class' => 'yeesoft\grid\columns\TitleActionColumn',
                        'controller' => '/board/data',
                        'title' => function (Data $model) {
							return $model->title;
                        },
                    ],
					[
						'attribute' => 'board',
						'value' => function(Data $model) { return $model->board->name; },
						'options' => ['style' => 'width:150px'],
					],
                    [
                        'attribute' => 'name',
                        'value' => function (Data $model) {
							$str = $model->name;
							if($model->nic) $str .= " (".$model->nic.")";
							if($model->created_by) $str .= " (".$model->author->username.")";
							return $str;
                        },
                        'format' => 'raw',
                        'visible' => User::hasPermission('viewBoardData'),
                        'options' => ['style' => 'width:180px'],
                    ],
                    [
                        'class' => 'yeesoft\grid\columns\DateFilterColumn',
                        'attribute' => 'created_at',
                        'value' => function (Data $model) {
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
			
	<div id="bbs-select" class="post-search collapse grid-nav">
		<div class="row">
			<div class="col-sm-3">
				<?= Html::dropDownList(
					'copymove-select',
					null,
					$bbslist_items,
					['class' => 'copymove-select form-control input-sm', 'prompt' => Yii::t('yee', '====== 게시판 선택 ======')]
				) ?>
			</div>
			<?php echo Html::hiddenInput('copymove_bbsid', '', ['id'=>'copymove_bbsid', 'class'=>'copymove_bbsid']
			); ?>
		</div>
	</div>
	
        </div>
    </div>
</div>

	