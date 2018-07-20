<?php
/**
 * Created with love.
 * User: BenasPaulikas
 * Date: 2016-03-30
 * Time: 22:00
 */

use yii\helpers\Html;

/* @var $model benaspaulikas\forum\models\Post */
?>

<div class="comment_list">
	<div class="chip_box">
	</div>
	<div class="info_box">
		<span class="name"><strong><?php echo $model->name; ?></strong></span> &nbsp; 
		<span class="rdate">(<?= Yii::$app->formatter->asRelativeTime(strtotime($model->date))?>)</span>
	</div>
	<div class="content_box">
		<?= $model->content; ?>
	</div>
	<ul class="option_list">
		<!--<li><a class="hand" onclick="onelineOpen('<?php echo $R['uid']?>');">댓글</a></li>
		<?php if($my['admin'] or $R['id'] == $my['id']):?>
		<li><a href="<?php echo $g['cment_modify'].$R['uid']?>" onclick="return cmentModify('<?php echo $R['id']?>','<?php echo $R['uid']?>',event);">수정</a></li>
		<li><a href="<?php echo $g['cment_delete'].$R['uid']?>" target="_action_frame_<?php echo $m?>" onclick="return cmentDel('<?php echo $R['id']?>','<?php echo $R['uid']?>',event);">삭제</a></li>
		-->		
		<li><?php echo Html::a('삭제', ['/bbs/view/comment_delete', 'id' => $model->id], ['onclick'=>"return confirm('삭제하겠습니까?');"]); ?></li>
		<?php endif?>
	</ul>
</div>

