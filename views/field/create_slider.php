<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

/**
 * @var $model \maks757\articlesdata\entities\Yii2DataArticleGallery
 * @var $model_translation \maks757\articlesdata\entities\Yii2DataArticleGalleryTranslation
 * @var $languagePrimaryKeyFieldName string
 * @var $article_id integer
 * @var $language_id integer
 */
use dosamigos\tinymce\TinyMce;
use kartik\file\FileInput;
use maks757\articlesdata\components\interfaces\LanguageInterface;
use maks757\language\entities\Language;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>
<a href="<?= \yii\helpers\Url::toRoute(['/'.$this->module->id.'/post/create', 'id' => $article_id, 'languageId' => $language_id]) ?>"
   class="btn btn-info">Назад к статье</a><br><br>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <?php $translations = ArrayHelper::index($model->translations, 'language.'.$languagePrimaryKeyFieldName); ?>
    <?php /** @var $languages LanguageInterface[] */ foreach ($languages as $language): ?>
        <a href="<?= Url::to([
            '/'.$this->module->id.'/field/create-slider',
            'id' => $model->id,
            'article_id' => $model->article_id,
            'languageId' => $language->getPrimaryKey()
        ]) ?>"
           class="btn btn-xs btn-<?= $translations[$language->getPrimaryKey()] ? 'success' : 'danger' ?>">
            <?= $language->getLanguageName() ?>
        </a>
    <?php endforeach ?>
    <br><br>
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
<?php if(!empty($model->id)): ?>
    <?= $form->field(new \maks757\egallery\components\UploadForm(), 'imageFiles[]')->widget(FileInput::className(), [
        'options' => [
            'multiple' => true,
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'showRemove' => false,
            'previewFileType' => 'image',
            'maxFileCount' => 20,
            'uploadUrl' => Url::toRoute(['/egallery/image/upload']),
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
    <?= \maks757\egallery\widgets\show_images\Gallery::widget(['object' => $model, 'show_name' => false]) ?>
    <?php Pjax::end() ?>
<?php endif; ?>
