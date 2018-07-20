<?php
/**
 * Created with love.
 * User: BenasPaulikas
 * Date: 2016-03-30
 * Time: 22:01
 */
include_once __DIR__."/_common.php";

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

use modules\bbs\widgets\TableView;
use kartik\grid\GridView;
use yii\imperavi\Widget as EditorWidget;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

use modules\bbs\models\BbsList;
use modules\bbs\models\BbsData;

/* @var $model benaspaulikas\forum\models\Post */
/* @var $comment benaspaulikas\forum\models\Post */
/* @var $dataProvider yii\data\ActiveDataProvider */

#$this->title = HtmlPurifier::process($model->subject);
$this->title = HtmlPurifier::process($model->title);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Forum'), 'url' => ['/forum']];
$this->params['breadcrumbs'][] = ['label' => 'bblist', 'url' => ['/forum/category', 'id' => '1']];
$this->params['breadcrumbs'][] = $this->title;

#var_dump($urls);

?>

<div id="bbsview">

	<div class="viewbox">

		<table>
			<tr>
			<td class="td1">제목</td>
			<td class="td3"><?php echo $model->title; ?></td>
			<td class="td1">글쓴이</td>
			<td class="td2">
				<span class="han"><?php echo $model->name; ?></span>
			</td>
			<td class="td1">날짜</td>
			<td class="td2">
				<span class="han"><?php echo \Yii::$app->formatter->asDate($model->created_at);
					#echo getDateFormat($model->d_regis, 'Y-m-d'); ?></span>
			</td>			
			</tr>
		</table>
	
		<div id="vContent" class="content">
		<?php 		
			if($model->html == 'TEXT') {
				echo nl2br($model->content);
			} else {
				echo $model->content;
			}
		?>
		</div>
	</div>

	<div class="bottom">
		<?php if(checkAccess('administrator')): ?>
		<span class="btn00">
			<?php echo Html::a('복사', Yii::$app->urlManagerBack->createUrl(['bbs/bbsdata/index', 'BbsData[id]'=>$model->id, 'action'=>'copy']), ['target'=>'_blank']); 
			?>
		</span>
		<span class="btn00">
			<?php echo Html::a('이동', Yii::$app->urlManagerBack->createUrl(['bbs/bbsdata/index', 'BbsData[id]'=>$model->id, 'action'=>'move']), ['target'=>'_blank']); 
			?>
		</span>
		<?php endif; ?>
		<span class="btn00">
			<?php echo Html::a('수정', $urls['update']); ?>
		</span>
		<span class="btn00">
			<?php echo Html::a('삭제', ['/board/delete', 'id' => $model->id, 'mid'=>\Yii::$app->request->get('mid')], [
				'onclick' => 'return confirm("정말로 삭제하시겠습니까");',
			]); ?>
		</span>
		<span class="btn00">
			<?php echo Html::a('목록으로', $urls['list']); ?>
		</span>
	</div>

</div> 

<div id="comment_box">
<?php 
// echo ListView::widget([
    // 'id' => 'commentlist',
    // 'dataProvider' => $commentDataProvider,
	// 'summary' => false,
    // 'itemView' => '_comment_view',
	// 'emptyText' => '',
// ]);
?>
</div>

<div id="cwrite">
<?php
	// echo $this->render('_comment_form', [ 
		// 'model' => $comment_model, 
		// 'bid' => $model->bid, 
		// 'bbsdata_id' => $model->id, 
	// ]);
?>
</div>


<?php 
///////////////////

$topHtml = <<<EOD
	<table>
	<colgroup> 
	<col width="50"> 
	<col> 
	<col width="100"> 
	<col width="50"> 
	<col width="100"> 
	</colgroup> 
	<thead>
	<tr>
	<th scope="col" class="side1">번호</th>
	<th scope="col">제목</th>
	<th scope="col">글쓴이</th>
	<th scope="col">조회</th>
	<th scope="col" class="side2">날짜</th>
	</tr>
	</thead>
	<tbody>
EOD;

$bottomHtml = <<<EOD
	</tbody>
	</table>
EOD;


$endHtml = <<<EOD
	<div class="bottom">
		<div class="btnbox2">
		<span class="btn00"><a href="/?c=boards/board">처음목록</a></span>
		</div>
	</div>

	<div class="searchform">
		<form name="bbssearchf" action="/">
		<input type="hidden" name="r" value="jsd" />
		<input type="hidden" name="c" value="boards/board" />
		<input type="hidden" name="m" value="bbs" />
		<input type="hidden" name="bid" value="jsd_board1" />
		<input type="hidden" name="cat" value="" />
		<input type="hidden" name="sort" value="gid" />
		<input type="hidden" name="orderby" value="asc" />
		<input type="hidden" name="recnum" value="15" />
		<input type="hidden" name="type" value="" />
		<input type="hidden" name="iframe" value="" />
		<input type="hidden" name="skin" value="" />

		<select name="where">
		<option value="subject|tag">제목+태그</option>
		<option value="content">본문</option>
		<option value="name">이름</option>
		<option value="nic">닉네임</option>
		<option value="id">아이디</option>
		<option value="term">등록일</option>
		</select>
		
		<input type="text" name="keyword" size="20" value="" class="input" />
		<input type="submit" value=" 검색 " class="btngray" />
				</form>
	</div>
EOD;

echo TableView::widget([
    'id' => 'bbslist',
    'dataProvider' => $dataProvider,
	'summary' => false,
	'startHtml' => '', 	
	'topHtml' => $topHtml,
    'itemView' => '_listitem',
	'bottomHtml' => $bottomHtml, 	
	'endHtml' => $endHtml,
	'itemOptions' => [ 'tag' => false ], 
	'viewParams' => [ 'pagesize'=>$pagesize, 'page' => $page ], 
]);


