<?php
header("Content-type:text/html;charset=utf-8");
define('__KIMS__',true);
error_reporting(E_ALL ^ E_NOTICE);
session_save_path('../../../../_tmp/session');
session_start();
$d = array();
$g = array(
	'path_root'   => '../../../../',
	'path_core'   => '../../../../_core/',
	'path_var'    => '../../../../_var/',
	'path_tmp'    => '../../../../_tmp/',
	'path_layout' => '../../../../layouts/',
	'path_module' => '../../../../modules/',
	'path_widget' => '../../../../widgets/',
	'path_switch' => '../../../../switchs/',
	'path_page'   => '../../../../pages/',
	'path_file'   => '../../../../files/',
	'sys_lang'    => 'korean'
);
$g['time_split'] = explode(' ',microtime());
$g['time_start'] = $g['time_split'][0]+$g['time_split'][1];
$g['url_root']   = 'http'.($_SERVER['HTTPS']=='on'?'s':'').'://'.$_SERVER['HTTP_HOST'].str_replace('/modules/daumEditor/theme/default/imgeditor.php','',$_SERVER['SCRIPT_NAME']);

if(!get_magic_quotes_gpc())
{
	if (is_array($_GET))
		foreach($_GET as $_tmp['k'] => $_tmp['v'])
			if (is_array($_GET[$_tmp['k']]))
				foreach($_GET[$_tmp['k']] as $_tmp['k1'] => $_tmp['v1'])
					$_GET[$_tmp['k']][$_tmp['k1']] = ${$_tmp['k']}[$_tmp['k1']] = addslashes($_tmp['v1']);
			else $_GET[$_tmp['k']] = ${$_tmp['k']} = addslashes($_tmp['v']);
	if (is_array($_POST))
		foreach($_POST as $_tmp['k'] => $_tmp['v'])
			if (is_array($_POST[$_tmp['k']]))
				foreach($_POST[$_tmp['k']] as $_tmp['k1'] => $_tmp['v1'])
					$_POST[$_tmp['k']][$_tmp['k1']] = ${$_tmp['k']}[$_tmp['k1']] = addslashes($_tmp['v1']);
			else $_POST[$_tmp['k']] = ${$_tmp['k']} = addslashes($_tmp['v']);
}
else {
	if (!ini_get('register_globals'))
	{
		extract($_GET);
		extract($_POST);
	}
}
if (is_file($g['path_var'].'db.info.php'))
{
	require $g['path_module'].'admin/var/var.system.php';
	require $g['path_var'].'db.info.php';
	require $g['path_var'].'table.info.php';
	require $g['path_var'].'switch.var.php';
	require $g['path_core'].'function/db.mysql.func.php';
	require $g['path_core'].'function/sys.func.php';
	foreach(getSwitchInc('start') as $_switch) include $_switch;
	require $g['path_core'].'engine/main.engine.php';
  require $g['path_module'].'daumEditor/Snoopy-1.2.4/Snoopy.class.php';
}
else $m = 'admin';
include_once $g['path_core'].'function/thumb.func.php';
$S = 0;$N = 0;$P = array();
$sescode = str_replace('.','',$g['time_start']);
if ($sescode){
	$PHOTOS = getDbArray($table['s_upload'],"tmpcode='".$sescode."'",'*','uid','asc',0,0);
	while($R = db_fetch_array($PHOTOS))	{
		$P[] = $R;
		$S += $R['size'];
		$N++;
	}
}
$savePath1	= $g['path_file'].substr($date['today'],0,4);
$savePath2	= $savePath1.'/'.substr($date['today'],4,2);
$savePath3	= $savePath2.'/'.substr($date['today'],6,2);

$n_url		= $_REQUEST["imageData"];
$name		  = substr($n_url,strrpos($n_url,"=")+1).'.png';
$fileExt	= getExt($name);
$fileExt	= $fileExt == 'jpeg' ? 'jpg' : $fileExt;
$type		  = getFileType($fileExt);
$tmpname	= md5($name).substr($date['totime'],8,14);
$tmpname	= $type == 2 ? $tmpname.'.'.$fileExt : $tmpname;
$saveFile = $savePath3.'/'.$tmpname;
for ($i = 1; $i < 4; $i++)
{
  if (!is_dir(${'savePath'.$i}))
  {
    mkdir(${'savePath'.$i},0707);
    @chmod(${'savePath'.$i},0707);
  }
}
$snoopy = new Snoopy;
$snoopy->fetch($n_url);
$wfp        = fopen($savePath3."/".$tmpname, "wb");
fwrite($wfp,$snoopy->results);
fclose($wfp);
$sess_Code  = $sescode.'_'.$my['uid'];
$sessArr	= explode('_',$sess_Code);
$tmpcode	= $sessArr[0];
$mbruid		= $sessArr[1];
$url		  = $g['url_root'].'/files/';
$size		  = filesize($saveFile);
$width		= 0;
$height		= 0;
$caption	= trim($caption);
$down		= 0;
$d_regis	= $date['totime'];
$d_update	= '';
$hidden		= $type == 2 ? 1 : 0;
$folder		= substr($date['today'],0,4).'/'.substr($date['today'],4,2).'/'.substr($date['today'],6,2);
$thumbname = md5($tmpname).'.'.$fileExt;
$thumbFile = $savePath3.'/'.$thumbname;
ResizeWidth($saveFile,$thumbFile,150);
@chmod($thumbFile,0707);
$IM = getimagesize($saveFile);
$width = $IM[0];
$height= $IM[1];
$mingid = getDbCnt($table['s_upload'],'min(gid)','');
$gid = $mingid ? $mingid - 1 : 100000000;
$QKEY = "gid,hidden,tmpcode,site,mbruid,type,ext,fserver,url,folder,name,tmpname,thumbname,size,width,height,caption,down,d_regis,d_update,cync";
$QVAL = "'$gid','$hidden','$tmpcode','$s','$mbruid','$type','$fileExt','$fserver','$url','$folder','$name','$tmpname','$thumbname','$size','$width','$height','$caption','$down','$d_regis','$d_update','$cync'";
getDbInsert($table['s_upload'],$QKEY,$QVAL);
getDbUpdate($table['s_numinfo'],'upload=upload+1',"date='".$date['today']."' and site=".$s);
if ($gid == 100000000) db_query("OPTIMIZE TABLE ".$table['s_upload'],$DB_CONNECT);
$_up = getDbData($table['s_upload'],'gid='.$gid,'*');
?>
<script type="text/javascript">
//<![CDATA[
	<?php if(!$_up['uid']):?>
	alert('이미지전송에 오류가 있습니다.\n다시 이용해 주세요.');
	<?php else:?>
	var photo = '<img src="<?php echo $_up['url'].$_up['folder'].'/'.$_up['tmpname']?>" width="<?php echo $width?>" align="center" class="photo" alt="" />';
  opener.parent.document.getElementById('upfilesValue').value = opener.parent.document.getElementById('upfilesValue').value + '[<?php echo $_up['uid']?>]';
	opener.parent.frames.upfilesFrame.location.href = opener.parent.frames.upfilesFrame.location.href + '[<?php echo $_up['uid']?>]';
  window.Editor = opener.parent.document.getElementById('editFrame').contentWindow.Editor;
  Editor.getCanvas().pasteContent(photo);
	<?php endif?>
	top.close();
//]]>
</script>
