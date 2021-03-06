<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

/**
 * @var $model \maks757\articlesdata\entities\Yii2DataArticleText
 * @var $model_translation \maks757\articlesdata\entities\Yii2DataArticleTextTranslation
 * @var $languagePrimaryKeyFieldName string
 * @var $article_id integer
 * @var $language_id integer
 */

use maks757\articlesdata\entities\language\Language;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<a href="<?= \yii\helpers\Url::toRoute(['/articles/post/create', 'id' => $article_id, 'languageId' => $language_id]) ?>"
   class="btn btn-info">Назад к статье</a><br><br>
<?php $form = ActiveForm::begin() ?>
    <?php $translations = ArrayHelper::index($model->translations, 'language.id'); ?>
        <?php /** @var $languages Language[] */
        foreach ($languages as $language): ?>
            <a href="<?= Url::to([
                '/articles/field/create-text',
                'id' => $model->id,
                'article_id' => empty($model->article_id) ? $article_id : $model->article_id,
                'languageId' => $language->id
            ]) ?>"
               class="btn btn-xs btn-<?= !empty($translations[$language->id]) ? 'success' : ($language->id == $language_id ? 'warning' : 'danger') ?>">
                <?= $language->name ?>
            </a>
        <?php endforeach ?>
    <br><br>
    <?= $form->field($model_translation, 'text')->widget(\dosamigos\ckeditor\CKEditor::className(), [
        'preset' => 'full',
        'options' => ['rows' => 20],
        'clientOptions' => [
            'extraPlugins' => 'iframe,font,uicolor,colordialog,colorbutton,flash,magicline,print',
            'filebrowserUploadUrl' => \yii\helpers\Url::toRoute(['/articles/post/upload'], true)
        ]
    ])->label('Текст') ?>
    <?= \yii\bootstrap\Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end() ?>
