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
class Group extends ActiveRecord implements OwnerAccess
{

    public $parent_id;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%board_group}}';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->visible = 1;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'visible', 'parent_id'], 'integer'],
            [['slug', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            BlameableBehavior::className(),
            TimestampBehavior::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
            ],
            #'nestedInterval' => [
            #    'class' => NestedIntervalsBehavior::className(),
            #    'leftAttribute' => 'left_border',
            #    'rightAttribute' => 'right_border',
            #    'amountOptimize' => '25',
            #    'noPrepend' => true,
            #],
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
            'slug' => Yii::t('yee', 'Slug'),
            'title' => Yii::t('yee', 'Title'),
            'visible' => Yii::t('yee', 'Visible'),
            'description' => Yii::t('yee', 'Description'),
            'created_by' => Yii::t('yee', 'Created By'),
            'updated_by' => Yii::t('yee', 'Updated By'),
            'created_at' => Yii::t('yee', 'Created'),
            'updated_at' => Yii::t('yee', 'Updated'),
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        #$parent = null;

        #if (isset($this->parent_id) && $this->parent_id) {
        #    $parent = Group::findOne((int)$this->parent_id);
        #}

        #if (!$parent) {
        #    $parent = Group::findOne(1);
        #}

        #if (!$parent) {
        #    throw new \yii\base\InvalidParamException();
        #}

        #$this->appendTo($parent);

        try {
            return parent::save($runValidation, $attributeNames);
        } catch (yii\base\Exception $exc) {
            \Yii::$app->session->setFlash('crudMessage', $exc->getMessage());
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBoards()
    {
        return $this->hasMany(Board::className(), ['group_id' => 'id']);
    }

    /**
     * Return all categories.
     *
     * @param bool $asArray
     *
     * @return static[]
     */
/*     public static function getGroups($skipCategories = [])
    {
        $result = [];
        $categories = Group::findOne(1); #->getDescendants()->joinWith('translations')->all();

        foreach ($categories as $category) {
            if (!in_array($category->id, $skipCategories)) {
                $result[$category->id] = str_repeat('   ', ($category->depth - 1)) . $category->title;
            }
        }

        return $result;
    }
 */

    public static function find()
    {
        return new GroupQuery(get_called_class());

    }

    /**
     *
     * @inheritdoc
     */
    public static function getFullAccessPermission()
    {
        return 'fullBoardGroupAccess';
    }

    /**
     *
     * @inheritdoc
     */
    public static function getOwnerField()
    {
        return 'created_by';
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