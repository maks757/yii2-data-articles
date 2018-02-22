<?php

namespace maks757\articlesdata\entities;

use maks757\imagable\Imagable;
use maks757\multilang\behaviors\TranslationBehavior;
use Yii;
use yii\helpers\FileHelper;
use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "yii2_data_article_image".
 *
 * @property integer $id
 * @property string $image
 * @property string $position
 * @property integer $article_id
 *
 * @property Yii2DataArticle $article
 * @property Yii2DataArticleImageTranslation[] translations
 * @property Yii2DataArticleImageTranslation translation
 */
class Yii2DataArticleImage extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::className(),
                'translationClass' => Yii2DataArticleImageTranslation::className(),
                'relationColumn' => 'article_image_id'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_article_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image'], 'required'],
            [['article_id', 'position'], 'integer'],
            [['image'], 'string', 'max' => 100],
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
            'image' => 'Image',
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
        return $this->hasMany(Yii2DataArticleImageTranslation::className(), ['article_image_id' => 'id']);
    }

    public function getImage(){
        /**@var Imagable $imagine */
        $imagine = \Yii::$app->article;
        $imagePath = $imagine->getOriginal('images', $this->image);
        $aliasPath = FileHelper::normalizePath(Yii::getAlias('@frontend/web'));
        return str_replace($aliasPath,'',$imagePath);
    }
}
