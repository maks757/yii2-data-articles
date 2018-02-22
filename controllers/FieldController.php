<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

namespace maks757\articlesdata\controllers;

use maks757\articlesdata\ArticleModule;
use maks757\articlesdata\components\UploadImages;
use maks757\articlesdata\entities\language\Language;
use maks757\articlesdata\entities\Yii2DataArticle;
use maks757\articlesdata\entities\Yii2DataArticleGallery;
use maks757\articlesdata\entities\Yii2DataArticleGalleryTranslation;
use maks757\articlesdata\entities\Yii2DataArticleImage;
use maks757\articlesdata\entities\Yii2DataArticleImageTranslation;
use maks757\articlesdata\entities\Yii2DataArticleText;
use maks757\articlesdata\entities\Yii2DataArticleTextTranslation;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;

class FieldController extends Controller
{
    //<editor-fold desc="Text field">
    public function actionCreateText($id = null, $languageId = null, $article_id = null)
    {
        $request = \Yii::$app->request;
        $model = new Yii2DataArticleText();
        $model_translation = new Yii2DataArticleTextTranslation();

        if($model_data = Yii2DataArticleText::findOne($id)){
            $model = $model_data;
            if($translation = Yii2DataArticleTextTranslation::findOne(['article_text_id' => $model->id, 'language_id' => $languageId])){
                $model_translation = $translation;
            }
        }

        if($request->isPost){
            $model->load($request->post());
            $model->article_id = $article_id;
            $model->save();

            $model_translation->load($request->post());
            $model_translation->article_text_id = $model->id;
            $model_translation->language_id = $languageId;
            $model_translation->save();
            $redirect_url = Url::toRoute(['/articles/post/create', 'id' => $article_id, 'languageId' => $languageId]);
            return $this->redirect($redirect_url);
        }

        return $this->render('create_text', [
            'model' => $model,
            'model_translation' => $model_translation,
            'article_id' => $article_id,
            'languages' => Language::find()->all(),
            'language_id' => $languageId
        ]);
    }

    public function actionTextDelete($id)
    {
        Yii2DataArticleText::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>

    //<editor-fold desc="Image">
    public function actionCreateImage($id = null, $article_id = null, $languageId = null)
    {
        $request = \Yii::$app->request;
        $model = new Yii2DataArticleImage();
        $model_translation = new Yii2DataArticleImageTranslation();
        $model_image = new UploadImages();

        if($model_data = Yii2DataArticleImage::findOne($id)){
            $model = $model_data;
            if($model_translation_data = Yii2DataArticleImageTranslation::findOne(['article_image_id' => $model->id, 'language_id' => $languageId])){
                $model_translation = $model_translation_data;
            }
        }

        if($request->isPost){
            $model_image->imageFile = UploadedFile::getInstance($model_image, 'imageFile');

            $model->load($request->post());
            if($image = $model_image->upload())
                $model->image = $image;
            $model->article_id = $article_id;
            $model->save();

            $model_translation->load($request->post());
            $model_translation->article_image_id = $model->id;
            $model_translation->language_id = $languageId;
            $model_translation->save();

            return $this->redirect(Url::toRoute(['/articles/field/create-image', 'id' => $model->id, 'article_id' => $article_id, 'languageId' => $languageId]));
        }

        return $this->render('create_image', [
            'model' => $model,
            'model_translation' => $model_translation,
            'languages' => Language::find()->all(),
            'article_id' => $article_id,
            'model_image' => $model_image,
            'language_id' => $languageId,
        ]);
    }

    public function actionImageDelete($id)
    {
        Yii2DataArticleImage::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>

    //<editor-fold desc="Slider">
    public function actionCreateSlider($id = null, $article_id = null, $languageId = null)
    {
        $request = \Yii::$app->request;
        $model = new Yii2DataArticleGallery();
        $model_translation = new Yii2DataArticleGalleryTranslation();

        if($model_data = Yii2DataArticleGallery::findOne($id)){
            $model = $model_data;
            if($translation = Yii2DataArticleGalleryTranslation::findOne(['article_gallery_id' => $model->id, 'language_id' => $languageId])){
                $model_translation = $translation;
            }
        }

        if($request->isPost){
            $model->load($request->post());
            $model->article_id = $article_id;
            $model->save();

            $model_translation->load($request->post());
            $model_translation->article_gallery_id = $model->id;
            $model_translation->language_id = $languageId;
            $model_translation->save();
            $redirect_url = Url::toRoute(['/articles/field/create-slider', 'id' => $model->id, 'article_id' => $article_id, 'languageId' => $languageId]);
            return $this->redirect($redirect_url);
        }

        return $this->render('create_slider', [
            'model' => $model,
            'model_translation' => $model_translation,
            'article_id' => $article_id,
            'languages' => Language::find()->all(),
            'language_id' => $languageId
        ]);
    }

    public function actionSliderDelete($id)
    {
        Yii2DataArticleGallery::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>
}
