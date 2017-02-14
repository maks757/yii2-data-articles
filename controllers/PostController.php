<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

namespace maks757\articlesdata\controllers;

use common\models\User;
use maks757\articlesdata\ArticleModule;
use maks757\articlesdata\components\UploadImage;
use maks757\articlesdata\entities\Yii2DataArticle;
use maks757\articlesdata\entities\Yii2DataArticleTranslation;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;

class PostController extends Controller
{
    public function actionIndex()
    {
        /** @var $module ArticleModule */
        $module = $this->module;
        $languages = \Yii::createObject($module->language_class);
        $language = \Yii::createObject($module->language_class);
        $languages = $languages::findAll($module->language_where);
        $language = $language::findOne($module->language_default);
        return $this->render('index', [
            'articles' => Yii2DataArticle::find()->orderBy(['date' => SORT_DESC])->all(),
            'languages' => $languages,
            'language' => $language,
            'language_field_name' => $module->language_field
        ]);
    }

    public function actionDelete($id)
    {
        Yii2DataArticle::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionCreate($id = null, $languageId = null, $type = null, $block = null, $block_id = null)
    {
        //Change field position
        Yii2DataArticle::fieldsPosition($block, $type, $block_id);
        //Create
        $request = \Yii::$app->request;
        $model = new Yii2DataArticle();
        $model_translation = new Yii2DataArticleTranslation();
        $image_model = new UploadImage();
        //Languages
        /** @var $module ArticleModule */
        $module = $this->module;
        $languages = \Yii::createObject($module->language_class);
        $languages = $languages::findAll($module->language_where);

        if(!empty($request->post('id')))
            $id = $request->post('id');

        if(empty($languageId))
            $languageId = (integer)$module->language_default;

        if($model_data = Yii2DataArticle::findOne($id)){
            $model = $model_data;
            if($model_translation_data = Yii2DataArticleTranslation::findOne(['article_id' => $model->id, 'language_id' => $languageId])){
                $model_translation = $model_translation_data;
            }
        }

        if(empty($model_translation->language_id))
            $model_translation->language_id = $languageId;

        if($request->isPost){
            $image_model->imageFile = UploadedFile::getInstance($image_model, 'imageFile');
            $image = $image_model->upload();

            $model->create($request->post(), $image);
            $model_translation->create($request->post(), $model->id);

            return $this->redirect(Url::toRoute(['/articles/post/create', 'id' => $model->id, 'languageId' => $languageId]));
        }

        $rows = $model->getField($languageId);

        return $this->render('create', [
            'model' => $model,
            'model_translation' => $model_translation,
            'image_model' => $image_model,
            'rows' => $rows,
            'users' => User::find()->all(),
            'languages' => $languages,
            'language_field_name' => $module->language_field
        ]);
    }

    public function actionUpload(){
        $callback = $_GET['CKEditorFuncNum'];

        $file_name = $_FILES['upload']['name'];
        $file_name_tmp = $_FILES['upload']['tmp_name'];

        $file_new_name = '/textEditor/';
        $full_path = FileHelper::normalizePath(Yii::getAlias('@frontend/web').$file_new_name.$file_name);
        $http_path = $file_new_name.$file_name;

        if( move_uploaded_file($file_name_tmp, $full_path) )
            $message = 'Зображення успiшно завантажено.';
        else
            $message = 'Не вдалося завантажити зображення.';

        echo "<script type='text/javascript'>// <![CDATA[
            window.parent.CKEDITOR.tools.callFunction('$callback',  '$http_path', '$message');
    // ]]></script>";
    }

}