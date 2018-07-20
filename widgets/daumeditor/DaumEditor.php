<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace modules\board\widgets\daumeditor;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\AssetBundle;
use yii\widgets\InputWidget;


class DaumEditor extends InputWidget
{
    /**
     * @var array the options for the Imperavi Redactor.
     * Please refer to the corresponding [Imperavi Web page](http://imperavi.com/redactor/docs/)  for possible options.
     */
    public $options = [];

    /**
     * @var array the html options.
     */
    public $htmlOptions = [];

    /**
     * @var array plugins that you want to use
     */
    public $plugins = [];

    /*
     * @var object model for active text area
     */
    public $model = null;

    /*
     * @var string selector for init js scripts
     */
    protected $selector = null;

    /*
     * @var string name of textarea tag or name of attribute
     */
    public $attribute = null;

    /*
     * @var string value for text area (without model)
     */
    public $value = '';
	
	
	public $bbsdata_id = null;

    /**
     * @var \yii\web\AssetBundle|null Imperavi Redactor Asset bundle
     */
    protected $_assetBundle = null;

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();
        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->getId();
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->selector = '#' . $this->htmlOptions['id'];

		echo Html::activeHiddenInput($this->model, 'content', $this->htmlOptions);

        $this->registerDaumEditorAsset();
        $this->registerClientScript();
		
		include __DIR__.'/_editor.php';
		
		#$this->getView()->registerJsFile('/_core/plugin/editor/daumeditor/main.js');
    }

    /**
     * Registers Imperavi Redactor asset bundle
     */
    protected function registerDaumEditorAsset()
    {
        $this->_assetBundle = DaumEditorAsset::register($this->getView());
    }

    /**
     * Returns current asset bundle
     * @return \yii\web\AssetBundle current asset bundle for Redactor
     */
    protected function getAssetBundle()
    {
        if (!($this->_assetBundle instanceof AssetBundle)) {
            $this->registerDaumEditorAsset();
        }

        return $this->_assetBundle;
    }

    /**
     * Registers Imperavi Redactor JS
     */
    protected function registerClientScript()
    {
        $view = $this->getView();

		$baseUrl = $this->_assetBundle->baseUrl;
		
		$form_id = $this->htmlOptions['id'];
		
		$js = <<< JS
	
	window.DaumEditor = window.DaumEditor || []; 
	
	var editor_form_id = '$form_id';
	
	var config = {
		txHost: '', 
		txPath: '$baseUrl',
		txService: 'sample', /* 수정필요없음. */
		txProject: 'sample', /* 수정필요없음. 프로젝트가 여러개일 경우만 수정한다. */
		initializedId: "", /* 대부분의 경우에 빈문자열 */
		wrapper: "tx_trex_container", /* 에디터를 둘러싸고 있는 레이어 이름(에디터 컨테이너) */
		form: '$form_id'+"", /* 등록하기 위한 Form 이름 */
		txIconPath: '$baseUrl'+"/images/icon/editor/", 
		txDecoPath: '$baseUrl'+"/images/deco/contents/", 
		canvas: {
            exitEditor:{
                /*
                desc:'빠져 나오시려면 shift+b를 누르세요.',
                hotKey: {
                    shiftKey:true,
                    keyCode:66
                },
                nextElement: document.getElementsByTagName('button')[0]
                */
            },
			styles: {
				color: "#123456", /* 기본 글자색 */
				fontFamily: "굴림", /* 기본 글자체 */
				fontSize: "12pt", /* 기본 글자크기 */
				backgroundColor: "#fff", /*기본 배경색 */
				lineHeight: "1.5", /*기본 줄간격 */
				padding: "8px" /* 위지윅 영역의 여백 */
			},
			showGuideArea: false
		},
		events: {
			preventUnload: false
		},
		sidebar: {
			attachbox: {
				show: true,
				confirmForDeleteAll: true
			}
		},
		size: {
			contentWidth: 700 /* 지정된 본문영역의 넓이가 있을 경우에 설정 */
		}
	};

	EditorJSLoader.ready(function(Editor) {
		var editor = new Editor(config);
		
		window.DaumEditor[editor_form_id] = editor; 
	});
	
	//$('#'+editor_form_id).css('display', 'none');
	
	// 폼 submit 하기 전 에디터의 내용을 textarea에 넣음. 
	jQuery('#'+editor_form_id).closest('form').on('submit', function(e) {
		var rv; 
		updateAttachment();
		rv = checkNsendBBS();
		
		return rv;
	});
	
	jQuery('a.tx-insert').click(function(e) {
		//console.log('1');
		var contentData = Editor.getContent();
		contentData = contentData.replace(/style=""""/gi, "");		
		Editor.modify({ content: contentData });
	});
	
	// 에디터가 정상적으로 로드 완료되면 이벤트를 발생
	Editor.getCanvas().observeJob(Trex.Ev.__IFRAME_LOAD_COMPLETE, function() {
		//최초 높이 세팅
		//Editor.getCanvas().setCanvasSize({height:'500'});
		
		//부모 아이프레임 크기 수정		
		parentSize();
		
		//데이터 가져옴 양식이 있을 수 있으므로 작성/수정 모두 동작
		loadData();
		
		//if (myagent == 'ie') // IE일때만..
		//	document.getElementById('tx_trex_container').onresize = parentSize; //데이터 크기 바뀔때..IE만 해당
		
		loadAttachment();
	});

	//iframe에디터 로드후 세팅, 수정시 데이터 세팅
	function loadData()
	{
		var contentData = parent.document.getElementById('$form_id').value;
		var editMode = '';
		
		//console.log(contentData);
		//console.log(Editor);
		
		//데이터
		if (contentData)
		{
			//편집모드
			if (editMode == 'text')
			{
				//contentData = contentData.replace(/\\r/gi, "");
				//contentData = contentData.replace(/\\n/gi, "");
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
	function checkNsendBBS()
	{
		var targetObj =  document.getElementById('$form_id');
		
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
			//targetObj.value = data.replace(/imgOrignWin\([a-z]{4}\.src\)=""/gi, "imgOrignWin(this.src)");
			
			targetObj.value = data;
		}
		
		return true;
	}
	
	function updateAttachment() 
	{
		//var uploadObj = document.getElementById('bbsdata-upload_json');		
		//var uploadObj = document.getElementById('upload_json');
		//uploadObj.value = JSON.stringify(Editor.getAttachments());
		
		$('input[name=upload_json]').val(
			JSON.stringify(Editor.getAttachments()));
	}
	
	function loadAttachment() 
	{
		//var uploadObj = document.getElementById('bbsdata-upload_json');
		//var uploadObj = document.getElementByName('upload_json');
		//var upload_jon_obj = ''; 
		//if(uploadObj.value == '')
		//	return;
	
		var upload_value = $('input[name=upload_json]').val();
		
		if(upload_value == '') return;

		try {
			upload_jon_obj = JSON.parse(upload_value); 
			Editor.modify({
				"attachments": upload_jon_obj
			});
			
		} catch(e) { 
			console.log(e)
		}
	}
	
	//아이프레임 크기
	function parentSize()
	{
/* 		//부모 높이
		var parentH = parent.document.getElementById('editFrame').height;
		//내 높이
		var selfH  = document.body.clientHeight;
		//부모높이 수정 {필요에 따라 수정값 넣고 빼고 하면댐. 기본은 6px}
		var parent_var = 2;
		//console.log('self:' + selfH);
		//console.log('parentH:' + parentH);
		//console.log('parent_var:' + parent_var);

		parent.document.getElementById('editFrame').style.height = (selfH + parent_var)+'px';
*/	}
	
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
JS;
	
		$view->registerJs($js);
 
	}
}

