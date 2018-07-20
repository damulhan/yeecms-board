<?php

namespace modules\board\controllers\admin;

use yeesoft\controllers\admin\BaseController;

/**
 * CategoryController implements the CRUD actions for yeesoft\post\models\Category model.
 */
class GroupController extends BaseController
{

    public $disabledActions = ['view', 'bulk-activate', 'bulk-deactivate'];

    public function init()
    {
        $this->modelClass = $this->module->groupModelClass;
        $this->modelSearchClass = $this->module->groupModelSearchClass;

        $this->indexView = $this->module->groupIndexView;
        #$this->viewView = $this->module->groupViewView;
        $this->createView = $this->module->groupCreateView;
        $this->updateView = $this->module->groupUpdateView;

        parent::init();
    }

    protected function getRedirectPage($action, $model = null)
    {
        switch ($action) {
            case 'update':
                return ['update', 'id' => $model->id];
                break;
            case 'create':
                return ['update', 'id' => $model->id];
                break;
            default:
                return parent::getRedirectPage($action, $model);
        }
    }

}
