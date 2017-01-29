<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

/**
 * @var $model \common\modules\article\entities\AmtimeArticleBlock
 */
use dosamigos\tinymce\TinyMce;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<a href="<?= \yii\helpers\Url::toRoute(['/articles/post/create', 'id' => $article_id]) ?>"
class="btn btn-info">Назад к статье</a><br><br>
<?php $form = ActiveForm::begin() ?>
    <?= $form->field($model, 'article_id')->hiddenInput(['value' => $article_id])->label(false) ?>
<?php if(
!empty($model->description) ||
!empty($model->id) &&
empty($model->description) && count($model->integers) < 3 ||
!empty($model->description) && count($model->integers) < 2
): ?>
    <?= $form->field($model, 'description')->widget(TinyMce::className(), [
        'options' => ['rows' => 12],
        'language' => 'ru',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
            ],
            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
        ]
    ])->label('Текст')?>
<?php else: ?>
    <label>Для создания блока нажмите на кнопку сохранить</label>
    <br>
<?php endif; ?>
    <?= \yii\bootstrap\Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
<br>
<br>
<?php if(
    !empty($model->id) &&
    empty($model->description) && count($model->integers) < 3 ||
    !empty($model->description) && count($model->integers) < 2
    ): ?>
<a href="<?= \yii\helpers\Url::toRoute(['/articles/field/create-block-integer', 'block_id' => $model->id])?>"
class="btn btn-info">Добавить числовой блок</a>
<?php endif; ?>
    <?php if(!empty($model->id)): ?>
        <?php
        $integers = $model->integers;
        \yii\helpers\ArrayHelper::multisort($integers, 'position');
        ?>
        <?php foreach ($integers as $integer): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3>Текст</h3>
                            </div>
                            <div class="col-sm-1 text-center">
                                <div>
                                    <h5>позиция <?= $integer->position ?></h5>
                                    <a class="fa fa-upload" href="<?= Url::toRoute(['/articles/field/text-position', 'id' => $integer->id, 'type' => 'up'])?>"
                                       style="margin-right: 10px; cursor: pointer; font-size: 20px;"></a>
                                    <a class="fa fa-download" href="<?= Url::toRoute(['/articles/field/text-position', 'id' => $integer->id, 'type' => 'down'])?>"
                                       style="margin-left: 10px; cursor: pointer; font-size: 20px;"></a>
                                </div>
                            </div>
                            <div class="col-sm-1 text-center">
                                <a class="fa fa-remove" href="<?= Url::toRoute(['/articles/field/block-integer-delete', 'id' => $integer->id])?>"
                                   style="margin-left: 10px; cursor: pointer; font-size: 30px; padding: 13px 0;"></a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?= $integer->integer ?>
                        <?= $integer->name ?>
                    </div>
                </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php ActiveForm::end() ?>
