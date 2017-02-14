<?php
namespace maks757\articlesdata\components\image;
/*
 * @author Cherednyk Maxim maks757q@gmail.com
*/


use maks757\imagable\name\BaseName;
use yii\base\Security;

class GenerateName extends BaseName
{

    public function generate($baseName)
    {
        $security = new Security();
        return uniqid($security->generateRandomString());
    }
}