<?php

namespace maks757\articlesdata\entities;

use maks757\language\entities\Language;
use Yii;

/**
 * This is the model class for table "yii2_data_article_gallery_translation".
 *
 * @property integer $id
 * @property integer $article_gallery_id
 * @property integer $language_id
 * @property string $name
 * @property string $description
 *
 * @property Yii2DataArticleGallery $articleGallery
 * @property Language $language
 */
class Yii2DataArticleGalleryTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_article_gallery_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_gallery_id', 'language_id'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['article_gallery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii2DataArticleGallery::className(), 'targetAttribute' => ['article_gallery_id' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_gallery_id' => 'Article Gallery ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasOne(Yii2DataArticleGallery::className(), ['id' => 'article_gallery_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }
}
