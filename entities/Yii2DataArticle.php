<?php

namespace maks757\articlesdata\entities;

use dosamigos\transliterator\TransliteratorHelper;
use maks757\articlesdata\components\ArticleHelper;
use maks757\friendly\components\IUrlRules;
use maks757\imagable\Imagable;
use maks757\multilang\behaviors\TranslationBehavior;
use maks757\seo\behaviors\SeoDataBehavior;
use maks757\seo\entities\SeoData;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "yii2_data_article".
 *
 * @property integer $id
 * @property string $image
 * @property integer $date
 * @property integer $author
 * @property integer $position
 * @property string $seoUrl
 * @property string $seoTitle
 * @property string $seoDescription
 * @property string $seoKeywords
 *
 * @property Yii2DataArticleGallery[] galleries
 * @property Yii2DataArticleImage[] images
 * @property Yii2DataArticleText[] texts
 * @property Yii2DataArticleTranslation[] translations
 * @property Yii2DataArticleTranslation translation
 * @property SeoData seo
 */
class Yii2DataArticle extends \yii\db\ActiveRecord implements IUrlRules
{
    public function behaviors()
    {
        return [
            'seoData' => [
                'class' => SeoDataBehavior::className()
            ],
            'translation' => [
                'class' => TranslationBehavior::className(),
                'translationClass' => Yii2DataArticleTranslation::className(),
                'relationColumn' => 'article_id'
            ],
            'positionBehavior' => [
                'class' => PositionBehavior::className(),
                'positionAttribute' => 'position',
            ],
        ];
    }

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
            // seo data
            [['seoUrl', 'seoTitle', 'seoDescription', 'seoKeywords'], 'string']
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

    /**
     * @param string $type
     * @return mixed|string
     */
    public function getImage($type = 'origin'){
        /**@var Imagable $imagine */
        $imagine = \Yii::$app->article;
        $imagePath = $imagine->get('article', $type, $this->image);
        $aliasPath = FileHelper::normalizePath(Yii::getAlias('@frontend/web'));
        return str_replace($aliasPath,'',$imagePath);
    }

    public function getFields()
    {
        $rows = array_merge($this->texts, $this->images, $this->galleries);
        ArrayHelper::multisort($rows, 'position');
        return $rows;
    }

    public function create($post, $image)
    {
        if(!empty($post)){
            $this->load($post);
            $this->date = !empty($this->date) ? strtotime($this->date) : time();
            if(!empty($image))
                $this->image = $image;
            $this->save();
        }
    }

    public function getSeo()
    {
        return $this->hasOne(SeoData::className(), ['entity_id' => 'id'])
            ->andOnCondition(['entity_name' => self::className()]);
    }

    /**
     * @param mixed $key
     * @return integer model id
     */
    function fiendKey($key)
    {
        $model = self::find()->innerJoinWith(['seo' => function(Query $query) use($key) {
            $query->where(['seo_url' => $key]);
        }])->one();
        return empty($model) ? false : $model->id;
    }

    /**
     * @param integer $id
     * @return string
     */
    function seoUrl($id)
    {
        return self::find()->where(['id' => $id])->with(['seo'])->one()->seo->seo_url;
    }
}
