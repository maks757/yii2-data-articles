<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */
use maks757\articlesdata\components\interfaces\LanguageInterface;
use maks757\articlesdata\entities\Yii2DataArticle;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/**
 * @var $module \yii\base\Module
 */
?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th class="col-sm-1">Ид</th>
        <th class="col-sm-2">Название</th>
        <th class="col-sm-2">Описание</th>
        <th class="col-sm-2">Изображение</th>
        <th class="col-sm-1">Дата</th>
        <th class="col-sm-2">Языки</th>
        <th class="col-sm-2"></th>
    </tr>
    </thead>
    <tbody>
    <?php /** @var $articles Yii2DataArticle[] */ foreach ($articles as $article): ?>
        <tr style="height: 70px;">
            <th><?= $article->id ?></th>
            <td><?= $article->getTranslation()->name ?></td>
            <td><?= $article->getTranslation()->description ?></td>
            <td><img src="<?= $article->getImage() ?>" alt="" width="100"></td>
            <td><?= date('d-m-Y', $article->date) ?></td>
            <td>
                <?php $translations = ArrayHelper::index($article->translations, 'language.id'); ?>
                <?php /** @var $languages LanguageInterface[] */ foreach ($languages as $language): ?>
                    <a href="<?= Url::to([
                        '/articles/post/create',
                        'id' => $article->id,
                        'languageId' => $language->getPrimaryKey()
                    ]) ?>"
                       class="btn btn-xs btn-<?= $translations[$language->getPrimaryKey()] ? 'success' : 'danger' ?>">
                        <?= $language->getLanguageName() ?>
                    </a>
                <?php endforeach ?>
            </td>
            <td>
                <a href="<?= \yii\helpers\Url::toRoute(['/articles/post/create', 'id' => $article->id])?>"
                    class="btn btn-info btn-xs">Изменить</a>
                <a href="<?= \yii\helpers\Url::toRoute(['/articles/post/delete', 'id' => $article->id])?>"
                    class="btn btn-danger btn-xs">Удалить</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<a href="<?= \yii\helpers\Url::toRoute(['/articles/post/create'])?>"
    class="btn btn-success pull-right">Добавить статью</a>