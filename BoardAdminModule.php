<?php

/**
 * @link http://www.yee-soft.com/
 * @copyright Copyright (c) 2015 Taras Makitra
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace modules\board;

use Yii;

/**
 * Post Module For Yee CMS
 *
 * @author Taras Makitra <makitrataras@gmail.com>
 */
class BoardAdminModule extends \yii\base\Module
{

    /**
     * Version number of the module.
     */
    const VERSION = '0.1.0';

    public $controllerNamespace = 'modules\board\controllers\admin';
    public $viewList;
    public $layoutList;

    /**
     * Post model class
     *
     * @var string
     */
    public $boardModelClass = 'modules\board\models\Board';

    /**
     * Post search model class
     *
     * @var string
     */
    public $boardModelSearchClass = 'modules\board\models\search\BoardSearch';

    /**
     * Index page view
     *
     * @var string
     */
    public $indexView = 'index';

    /**
     * View page view
     *
     * @var string
     */
    public $viewView = 'view';

    /**
     * Create page view
     *
     * @var string
     */
    public $createView = 'create';

    /**
     * Update page view
     *
     * @var string
     */
    public $updateView = 'update';

    /**
     * Data model class
     *
     * @var string
     */
    public $dataModelClass = 'modules\board\models\Data';

    /**
     * Data search model class
     *
     * @var string
     */
    public $dataModelSearchClass = 'modules\board\models\search\DataSearch';

    /**
     * Index data view
     *
     * @var string
     */
    public $dataIndexView = 'index';

    /**
     * View data view
     *
     * @var string
     */
    public $dataViewView = 'view';

    /**
     * Create data view
     *
     * @var string
     */
    public $dataCreateView = 'create';

    /**
     * Update data view
     *
     * @var string
     */
    public $dataUpdateView = 'update';

    /**
     * Group model class
     *
     * @var string
     */
    public $groupModelClass = 'modules\board\models\Group';

    /**
     * Group search model class
     *
     * @var string
     */
    public $groupModelSearchClass = 'modules\board\models\search\GroupSearch';

    /**
     * Index group view
     *
     * @var string
     */
    public $groupIndexView = 'index';

    /**
     * View group view
     *
     * @var string
     */
    public $groupViewView = 'view';

    /**
     * Create group view
     *
     * @var string
     */
    public $groupCreateView = 'create';

    /**
     * Update group view
     *
     * @var string
     */
    public $groupUpdateView = 'update';

    /**
     * Size of thumbnail image of the post.
     *
     * Expected values: 'original' or sizes from yeesoft\media\MediaModule::$thumbs,
     * by default there are: 'small', 'medium', 'large'
     *
     * @var string
     */
    public $thumbnailSize = 'medium';
	
	public $skinList;
	
	public $mSkinList;

    /**
     * Default views and layouts
     * Add more views and layouts in your main config file by calling the module
     *
     *   Example:
     *
     *   'post' => [
     *       'class' => 'yeesoft\post\PostModule',
     *       'viewList' => [
     *           'post' => 'View Label 1',
     *           'post_test' => 'View Label 2',
     *       ],
     *       'layoutList' => [
     *           'main' => 'Layout Label 1',
     *           'dark_layout' => 'Layout Label 2',
     *       ],
     *   ],
     */
    public function init()
    {
        if (in_array($this->thumbnailSize, [])) {
            $this->thumbnailSize = 'medium';
        }

        if (empty($this->viewList)) {
            $this->viewList = [
                'post' => Yii::t('yee', 'Post view')
            ];
        }

        if (empty($this->layoutList)) {
            $this->layoutList = [
                'main' => Yii::t('yee', 'Main layout')
            ];
        }
		
		$this->skinList = [
			'default' => 'default',
		];
		
		$this->mSkinList = [
			'm_default' => 'm_default',
		];

        parent::init();
    }
	
	public function getViewPath() {
		return "@modules/board/views/admin/";
	}

}
