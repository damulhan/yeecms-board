<?php

namespace modules\board\controllers\admin;

use Yii;
use yeesoft\helpers\YeeHelper;
use yeesoft\models\OwnerAccess;
use yii\helpers\StringHelper;

use yeesoft\controllers\admin\BaseController;
use yeesoft\models\User;
use modules\board\models\Group;
use modules\board\BoardModuleAsset;

/**
 * PostController implements the CRUD actions for Post model.
 */
class DefaultController extends BaseController
{

    public function init()
    {
        $this->modelClass = $this->module->boardModelClass;
        $this->modelSearchClass = $this->module->boardModelSearchClass;

        $this->indexView = $this->module->indexView;
        $this->viewView = $this->module->viewView;
        $this->createView = $this->module->createView;
        $this->updateView = $this->module->updateView;

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
		
        return $this->renderIsAjax($this->indexView, compact('dataProvider', 'searchModel'));
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

		$groups = Group::find()->all();
		
        return $this->renderIsAjax($this->createView, compact('model', 'groups'));
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
			$data = $model->getData()->all();
			foreach($data as $d) { 
				$d->bid = $model->bid; 
				$d->save(); 
			}
			
            Yii::$app->session->setFlash('crudMessage', Yii::t('yee', 'Your item has been updated.'));
            return $this->redirect($this->getRedirectPage('update', $model));
        }
		
		$groups = Group::find()->all();
		
		$model->_bbsvar = json_decode($model->bbsvar);
				
        return $this->renderIsAjax($this->updateView, compact('model', 'groups'));
    }

    protected function getRedirectPage($action, $model = null)
    {
        if (!User::hasPermission('editBoards') && $action == 'create') {
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

}
