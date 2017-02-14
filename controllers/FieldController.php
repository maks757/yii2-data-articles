<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

namespace maks757\articlesdata\controllers;

use maks757\articlesdata\ArticleModule;
use maks757\articlesdata\components\UploadImages;
use maks757\articlesdata\entities\Yii2DataArticle;
use maks757\articlesdata\entities\Yii2DataArticleGallery;
use maks757\articlesdata\entities\Yii2DataArticleGalleryTranslation;
use maks757\articlesdata\entities\Yii2DataArticleImage;
use maks757\articlesdata\entities\Yii2DataArticleImageTranslation;
use maks757\articlesdata\entities\Yii2DataArticleText;
use maks757\articlesdata\entities\Yii2DataArticleTextTranslation;
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
        //Languages
        /** @var $module ArticleModule */
        $module = $this->module;
        $languages = \Yii::createObject($module->language_class);
        $languages = $languages::findAll($module->language_where);

        if(!empty($request->post('id')))
            $id = $request->post('id');

        if(!empty($request->post('article_id')))
            $article_id = $request->post('article_id');

        if($model_data = Yii2DataArticleText::findOne($id)){
            $model = $model_data;
            if($model_translation_data = Yii2DataArticleTextTranslation::findOne(['article_text_id' => $model->id, 'language_id' => $languageId])){
                $model_translation = $model_translation_data;
            }
        }

        if($request->isPost){
            $fields = Yii2DataArticle::findOne($article_id)->getField($languageId);

            $model->load($request->post());
            $model->article_id = $article_id;
            if(!is_integer($model->position))
                $model->position = ($fields[count($fields) - 1]['position'] + 1);
            $model->save();

            $model_translation->load($request->post());
            $model_translation->article_text_id = $model->id;
            $model_translation->language_id = $languageId;
            $model_translation->save();

            return $this->redirect(Url::toRoute(['/articles/post/create', 'id' => $article_id, 'languageId' => $languageId]));
        }

        return $this->render('create_text', [
            'model' => $model,
            'model_translation' => $model_translation,
            'article_id' => $article_id,
            'languages' => $languages,
            'language_id' => $languageId,
            'language_field_name' => $module->language_field
        ]);
    }

    public function actionTextPosition($id, $type)
    {
        $field = Yii2DataArticleText::findOne($id);
        switch ($type){
            case 'up':{
                if($field->position > 0)
                    $field->position = ($field->position - 1);
                break;
            }
            case 'down':{
                $field->position = ((integer)$field->position + 1);
                break;
            }
        }
        $field->save();

        return $this->redirect(\Yii::$app->request->referrer);
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
        //Languages
        /** @var $module ArticleModule */
        $module = $this->module;
        $languages = \Yii::createObject($module->language_class);
        $languages = $languages::findAll($module->language_where);
        //end
        $model_image = new UploadImages();


        if(!empty($request->post('id')))
            $id = $request->post('id');

        if($model_data = Yii2DataArticleImage::findOne($id)){
            $model = $model_data;
            if($model_translation_data = Yii2DataArticleImageTranslation::findOne(['article_image_id' => $model->id, 'language_id' => $languageId])){
                $model_translation = $model_translation_data;
            }
        }

        if($request->isPost){
            $fields = Yii2DataArticle::findOne($article_id)->getField($languageId);
            $model_image->imageFile = UploadedFile::getInstance($model_image, 'imageFile');

            $model->load($request->post());
            if($image = $model_image->upload())
                $model->image = $image;
            $model->article_id = $article_id;
            if(!is_integer($model->position))
                $model->position = $fields[count($fields) - 1]['position'] + 1;
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
            'languages' => $languages,
            'article_id' => $article_id,
            'model_image' => $model_image,
            'language_id' => $languageId,
            'language_field_name' => $module->language_field
        ]);
    }

    public function actionImagePosition($id, $type)
    {
        $field = Yii2DataArticleImage::findOne($id);
        switch ($type){
            case 'up':{
                if($field->position > 0)
                    $field->position = ($field->position - 1);
                break;
            }
            case 'down':{
                $field->position = ((integer)$field->position + 1);
                break;
            }
        }
        $field->save();

        return $this->redirect(\Yii::$app->request->referrer);
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
        //Languages
        /** @var $module ArticleModule */
        $module = $this->module;
        $languages = \Yii::createObject($module->language_class);
        $languages = $languages::findAll($module->language_where);
        //end

        if(!empty($request->post('id')))
            $id = $request->post('id');

        if(!empty($request->post('article_id')))
            $article_id = $request->post('article_id');

        if($model_data = Yii2DataArticleGallery::findOne($id)){
            $model = $model_data;
            if($model_translation_data = Yii2DataArticleGalleryTranslation::findOne(['article_gallery_id' => $model->id, 'language_id' => $languageId])){
                $model_translation = $model_translation_data;
            }
        }

        if($request->isPost){
            $fields = Yii2DataArticle::findOne($article_id)->getField($languageId);
            $model->load($request->post());
            $model->article_id = $article_id;
            if(!is_integer($model->position))
                $model->position = $fields[count($fields) - 1]['position'] + 1;
            $model->save();

            $model_translation->load($request->post());
            $model_translation->article_gallery_id = $model->id;
            $model_translation->language_id = $languageId;
            $model_translation->save();

            return $this->redirect(Url::toRoute(['/articles/field/create-slider', 'id' => $model->id, 'article_id' => $article_id, 'languageId' => $languageId]));
        }

        return $this->render('create_slider', [
            'model' => $model,
            'model_translation' => $model_translation,
            'article_id' => $article_id,
            'languages' => $languages,
            'language_id' => $languageId,
            'language_field_name' => $module->language_field
        ]);
    }

    public function actionSliderPosition($id, $type)
    {
        $field = Yii2DataArticleGallery::findOne($id);
        switch ($type){
            case 'up':{
                if($field->position > 0)
                    $field->position = ($field->position - 1);
                break;
            }
            case 'down':{
                $field->position = ((integer)$field->position + 1);
                break;
            }
        }
        $field->save();

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionSliderDelete($id)
    {
        Yii2DataArticleGallery::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>
}