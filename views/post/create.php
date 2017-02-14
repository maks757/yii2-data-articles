<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

/**
 * @var $model \maks757\articlesdata\entities\Yii2DataArticle
 * @var $this \yii\web\View
 * @var $languages \maks757\language\entities\Language[]
 * @var $language \maks757\language\entities\Language
 * @var $model_translation \maks757\articlesdata\entities\Yii2DataArticleTranslation
 * @var $image_model \maks757\articlesdata\entities\Yii2DataArticle
 * @var $users \common\models\User[]
 * @var $rows array
 */

use dosamigos\tinymce\TinyMce;
use kartik\file\FileInput;
use maks757\language\entities\Language;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$css = <<<css
iframe{
    width: 100%;
    height: 600px;
}
css;
$this->registerCss($css);
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
<?php $translations = ArrayHelper::index($model->translations, 'language.lang_id'); ?>
<?php /** @var $languages Language[] */ foreach ($languages as $language): ?>
    <a href="<?= Url::to([
        '/articles/post/create',
        'id' => $model->id,
        'languageId' => $language->id
    ]) ?>"
       class="btn btn-xs btn-<?= $translations[$language->lang_id] ? 'success' : 'danger' ?>">
        <?= $language->name ?>
    </a>
<?php endforeach ?>
<br><br>
<?= $form->field($image_model, 'imageFile')->widget(FileInput::className(), [
    'options' => [
        'accept' => 'image/*'
    ],
    'pluginOptions' => [
        'showRemove' => false,
        'previewFileType' => 'image',
        'initialPreviewAsData' => true,
        'initialPreview' => [
            $model->getImage()
        ],
    ],
])->label('Миниатюра') ?>
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
])->label('Описание') ?>
<?= $form->field($model, 'date')->widget(DatePicker::className(), [
    'language' => 'ru',
    'dateFormat' => 'dd-MM-yyyy',
    'options' => [
        'class' => 'form-control',
        'id' => 'amtimevideo-date'
    ]
])->label('Дата') ?><br>
<?= $form->field($model, 'author')->dropDownList(\yii\helpers\ArrayHelper::map($users, 'id', 'username'))->label('Автор') ?><br>
<?//= $form->field($model, 'show')->checkbox(['label' => ''])->label('Отображать на главной') ?><!--<br>-->
<?= \yii\bootstrap\Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end() ?>
<?php Pjax::begin(['enablePushState' => false]); ?>
<?php if (!empty($model->id)): ?>
    <hr>
    <h2 class="text-center">Поля статьи</h2>
    <div class="btn-group dropup">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Добавить поле<span class="caret" style="margin-left: 10px;"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="<?= Url::toRoute(['/articles/field/create-text', 'article_id' => $model->id, 'languageId' => $model_translation->language_id]) ?>">Добавить
                    текст</a></li>
            <li><a href="<?= Url::toRoute(['/articles/field/create-image', 'article_id' => $model->id, 'languageId' => $model_translation->language_id]) ?>">Добавить
                    изображение</a></li>
            <li><a href="<?= Url::toRoute(['/articles/field/create-slider', 'article_id' => $model->id, 'languageId' => $model_translation->language_id]) ?>">Добавить
                    слайдер</a></li>
        </ul>
    </div>
    <hr>
    <?php foreach ($rows as $row): ?>
        <?php if ($row['key'] == 'text'): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-7">
                            <h3>Текст</h3>
                        </div>
                        <div class="col-sm-2">
                            <a class="btn btn-info"
                               href="<?=
                               Url::toRoute([
                                   '/articles/field/create-text',
                                   'id' => $row['id'],
                                   'article_id' => $model->id,
                                   'languageId' => $model_translation->language_id
                               ]) ?>"
                               style="margin-right: 10px; cursor: pointer; font-size: 20px;">Изменить</a>
                        </div>
                        <div class="col-sm-2 text-center">
                            <div>
                                <h5>позиция <?= $row['position'] ?></h5>
                                <a class="glyphicon glyphicon-upload"
                                   href="<?= Url::toRoute(['/articles/post/create', 'id' => $model->id, 'block_id' => $row['id'], 'languageId' => $model_translation->language_id, 'block' => 'text', 'type' => 'up']) ?>"
                                   style="margin-right: 10px; cursor: pointer; font-size: 20px;"></a>
                                <a class="glyphicon glyphicon-download"
                                   href="<?= Url::toRoute(['/articles/post/create', 'id' => $model->id, 'block_id' => $row['id'], 'languageId' => $model_translation->language_id, 'block' => 'text', 'type' => 'down']) ?>"
                                   style="margin-left: 10px; cursor: pointer; font-size: 20px;"></a>
                            </div>
                        </div>
                        <div class="col-sm-1 text-center">
                            <a class="glyphicon glyphicon-remove"
                               href="<?= Url::toRoute(['/articles/field/text-delete', 'id' => $row['id']]) ?>"
                               style="margin-left: 10px; cursor: pointer; font-size: 30px; padding: 13px 0;"></a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <?= $row['text'] ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($row['key'] == 'image'): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-7">
                            <h3>Изображение</h3>
                        </div>
                        <div class="col-sm-2">
                            <a class="btn btn-info"
                               href="<?=
                               Url::toRoute([
                                   '/articles/field/create-image',
                                   'id' => $row['id'],
                                   'article_id' => $model->id,
                                   'languageId' => $model_translation->language_id
                               ]) ?>"
                               style="margin-right: 10px; cursor: pointer; font-size: 20px;">Изменить</a>
                        </div>
                        <div class="col-sm-2 text-center">
                            <div>
                                <h5>позиция <?= $row['position'] ?></h5>
                                <a class="glyphicon glyphicon-upload"
                                   href="<?= Url::toRoute(['/articles/post/create', 'id' => $model->id, 'block_id' => $row['id'], 'languageId' => $model_translation->language_id, 'block' => 'image', 'type' => 'up']) ?>"
                                   style="margin-right: 10px; cursor: pointer; font-size: 20px;"></a>
                                <a class="glyphicon glyphicon-download"
                                   href="<?= Url::toRoute(['/articles/post/create', 'id' => $model->id, 'block_id' => $row['id'], 'languageId' => $model_translation->language_id, 'block' => 'image', 'type' => 'down']) ?>"
                                   style="margin-left: 10px; cursor: pointer; font-size: 20px;"></a>
                            </div>
                        </div>
                        <div class="col-sm-1 text-center">
                            <a class="glyphicon glyphicon-remove"
                               href="<?= Url::toRoute(['/articles/field/image-delete', 'id' => $row['id']]) ?>"
                               style="margin-left: 10px; cursor: pointer; font-size: 30px; padding: 13px 0;"></a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <img src="<?= $row['image'] ?>" style="width: 100%;">
                </div>
            </div>
        <?php endif; ?>
        <?php if ($row['key'] == 'slider'): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-7">
                            <h3>Слайдер</h3>
                        </div>
                        <div class="col-sm-2">
                            <a class="btn btn-info"
                               href="<?= Url::toRoute(['/articles/field/create-slider', 'id' => $row['id'], 'article_id' => $model->id]) ?>"
                               style="margin-right: 10px; cursor: pointer; font-size: 20px;">Изменить</a>
                        </div>
                        <div class="col-sm-2 text-center">
                            <div>
                                <h5>позиция <?= $row['position'] ?></h5>
                                <a class="fa fa-upload"<?= \yii\helpers\Url::toRoute(['/articles/post/create', 'id' => $article->id])?>
                                   href="<?= Url::toRoute(['/articles/post/create', 'id' => $model->id, 'block_id' => $row['id'], 'block' => 'slider', 'type' => 'up']) ?>"
                                   style="margin-right: 10px; cursor: pointer; font-size: 20px;"></a>
                                <a class="fa fa-download"
                                   href="<?= Url::toRoute(['/articles/post/create', 'id' => $model->id, 'block_id' => $row['id'], 'block' => 'slider', 'type' => 'down']) ?>"
                                   style="margin-left: 10px; cursor: pointer; font-size: 20px;"></a>
                            </div>
                        </div>
                        <div class="col-sm-1 text-center">
                            <a class="fa fa-remove"
                               href="<?= Url::toRoute(['/articles/field/slider-delete', 'id' => $row['id']]) ?>"
                               style="margin-left: 10px; cursor: pointer; font-size: 30px; padding: 13px 0;"></a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?php foreach ($row['images'] as $integer): ?>
                            <div class="col-sm-3">
                                <img src="<?= $integer['image'] ?>" style="width: 100%;">
                                <p><?= $integer['name'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php Pjax::end(); ?>
