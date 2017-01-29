<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

/**
 * @var $model \common\modules\article\entities\AmtimeArticleImage
 * @var $tag_model \common\modules\tags\entities\TagsAssociative
 */
use common\modules\gallery\components\UploadForm;
use common\modules\gallery\widgets\show_images\Gallery;
use dosamigos\tinymce\TinyMce;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
$tag_model->tag_id = $model->tag->id;
?>
    <a href="<?= \yii\helpers\Url::toRoute(['/articles/post/create', 'id' => $article_id]) ?>"
       class="btn btn-info">Назад к статье</a><br><br>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
<!--    --><?//= $form->field($tag_model, 'tag_id')->dropDownList(
//        \yii\helpers\ArrayHelper::map($tags, 'id', 'name')
//    )->label('Тег') ?>
    <?= $form->field($model_image, 'imageFile')->widget(FileInput::className(), [
        'options' => [
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'showRemove' => false,
            'previewFileType' => 'image',
            'initialPreviewAsData'=>true,
            'initialPreview'=>[
                $model->getImage()
            ],
        ],
    ])->label('Изображение') ?>
    <?= $form->field($model, 'name')->textInput()->label('Название') ?>
    <?= $form->field($model, 'description')->widget(TinyMce::className(), [
        'options' => ['rows' => 2],
        'language' => 'ru',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
            ],
            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
        ]
    ])->label('Описание')?>
<!--    --><?//= $form->field($model, 'position')->textInput()->label('Позиция')?>
    <?= \yii\bootstrap\Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
<?php ActiveForm::end() ?>