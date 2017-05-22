<?php

namespace maks757\articlesdata\entities;

use maks757\imagable\Imagable;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

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
 * @property Yii2DataArticleTranslation[] $translations
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

    /**
     * @return Yii2DataArticleTranslation
     */
    public function getTranslation()
    {
        return Yii2DataArticleTranslation::findOne(['article_id' => $this->id, 'language_id' => Yii::$container->get('language')->getDefault()->getPrimaryKey()]);
    }

    public function getEndPositionFromFields(){
        $texts = Yii2DataArticleText::find()->select('position')->where(['article_id' => $this->id])->all();
        $images = Yii2DataArticleImage::find()->select('position')->where(['article_id' => $this->id])->all();
        $galleries = Yii2DataArticleGallery::find()->select('position')->where(['article_id' => $this->id])->all();
        $rows = array_merge($texts, $images, $galleries);
        ArrayHelper::multisort($rows, 'position', SORT_DESC);
        return !empty($rows) ? $rows[0]->position : 0;
    }

    /**
     * @return mixed|string
     */
    public function getImage(){
        if(!empty($this->image)) {
            /**@var Imagable $imagine */
            $imagine = \Yii::$app->article;
            $imagePath = $imagine->getOriginal('article', $this->image);
            $aliasPath = FileHelper::normalizePath(Yii::getAlias('@frontend/web'));
            return str_replace($aliasPath, '', $imagePath);
        } else {
            return '';
        }
    }

    public function getField($language_id)
    {
        $rows = [];
        foreach (Yii2DataArticleText::findAll(['article_id' => $this->id]) as $text){
            $rows[] = ($text->toArray() + $text->getTranslation($language_id)->toArray() + ['key' => 'text']);
        }
        foreach (Yii2DataArticleImage::findAll(['article_id' => $this->id]) as $image){
            $image->image = $image->getImage();
            $rows[] = ($image->toArray() + ['key' => 'image']);
        }
        foreach (Yii2DataArticleGallery::findAll(['article_id' => $this->id]) as $gallery){
            $rows[] = ($gallery->toArray() + ['images' => $gallery->getImages()] + ['key' => 'slider']);
        }
        ArrayHelper::multisort($rows, 'position');
        return $rows;
    }

    /**
     * @return array|null|\yii\db\ActiveRecord|Yii2DataArticle
     */
    public function getNext() {
        $next = $this->find()->where(['>', 'id', $this->id])->one();
        return $next;
    }

    /**
     * @return array|null|\yii\db\ActiveRecord|Yii2DataArticle
     */
    public function getPrev() {
        $prev = $this->find()->where(['<', 'id', $this->id])->orderBy('id desc')->one();
        return $prev;
    }

    /**
     * @return array|null|\yii\db\ActiveRecord|Yii2DataArticle
     */
    public function getFirst() {
        $prev = $this->find()->orderBy(['id' => SORT_ASC])->one();
        return $prev;
    }

    public static function fieldsPosition($block, $type, $block_id){
        if(!empty($block) && !empty($type) && !empty($block_id)) {
            switch ($block) {
                case 'image': {
                    $field = Yii2DataArticleImage::findOne($block_id);
                    break;
                }
                case 'slider': {
                    $field = Yii2DataArticleGallery::findOne($block_id);
                    break;
                }
                case 'text': {
                    $field = Yii2DataArticleText::findOne($block_id);
                    break;
                }
            }

            /* @var $field  Yii2DataArticleImage|Yii2DataArticleGallery|Yii2DataArticleText */
            switch ($type) {
                case 'up': {
                    if ($field->position > 0)
                        $field->position = (integer)$field->position - 1;
                    break;
                }
                case 'down': {
                    $field->position = (integer)$field->position + 1;
                    break;
                }
            }
            if(!empty($field))
                $field->save();
        }
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
}
