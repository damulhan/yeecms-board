<?php

namespace modules\board\controllers\admin;

use yeesoft\controllers\admin\BaseController;
use yeesoft\models\User;
use yeesoft\helpers\YeeHelper;
use yeesoft\models\OwnerAccess;
use yii\helpers\StringHelper;
use Yii;
use modules\board\models\Board;
use yii\helpers\BaseInflector;
use yii\helpers\ArrayHelper;
use modules\board\BoardModuleAsset;

/**
 * PostController implements the CRUD actions for Post model.
 */
class DataController extends BaseController
{

    public function init()
    {
        $this->modelClass = $this->module->dataModelClass;
        $this->modelSearchClass = $this->module->dataModelSearchClass;

        $this->indexView = $this->module->dataIndexView;
        $this->viewView = $this->module->dataViewView;
        $this->createView = $this->module->dataCreateView;
        $this->updateView = $this->module->dataUpdateView;

		BoardModuleAsset::register( $this->getView() );
		
        parent::init();
    }
	
    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndex()
    {
        $modelClass = $this->modelClass;
        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;
        $restrictAccess = (YeeHelper::isImplemented($modelClass, OwnerAccess::CLASSNAME)
            && !User::hasPermission($modelClass::getFullAccessPermission()));

        if ($searchModel) {
            $searchName = StringHelper::basename($searchModel::className());
            $params = Yii::$app->request->getQueryParams();

            if ($restrictAccess) {
                $params[$searchName][$modelClass::getOwnerField()] = Yii::$app->user->identity->id;
            }

            $dataProvider = $searchModel->search($params);
			
        } else {
            $restrictParams = ($restrictAccess) ? [$modelClass::getOwnerField() => Yii::$app->user->identity->id] : [];
            $dataProvider = new ActiveDataProvider(['query' => $modelClass::find()->where($restrictParams)]);
        }

		$boards = Board::find()->all();
		
        return $this->renderIsAjax($this->indexView, compact('dataProvider', 'searchModel', 'boards'));
    }
	
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /* @var $model \yeesoft\db\ActiveRecord */
        $model = new $this->modelClass;
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('crudMessage', Yii::t('yee', 'Your item has been created.'));
            return $this->redirect($this->getRedirectPage('create', $model));
        }

		$boards = Board::find()->all();
		
        return $this->renderIsAjax($this->createView, compact('model', 'boards'));
    }	
	
    /**
     * Updates an existing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /* @var $model \yeesoft\db\ActiveRecord */
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) AND $model->save()) {
            Yii::$app->session->setFlash('crudMessage', Yii::t('yee', 'Your item has been updated.'));
            return $this->redirect($this->getRedirectPage('update', $model));
        }

		$boards = Board::find()->all();

        return $this->renderIsAjax($this->updateView, compact('model', 'boards'));
    }
	

	public function actionBulkCopy()
    {
        if (Yii::$app->request->post('selection')) {
            $modelClass = $this->modelClass;
            $restrictAccess = (YeeHelper::isImplemented($modelClass, OwnerAccess::CLASSNAME)
                && !User::hasPermission($modelClass::getFullAccessPermission()));

			$target_board_id = Yii::$app->request->post('target', '');
			
            foreach (Yii::$app->request->post('selection', []) as $id) {
                $where = ['id' => $id];

                if ($restrictAccess) {
                    $where[$modelClass::getOwnerField()] = Yii::$app->user->identity->id;
                }

                $model = $modelClass::findOne($where);

                if ($model) 
					$modelClass::copyData($id, $target_board_id);
            }
        }
    }
	
	public function actionBulkMove()
    {
        if (Yii::$app->request->post('selection')) {
            $modelClass = $this->modelClass;
            $restrictAccess = (YeeHelper::isImplemented($modelClass, OwnerAccess::CLASSNAME)
                && !User::hasPermission($modelClass::getFullAccessPermission()));

			$target_board_id = Yii::$app->request->post('target', '');
			
            foreach (Yii::$app->request->post('selection', []) as $id) {
                $where = ['id' => $id];

                if ($restrictAccess) {
                    $where[$modelClass::getOwnerField()] = Yii::$app->user->identity->id;
                }

                $model = $modelClass::findOne($where);

                if ($model) 
					$modelClass::moveData($id, $target_board_id);
            }
        }
    }
	
	
    /**
     * Activate all selected grid items
     */
    public function actionBulkAction()
    {
		$action = Yii::$app->request->post('action');
		if($action) {
			$actionName = 'action'.BaseInflector::camelize($action);
			$this->{$actionName}();
		}
    }
	
    protected function getRedirectPage($action, $model = null)
    {
        if (!User::hasPermission('editBoardData') && $action == 'create') {
            return ['view', 'id' => $model->id];
        }

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

    public function beforeAction($action) {
		
		$this->_implementedActions = 
			ArrayHelper::merge($this->_implementedActions, [
				'actionBulkAction'
			]);
			
		return parent::beforeAction($action);
	}

}
