<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

namespace maks757\articlesdata\controllers;

use common\modules\article\components\UploadImage;
use common\modules\article\entities\AmtimeArticle;
use common\modules\article\entities\AmtimeArticleBlock;
use common\modules\article\entities\AmtimeArticleGallery;
use common\modules\article\entities\AmtimeArticleImage;
use common\modules\article\entities\AmtimeArticleText;
use common\modules\autchor\entities\Autchor;
use common\modules\tags\entities\Tags;
use common\modules\tags\entities\TagsAssociative;
use Yii;
use yii\helpers\BaseFileHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;

class PostController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', [
            'articles' => AmtimeArticle::find()->orderBy(['date' => SORT_DESC])->all()
        ]);
    }

    public function actionDelete($id)
    {
        AmtimeArticle::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionCreate($id = null, $type = null, $block = null, $block_id = null)
    {
        //Position
        if(!empty($block) && !empty($type) && !empty($block_id)) {
            switch ($block) {
                case 'image': {
                    $field = AmtimeArticleImage::findOne($block_id);
                    break;
                }
                case 'slider': {
                    $field = AmtimeArticleGallery::findOne($block_id);
                    break;
                }
                case 'block': {
                    $field = AmtimeArticleBlock::findOne($block_id);
                    break;
                }
                case 'text': {
                    $field = AmtimeArticleText::findOne($block_id);
                    break;
                }
            }

            switch ($type) {
                case 'up': {
                    if ($field->position > 0)
                        $field->position = ($field->position - 1);
                    break;
                }
                case 'down': {
                    $field->position = ($field->position + 1);
                    break;
                }
            }
            if(!empty($field))
                $field->save();
        }

        $request = \Yii::$app->request;
        $model = new AmtimeArticle();
        $image_model = new UploadImage();
        $tag_model = new TagsAssociative();
        $tags = Tags::find()->all();

        if(!empty($request->post('id')))
            $id = $request->post('id');

        if($model_data = AmtimeArticle::findOne($id)){
            $model = $model_data;
        }

        if($request->isPost){
            $image_model->imageFile = UploadedFile::getInstance($image_model, 'imageFile');
            $image = $image_model->upload();

            $model->load($request->post());
            $model->date = !empty($model->date) ? strtotime($model->date) : time();
            if(!empty($image))
                $model->image = $image;
            $model->save();

            if($tag = TagsAssociative::find()->where(['article_id' => $model->id, 'key' => md5($model::className())])->one())
                $tag_model = $tag;

            $tag_model->load($request->post());
            $tag_model->article_id = $model->id;
            $tag_model->key = md5($model::className());
            $tag_model->save();

            return $this->redirect(Url::toRoute(['/articles/post/create', 'id' => $model->id]));
        }

        $rows = $model->getField();

        return $this->render('create', [
            'model' => $model,
            'image_model' => $image_model,
            'rows' => $rows,
            'tag_model' => $tag_model,
            'tags' => $tags,
            'users' => Autchor::find()->all()
        ]);
    }

    public function actionUpload(){
        $callback = $_GET['CKEditorFuncNum'];

        $file_name = $_FILES['upload']['name'];
        $file_name_tmp = $_FILES['upload']['tmp_name'];

        $file_new_name = '/textEditor/';
        $full_path = BaseFileHelper::normalizePath(Yii::getAlias('@frontend/web').$file_new_name.$file_name);
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