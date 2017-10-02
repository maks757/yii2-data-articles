<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

namespace maks757\articlesdata;


use maks757\articlesdata\components\interfaces\LanguageInterface;
use maks757\articlesdata\entities\language\Language;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\console\Application;
use yii\db\ActiveQuery;
use yii\di\Container;
use yii\di\Instance;
use yii\di\NotInstantiableException;

class ArticleModule extends Module
{
    public $languageModel = Language::class;
    public $defaultRoute = 'post/index';

    public function init()
    {
        \Yii::$container->set(LanguageInterface::class, $this->languageModel);
        \Yii::$container->set('language', $this->languageModel);
        if(!(\Yii::$container->get('language') instanceof LanguageInterface)) {
            throw new NotInstantiableException($this->languageModel);
        }
        parent::init();
    }

}