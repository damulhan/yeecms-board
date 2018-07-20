<?php
#if(!defined('__KIMS__')) exit;

$S = 0;
$N = 0;

if ($files)
{
	$d['upload'] = getArrayString($files);
	foreach($d['upload']['data'] as $_val)
	{
		$U = getUidData($table['s_upload'],$_val);
		if ($U['uid'])
		{
			$S+= $U['size'];
			$N++;
		}
	}
}

$P = array();
if (!$_SESSION['upsescode'])
{
	$_SESSION['upsescode'] = str_replace('.','',$g['time_start']);
}

$sescode = $_SESSION['upsescode'];

if ($sescode)
{
	$PHOTOS = getDbArray($table['s_upload'],"tmpcode='".$sescode."'",'*','uid','asc',0,0);
	while($R = db_fetch_array($PHOTOS))
	{
		$P[] = $R;
		$S += $R['size'];
		$N++;
	}
}
if ($mod == 'photo')
{
	$d['upload']['limitnum'] = $d['upload']['maxnum_img'];
	$d['upload']['limitsize'] = $d['upload']['maxsize_img'];
}
else {
	$d['upload']['limitnum'] = $d['upload']['maxnum_file'];
	$d['upload']['limitsize'] = $d['upload']['maxsize_file'];
}
$d['upload']['limitsize'] = $d['upload']['limitsize'] * 1024 * 1024;


$LimitNum = $d['upload']['limitnum'] - $N;
$LimitSize= $d['upload']['limitsize'] - $S;

?>