<?php

namespace maks757\articlesdata\entities\language;

use maks757\articlesdata\components\interfaces\LanguageInterface;
use Yii;
use yii\db\ActiveRecord;

class Language extends ActiveRecord {

    public static function tableName() {
        return 'language';
    }

    /**
     * @return Language
     */
    public static function getDefault() {
        return Language::findOne([
            'default' => true
        ]);
    }

    public static function getCurrent() {
        $language = Language::findOne([
            'lang_id' => Yii::$app->language
        ]);

        if(!$language) {
            $language = static::getDefault();
        }

        return $language;
    }

    public static function findOrDefault($languageId) {
        if (empty($languageId) || !$language = Language::findOne($languageId)) {
            $language = Language::find()
                    ->where(['lang_id' => \Yii::$app->sourceLanguage])
                    ->one();
        }
        return $language;
    }
}
