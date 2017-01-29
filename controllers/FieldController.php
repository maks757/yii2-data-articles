<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

namespace maks757\articlesdata\controllers;

use common\modules\article\components\UploadBlockImage;
use common\modules\article\components\UploadImage;
use common\modules\article\entities\AmtimeArticle;
use common\modules\article\entities\AmtimeArticleBlock;
use common\modules\article\entities\AmtimeArticleBlockInteger;
use common\modules\article\entities\AmtimeArticleGallery;
use common\modules\article\entities\AmtimeArticleImage;
use common\modules\article\entities\AmtimeArticleText;
use common\modules\tags\entities\Tags;
use common\modules\tags\entities\TagsAssociative;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;

class FieldController extends Controller
{
    //<editor-fold desc="Text field">
    public function actionCreateText($id = null, $article_id = null)
    {
        $request = \Yii::$app->request;
        $model = new AmtimeArticleText();

        if(!empty($request->post('id')))
            $id = $request->post('id');

        if(!empty($request->post('article_id')))
            $article_id = $request->post('article_id');

        if($model_data = AmtimeArticleText::findOne($id)){
            $model = $model_data;
        }

        if($request->isPost){
            $fields = AmtimeArticle::findOne($article_id)->getField();
            $model->load($request->post());
            $model->article_id = $article_id;
            if(!is_integer($model->position))
                $model->position = $fields[count($fields) - 1]['position'] + 1;
            $model->save();

            return $this->redirect(Url::toRoute(['/articles/post/create', 'id' => $article_id]));
        }

        return $this->render('create_text', [
            'model' => $model,
            'article_id' => $article_id
        ]);
    }

    public function actionTextPosition($id, $type)
    {
        $field = AmtimeArticleText::findOne($id);
        switch ($type){
            case 'up':{
                if($field->position > 0)
                    $field->position = ($field->position - 1);
                break;
            }
            case 'down':{
                $field->position = ($field->position + 1);
                break;
            }
        }
        $field->save();

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionTextDelete($id)
    {
        AmtimeArticleText::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>

    //<editor-fold desc="Block">
    public function actionCreateBlock($id = null, $article_id = null)
    {
        $request = \Yii::$app->request;
        $model = new AmtimeArticleBlock();

        if(!empty($request->post('id')))
            $id = $request->post('id');

        if(!empty($request->post('article_id')))
            $article_id = $request->post('article_id');

        if($model_data = AmtimeArticleBlock::findOne($id)){
            $model = $model_data;
        }

        if($request->isPost){
            $fields = AmtimeArticle::findOne($article_id)->getField();
            $model->load($request->post());
            $model->article_id = $article_id;
            if(!is_integer($model->position))
                $model->position = $fields[count($fields) - 1]['position'] + 1;
            $model->save();
            return $this->redirect(Url::toRoute(['/articles/field/create-block', 'id' => $model->id, 'article_id' => $article_id]));
        }

        return $this->render('create_block', [
            'model' => $model,
            'article_id' => $article_id
        ]);
    }

    public function actionCreateBlockInteger($id = null, $block_id = null)
    {
        $request = \Yii::$app->request;
        $model = new AmtimeArticleBlockInteger();

        if(!empty($request->post('id')))
            $id = $request->post('id');

        if(!empty($request->post('block_id')))
            $block_id = $request->post('block_id');

        if($model_data = AmtimeArticleBlockInteger::findOne($id)){
            $model = $model_data;
        }

        if($request->isPost){
            $model->load($request->post());
            $model->article_block_id = $block_id;
            $model->save();

            return $this->redirect(Url::toRoute(['/articles/field/create-block', 'id' => $block_id, 'article_id' => AmtimeArticleBlock::findOne($block_id)->article_id]));
        }

        return $this->render('create_block_integer', [
            'model' => $model,
            'block_id' => $block_id
        ]);
    }

    public function actionBlockPosition($id, $type)
    {
        $field = AmtimeArticleBlock::findOne($id);
        switch ($type){
            case 'up':{
                if($field->position > 0)
                    $field->position = ($field->position - 1);
                break;
            }
            case 'down':{
                $field->position = ($field->position + 1);
                break;
            }
        }
        $field->save();

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionBlockDelete($id)
    {
        AmtimeArticleBlock::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionBlockIntegerDelete($id)
    {
        AmtimeArticleBlockInteger::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>

    //<editor-fold desc="Image">
    public function actionCreateImage($id = null, $article_id = null)
    {
        $request = \Yii::$app->request;
        $model = new AmtimeArticleImage();
        $model_image = new UploadBlockImage();
        $tag_model = new TagsAssociative();
        $tags = Tags::find()->all();


        if(!empty($request->post('id')))
            $id = $request->post('id');

        if(!empty($request->post('article_id')))
            $article_id = $request->post('article_id');

        if($model_data = AmtimeArticleImage::findOne($id)){
            $model = $model_data;
        }

        if($request->isPost){
            $fields = AmtimeArticle::findOne($article_id)->getField();
            $model_image->imageFile = UploadedFile::getInstance($model_image, 'imageFile');

            $model->load($request->post());
            if($image = $model_image->upload())
                $model->image = $image;
            $model->article_id = $article_id;
            if(!is_integer($model->position))
                $model->position = $fields[count($fields) - 1]['position'] + 1;
            $model->save();

            if($tag = TagsAssociative::find()->where(['article_id' => $model->id, 'key' => md5($model::className())])->one())
                $tag_model = $tag;

            $tag_model->load($request->post());
            $tag_model->article_id = $model->id;
            $tag_model->key = md5($model::className());
            $tag_model->save();
            return $this->redirect(Url::toRoute(['/articles/field/create-image', 'id' => $model->id, 'article_id' => $article_id]));
        }

        return $this->render('create_image', [
            'model' => $model,
            'article_id' => $article_id,
            'model_image' => $model_image,
            'tag_model' => $tag_model,
            'tags' => $tags
        ]);
    }

    public function actionImagePosition($id, $type)
    {
        $field = AmtimeArticleImage::findOne($id);
        switch ($type){
            case 'up':{
                if($field->position > 0)
                    $field->position = ($field->position - 1);
                break;
            }
            case 'down':{
                $field->position = ($field->position + 1);
                break;
            }
        }
        $field->save();

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionImageDelete($id)
    {
        AmtimeArticleImage::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>

    //<editor-fold desc="Slider">
    public function actionCreateSlider($id = null, $article_id = null)
    {
        $request = \Yii::$app->request;
        $model = new AmtimeArticleGallery();
        $tag_model = new TagsAssociative();
        $tags = Tags::find()->all();


        if(!empty($request->post('id')))
            $id = $request->post('id');

        if(!empty($request->post('article_id')))
            $article_id = $request->post('article_id');

        if($model_data = AmtimeArticleGallery::findOne($id)){
            $model = $model_data;
        }

        if($request->isPost){
            $fields = AmtimeArticle::findOne($article_id)->getField();
            $model->load($request->post());
            $model->article_id = $article_id;
            if(!is_integer($model->position))
                $model->position = $fields[count($fields) - 1]['position'] + 1;
            $model->save();

            if($tag = TagsAssociative::find()->where(['article_id' => $model->id, 'key' => md5($model::className())])->one())
                $tag_model = $tag;

            $tag_model->load($request->post());
            $tag_model->article_id = $model->id;
            $tag_model->key = md5($model::className());
            $tag_model->save();
            return $this->redirect(Url::toRoute(['/articles/field/create-slider', 'id' => $model->id, 'article_id' => $article_id]));
        }

        return $this->render('create_slider', [
            'model' => $model,
            'article_id' => $article_id,
            'tag_model' => $tag_model,
            'tags' => $tags
        ]);
    }

    public function actionSliderPosition($id, $type)
    {
        $field = AmtimeArticleGallery::findOne($id);
        switch ($type){
            case 'up':{
                if($field->position > 0)
                    $field->position = ($field->position - 1);
                break;
            }
            case 'down':{
                $field->position = ($field->position + 1);
                break;
            }
        }
        $field->save();

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionSliderDelete($id)
    {
        AmtimeArticleGallery::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>
}