<?php

namespace maks757\articlesdata\entities;

use maks757\multilang\behaviors\TranslationBehavior;
use Yii;
use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "yii2_data_article_text".
 *
 * @property integer $id
 * @property string $position
 * @property integer $article_id
 *
 * @property Yii2DataArticle $article
 * @property Yii2DataArticleTextTranslation[] $translations
 * @property Yii2DataArticleTextTranslation translation
 */
class Yii2DataArticleText extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::className(),
                'translationClass' => Yii2DataArticleTextTranslation::className(),
                'relationColumn' => 'article_text_id'
            ],
        ];
    }

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
        return $this->hasMany(Yii2DataArticleTextTranslation::className(), ['article_text_id' => 'id']);
    }
}
