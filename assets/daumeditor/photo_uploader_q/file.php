<?php include_once $g['dir_module_skin'].'_cal.php'?>
<div id="filebox">
	<div class="header">
		<div class="xl">
			<img src="<?php echo $g['img_core']?>/_public/ico_rb.gif" class="logo" alt="kimsQ-Rb" />
		</div>
		<div class="xr">
			<?php if($d['upload']['use_classicup']):?>
			<?php if($cupload):?>
			<div class="cupload">
			<form name="cform" action="<?php echo $g['s']?>/" method="post" enctype="multipart/form-data" onsubmit="return upCheck(this);" />
			<input type="hidden" name="r" value="<?php echo $r?>" />
			<input type="hidden" name="m" value="<?php echo $m?>" />
			<input type="hidden" name="a" value="upload" />
			<input type="hidden" name="sess_Code" value="<?php echo $sescode?>_<?php echo $my['uid']?>" />
			<input type="hidden" name="saveDir" value="<?php echo $g['path_file']?>" />
			<input type="hidden" name="upType" value="normal" />
			<input type="hidden" name="mod" value="<?php echo $mod?>" />
			<input type="hidden" name="gparam" value="<?php echo $gparam?>" />
			<input type="hidden" name="cupload" value="<?php echo $cupload?>" />
			<input type="file" name="Filedata" class="file" />
			<input type="submit" value="첨부" class="btngray" />
			<input type="button" value="취소" class="btngray" onclick="goHref('<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $m?>&mod=<?php echo $mod?>&gparam=<?php echo $gparam?>');" />
			</form>
			</div>
			<?php else:?>
			<div class="dupload"><a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;mod=<?php echo $mod?>&amp;gparam=<?php echo $gparam?>&amp;cupload=Y">* 업로드가 안되시면 여기를 클릭하세요.</a></div>
			<?php endif?>
			<?php endif?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="bar">
		<div class="xl">
			<img src="<?php echo $g['img_module_skin']?>/ico_drag.gif" alt="" />
			<span class="dragstr">마우스로 드래그하면 순서를 변경할 수 있습니다.</span>
		</div>
		<div class="xr">
			<script type="text/javascript" src="<?php echo $g['s']?>/_core/lib/kimsqSwfuploader.js" charset="utf-8"></script>
			<script type="text/javascript">
			var object_Id = 'kimsqSwfuploader';
			var limitFile = '<?php echo $LimitNum?>';
			var limitSize = '<?php echo $LimitSize?>';
			var flash_Src = '<?php echo $g['s']?>/_core/lib/kimsqSwfuploaderFile.swf';
			var quploader = '../../index.php';
			var qupload_m = '<?php echo $m?>';
			var qupload_a = 'upload';
			var save_Path = '<?php echo $g['path_file']?>';
			var sess_Code = '<?php echo $sescode?>_<?php echo $my['uid']?>';
			var Permision = 'true';
			var Overwrite = 'false';
			var ftypeName = '<?php echo $d['upload']['name_file']?>';
			var ftypeExt1 = '<?php echo $d['upload']['ext_file']?>';
			var ftypeExt2 = '<?php echo $d['upload']['ext_cut']?>';
			var swbgcolor = '#F4F4F4';
			var swf_width = '82';
			var swfheight = '26';
			makeKimsqSwfuploader();
			</script>
			<a href="#." onclick="filesAlldelete('<?php echo $N?>','<?php echo $m?>');"><img src="<?php echo $g['img_module_skin']?>/btn_delete_all.gif" alt="전체삭제" /></a>
		</div>
	</div>
	<div class="body">
		<div class="fbox">
			<div class="title">
				<span class="info">
				용량 : <span class="b"><?php echo getSizeFormat($S,0)?></span> / <?php echo getSizeFormat($d['upload']['limitsize'],0)?>
				개수 : <span class="b"><?php echo $N?>개</span> / <?php echo $d['upload']['limitnum']?>개
				</span>
			</div>
			<div class="ibox scrollbar01">
			<?php if($N):?>
			<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/core.js"></script>
			<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/events.js"></script>
			<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/css.js"></script>
			<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/coordinates.js"></script>
			<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/drag.js"></script>
			<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/dragsort.js"></script>
			<script type="text/javascript">
			//<![CDATA[
			var dragsort = ToolMan.dragsort();
			function slideshowOpen()
			{
				dragsort.makeListSortable(getId("fileorder"));
			}
			window.onload = slideshowOpen;
			//]]>
			</script>

			<ul id="fileorder">
				<?php foreach($P as $val):?>
				<?php $file_ext = getExt($val['name'])?>
				<li>
					<input type="checkbox" name="filemembers[]" value="<?php echo $val['uid']?>" checked="checked" />
					<img src="<?php echo $g['img_core']?>/file/small/<?php echo is_file($g['path_core'].'image/file/small/'.$file_ext.'.gif')?$file_ext:'unknown'?>.gif" alt="" />
					<?php echo $val['name']?><span>(<?php echo getSizeFormat($val['size'],1)?>)</span>
				</li>
				<?php endforeach?>
			</ul>
			<?php endif?>
			</div>
		</div>
	</div>
	<div class="footer">
			<img src="<?php echo $g['img_module_skin']?>/btn_regis.gif" alt="등록" class="hand" onclick="insertFiles();" />
			<img src="<?php echo $g['img_module_skin']?>/btn_cancel.gif" alt="취소" class="hand" onclick="closeFile();" />
	</div>
</div>
<div id="progress">
	<table>
		<tr>
		<td class="td1">전체크기 : </td>
		<td class="td2"><span id="totalsize"></span></td>
		<td class="td3"></td>
		</tr>
		<tr>
		<td class="td1">파일갯수 : </td>
		<td class="td2"><span id="totalnum">0</span>개</td>
		<td class="td3"></td>
		</tr>
		<tr>
		<td class="td1">남은파일 : </td>
		<td class="td2"><span id="remainnum">0</span>개</td>
		<td class="td3"></td>
		</tr>
		<tr>
		<td class="td1">진행파일 : </td>
		<td class="td2"><span id="filename"></span></td>
		<td class="td3"></td>
		</tr>
		<tr>
		<td class="td1">전송상태 : </td>
		<td class="td2"><span class="bggrap"><span id="filegrap" class="grap"></span></span></td>
		<td class="td3">(<span id="filepers">0%</span>)</td>
		</tr>
	</table>
</div>
<script type="text/javascript">
//<![CDATA[
function upCheck(f)
{
	<?php if($d['upload']['limitnum'] < $N+1 || $d['upload']['limitsize'] <= $S):?>
	alert('더 이상 첨부할 수 없습니다.');
	return false;
	<?php endif?>

	if (f.Filedata.value == '')
	{
		alert('첨부할 파일을 선택해 주세요.');
		f.Filedata.focus();
		return false;
	}
	var extarr = f.Filedata.value.split('.');
	var filext = extarr[extarr.length-1].toLowerCase();
	var notext = '[html][php3][inc][cgi][pl][jsp]';

	if (filext == '')
	{
		alert('파일확장자가 없습니다.');
		return false;
	}
	if (notext.indexOf(filext) != -1)
	{
		alert('php,cgi,jsp 파일은 등록할 수 없습니다.    ');
		f.Filedata.focus();
		return false;
	}

	return true;
}
function filesAlldelete(n,m)
{
	if (n == '0')
	{
		alert('첨부된 파일이 없습니다.    ');
		return false;
	}
	if (confirm('정말로 모두 삭제하시겠습니까?   '))
	{
		frames._action_frame_<?php echo $m?>.location.href = '<?php echo $g['s']?>/?r=<?php echo $r?>&m=' + m + '&a=files_delete&dtype=file';
	}
}
function insertFiles()
{
    var l = document.getElementsByName('filemembers[]');
    var n = l.length;
    var i;
	var j = 0;
	var upfiles = '';

	for	(i = 0; i < n; i++)
	{
		if (l[i].checked == true)
		{
			j++;
			upfiles += '['+l[i].value+']';
		}
	}
	if (!j)
	{
		alert('첨부된 파일이 없습니다.     ');
		return false;
	}
  opener.parent.getId('upfilesValue').value = opener.parent.getId('upfilesValue').value + upfiles;
  opener.parent.frames.upfilesFrame.location.href = opener.parent.frames.upfilesFrame.location.href + upfiles;
	frames._action_frame_<?php echo $m?>.location.href = '<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $m?>&a=sess_delete';
}
function closeFile()
{
	frames._action_frame_<?php echo $m?>.location.href = '<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $m?>&a=files_delete&dtype=file&close=Y';
}
if (!opener)
{
	location.href = '<?php echo RW(0)?>';
}
else {
	document.title = '파일첨부';
	top.resizeTo(470,410);
}
//]]>
</script>