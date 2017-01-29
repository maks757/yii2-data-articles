<?php

namespace maks757\articlesdata\entities;

use maks757\language\entities\Language;
use Yii;

/**
 * This is the model class for table "yii2_data_article_text_translation".
 *
 * @property integer $id
 * @property integer $article_text_id
 * @property integer $language_id
 * @property string $text
 *
 * @property Yii2DataArticleText $articleText
 * @property Language $language
 */
class Yii2DataArticleTextTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_article_text_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_text_id', 'language_id'], 'integer'],
            [['text'], 'string'],
            [['article_text_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii2DataArticleText::className(), 'targetAttribute' => ['article_text_id' => 'id']],
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
            'article_text_id' => 'Article Text ID',
            'language_id' => 'Language ID',
            'text' => 'Text',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getText()
    {
        return $this->hasOne(Yii2DataArticleText::className(), ['id' => 'article_text_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }
}
