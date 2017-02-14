<?php

namespace maks757\articlesdata\entities;

use bl\imagable\Imagable;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "yii2_data_article_gallery".
 *
 * @property integer $id
 * @property string $position
 * @property integer $article_id
 *
 * @property Yii2DataArticle $article
 * @property Yii2DataArticleGalleryTranslation[] $yii2DataArticleGalleryTranslations
 */
class Yii2DataArticleGallery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_article_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position'], 'required'],
            [['article_id'], 'integer'],
            [['position'], 'string', 'max' => 100],
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
        return $this->hasMany(Yii2DataArticleGalleryTranslation::className(), ['article_gallery_id' => 'id']);
    }

    public function getImages(){
        $images = [];
        $galleries = Gallery::findAll(['key' => md5(self::className()), 'object_id' => $this->id]);
        ArrayHelper::multisort($galleries, 'position');
        foreach ($galleries as $gallery){
            /**@var Imagable $imagine */
            $imagine = \Yii::$app->gallery;
            $imagePath = $imagine->get('gallery', 'origin', $gallery->image);
            $aliasPath = FileHelper::normalizePath(Yii::getAlias('@frontend/web'));
            $images[] = [
                'image' => str_replace('\\', '/', str_replace($aliasPath,'',$imagePath)),
                'name' => $gallery->title
            ];
        }
        return $images;
    }
}
