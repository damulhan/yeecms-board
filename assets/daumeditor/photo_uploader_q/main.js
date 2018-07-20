//편집모드 토글
function toggleEditMode()
{
  var modeContainer = parent.document.getElementById("editFrameHtml");
  var mode = modeContainer.value;

  switch (mode)
  {
    case 'HTML':
      var msg = '텍스트모드로 변경합니다.\n\n변경후엔 위지위그 요소가 사라지며 되돌릴 수 없습니다. \n\n그래도 변경하시겠습니까?';
      if (confirm(msg))
      {
        Editor.getCanvas().changeMode('text');
        modeContainer.value = 'TEXT';
        document.getElementById("onIcon1").style.display="none";
        document.getElementById("onIcon2").style.display="none";
        document.getElementById("imgEditor").style.display="none";
        document.getElementById("offIcon1").style.display="block";
        document.getElementById("offIcon2").style.display="block";
        document.getElementById("offIcon3").style.display="block";
        parent.document.getElementById("upfilesFrame").style.display="none";
      }
    break;

    case 'TEXT':
      Editor.getCanvas().changeMode('html');
      modeContainer.value = 'HTML';
      document.getElementById("onIcon1").style.display="block";
      document.getElementById("onIcon2").style.display="block";
      document.getElementById("imgEditor").style.display="block";
      document.getElementById("offIcon1").style.display="none";
      document.getElementById("offIcon2").style.display="none";
      document.getElementById("offIcon3").style.display="none";
      if (parent.document.getElementById("upfilesValue").value )
        parent.document.getElementById("upfilesFrame").style.display="block";
    break;
  }
}

//결과값삽입
function EditDrop(result)
{
	var modeContainer = parent.document.getElementById("editFrameHtml");
	var mode = modeContainer.value;

	//if(CheckTextMode())
	if(mode == 'HTML')
	{
		window.Editor = parent.document.getElementById('editFrame').contentWindow.Editor;
		Editor.getCanvas().pasteContent(result);
	}
}

//작성코드얻기
function getEditCode(content,html)
{
	//content.value = editStartMode == 'HTML' ? frames.editAreaIframe.document.body.innerHTML : getId('editAreaTextarea').value; 

	content.value = Editor.getCanvas().getContent();
	content.value = content.value.replace(/src=\"files\//g,'src="'+rooturl+'/files/');
	
	if (content.value == '<br>') content.value = '';
	
	html.value = editStartMode;
}
