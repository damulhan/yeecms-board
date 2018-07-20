<?php

//게시판
if ($use == 'bbs'){
  require './modules/bbs/theme/'.$skin.'/_var.php';
  $toolbar = 'ON'; //$d['theme']['show_edittool2'] <= $my['level'] ? 'ON':'OFF';
}
//댓글
else if ($use == 'comment'){
  require './modules/comment/var/var.php';
  if(!$height) $height = '100';
  $toolbar = 'OFF';
}
//소셜팩 
else if ($use == 'spackgroup') {
  #echo is_directory('./') ? "ddddd Y" : "N"; 
  #include './modules/spack/theme/_bbs/_pc/group/_var.php'; 
  #$toolbar = $d['theme']['show_edittool2'] <= $my['level'] ? 'ON':'OFF';
}
else if ($use == 'editor') {
  $editmode = 'HTML';
  if(!$height) $height = '565';
  $d['theme']['perm_photo'] = '100'; // disable 
  $d['theme']['perm_upload'] = '100'; //disable
} else {
  $editmode = 'HTML';
  if(!$height) $height = '565';
  $d['theme']['perm_photo'] = '100'; // disable 
  $d['theme']['perm_upload'] = '100'; //disable  
}

#include './modules/spack/theme/_bbs/_pc/group/_var.php'; 

//사진과 파일 업로드 기준
#$upPhoto = $d['theme']['perm_upload'] ? $d['theme']['perm_upload'] : $d['comment']['perm_photo'];
#$upFile  = $d['theme']['perm_photo']  ? $d['theme']['perm_photo']  : $d['comment']['perm_upfile'];
$upPhoto = $d['theme']['perm_photo'] ? $d['theme']['perm_photo'] : '1'; 
$upFile  = $d['theme']['perm_upload']  ? $d['theme']['perm_upload']  : '1';
?>
<script type="text/javascript" charset="utf-8" src="<?php echo $g['url_module']?>/js/editor_loader.js?service=KIMSQ-RB"></script>
  <!-- 에디터 시작 -->
  <form name="daumEditorForm" id="daumEditorForm" method="post" action="" onsubmit="return false;">
    <!-- 에디터 컨테이너 시작 -->
		<div id="tx_trex_container" class="tx-editor-container">
      <!-- 툴바 S -->
      <?php include_once 'toolbar.php';?>
      <!-- 툴바 E -->
      <!-- 편집영역 시작 -->
      <!-- 에디터 Start -->
	   
      <div id="tx_canvas" class="tx-canvas">
        <div id="tx_loading" class="tx-loading"><div><img src="<?php echo $g['img_module']?>/icon/editor/loading2.png" width="113px" height="21px" align="absmiddle"/></div></div>
        <div id="tx_canvas_wysiwyg_holder" class="tx-holder" style="display:block;">
          <iframe id="tx_canvas_wysiwyg" name="tx_canvas_wysiwyg" allowtransparency="true" frameborder="0"></iframe>
        </div>
        <div class="tx-source-deco">
          <div id="tx_canvas_source_holder" class="tx-holder">
            <textarea id="tx_canvas_source" rows="30" cols="30"></textarea>
          </div>
        </div>
        <div id="tx_canvas_text_holder" class="tx-holder">
          <textarea id="tx_canvas_text" rows="30" cols="30"></textarea>
        </div>
      </div>
		<!-- 높이조절 Start -->
      <?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') && $use == 'bbs' ): ?>
      <div id="tx_resizer" class="tx-resize-bar" style="border-left:1px solid #E0E0E0;border-right:1px solid #e0e0e0">
        <div class="tx-resize-bar-bg"></div>
        <img id="tx_resize_holder" src="<?php echo $g['img_module']?>/icon/editor/skin/01/btn_drag01.gif" width="58px" height="12px" unselectable="on" alt="" />
      </div>
      <?php endif ?>
      <!-- 편집영역 끝 -->
    </div>
    <!-- 에디터 컨테이너 끝 -->
  </form>
<!-- 에디터 끝 -->
<script type="text/javascript">
//<![CDATA[
<?php if($editmode): ?>
var editMode = "<?php echo $editmode?>".toLowerCase();
<?php else: ?>
var editMode = parent.document.getElementById('editFrameHtml').value.toLowerCase();
<?php endif ?>
//에디터 기본 환경설정
var config = {
  txHost: '',
  txPath: '',
  txService: 'KIMSQ-RB',
  txProject: 'sample',
  initializedId: "",
  wrapper: "tx_trex_container",
  form: 'daumEditorForm'+"", //에디터 폼
  txIconPath: "<?php echo $g['img_module']?>/icon/editor/", //아이콘 경로
  txDecoPath: "<?php echo $g['img_module']?>/deco/contents/", //이미지 경로
  canvas: {
    styles: {
      color           : "<?php echo $d['daumEditor']['color']?>" //글꼴색상
     ,fontFamily      : "<?php echo $d['daumEditor']['font_etc']?$d['daumEditor']['font_etc']:$d['daumEditor']['font']?>" //글꼴
     ,fontSize        : "<?php echo $d['daumEditor']['size_etc']?$d['daumEditor']['size_etc']:$d['daumEditor']['size']?>" //글꼴 크기
     ,backgroundColor : "<?php echo $d['daumEditor']['bgcolor']?>" //배경색상
     ,lineHeight      : "<?php echo $d['daumEditor']['line']?>" //행간
     ,padding         : "<?php echo $d['daumEditor']['padding']?>" //에디터 패딩
    },
    selectedMode: editMode, //편집모드
    sidebar: {
      attachbox:{ show:false } //첨부박스 숨김
    }
  },
  events : {preventUnload: false } //확인메시지 발생안함
};

var editStartMode  = editMode;
var editSrcMode = false;

EditorJSLoader.ready(function(Editor) {
  var editor = new Editor(config); //에디터생성
});

// 에디터가 정상적으로 로드 완료되면 이벤트를 발생
Editor.getCanvas().observeJob(Trex.Ev.__IFRAME_LOAD_COMPLETE, function() {
  //최초 높이 세팅
  Editor.getCanvas().setCanvasSize({height:<?php echo $height ? $height : 100; ?>});
  //부모 아이프레임 크기 수정
  parentSize();
  //데이터 가져옴 양식이 있을 수 있으므로 작성/수정 모두 동작
  loadData();
  if (myagent == 'ie') // IE일때만..
    document.getElementById('tx_trex_container').onresize = parentSize; //데이터 크기 바뀔때..IE만 해당
});

//iframe에디터 로드후 세팅, 수정시 데이터 세팅
function loadData()
{
  var contentData = parent.document.getElementById('editFrameContent').value;
  //데이터
  if (contentData)
  {
    //편집모드
    if (editMode == 'text')
    {
      //contentData = contentData.replace(/\r/gi, "");
      //contentData = contentData.replace(/\n/gi, "");
      Editor.getCanvas().changeMode('text');
    }
    else
    {
      contentData = contentData.replace(/onclick=\"imgOrignWin\(([a-z]{4})\.src\)\"/gi, "imgOrignWin(this.src)");
      contentData = contentData.replace(/onclick=imgOrignWin\(([a-z]{4})\.src\)/gi, "imgOrignWin(this.src)");
      Editor.getCanvas().changeMode('html');
    }
    //데이터 밀어넣기
    Editor.modify({ content: contentData });
    //에디터에 포커스 맞추기
    //Editor.focusOnBottom(); //불편할때가 의외로 많아 일단 사용안함
  }
}

//작성된 데이터 검증 후 확인값 또는 데이터 넘겨주기 게시판용
function checkNsendBBS(targetObj)
{
  //검증 오브젝트 생성
  var validator = new Trex.Validator();
  //데이터 가져와서
  var data = Editor.getContent();
  //검증
  if (!validator.exists(data))
  {
    //검증 통과 못함.
    return false;
  }
  else
  {
    //좋아! 저장하기 위해 넘겨주자고.. 이미지 확대 보기가 또 문제. 췟..
    targetObj.value = data.replace(/imgOrignWin\([a-z]{4}\.src\)=""/gi, "imgOrignWin(this.src)");
  }
  return true;
}
//작성된 데이터 검증 후 확인값 또는 데이터 넘겨주기 댓글용
function checkNsendCOMMENT(targetObj, subjectObj)
{
  //검증 오브젝트 생성
  var validator = new Trex.Validator();
  //데이터 가져와서
  var data = Editor.getContent();
  //검증
  if (!validator.exists(data))
  {
    //검증 통과 못함.
    return 'NODATA';
  }
  else
  {
    //이미지 원본 확대창을 사용하기 위해서.
    data = data.replace(/imgOrignWin\([a-z]{4}\.src\)=""/gi, "imgOrignWin(this.src)");
    //제목 사용하지 않을때 이미지만 덩그러니 넣으면 제목생성이 안된다.
    if (!subjectObj)
    {
      //태그 지워버리고 최소한의 텍스트 문자열이 없으면 경고후 halt
      var testStr = data.replace(/<(?:.|\n)*?>/gm, '');
      if (!validator.exists(testStr))//통과 못하면
      {
        //통과 못함.
        return 'NOTEXT';
      }
      else
      {
        //본문과 제목 데이터 넘겨줌
        targetObj.value = data;
        subjectObj.value = data;
      }
    }
    //제목 사용하면
    else
    {
      //본문과 제목 데이터 넘겨줌
      targetObj.value = data;
    }
  }
}
//아이프레임 크기
function parentSize()
{
  //부모 높이
  var parentH = parent.document.getElementById('editFrame').height;
  //내 높이
  var selfH  = document.body.clientHeight;
  //부모높이 수정 {필요에 따라 수정값 넣고 빼고 하면댐. 기본은 6px}
  var parent_var = 2;
  //console.log('self:' + selfH);
  //console.log('parentH:' + parentH);
  //console.log('parent_var:' + parent_var);
  
  parent.document.getElementById('editFrame').style.height = (selfH + parent_var)+'px';
}
//포토에디터
function runImgEditor()
{
  //포토에디터 주소
  //var address = 'http://s.lab.naver.com/pe/service?import=&exportMethod=BROWSER&exportTitle=';
  var address = 'http://www.picmonkey.com/';
  //화면중앙값
  var left = (screen.width/2)-512;
  var top = (screen.height/2)-384;
  //주소생성
  //address = address+encodeURIComponent("에디터에 삽입");
  //address = address+'&exportTo=';
  //address = address+encodeURIComponent(rooturl+'/modules/daumEditor/theme/default/imgeditor.php?appId=ir1');
  //윈도 오픈
  window.open(address,"_blank","left="+left+", top="+top+",width=1024,height=768, resizable=yes,directories=no,menubar=no,toolbar=no,location=no,status=no,copyhistory=no");
}
//도구상자열고닫기
var isToolbarOpen = false;
function showToolbar(enable) {
  var enable_txt = enable? 'block': 'none';
  document.getElementById('toolBarWrap').style.display = enable_txt;
}
function ToolboxShowHide(plusH) {
  var disp = document.getElementById('toolBarWrap').style.display;
  var toggle = (disp=='block'||disp=='') ? false : true;
  isToolbarOpen = toggle;
  showToolbar(toggle);
  
  if (isToolbarOpen == false)
  {
    document.body.clientHeight -= plusH;
  }
  else {
    document.body.clientHeight += plusH;
  }
  
  parentSize();
  toggleEditMode();
}
//]]>
</script>