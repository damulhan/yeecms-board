<?php

namespace modules\board\models;

use yeesoft\behaviors\MultilingualBehavior;
use yeesoft\models\OwnerAccess;
use yeesoft\models\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yeesoft\db\ActiveRecord;
use yii\helpers\Html;

use modules\board\models\Board;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property string $slug
 * @property string $view
 * @property string $layout
 * @property integer $category_id
 * @property integer $status
 * @property integer $comment_status
 * @property string $thumbnail
 * @property integer $published_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $revision
 *
 * @property PostCategory $category
 * @property User $createdBy
 * @property User $updatedBy
 * @property PostLang[] $postLangs
 * @property Tag[] $tags
 */
class Data extends ActiveRecord implements OwnerAccess
{

    const DISPLAY_HIDDEN = 0;
    const DISPLAY_DISPLAY = 1;
	const HIDDEN_NOT_HIDDEN = 0;
	const HIDDEN_HIDDEN = 1;
    const COMMENT_STATUS_CLOSED = 0;
    const COMMENT_STATUS_OPEN = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%board_data}}';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        #$this->on(self::EVENT_BEFORE_UPDATE, [$this, 'updateRevision']);
        #$this->on(self::EVENT_AFTER_UPDATE, [$this, 'saveTags']);
        #$this->on(self::EVENT_AFTER_INSERT, [$this, 'saveTags']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'board_id'], 'required'],
            [['depth', 'display', 'hidden', 'created_by', 'updated_by'], 'integer'],
            [['title', 'content', 'category', 'name', 'nic', 'pw', 'html', 'tag', 'addinfo', 'adddata'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yee', 'ID'),
            'created_by' => Yii::t('yee', 'Author'),
            'updated_by' => Yii::t('yee', 'Updated By'),
            'view' => Yii::t('yee', 'View'),
            'layout' => Yii::t('yee', 'Layout'),
            'title' => Yii::t('yee', 'Title'),
            'status' => Yii::t('yee', 'Status'),
            'comment_status' => Yii::t('yee', 'Comment Status'),
            'content' => Yii::t('yee', 'Content'),
            'category_id' => Yii::t('yee', 'Category'),
            'thumbnail' => Yii::t('yee/post', 'Thumbnail'),
            'created_at' => Yii::t('yee', 'Created'),
            'updated_at' => Yii::t('yee', 'Updated'),
            'revision' => Yii::t('yee', 'Revision'),
            'tagValues' => Yii::t('yee', 'Tags'),
        ];
    }

    /**
     * @inheritdoc
     * @return PostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DataQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBoard()
    {
        return $this->hasOne(Board::className(), ['id' => 'board_id']);
    }
	
	public static function copyData($id, $target_board_id) {
		$model = self::findOne(['id' => $id]);
		$model->id = null;
		$model->board_id = $target_board_id; 
		$model->bid = Board::id2bid($target_board_id);
		$model->isNewRecord = true;
		
		## upload file dupplicate 
/* 		$upload = getArrayString( $model->upload );
		$upload_arr = $upload['data'];
		$upload_arr1 = [];
		foreach($upload_arr as $upload_id) {
			$upload_id1 = BbsUpload::copyUpload(['id' => $upload_id]);
			if(!$upload_id1) debug('upload_id1 is null, copyUpload failed', $upload); 			
			array_push($upload_arr1, $upload_id1);
		}
		
		$model->upload = makeArrayString($upload_arr1);
 */		
 
		# save 
		$model->save();
	}
	
	public static function moveData($id, $target_board_id) {
		$model = self::findOne(['id' => $id]);
		$model->board_id = $target_board_id; 
		$model->bid = Board::board_id2bid($target_board_id);
		$model->save();
	}
	
    public function getUrl()
    {
        //return ['/board/view', 'id' => $this->id];
        return '/board/view/'.$this->id;
    }	
	
    /**
     * @return string 
     */	
	// public function getCategory()
    // {
        // return $this->category; 
    // }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getCreatedDate()
    {
        return Yii::$app->formatter->asDate(($this->isNewRecord) ? time() : $this->created_at);
    }

    public function getUpdatedDate()
    {
        return Yii::$app->formatter->asDate(($this->isNewRecord) ? time() : $this->updated_at);
    }

    public function getCreatedTime()
    {
        return Yii::$app->formatter->asTime(($this->isNewRecord) ? time() : $this->created_at);
    }

    public function getUpdatedTime()
    {
        return Yii::$app->formatter->asTime(($this->isNewRecord) ? time() : $this->updated_at);
    }

    public function getCreatedDatetime()
    {
        return "{$this->createdDate} {$this->createdTime}";
    }

    public function getUpdatedDatetime()
    {
        return "{$this->updatedDate} {$this->updatedTime}";
    }

    public function getStatusText()
    {
        return $this->getStatusList()[$this->status];
    }

    public function getCommentStatusText()
    {
        return $this->getCommentStatusList()[$this->comment_status];
    }

    public function getRevision()
    {
        return ($this->isNewRecord) ? 1 : $this->revision;
    }

    public function updateRevision()
    {
        $this->updateCounters(['revision' => 1]);
    }

    public function getShortContent($delimiter = '<!-- pagebreak -->', $allowableTags = '<a>')
    {
        $content = explode($delimiter, $this->content);
        return strip_tags($content[0], $allowableTags);
    }

    public function getThumbnail($options = ['class' => 'thumbnail pull-left', 'style' => 'width: 240px'])
    {
        if (!empty($this->thumbnail)) {
            return Html::img($this->thumbnail, $options);
        }

        return;
    }

    /**
     * getTypeList
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_PENDING => Yii::t('yee', 'Pending'),
            self::STATUS_PUBLISHED => Yii::t('yee', 'Published'),
        ];
    }

    /**
     * getCommentStatusList
     * @return array
     */
    public static function getCommentStatusList()
    {
        return [
            self::COMMENT_STATUS_OPEN => Yii::t('yee', 'Open'),
            self::COMMENT_STATUS_CLOSED => Yii::t('yee', 'Closed')
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public static function getFullAccessPermission()
    {
        return 'fullBoardDataAccess';
    }

    /**
     *
     * @inheritdoc
     */
    public static function getOwnerField()
    {
        return 'created_by';
    }

	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			// Place your custom code here
			
			if(!$this->depth) $this->depth = 0;
			if(!$this->display) $this->display = 1;
			if(!$this->hidden) $this->hidden = 0;

			return true;
		} else {
			return false;
		}
	}

}
