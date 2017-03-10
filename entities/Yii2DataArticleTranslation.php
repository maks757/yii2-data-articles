<?php

namespace maks757\articlesdata\entities;

use maks757\articlesdata\components\interfaces\LanguageInterface;
use maks757\articlesdata\entities\language\Language;
use Yii;

/**
 * This is the model class for table "yii2_data_article_translation".
 *
 * @property integer $id
 * @property integer $article_id
 * @property integer $language_id
 * @property string $name
 * @property string $description
 *
 * @property Yii2DataArticle $article
 * @property LanguageInterface $language
 */
class Yii2DataArticleTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_article_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $language = Yii::$container->get('language');
        return [
            [['article_id', 'language_id'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii2DataArticle::className(), 'targetAttribute' => ['article_id' => 'id']],
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
            'article_id' => 'Article ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
            'description' => 'Description',
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
    public function getLanguage()
    {
        $language = Yii::$container->get('language');
        return $this->hasOne($language::className(), ['id' => 'language_id']);
    }

    public function create($post, $id)
    {
        if(!empty($post) && !empty($id)){
            $this->load($post);
            $this->article_id = $id;
            $this->save();
        }
    }
}
