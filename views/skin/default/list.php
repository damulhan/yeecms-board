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
use yii\widgets\ListView;

use modules\bbs\models\BbsList;
use modules\bbs\models\BbsData;

use modules\bbs\widgets\TableView;
use kartik\grid\GridView;


/* @var $model benaspaulikas\forum\models\Post */
/* @var $comment benaspaulikas\forum\models\Post */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = HtmlPurifier::process($model->subject);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'bbs') ];
$this->params['breadcrumbs'][] = ['label' => 'bblist'];
$this->params['breadcrumbs'][] = $this->title;

//var_dump (Yii::$app->getUser()->);
//var_dump (Yii::$app->authManager->checkAccess(Yii::$app->getUser()->id, 'administrator'));
//var_dump ( checkAccess('administrator') );

$adminUrl1 = Yii::$app->urlManagerBack->createUrl(['/bbs/bbslist/view', 'id' => $bbslist_model->id]);
$adminUrl2 = Yii::$app->urlManagerBack->createUrl(['/bbs/bbsdata/index', 'BbsData[bbsid]' => $bbslist_model->bbsid]);

?>
<div id="bbslist">

	<div class="info">		
		<div class="article">
			<?php echo number_format($dataProvider->totalCount)?>개(<?php echo $page?>페이지)
		</div>
		
		<div class="category">
			<?php if(checkAccess('administrator')): ?>
			<a href="<?php echo $adminUrl1; ?>" target="_blank">
				<img src="/_core/image/_public/btn_admin.gif" alt="" title="게시판관리" /></a>
			<a href="<?php echo $adminUrl2; ?>" target='_blank'>
				<img src="/_core/image/_public/btn_admin.gif" alt="" title="게시물관리" /></a>
			<?php endif; ?>
		</div>
		<div class="clear"></div>
	</div>
	
<?php 

function getTopHtml() {
	$topHtml = <<<EOD
	<!-- top -->
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
	return $topHtml;
}

function getBottomHtml() {
	$bottomHtml = <<<EOD
	<!-- bottom -->
		</tbody>
		</table>
EOD;
	return $bottomHtml;
}

function getEndHtml($urls) {
	
	$bid = Yii::$app->request->get('bid');
	
	ob_start();
?>
	<!-- end -->
	<div class="bottom">
		<div class="btnbox2">
		<span class="btn00"><?php echo Html::a('글쓰기',   $urls['create']); ?></span>
		<span class="btn00"><?php echo Html::a('처음목록', $urls['list']); ?></span>
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
	<!-- //end -->
<?php 
	$out = ob_get_contents();
	ob_end_clean();
	return $out;
}
?>
<?php 
	echo TableView::widget([
		'id' => '',
		'dataProvider' => $dataProvider,
		'summary' => false,
		'startHtml' => '', 	
		'topHtml' => getTopHtml(),
		'itemView' => '_listitem',
		'bottomHtml' => getBottomHtml(), 	
		'endHtml' => getEndHtml($urls), 
		'itemOptions' => [ 'tag' => false ], 
		'viewParams' => [ 'pagesize'=>$pagesize, 'page' => $page ],
		'showOnEmpty' => true, 
	]);
?>

</div>



