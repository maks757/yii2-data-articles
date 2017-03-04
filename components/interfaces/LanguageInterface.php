<?php
namespace maks757\articlesdata\components\interfaces;
use yii\db\ActiveRecordInterface;

/**
 * Created by PhpStorm.
 * User: Cherednyk Maxim
 * Email: maks757q@gmail.com
 * Phone: +380639960375
 * Date: 03.03.2017
 * Time: 14:03
 */

interface LanguageInterface extends ActiveRecordInterface
{

    /**
     * @return string language key ['ru' or 'en' or 'pl'...]
     */
    public function getLanguageKey();

    /**
     * @return string name language ['Russian' or 'English', 'Polish'...]
     */
    public function getLanguageName();

    /**
     * @return string
     */
    public static function getPrimaryKeyFieldName();

    public static function getCurrent();

    public static function findOrDefault($languageId);

    public static function getDefault();
}