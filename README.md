# yii2-data-articles

### migration
```text
yii migrate --migrationPath=@vendor/maks757/yii2-data-articles/migrations
yii migrate --migrationPath=@vendor/maks757/embeddable-gallery/migrations
```

### backend config 
```php
'modules' => [
    'articles' => [
        'class' => \maks757\articlesdata\ArticleModule::className(),
    ],
    'egallery' => [
        'class' => \maks757\egallery\GalleryModule::className()
    ],
    //...
],
```

### common config 
```php
'components' => [
    //start imagine
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
                        ],
                        'thumb' => [
                            'width' => 300,
                            'height' => 300,
                        ]
                    ]
                ],
                'images' => [
                    'size' => [
                        'origin' => [
                            'width' => 0,
                            'height' => 0,
                        ],
                        'thumb' => [
                            'width' => 300,
                            'height' => 300,
                        ]
                    ]
                ]
            ]
        ]
    ],
    'egallery' => [
        'class' => \maks757\imagable\Imagable::className(),
        'imageClass' => CreateImageMetaMulti::className(),
        'nameClass' => GenerateName::className(),
        'imagesPath' => '@frontend/web/images',
        'categories' => [
            'category' => [
                'egallery' => [
                    'size' => [
                        'origin' => [
                            'width' => 0,
                            'height' => 0,
                        ],
                        'thumb' => [
                            'width' => 300,
                            'height' => 300,
                        ]
                    ]
                ]
            ]
        ]
    ],
    //end imagine
    //...
],
```
![Alt text](/image/author.jpg "Optional title")

[VK](https://vk.com/maverick757)<br>
[Google](https://plus.google.com/u/1/115560753977134232792)<br>
[GitHub](https://github.com/maks757)
