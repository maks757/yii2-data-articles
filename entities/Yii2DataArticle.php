<?php

namespace maks757\articlesdata\entities;

use Yii;

/**
 * This is the model class for table "yii2_data_article".
 *
 * @property integer $id
 * @property string $image
 * @property integer $date
 * @property integer $author
 *
 * @property Yii2DataArticleGallery[] $yii2DataArticleGalleries
 * @property Yii2DataArticleImage[] $yii2DataArticleImages
 * @property Yii2DataArticleText[] $yii2DataArticleTexts
 * @property Yii2DataArticleTranslation[] $yii2DataArticleTranslations
 */
class Yii2DataArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image'], 'required'],
            [['date', 'author'], 'integer'],
            [['image'], 'string', 'max' => 100],
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
            'date' => 'Date',
            'author' => 'Author',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleries()
    {
        return $this->hasMany(Yii2DataArticleGallery::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Yii2DataArticleImage::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTexts()
    {
        return $this->hasMany(Yii2DataArticleText::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Yii2DataArticleTranslation::className(), ['article_id' => 'id']);
    }
}
