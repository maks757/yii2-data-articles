<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

namespace maks757\articlesdata\components;

use maks757\imagable\Imagable;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadImages extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, svg'],
        ];
    }

    public function upload()
    {
        if ($this->validate() && !empty($this->imageFile)) {
            /**@var Imagable $imagine */
            $imagine = \Yii::$app->article;
            $path = $imagine->create('images', $this->imageFile);
            return $path;
        } else {
            return false;
        }
    }
}