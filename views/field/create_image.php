<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

/**
 * @var $model \maks757\articlesdata\entities\Yii2DataArticleImage
 * @var $model_translation \maks757\articlesdata\entities\Yii2DataArticleImageTranslation
 * @var $model_image \maks757\articlesdata\components\UploadImages
 * @var $language_id integer
 * @var $article_id integer
 * @var $languagePrimaryKeyFieldName string
 */
use dosamigos\tinymce\TinyMce;
use kartik\file\FileInput;
use maks757\articlesdata\entities\language\Language;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
    <a href="<?= \yii\helpers\Url::toRoute(['/articles/post/create', 'id' => $article_id, 'languageId' => $language_id]) ?>"
       class="btn btn-info">Назад к статье</a><br><br>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <?php $translations = ArrayHelper::index($model->translations, 'language_id'); ?>
    <?php /** @var $languages Language */ foreach ($languages as $language): ?>
        <a href="<?= Url::to([
            '/articles/field/create-image',
            'id' => $model->id,
            'article_id' => empty($model->article_id) ? $article_id : $model->article_id,
            'languageId' => $language->id
        ]) ?>"
            class="btn btn-xs btn-<?= !empty($translations[$language->id]) ? 'success' : ( $language->id == $language_id ? 'warning' : 'danger') ?>">
            <?= $language->name ?>
        </a>
    <?php endforeach ?>
    <br><br>
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
    <?= $form->field($model_translation, 'name')->textInput()->label('Название') ?>
    <?= $form->field($model_translation, 'description')->widget(TinyMce::className(), [
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
    <?= \yii\bootstrap\Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
<?php ActiveForm::end() ?>
