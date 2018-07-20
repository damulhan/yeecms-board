<link rel="stylesheet" href="./photo.css" type="text/css"  charset="utf-8"/>

<?php include_once $g['dir_module_skin'].'_cal.php'?>

<div id="photobox">

	<div class="header">
		<div class="xl">
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
	<div class="wrap">

		<div class="bar">

			<div class="xl">
				<img src="<?php echo $g['img_module_skin']?>/ico_drag.gif" alt="" />
				<span class="dragstr">마우스로 드래그하면 순서를 변경할 수 있습니다.</span>
			</div>
			<div class="xr">

				<span class="info">
				용량 : <span class="b"><?php #echo getSizeFormat($S,0)?></span> / <?php #echo getSizeFormat($d['upload']['limitsize'],0)?>
				개수 : <span class="b"><?php #echo $N?>개</span> / <?php #echo $d['upload']['limitnum']?>개
				</span>

				<script type="text/javascript" src="<?php echo $g['s']?>/_core/lib/kimsqSwfuploader.js" charset="utf-8"></script>
				<script type="text/javascript">
				var object_Id = 'kimsqSwfuploader';
				var limitFile = '<?php echo $LimitNum?>';
				var limitSize = '<?php echo $LimitSize?>';
				var flash_Src = '<?php echo $g['s']?>/_core/lib/kimsqSwfuploaderPhoto.swf';
				var quploader = '../../index.php';
				var qupload_m = '<?php echo $m?>';
				var qupload_a = 'upload';
				var save_Path = '<?php echo $g['path_file']?>';
				var sess_Code = '<?php echo $sescode?>_<?php echo $my['uid']?>';
				var Permision = 'true';
				var Overwrite = 'false';
				var ftypeName = '<?php echo $d['upload']['name_img']?>';
				var ftypeExt1 = '<?php echo $d['upload']['ext_img']?>';
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
			<div class="viewer scrollbar01">
				<?php if($N):?>
				<script type="text/javascript" src="/lib/tool-man/core.js"></script>
				<script type="text/javascript" src="/lib/tool-man/events.js"></script>
				<script type="text/javascript" src="/lib/tool-man/css.js"></script>
				<script type="text/javascript" src="/lib/tool-man/coordinates.js"></script>
				<script type="text/javascript" src="/lib/tool-man/drag.js"></script>
				<script type="text/javascript" src="/lib/tool-man/dragsort.js"></script>
				<script type="text/javascript">
				//<![CDATA[
				var dragsort = ToolMan.dragsort();
				function slideshowOpen()
				{
					dragsort.makeListSortable(getId("photoorder"));
				}
				window.onload = slideshowOpen;
				//]]>
				</script>

				<ul id="photoorder">
					<?php foreach($P as $val):?>
					<li>
						<input type="checkbox" name="photomembers[]" value="<?php echo $val['uid']?>|<?php echo $val['url'].$val['folder'].'/'.$val['tmpname']?>|<?php echo $val['width']?>" checked="checked" />
						<span id="finfo_name_<?php echo $val['uid']?>" class="hide"><?php echo htmlspecialchars(str_replace('.'.getExt($val['name']),'',$val['name']))?></span>
						<span id="finfo_caption_<?php echo $val['uid']?>" class="hide"><?php echo htmlspecialchars($val['caption'])?></span>
						<span id="finfo_tmpcode_<?php echo $val['uid']?>" class="hide"><?php echo $val['tmpcode']?></span>
						<div title="<?php echo $val['name']?> (<?php echo getSizeFormat($val['size'],1)?>/<?php echo $val['width']?>*<?php echo $val['height']?>px)" class="photo" style="background:url('<?php echo $val['url'].$val['folder'].'/'.$val['thumbname']?>') center center no-repeat;" onclick="captionCheck('<?php echo $val['uid']?>');"></div>
					</li>
					<?php endforeach?>
				</ul>
				<?php else:?>
				<div class="none">
				<div class="ment"><span><?php #echo getSizeFormat($d['upload']['limitsize'],0)?>,<?php #echo $d['upload']['limitnum']?>개</span>까지 올릴 수 있습니다.</div>
				</div>
				<?php endif?>

			</div>

			<div class="tool">

				<div class="tt">가로크기<span>(모든사진)</span></div>
				<div class="stype shift">
				<input type="radio" name="sizetype" id="sizetype1" value="1" checked="checked" onclick="sizeTypeSelect(1);" />
				<select id="wsize1" onclick="sizeTypeSelect(1);">
				<option value="240"<?php if($d['upload']['width_img']=='240'):?> selected="selected"<?php endif?>>240px</option>
				<option value="320"<?php if($d['upload']['width_img']=='320'):?> selected="selected"<?php endif?>>320px</option>
				<option value="400"<?php if($d['upload']['width_img']=='400'):?> selected="selected"<?php endif?>>400px</option>
				<option value="480"<?php if($d['upload']['width_img']=='480'):?> selected="selected"<?php endif?>>480px</option>
				<option value="640"<?php if($d['upload']['width_img']=='640'):?> selected="selected"<?php endif?>>640px</option>
				<option value="720"<?php if($d['upload']['width_img']=='720'):?> selected="selected"<?php endif?>>720px</option>
				<option value="1024"<?php if($d['upload']['width_img']=='1024'):?> selected="selected"<?php endif?>>1024px</option>
				</select>
				</div>
				<div class="stype shift">
				<input type="radio" name="sizetype" id="sizetype2" value="2" onclick="sizeTypeSelect(2);" />
				<input type="text" id="wsize2" value="직접입력" maxlength="4" class="input" onfocus="sizeFocus(this);" onblur="sizeBlur(this);" />px
				</div>

				<div class="guide">
				사진 크기는 원본 사이즈<br />
				내에서 변경 가능합니다.<br /><br />
				파일명을 변경하거나<br />
				캡션을 등록하려면 사진을<br />
				클릭하세요.
				</div>

				<div class="line"></div>

				<div class="tt">위치<span>(모든사진)</span></div>

				<div class="align shift">
				<input type="radio" name="align" id="xalign1" value="top" checked="checked" /><img src="<?php echo $g['img_module_skin']?>/align_top.gif" alt="맨위" /><label for="xalign1">맨위</label><br />
				<input type="radio" name="align" id="xalign2" value="bottom" /><img src="<?php echo $g['img_module_skin']?>/align_bottom.gif" alt="아래" /><label for="xalign2">아래</label><br />
				<input type="radio" name="align" id="xalign3" value="left" /><img src="<?php echo $g['img_module_skin']?>/align_left.gif" alt="왼쪽" /><label for="xalign3">왼쪽</label><br />
				<input type="radio" name="align" id="xalign4" value="right" /><img src="<?php echo $g['img_module_skin']?>/align_right.gif" alt="오른쪽" /><label for="xalign4">오른쪽</label><br />
				</div>

				<div class="line"></div>

				<div class="oview shift"><input type="checkbox" id="orignview" value="1" />클릭시 원본사진 보기</div>

			</div>
		</div>
		<div class="clear"></div>

		<div id="captionDiv" class="caption">
			<form name="captionForm" action="<?php echo $g['s']?>/" method="post" target="_action_frame_<?php echo $m?>" onsubmit="return captionRegis(this);">
			<input type="hidden" name="r" value="<?php echo $r?>" />
			<input type="hidden" name="m" value="<?php echo $m?>" />
			<input type="hidden" name="a" value="caption_regis" />
			<input type="hidden" name="tmpcode" value="" />
			<input type="hidden" name="uid" value="" />
			파일명 <input type="text" name="name" value="" class="input c1" />
			캡션 <input type="text" name="caption" value="" class="input c2" />
			<input type="submit" value="등록" class="btnblue" />
			<input type="button" value="취소" class="btngray" onclick="captionClose();" />
			</form>
		</div>

		<div class="footer">
			<img src="<?php echo $g['img_module_skin']?>/btn_regis.gif" alt="등록" class="hand" onclick="insertPhoto();" />
			<img src="<?php echo $g['img_module_skin']?>/btn_cancel.gif" alt="취소" class="hand" onclick="closePhoto();" />
		</div>
		</div>
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
function captionClose()
{
	getId('captionDiv').style.visibility = 'hidden';
}
function captionRegis(f)
{
	if (f.uid.value == '')
	{
		return false;
	}
	if (f.name.value == '')
	{
		alert('파일명을 입력해 주세요. ');
		f.name.focus();
		return false;
	}
	return confirm('정말로 실행하시겠습니까?  ');
}
function captionCheck(xuid)
{
	var f = document.captionForm;
	var xname = getId('finfo_name_'+xuid).innerHTML;
	var xcaption = getId('finfo_caption_'+xuid).innerHTML;
	var xtmpcode = getId('finfo_tmpcode_'+xuid).innerHTML;
	getId('captionDiv').style.visibility = 'visible';
	f.uid.value = xuid;
	f.name.value = xname;
	f.caption.value = xcaption;
	f.tmpcode.value = xtmpcode;
	f.caption.focus();
}
function upCheck(f)
{
	<?php if($d['upload']['limitnum'] < $N+1 || $d['upload']['limitsize'] <= $S):?>
	alert('더 이상 첨부할 수 없습니다.');
	return false;
	<?php endif?>

	if (f.Filedata.value == '')
	{
		alert('사진파일을 선택해 주세요.');
		f.Filedata.focus();
		return false;
	}
	var extarr = f.Filedata.value.split('.');
	var filext = extarr[extarr.length-1].toLowerCase();
	var permxt = '[gif][jpg][jpeg][png]';
	var notext = '[html][php][php3][inc][cgi][pl][jsp]';

	if (notext.indexOf(filext) != -1)
	{
		alert('php,cgi,jsp 파일은 등록할 수 없습니다.    ');
		f.Filedata.focus();
		return false;
	}

	if (permxt.indexOf(filext) == -1)
	{
		alert('gif/jpg/png 파일만 등록할 수 있습니다.    ');
		f.Filedata.focus();
		return false;
	}
	return true;
}
function filesAlldelete(n,m)
{
	if (n == '0')
	{
		alert('첨부된 사진이 없습니다.    ');
		return false;
	}
	if (confirm('정말로 모두 삭제하시겠습니까?   '))
	{
		frames._action_frame_<?php echo $m?>.location.href = '<?php echo $g['s']?>/?r=<?php echo $r?>&m=' + m + '&a=files_delete&dtype=photo';
	}
}
function sizeTypeSelect(n)
{
	getId('sizetype' + n).checked = true;
	if (n == 1) getId('wsize2').value = getId('wsize2').defaultValue;
}
function sizeFocus(obj)
{
	getId('sizetype2').checked = true;
	if (obj.value == obj.defaultValue) obj.value = '';
}
function sizeBlur(obj)
{
	if (obj.value == '')
	{
		sizeTypeSelect(1);
		obj.value = obj.defaultValue;
	}
	else {
		numFormat(obj);
	}
}
function insertPhoto()
{
    var l = document.getElementsByName('photomembers[]');
    var n = l.length;
    var i;
	var j = 0;
	var photos = '';
	var upfiles= '';

	var d_width;
	var x_width;
	var x_align;
	var x_orign;
	var val;

	if (getId('sizetype1').checked == true)
	{
		x_width = parseInt(getId('wsize1').value);
	}
	else {
		x_width = parseInt(getId('wsize2').value);
	}

	for (i = 1; i < 5; i++)
	{
		if(getId('xalign'+i).checked == true)
		{
			x_align = getId('xalign'+i).value;
			break;
		}
	}
	if (getId('orignview').checked == true)
	{
		x_orign = true;
	}

	for	(i = 0; i < n; i++)
	{
		if (l[i].checked == true)
		{
			val = l[i].value.split('|');

			d_width = parseInt(val[2]) < x_width ? parseInt(val[2]) : x_width;

			if (x_orign == true)
			{
				photos += '<img src="' + val[1] +'" width="'+d_width+'" align="'+x_align+'" class="txc-image photo hand" imgOrignWin(this.src) alt="" />';
			}
			else
			{
				photos += '<img src="' + val[1] +'" width="'+d_width+'" align="'+x_align+'" class="txc-image photo" alt="" />';
			}
			//photos += '<br /><br />';
			j++;
		}
		upfiles += '['+val[0]+']';
	}
	if (!j)
	{
		alert('첨부된 사진이 없습니다.     ');
		return false;
	}
	
	opener.parent.getId('upfilesValue').value = opener.parent.getId('upfilesValue').value + upfiles;
	opener.parent.frames.upfilesFrame.location.href = opener.parent.frames.upfilesFrame.location.href + upfiles;
	window.Editor = opener.parent.document.getElementById('editFrame').contentWindow.Editor;
	Editor.getCanvas().pasteContent(photos);

	frames._action_frame_<?php echo $m?>.location.href = '<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $m?>&a=sess_delete';
}
function closePhoto()
{
	frames._action_frame_<?php echo $m?>.location.href = '<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $m?>&a=files_delete&dtype=photo&close=Y';
}
if (!opener)
{
	location.href = '<?php echo RW(0)?>';
	document.body.style.overflow='hidden';
}
else {
	document.title = '사진첨부';
	document.body.style.overflow='hidden';
	top.resizeTo(750,660);
}
//]]>
</script>