<?php

namespace modules\board\controllers;

use Yii;
use yeesoft\helpers\YeeHelper;
use yeesoft\models\OwnerAccess;
use yeesoft\helpers\AuthHelper;

use yii\helpers\StringHelper;
use yii\data\ActiveDataProvider;

use yeesoft\controllers\admin\BaseController;
#use frontend\controllers\BaseController;
use yeesoft\models\User;

use modules\board\models\Board;
use modules\board\models\Group;
use modules\board\models\Data;
use yii\web\NotFoundHttpException;

#use yeesoft\behaviors\AccessFilter;
use frontend\behaviors\AccessFilter;
use modules\board\BoardModuleAsset;

/**
 * PostController implements the CRUD actions for Post model.
 */
class DefaultController extends BaseController
{

    public $modelClass;
    public $modelSearchClass;
    public $layout = '@frontend/views/layouts/main.php';

    public $skin = 'default';
    public $mobile_skin = 'm_default';
	
	public function behaviors()
    {
        return [
            'access-filter' => [
                'class' => AccessFilter::className(),
            ],
        ];
    }

    public function init()
    {
        $this->modelClass = $this->module->dataModelClass;
        $this->modelSearchClass = $this->module->dataModelSearchClass;

		$this->skin = 'default';
		
		$user = Yii::$app->user->identity; 
		
		BoardModuleAsset::register( $this->getView() );
		
        parent::init();
    }
	
    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndex()
    {
        $modelClass = $this->module->dataModelClass; 
        $modelSearchClass = $this->module->dataModelSearchClass;
			
		$id = Yii::$app->request->get('id');
		$bid = Yii::$app->request->get('bid');
		
		$board_model = Board::find()->where(['bid' => $bid])->one();
        if (!$board_model) throw new NotFoundHttpException('The requested board does not exist.');
		
		$orderby = 'desc'; 
		$query = Data::find()->where(['bid' => $bid])->orderBy('id', $orderby);
		
        $searchModel = new $modelSearchClass;
        #$restrictAccess = (YeeHelper::isImplemented($modelClass, OwnerAccess::CLASSNAME)
            #&& !User::hasPermission($modelClass::getFullAccessPermission()));

        if ($searchModel) {
            $searchName = StringHelper::basename($searchModel::className());
            $params = Yii::$app->request->getQueryParams();

            #if ($restrictAccess) {
            #    $params[$searchName][$modelClass::getOwnerField()] = Yii::$app->user->identity->id;
            #}

            $dataProvider = $searchModel->search($params);
			
        } else {
            $restrictParams = ($restrictAccess) ? [$modelClass::getOwnerField() => Yii::$app->user->identity->id] : [];
            $dataProvider = new ActiveDataProvider(['query' => $modelClass::find()->where($restrictParams)]);
        }
		
		$urls = $this->_getUrls($bid);
		
        return $this->renderIsAjax('../skin/'.$this->skin.'/list', 
				compact('dataProvider', 'searchModel', 'urls')
			);
    }
	
    public function actionView($id)
    {
		$model = $this->findModel($id);
		if (!$model) throw new NotFoundHttpException('The requested article does not exist.');
		
		$bid = $model->bid; 
		
		$board_model = Board::find()->where(['bid' => $bid])->one();
        if (!$board_model) throw new NotFoundHttpException('The requested board does not exist.');
		
		$orderby = 'desc'; 
		$query = Data::find()->where(['bid' => $bid])->orderBy('id', $orderby);
		
		$restrictParams = ($restrictAccess) ? [Data::getOwnerField() => Yii::$app->user->identity->id] : [];
		$dataProvider = new ActiveDataProvider(['query' => Data::find()->where($restrictParams)]);
		
		$urls = $this->_getUrls($bid);
				
        return $this->renderIsAjax('../skin/'.$this->skin.'/view', [
            'model' => $model,
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
			'urls' => $urls,
        ]);
    }
	

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$bid = Yii::$app->request->get('bid');
        if (!$bid) throw new NotFoundHttpException('bid does not exist.');
		
		$bbslist_model = Board::find()->where(['bid' => $bid])->one();
        if (!$bbslist_model) throw new NotFoundHttpException('The requested board does not exist.');
		
        /* @var $model \yeesoft\db\ActiveRecord */
        $model = new $this->modelClass;
		
		$model->bid = $bid; 
		$model->board_id = $bbslist_model->id; 
		 
		$user = Yii::$app->user->identity; 
		
		$model->name = $user->username;
		$model->nic = $user->username;
		$model->pw = '';
		
		$model->depth = 0; 
		$model->html = 'HTML';
		
		$model->display = 1;
		$model->hidden = 0;
		#$model->notice = 0;
		$model->ip = $_SERVER['REMOTE_ADDR'];
		$model->agent = $_SERVER['HTTP_USER_AGENT'];
		
		if (!Yii::$app->user->id) {
			return Yii::$app->getResponse()->redirect(['site/login'])->send();
		}
		
		$urls = $this->_getUrls($bid);
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('crudMessage', Yii::t('yee', 'Your item has been created.'));
            return $this->redirect($urls['list']);
        }
		
        return $this->renderIsAjax('../skin/'.$this->skin.'/create', [
            'model' => $model,
			'urls' => $urls,
        ]);		
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
		if (!$model) throw new NotFoundHttpException('The requested article does not exist.');
		
		$bid = $model->bid; 

		$urls = $this->_getUrls($bid);
		
        if ($model->load(Yii::$app->request->post()) AND $model->save()) {
            Yii::$app->session->setFlash('crudMessage', Yii::t('yee', 'Your item has been updated.'));
            return $this->redirect($urls['list']);
        }
		
		$model->_bbsvar = json_decode($model->bbsvar);
		
        return $this->renderIsAjax('../skin/'.$this->skin.'/update', [
            'model' => $model,
			'urls' => $urls,
        ]);
		
    }

	public function actionDelete($id)
    {
		$model = $this->findModel($id);
		$bid = $model->bid;
		$urls = $this->_getUrls($bid);
		
		#debug('sss', Yii::$app->user->can('administrator'));
		
        if (Yii::$app->user->id == $model->created_by || Yii::$app->user->can('administrator')) {
            $model->delete();
            #if ($model->parent_id) {
			return $this->redirect($urls['list']);
            #}
        }

		return $this->redirect($urls['list']);
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
	
	public function _getUrls($bid) {
		$mid = \Yii::$app->request->get('mid');
		$id = \Yii::$app->request->get('id');
		 
		return [
			'list' => Yii::$app->urlManager->createUrl(['/board/', 'bid'=> $bid, 'mid'=>$mid ]),
			'view' => Yii::$app->urlManager->createUrl(['/board/view', 'id' => $data_id, 'mid'=>$mid]),
			'create' => Yii::$app->urlManager->createUrl(['/board/create', 'bid'=>$bid, 'mid'=>$mid]),
			'update' => Yii::$app->urlManager->createUrl(['/board/update', 'id'=>$id, 'mid'=>$mid]),
		];
	}

}
