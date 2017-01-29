<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

/**
 * @var $model \common\modules\article\entities\AmtimeArticleBlockInteger
 */
use dosamigos\tinymce\TinyMce;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin() ?>
    <?= $form->field($model, 'article_block_id')->hiddenInput(['value' => $block_id])->label(false) ?>
    <?= $form->field($model, 'integer')->input('number')->label('Число') ?>
    <?= $form->field($model, 'name')->textInput()->label('Описание') ?>
    <?= $form->field($model, 'position')->input('number')->label('Позиция')?>
    <?= \yii\bootstrap\Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
<?php ActiveForm::end() ?>
