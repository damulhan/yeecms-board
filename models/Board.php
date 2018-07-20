<?php

namespace modules\board\models;

use paulzi\nestedintervals\NestedIntervalsBehavior;
use yeesoft\behaviors\MultilingualBehavior;
use yeesoft\models\OwnerAccess;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yeesoft\db\ActiveRecord;
use yeesoft\models\User;
use modules\board\models\Group;

/**
 * This is the model class for table "post_category".
 *
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property integer $visible
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Board extends ActiveRecord implements OwnerAccess
{
	private $bbsvar_keys = ['perm_user_list', 'perm_user_view', 'perm_user_write', 'perm_user_down'];
	public $_bbsvar; 
	
	public $perm_user_list = 0;
	public $perm_user_view = 0;
	public $perm_user_write = 0;
	public $perm_user_down = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%board}}';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
		
		#$this->_extractBbsvar();
		
		##debug('init', $this);
		#debug('attributes ', $this->attributes);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'bid', 'skin', 'm_skin'], 'required'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'category'], 'string', 'max' => 255],
            [['bid'], 'string', 'max' => 30],
            [['skin', 'm_skin'], 'string', 'max' => 30],
            [['bbsvar'], 'string'],
			[['perm_user_list', 'perm_user_view', 'perm_user_write', 'perm_user_down'], 'integer'],
        ];
    }
	
/* 	public function safeAttributes() {
		return ['id', 'group_id', 'bid', 'name', 'category', 'skin', 'm_skin', 'addinfo', 'bbsvar'];
	}
*/

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            BlameableBehavior::className(),
            TimestampBehavior::className(),
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yee', 'ID'),
            'name' => Yii::t('yee', 'Name'),
            'created_by' => Yii::t('yee', 'Created By'),
            'updated_by' => Yii::t('yee', 'Updated By'),
            'created_at' => Yii::t('yee', 'Created'),
            'updated_at' => Yii::t('yee', 'Updated'),
        ];
    }

	public function load( $data, $formName = null ) 
	{
		$this->bbsvar = $this->_makeJson($this->bbsvar, $data['_bbsvar'], $this->bbsvar_keys);
		
		return parent::load($data, $formName);		
	}

    /**
     *
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        try {
            return parent::save($runValidation, $attributeNames);
        } catch (yii\base\Exception $exc) {
            \Yii::$app->session->setFlash('crudMessage', $exc->getMessage());
        }

    }
	
	private function _makeJson($origdata, $data, $keys_to_extract = []) {
		$json = json_decode($origdata) ?: [];
		foreach($keys_to_extract as $k) {
			if(isset($data[$k])) $json->$k = $data[$k];
		}
		#debug('json', $json);
		
		return json_encode($json, JSON_UNESCAPED_UNICODE);
	}
	
	// private function _makeBbsvar() {
		// $bbsvar = array_combine($this->bbsvar_keys, 
			// array_map(function($x) { return $this->{$x}; }, 
				// $this->bbsvar_keys)
		// );
		
		// return json_encode($bbsvar);
	// }
	
	// private function _retouchJsonStr($str) {
		// $str = stripslashes(json_encode($str));
		// $str = rtrim(ltrim($str, '"'), '"');
		// return $str; 
	// }
	
	// private function _extractBbsvar() {
		// $bbsvar = json_decode($this->bbsvar ?: "{}");
		// foreach($bbsvar as $k => $v) {
			// if(property_exists(self::class, $k))
				// $this->{$k} = $v;
		// }
	// }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getData()
    {
        return $this->hasMany(Data::className(), ['board_id' => 'id']);
    }

    /**
     * Return all categories.
     *
     * @param bool $asArray
     *
     * @return static[]
     */
    // public static function getCategories($skipCategories = [])
    // {
        // $result = [];
        // $categories = Category::findOne(1)->getDescendants()->joinWith('translations')->all();

        // foreach ($categories as $category) {
            // if (!in_array($category->id, $skipCategories)) {
                // $result[$category->id] = str_repeat('   ', ($category->depth - 1)) . $category->title;
            // }
        // }

        // return $result;
    // }


    public static function find()
    {
        return new BoardQuery(get_called_class());
    }
	
	public static function id2bid($id) {
		$board = self::findOne($id);
		return ($board != null) ? $board->bid : null;
	}
	
	public static function bid2id($bid) {
		$board = self::find()->where(['bid'=>$bid])->one();
		return ($board != null) ? $board->id : null;
	}
	
    /**
     *
     * @inheritdoc
     */
    public static function getFullAccessPermission()
    {
        return 'fullBoardAccess';
    }

    /**
     *
     * @inheritdoc
     */
    public static function getOwnerField()
    {
        return 'created_by';
    }

	public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

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

    public function getPublishedTime()
    {
        return Yii::$app->formatter->asTime(($this->isNewRecord) ? time() : $this->published_at);
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
	
}