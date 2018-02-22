<?php
/**
 * Created by PhpStorm.
 * User: max
 * Name: Cherednyk Maxim
 * Phone: +380639960375
 * Email: maks757q@gmail.com
 * Date: 16.02.2018
 * Time: 15:26
 */

namespace maks757\articlesdata\components;


use maks757\articlesdata\components\src\ArticleObject;
use maks757\articlesdata\entities\Yii2DataArticleGallery;
use maks757\articlesdata\entities\Yii2DataArticleImage;
use maks757\articlesdata\entities\Yii2DataArticleText;
use PHPUnit\Framework\MockObject\BadMethodCallException;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;

class ArticleHelper extends ArticleObject
{
    const POSITION_UP = 0x0001;
    const POSITION_DOWN = 0x0002;

    /**
     * @param $field_id
     * @param $field_class Yii2DataArticleText|Yii2DataArticleGallery|Yii2DataArticleImage
     * @param int $type
     * @internal param $type :: POSITION_UP
     * @internal param $type :: POSITION_DOWN
     */
    public static function changeFieldPosition($field_id, $field_class, $type = ArticleHelper::POSITION_UP)
    {
        $class = $field_class::findOne($field_id);
        switch ($type){
            case ArticleHelper::POSITION_UP:{
                $class->position = (integer)$class->position + 1;
                break;
            }
            case ArticleHelper::POSITION_DOWN:{
                $class->position = (integer)$class->position > 1 ? (integer)$class->position - 1 : 1;
                break;
            }
            default:{
                throw new InvalidParamException();
            }
        }

        if(!$class->save()) {
            throw new BadMethodCallException();
        }
    }
}