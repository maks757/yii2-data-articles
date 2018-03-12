<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

/**
 * @var $this \yii\web\View
*/

namespace maks757\articlesdata\controllers;

use common\models\User;
use maks757\articlesdata\ArticleModule;
use maks757\articlesdata\components\ArticleHelper;
use maks757\articlesdata\components\interfaces\LanguageInterface;
use maks757\articlesdata\components\UploadImage;
use maks757\articlesdata\entities\language\Language;
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
        return $this->render('index', [
            'articles' => Yii2DataArticle::find()->orderBy(['date' => SORT_DESC])->all(),
            'languages' => Language::find()->all(),
        ]);
    }

    public function actionDelete($id)
    {
        Yii2DataArticle::findOne($id)->delete();
        return $this->redirect(\Yii::$app->getRequest()->getReferrer());
    }

    public function actionCreate($id = null, $languageId = null)
    {
        //Create
        $request = \Yii::$app->request;
        $model = new Yii2DataArticle();
        $model_translation = new Yii2DataArticleTranslation();
        $image_model = new UploadImage();

        if(empty($languageId))
            $languageId = Language::getDefault()->getPrimaryKey();

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

        return $this->render('create', [
            'model' => $model,
            'model_translation' => $model_translation,
            'image_model' => $image_model,
            'languages' => Language::find()->all(),
        ]);
    }

    public function actionChangePosition($id, $block, $type)
    {
        ArticleHelper::changeFieldPosition($id, $block, $type);
        return $this->redirect(Yii::$app->getRequest()->getReferrer());
    }

    public function actionUpload(){
        $callback = $_GET['CKEditorFuncNum'];

        $file_name = $_FILES['upload']['name'];
        $file_name_tmp = $_FILES['upload']['tmp_name'];

        $file_new_name = '/textEditor/';
        if(!is_dir(Yii::getAlias('@frontend/web').$file_new_name))
            mkdir(Yii::getAlias('@frontend/web').$file_new_name);
        $full_path = FileHelper::normalizePath(Yii::getAlias('@frontend/web').$file_new_name.$file_name);
        $http_path = $file_new_name.$file_name;

        if( move_uploaded_file($file_name_tmp, $full_path) )
            $message = 'Зображення успiшно завантажено.';
        else
            $message = 'Не вдалося завантажити зображення.';

        $this->view->registerJs("window.parent.CKEDITOR.tools.callFunction('$callback',  '$http_path', '$message');");
    }

}
