<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

namespace maks757\articlesdata;


use maks757\articlesdata\components\interfaces\LanguageInterface;
use maks757\articlesdata\entities\language\Language;
use yii\base\InvalidConfigException;
use yii\base\Module;
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
        \Yii::$container->set(LanguageInterface::class,
            $this->languageModel);
        \Yii::$container->set('language',
            $this->languageModel);

        if(!(\Yii::$container->get('language') instanceof LanguageInterface))
            throw new NotInstantiableException($this->languageModel);

//        /* @var $data1 LanguageInterface */
//        $data1 = \Yii::$container->get('language')->find()->one();
//
//        /* @var $data2 LanguageInterface */
//        $data2 = \Yii::$container->get('language')->find()->offset(1)->one();
//
//        var_dump($data1->getLanguageName());
//        var_dump($data2->getLanguageName());
//        die();
        parent::init(); // TODO: Change the autogenerated stub
    }

}