<?php

namespace maks757\articlesdata\entities;

use Yii;

/**
 * This is the model class for table "yii2_data_article_image".
 *
 * @property integer $id
 * @property string $image
 * @property string $position
 * @property integer $article_id
 *
 * @property Yii2DataArticle $article
 * @property Yii2DataArticleImageTranslation[] $yii2DataArticleImageTranslations
 */
class Yii2DataArticleImage extends \yii\db\ActiveRecord
{
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
            [['image', 'position'], 'required'],
            [['article_id'], 'integer'],
            [['image', 'position'], 'string', 'max' => 100],
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
}
