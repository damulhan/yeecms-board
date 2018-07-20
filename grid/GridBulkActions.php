<?php
namespace modules\board\grid;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Url;

class GridBulkActions extends Widget
{
    /**
     * @var array
     */
    public $actions;
    public $targets;

    /**
     * @var string
     */
    public $controller;
    /**
     * @var string
     */
    public $gridId;

    /**
     * Default - $this->gridId . '-pjax'
     *
     * @var string
     */
    public $pjaxId;

    /**
     * @var string
     */
    public $okButtonClass = 'btn btn-sm btn-default';

    /**
     * @var string
     */
    public $dropDownClass = 'form-control input-sm';

    /**
     * @var string
     */
    public $wrapperClass = 'form-inline';

    /**
     * @var string
     */
    public $promptText;

    /**
     * @var string
     */
    public $confirmationText;

    /**
     * Multilingual support
     */
    public function init()
    {
        parent::init();

        $this->promptText = $this->promptText ? $this->promptText : Yii::t('yee', '--- 게시판 선택 ---');
        $this->confirmationText = $this->confirmationText ? $this->confirmationText : Yii::t('yii', 'Are you sure you want to delete this item?');
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @return string
     */
    public function run()
    {
        if (!$this->gridId) {
            throw new InvalidConfigException('Missing gridId param');
        }

        $this->setDefaultOptions();

        $this->view->registerJs($this->js());

        return $this->render('bulk-actions');
    }

    /**
     * Set default options
     */
    protected function setDefaultOptions()
    {
        if (!$this->actions) {
            $this->actions = [
                ['action'=>'bulk-activate', 'desc' => Yii::t('yee', 'Activate'), 'target'=>null],
				['action'=>'bulk-deactivate', 'desc' => Yii::t('yee', 'Deactivate'), 'target'=>null],
				['action'=>'bulk-delete', 'desc' => Yii::t('yee', 'Delete'), 'target'=>null],
            ];
        }
		
		if (!$this->targets) {
			$this->targets = [];
		}

        if (!$this->pjaxId) {
            $this->pjaxId = $this->gridId . '-pjax';
        }
    }

    /**
     * @return string
     */
    protected function js()
    {
		$prejs = '		
		var urlto_base = "'.Url::to(['']).'/";
		var actions = '.json_encode($this->actions).';
		var action_names = actions.map( (x) => x.action );		
		';
		
		$js = $prejs;
		
        $js .= <<<JS
		
		var urlto = function(action) { return urlto_base + action; } 
		
		// Select values in bulk actions list
		$(document).off('change', '[name="grid-bulk-actions"]').on('change', '[name="grid-bulk-actions"]', function () {
			var _t = $(this);
			var okButton = $(_t.data('ok-button'));

			if (_t.val()) {
				okButton.removeClass('disabled');
			}
			else {
				okButton.addClass('disabled');
			}
			
			$(".bulk-actions-target-select").hide();
			
			$("option:selected", $(this)).each(function() {
				var action = $( this ).val();
				var index = action_names.indexOf(action);
				var target = actions[index]['target'];
				if(target != null) {
					$("#target-select-"+action).slideDown();
				} else {
					$("#target-select-"+action).slideUp();
				}
				
				$('.grid-bulk-ok-button').data('selected-action', action);
			});	
		});

		// Clicking OK button
		$(document).off('click', '.grid-bulk-ok-button').on('click', '.grid-bulk-ok-button', function () {
			var _t = $(this);
			var list = $(_t.data('list'));
			var selected_action = _t.data('selected-action');

			if (list.val().indexOf('bulk-delete') >= 0) {
				if ( ! confirm('$this->confirmationText') )
					return false;
			}

			var ids = $(_t.data('grid')).yiiGridView("getSelectedRows"); // returns an array of pkeys, and #grid is your grid element id
			var target_select = $("#target-"+selected_action);
			var target = target_select.val();
			
			$.ajax({
				type: 'POST',
				url: urlto('bulk-action'),
				data: { action: list.val(), selection: ids, target:target },
				success: function() {
					_t.addClass('disabled');
					list.val('');
					$.pjax.reload({container: _t.data('pjax')});
				}
			});
		});
		
		// 
		jQuery("select.bulk-action").change(function() {
			var str = "";
			$("option:selected", $(this)).each(function() {
				var val = $( this ).val();
				if(val == "copy" || val == "move") {
					$("#bbs-select").slideDown();
				} else {
					$("#bbs-select").slideUp();
				}
			});	
		});
		
		jQuery("select.copymove-select").change(function() {
			var selected = $( "option:selected", $(this) ).val();
			$("input[name=copymove_bbsid]").val( selected );
		});
		
		jQuery("select[name=pagesize-select]").change(function() {
			var selected_pagesize = jQuery("select[name=pagesize-select]").val();
			$("input[name=pagesize]").val( selected_pagesize );
			$("form", $("#post-search")).submit();
		});
JS;

        return $js;

    }
} 

