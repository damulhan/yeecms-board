<?php
/**
 * Created with love.
 * User: BenasPaulikas
 * Date: 2016-03-30
 * Time: 22:00
 */

use yii\helpers\Html;
use yii\helpers\Url;

$num = (($page-1) * $pagesize) + $index; 
$id = Yii::$app->request->get('id');
$mid = \Yii::$app->request->get('mid');
#$url = Url::to(yii\helpers\ArrayHelper::merge($model->url, ['mid'=>$mid]));
$url = Url::to($model->url, ['mid'=>$mid]);

?>

<tr>
	<td>
		<?php if($model->id != $id):?>
		<?php echo ($orderby=='desc') ? $num : ($totalCount-$num); ?>
		<?php else:?>
		<span class="now">&gt;&gt;</span>
		<?php endif?>
	</td>
	<td class="sbj">
		<?php echo Html::a(Html::encode($model->title), $url); ?>
	</td>
	<td class="name"><span class="hand"><?php echo $model->nic; ?></span></td>
	<td class="hit b"><?php echo $model->hit; ?></td>
	<td>
		<?php 
			echo \Yii::$app->formatter->asDate($model->created_at);
			#echo \Yii::$app->formatter->asDate($model->created_at.' '.\Yii::$app->timeZone);
			#echo getDateFormat($model->d_regis, 'Y-m-d'); 
		?>
	</td>
</tr> 

