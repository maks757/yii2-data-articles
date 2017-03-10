<?php

namespace maks757\articlesdata\entities;

use Yii;

/**
 * This is the model class for table "yii2_data_article_text".
 *
 * @property integer $id
 * @property string $position
 * @property integer $article_id
 *
 * @property Yii2DataArticle $article
 * @property array|Yii2DataArticleTextTranslation|null|\yii\db\ActiveRecord $translation
 * @property Yii2DataArticleTextTranslation[] $translations
 */
class Yii2DataArticleText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_article_text';
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
     * @return array|Yii2DataArticleTextTranslation|null|\yii\db\ActiveRecord
     */
    public function getTranslation($language_id = null)
    {
        $current = Yii2DataArticleTextTranslation::find()->where(['article_text_id' => $this->id, 'language_id' => (!empty($language_id) ? $language_id : Language::getCurrent()->id)])->one();
        if(empty($current)){
            $current = Yii2DataArticleTextTranslation::find()->where(['article_text_id' => $this->id])->one();
        }
        return $current;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Yii2DataArticleTextTranslation::className(), ['article_text_id' => 'id']);
    }
}
