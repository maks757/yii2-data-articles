<?php

namespace maks757\articlesdata\entities;

use maks757\articlesdata\components\interfaces\LanguageInterface;
use Yii;

/**
 * This is the model class for table "yii2_data_article_image_translation".
 *
 * @property integer $id
 * @property integer $article_image_id
 * @property integer $language_id
 * @property string $name
 * @property string $description
 *
 * @property Yii2DataArticleImage $articleImage
 * @property LanguageInterface $language
 */
class Yii2DataArticleImageTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_article_image_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $language = Yii::$container->get('language');
        return [
            [['article_image_id', 'language_id'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['article_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii2DataArticleImage::className(), 'targetAttribute' => ['article_image_id' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => $language::className(), 'targetAttribute' => ['language_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_image_id' => 'Article Image ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Yii2DataArticleImage::className(), ['id' => 'article_image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        $language = Yii::$container->get('language');
        return $this->hasOne($language::className(), ['id' => 'language_id']);
    }
}
