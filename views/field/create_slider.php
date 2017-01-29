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
    <?= $form->field($model, 'name')->textInput()->label('Название') ?>
    <?= \yii\bootstrap\Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
<?php ActiveForm::end() ?>
<?php if(!empty($model->id)): ?>
    <?= $form->field(new UploadForm(), 'imageFiles[]')->widget(FileInput::className(), [
        'options' => [
            'multiple' => true,
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'showRemove' => false,
            'previewFileType' => 'image',
            'maxFileCount' => 20,
            'uploadUrl' => Url::toRoute(['/gallery/image/upload']),
            'uploadExtraData' => [
                'id' => $model->id,
                'key' => $model->className()
            ],
        ],
        'pluginEvents' => [
            'fileuploaded' => 'function() { $.pjax.reload({container:"#pjax_block", timeout: 100000, url: "'.Url::to('', true).'"}); }'
        ]
    ])->label('Загрузка изображений') ?>
    <?php Pjax::begin(['enablePushState' => false, 'id' => 'pjax_block']) ?>
    <?= Gallery::widget(['object' => $model]) ?>
    <?php Pjax::end() ?>
<?php endif; ?>
