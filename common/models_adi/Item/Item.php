<?php

namespace common\models\Item;

use Yii;
use common\models\ItemCategory\ItemCategory;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "item".
 *
 * @property integer $id
 * @property string $name
 * @property integer $price
 * @property integer $category_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property ItemCategory $category
 * @property OrderItem[] $orderItems
 */
class Item extends \yii\db\ActiveRecord
{
    public $file1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price'], 'required'],
            [['price', 'category_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['file1'], 'file', 'extensions' => 'gif, jpg, png'],
            [['photo'], 'safe'],
         ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'category_id' => 'Category ID',
            'photo' => 'Photo',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ItemCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['item_id' => 'id']);
    }

    //Bahviors
    public function behaviors(){
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public function beforeSave($insert){
        if(parent::beforeSave($insert)){
            if (Yii::$app->request->isPost){
                $this->file1 = UploadedFile::getInstance($this, 'file1');
                if ($this->file1 && $this->validate()) {
                    $this->file1->saveAs('uploads/'.$this->file1->baseName . '.' .$this->file1->extension);
                    $this->photo = 'uploads/'.$this->file1->baseName . '.' .$this->file1->extension;
                    return true;
                }
            }
        }else{
            return false;
        }
    }

    public function getImageurl(){
        return \Yii::$app->request->BaseUrl.$this->file1;
    }
}
