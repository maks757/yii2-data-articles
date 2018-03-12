<?php

namespace maks757\articlesdata\entities;

use maks757\egallery\entities\Gallery;
use maks757\imagable\Imagable;
use maks757\multilang\behaviors\TranslationBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "yii2_data_article_gallery".
 *
 * @property integer $id
 * @property string $position
 * @property integer $article_id
 *
 * @property Yii2DataArticle $article
 * @property Yii2DataArticleGalleryTranslation[] $translations
 * @property Yii2DataArticleGalleryTranslation translation
 * @property Gallery[] images
 */
class Yii2DataArticleGallery extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::className(),
                'translationClass' => Yii2DataArticleGalleryTranslation::className(),
                'relationColumn' => 'article_gallery_id'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_article_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position'], 'required'],
            [['article_id', 'position'], 'integer'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii2DataArticle::className(), 'targetAttribute' => ['article_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position' => 'Position',
            'article_id' => 'Article ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Yii2DataArticle::className(), ['id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Yii2DataArticleGalleryTranslation::className(), ['article_gallery_id' => 'id']);
    }

    public function getImages(){
        return $this->hasMany(Gallery::className(), ['object_id' => 'id'])->andOnCondition(['key' => md5(Yii2DataArticleGallery::className())]);
    }
}
