# yii2-data-articles

### backend config 
```php
'modules' => [
    'articles' => [
        'class' => \maks757\articlesdata\ArticleModule::className(),
        // activate multi language
        'multi_languages' => true, // default false
        'language_class' => Object::className(), // yor language model, ( Language::className() )
        'language_default' => 1, // yor language model, param id ( Language::find()->one()->id or Language::find()->one()->getPrimaryKey() )
        'language_where' => ['show' => true], // yor language model, method where()
        'language_field' => 'name' // yor language model, field name language
    ],
    //...
],
```

### common config 
```php
'components' => [
        'article' => [
            'class' => \maks757\imagable\Imagable::className(),
            'imageClass' => CreateImageMetaMulti::className(),
            'nameClass' => GenerateName::className(),
            'imagesPath' => '@frontend/web/images',
            'categories' => [
                'category' => [
                    'article' => [
                        'size' => [
                            'origin' => [
                                'width' => 0,
                                'height' => 0,
                            ]
                        ]
                    ],
                    'images' => [
                        'size' => [
                            'origin' => [
                                'width' => 0,
                                'height' => 0,
                            ]
                        ]
                    ]
                ]
            ]
        ],
        //...
    ],
```
![Alt text](/image/author.jpg "Optional title")
